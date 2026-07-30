<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Services\RickAndMortyAPI;

header('Content-Type: application/json');

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
