<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Services\RickAndMortyAPI;

header('Content-Type: application/json');

// === LOAD BY IDs (for starred cache) ===
$ids = $_GET['ids'] ?? null;
if ($ids) {
    $api = new RickAndMortyAPI();
    $idArray = array_filter(explode(',', $ids));
    
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
$characterType = $_GET['characterType'] ?? null;
$status = $_GET['status'] ?? null;
$gender = $_GET['gender'] ?? null;
$species = $_GET['species'] ?? null;
$starred_ids = $_GET['starred_ids'] ?? null;
$api = new RickAndMortyAPI();

try {
    // === IF CHARACTER TYPE IS STARRED ===
    if ($characterType === 'starred') {
        // Get starred IDs from query parameter or cookie
        $favIds = [];
        if ($starred_ids) {
            $favIds = array_filter(explode(',', $starred_ids));
        } elseif (isset($_COOKIE['starred'])) {
            $favIds = json_decode($_COOKIE['starred'], true) ?? [];
        }
        
        if (empty($favIds)) {
            echo json_encode([
                'html' => '<p class="px-6 py-4 text-sm text-gray-400">No starred characters yet</p>',
                'hasMore' => false,
                'count' => 0,
            ]);
            exit;
        }
        
        // Get all starred characters and apply filters
        $allStarred = [];
        foreach ($favIds as $id) {
            try {
                $c = $api->getCharacterById((int)$id);
                
                // Apply filters if any
                $matches = true;
                
                if ($name && isset($c['name'])) {
                    $matches = $matches && stripos($c['name'], $name) !== false;
                }
                if ($status && $status !== 'All' && isset($c['status'])) {
                    $matches = $matches && strtolower($c['status']) === strtolower($status);
                }
                if ($species && $species !== 'All' && isset($c['species'])) {
                    $matches = $matches && strtolower($c['species']) === strtolower($species);
                }
                if ($gender && $gender !== 'All' && isset($c['gender'])) {
                    $matches = $matches && strtolower($c['gender']) === strtolower($gender);
                }
                
                if ($matches) {
                    $allStarred[] = $c;
                }
            } catch (\Exception $e) {
                // Ignore errors for individual characters
            }
        }
        
        // Paginate the filtered starred characters
        $pageSize = 20;
        $totalFiltered = count($allStarred);
        $offset = ($page - 1) * $pageSize;
        $pagedCharacters = array_slice($allStarred, $offset, $pageSize);
        
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
    }
    
    // === IF CHARACTER TYPE IS OTHERS OR ALL: Ignore characterType, use normal API ===
    $apiFilters = ['name' => $name];
    if ($species && $species !== 'All') {
        $apiFilters['species'] = $species;
    }
    if ($status && $status !== 'All') {
        $apiFilters['status'] = $status;
    }
    if ($gender && $gender !== 'All') {
        $apiFilters['gender'] = $gender;
    }
    
    $data = $api->getCharacters($apiFilters, $page);
    $html = '';
    $starredIds = [];
    if (isset($_COOKIE['starred'])) {
        $starredIds = json_decode($_COOKIE['starred'], true) ?? [];
    }
    
    foreach ($data['results'] ?? [] as $c) {
        $isActive = ($selectedId && isset($c['id']) && (int)$c['id'] === $selectedId);
        $isStarred = in_array($c['id'], $starredIds);
        $html .= App\Components\CharacterListItem::render($c, $isActive, $isStarred);
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