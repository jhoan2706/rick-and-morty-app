<?php

define('APP_RUNNING', true);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Services\RickAndMortyAPI;
use App\Components\CharacterDetail;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    header('Location: /');
    exit;
}

$api = new RickAndMortyAPI();
$character = null;

try {
    $character = $api->getCharacterById($id);
} catch (\RuntimeException $e) {
    // Character not found or API error, redirect to the main page
}

if (!$character) {
    header('Location: /');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rick and Morty | <?= htmlspecialchars($character['name'] ?? 'Character') ?></title>
    <link rel="stylesheet" href="assets/css/tailwind.css">
    <style>
        body {
            background: white;
        }

        .back-button {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 12px;
            width: 56px;
            height: 56px;
            border-radius: 12px;
            text-decoration: none;
            transition: background-color 0.15s;
            margin: 16px 0 0 20px;
        }

        .back-button:hover {
            background-color: #f3f4f6;
        }

        .back-button svg {
            width: 32px;
            height: 32px;
        }

        .detail-content {
            padding: 0 16px 24px;
        }

        @media (min-width: 768px) {
            .detail-content {
                padding: 0 100px 24px;
            }
        }
    </style>
</head>

<body>

    <!-- Header with back button -->
    <div>
        <a href="/" class="back-button">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="#8054C7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    </div>

    <!-- Detail Content -->
    <div class="detail-content">
        <div class="detail-content">
            <?= CharacterDetail::render($character, true) ?>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>

</html>