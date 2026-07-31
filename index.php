<?php

define('APP_RUNNING', true);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Services\RickAndMortyAPI;
use App\Components\SearchBar;
use App\Components\CharacterList;
use App\Components\CharacterDetail;

$filters = [
    'name' => $_GET['name'] ?? null,
    'status' => $_GET['status'] ?? null,
    'species' => $_GET['species'] ?? null,
    'gender' => $_GET['gender'] ?? null,
];
$selectedId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$starredIds = isset($_COOKIE['starred']) ? json_decode($_COOKIE['starred'], true) : [];

$api = new RickAndMortyAPI();

$characters = [];
$info = ['count' => 0];
$error = null;

try {
    $data = $api->getCharacters($filters, 1);
    $characters = $data['results'] ?? [];
    $info = $data['info'] ?? $info;
} catch (\RuntimeException $e) {
    $error = $e->getMessage();
}

$selectedCharacter = null;
if ($selectedId) {
    try {
        $selectedCharacter = $api->getCharacterById($selectedId);
    } catch (\RuntimeException $e) {
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rick and Morty | Character Explorer</title>
    <link rel="stylesheet" href="assets/css/tailwind.css">
    <style>
        /* En mobile, el aside ocupa todo el ancho */
        @media (max-width: 767px) {
            .aside-main {
                width: 100% !important;
                border-right: none !important;
            }
            .detail-panel {
                display: none !important;
            }
        }
        /* En desktop, el aside tiene 375px y el detail se muestra */
        @media (min-width: 768px) {
            .aside-main {
                width: 375px !important;
                border-right: 1px solid #f3f4f6 !important;
            }
            .detail-panel {
                display: flex !important;
                flex: 1 !important;
                height: 100vh !important;
            }
        }
        .detail-panel {
            display: flex;
            flex: 1;
            height: 100vh;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden bg-white">

    <aside class="aside-main border-r border-gray-100 bg-white flex flex-col flex-shrink-0 h-screen overflow-hidden">
        <div class="px-6 pt-12 flex-shrink-0">
            <h1 class="text-[24px] font-bold text-gray-900">
                <a href="/rick-and-morty-app/" class="hover:opacity-80 transition-opacity">Rick and Morty list</a>
            </h1>

            <!-- SearchBar contains search functionality -->
            <?= SearchBar::render($filters) ?>
        </div>

        <?php if ($error): ?>
            <div class="flex-1 flex items-center justify-center text-red-500 text-sm">Failed to load</div>
        <?php else: ?>
            <?= CharacterList::render($characters, $selectedId, $starredIds, $filters) ?>
        <?php endif; ?>
    </aside>

    <div class="detail-panel">
        <?= CharacterDetail::render($selectedCharacter) ?>
    </div>

    <script src="assets/js/app.js"></script>
</body>

</html>