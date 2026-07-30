<?php

define('APP_RUNNING', true);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Services\RickAndMortyAPI;
use App\Components\SearchBar;
use App\Components\CharacterList;
use App\Components\CharacterDetail;

$filters = ['name' => $_GET['name'] ?? null];
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
    } catch (\RuntimeException $e) {}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rick and Morty | Character Explorer</title>
    <link rel="stylesheet" href="assets/css/tailwind.css">
</head>
<body class="flex h-screen overflow-hidden bg-white">

    <aside class="w-[375px] border-r border-gray-100 bg-white flex flex-col flex-shrink-0 h-screen overflow-hidden">
        <div class="px-6 pt-12 flex-shrink-0">
            <h1 class="text-[24px] font-bold text-gray-900">Rick and Morty <span class="text-primary-600">list</span></h1>
            <?= SearchBar::render($filters) ?>
        </div>
        
        <?php if ($error): ?>
            <div class="flex-1 flex items-center justify-center text-red-500 text-sm">Failed to load</div>
        <?php else: ?>
            <?= CharacterList::render($characters, $selectedId, $starredIds) ?>
        <?php endif; ?>
    </aside>

    <?= CharacterDetail::render($selectedCharacter) ?>

<script src="assets/js/app.js"></script>
</body>
</html>
