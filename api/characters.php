<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Services\RickAndMortyAPI;

header('Content-Type: application/json');

// === LOAD BY IDs (for starred) ===
$ids = $_GET['ids'] ?? null;
if ($ids) {
    $api = new RickAndMortyAPI();
    $idArray = array_filter(explode(',', $ids));
    
    // Get the selected_id from GET parameters
    $selectedId = isset($_GET['selected_id']) ? (int)$_GET['selected_id'] : null;
    
    $html = '';
    foreach ($idArray as $id) {
        try {
            $c = $api->getCharacterById((int)$id);
            $isActive = ($selectedId && (int)$id === $selectedId);
            $html .= App\Components\CharacterListItem::render($c, $isActive, true);
        } catch (\Exception $e) {}
    }
    echo json_encode(['html' => $html, 'count' => count($idArray)]);
    exit;
}

// === PAGINATED ===
$selectedId = isset($_GET['selected_id']) ? (int)$_GET['selected_id'] : null;
$page = max(1, (int)($_GET['page'] ?? 1));
$name = $_GET['name'] ?? null;
$status = $_GET['status'] ?? null;
$species = $_GET['species'] ?? null;
$api = new RickAndMortyAPI();

try {
    // If the filter is "starred" or "others", we need to handle it differently
    if ($status === 'starred' || $status === 'others') {
        // Get starred IDs from cookie or GET parameter
        $starredIds = [];
        if (isset($_COOKIE['starred'])) {
            $starredIds = json_decode($_COOKIE['starred'], true) ?? [];
        }
        // Also check if starred_ids are passed via GET (for infinite scroll)
        if (isset($_GET['starred_ids'])) {
            $starredIds = explode(',', $_GET['starred_ids']);
        }
        
        $allCharacters = [];
        $pageSize = 20;
        
        if ($status === 'starred') {
            if (empty($starredIds)) {
                echo json_encode([
                    'html' => '<p class="px-6 py-4 text-sm text-gray-400">No starred characters yet</p>',
                    'hasMore' => false,
                    'count' => 0,
                ]);
                exit;
            }
            
            // Get all starred characters and filter them by name and species
            $allStarredCharacters = [];
            foreach ($starredIds as $id) {
                try {
                    $c = $api->getCharacterById((int)$id);
                    
                    $matchesSpecies = !$species || $species === 'All' || 
                        (isset($c['species']) && strtolower($c['species']) === strtolower($species));
                    $matchesName = !$name || 
                        (isset($c['name']) && stripos($c['name'], $name) !== false);
                    
                    if ($matchesSpecies && $matchesName) {
                        $allStarredCharacters[] = $c;
                    }
                } catch (\Exception $e) {}
            }
            
            // Paginate the filtered starred characters
            $totalFiltered = count($allStarredCharacters);
            $offset = ($page - 1) * $pageSize;
            $pagedCharacters = array_slice($allStarredCharacters, $offset, $pageSize);
            
            $html = '';
            foreach ($pagedCharacters as $c) {
                $isActive = ($selectedId && isset($c['id']) && (int)$c['id'] === $selectedId);
                $html .= App\Components\CharacterListItem::render($c, $isActive, true);
            }
            
            $hasMore = ($offset + $pageSize) < $totalFiltered;
            
            echo json_encode([
                'html' => $html ?: '<p class="px-6 py-4 text-sm text-gray-400">No characters match the filters</p>',
                'hasMore' => $hasMore,
                'count' => count($pagedCharacters),
            ]);
            exit;
            
        } elseif ($status === 'others') {
            // Show non-starred characters, filtered by name and species
            $apiFilters = ['name' => $name];
            if ($species && $species !== 'All') {
                $apiFilters['species'] = $species;
            }
            
            $data = $api->getCharacters($apiFilters, $page);
            $html = '';
            foreach ($data['results'] ?? [] as $c) {
                if (!in_array($c['id'], $starredIds)) {
                    $isActive = ($selectedId && isset($c['id']) && (int)$c['id'] === $selectedId);
                    $html .= App\Components\CharacterListItem::render($c, $isActive, false);
                }
            }
            echo json_encode([
                'html' => $html,
                'hasMore' => ($page < ($data['info']['pages'] ?? 1)),
                'count' => count($data['results'] ?? []),
            ]);
            exit;
        }
    }
    
    // Rgular API call for all characters with filters
    $apiFilters = ['name' => $name];
    if ($species && $species !== 'All') {
        $apiFilters['species'] = $species;
    }
    if ($status && $status !== 'starred' && $status !== 'others') {
        $apiFilters['status'] = $status;
    }
    
    $data = $api->getCharacters($apiFilters, $page);
    $html = '';
    foreach ($data['results'] ?? [] as $c) {
        $isActive = ($selectedId && isset($c['id']) && (int)$c['id'] === $selectedId);
        $html .= App\Components\CharacterListItem::render($c, $isActive, false);
    }
    echo json_encode([
        'html' => $html,
        'hasMore' => ($page < ($data['info']['pages'] ?? 1)),
        'count' => count($data['results'] ?? []),
    ]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}