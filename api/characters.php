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
    $html = '';
    foreach ($idArray as $id) {
        try {
            $c = $api->getCharacterById((int)$id);
            $html .= App\Components\CharacterListItem::render($c, false, true);
        } catch (\Exception $e) {}
    }
    echo json_encode(['html' => $html, 'count' => count($idArray)]);
    exit;
}

// === PAGINATED ===
$page = max(1, (int)($_GET['page'] ?? 1));
$name = $_GET['name'] ?? null;
$api = new RickAndMortyAPI();

try {
    $data = $api->getCharacters(['name' => $name], $page);
    $html = '';
    foreach ($data['results'] ?? [] as $c) {
        $html .= App\Components\CharacterListItem::render($c, false, false);
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