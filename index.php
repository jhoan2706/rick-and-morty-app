<?php

/**
 * Rick and Morty Character Explorer
 * 
 * Main application entry point that orchestrates:
 * - API data fetching
 * - Filter processing
 * - Component rendering
 * - State management
 * 
 * Architecture: Simple MVC-like pattern without frameworks.
 * Uses PHP 8+ features (match expressions, named arguments, typed properties).
 */

// Bootstrap the application
define('APP_RUNNING', true);

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Services\RickAndMortyAPI;
use App\Components\CharacterCard;
use App\Components\SearchFilters;
use App\Components\Pagination;
use App\Components\LoadingSpinner;
use App\Utils\Helpers;

/**
 * Application State Management
 * 
 * We extract filter parameters from GET request and maintain them
 * throughout the application lifecycle for consistent state.
 */
$filters = [
    'name' => $_GET['name'] ?? null,
    'status' => $_GET['status'] ?? null,
    'species' => $_GET['species'] ?? null,
    'gender' => $_GET['gender'] ?? null,
];

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, $currentPage); // Ensure minimum page is 1

// API Service instantiation
$api = new RickAndMortyAPI();

// Data fetching with error handling
$characters = [];
$info = ['count' => 0, 'pages' => 0, 'next' => null, 'prev' => null];
$error = null;
$isLoading = false;

// Only fetch if we have data to display (avoid unnecessary API calls on initial load with no filters)
// Actually, we should always fetch on load to show initial characters
try {
    $data = $api->getCharacters($filters, $currentPage);
    $characters = $data['results'] ?? [];
    $info = $data['info'] ?? $info;
} catch (\RuntimeException $e) {
    $error = $e->getMessage();
    error_log("API Error: " . $e->getMessage());
}

$hasResults = !empty($characters);
$hasActiveFilters = !empty(array_filter($filters));

?>
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rick and Morty Character Explorer - Browse characters from the multiverse">
    <meta name="theme-color" content="#1a1a2e">

    <title>Rick and Morty | Character Explorer</title>

    <link rel="stylesheet" href="assets/css/tailwind.css">

    <link rel="preconnect" href="https://rickandmortyapi.com">

    <!-- Custom styles for specific Figma design requirements -->
    <style>
        .rm-gradient {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a2e;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        .character-card {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="bg-slate-900 min-h-screen text-white font-sans antialiased">
    <header class="rm-gradient border-b border-slate-700/50 sticky top-0 z-50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-green-400" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="20" r="18" stroke="currentColor" stroke-width="2" stroke-dasharray="8 4" />
                        <circle cx="20" cy="20" r="12" stroke="currentColor" stroke-width="1.5" stroke-dasharray="6 3" />
                        <circle cx="20" cy="20" r="6" fill="currentColor" opacity="0.3" />
                    </svg>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-white">
                            <span class="text-green-400">Rick</span> & <span class="text-blue-400">Morty</span>
                        </h1>
                        <p class="text-xs text-slate-400 hidden sm:block">Character Explorer</p>
                    </div>
                </div>

                <!-- Stats Quick View -->
                <div class="hidden md:flex items-center space-x-6 text-sm">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-slate-300">
                            <span class="font-semibold text-white"><?= number_format($info['count']) ?></span> characters
                        </span>
                    </div>
                    <div class="text-slate-400">
                        Page <span class="font-semibold text-white"><?= $currentPage ?></span> of <span class="font-semibold text-white"><?= $info['pages'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">

        <!-- Search and Filters Section -->
        <section class="mb-8" aria-label="Search and filter characters">
            <div class="bg-slate-800/40 border border-slate-700/30 rounded-2xl p-6 backdrop-blur-sm">
                <h2 class="text-lg font-semibold text-white mb-4">Search Characters</h2>
                <?= SearchFilters::render($filters) ?>
            </div>
        </section>

        <!-- Results Section -->
        <section aria-label="Character results">

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-8 text-center">
                    <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-red-400 mb-2">Failed to load characters</h3>
                    <p class="text-slate-300">Please check your connection and try again.</p>
                </div>

            <?php elseif (!$hasResults && $hasActiveFilters): ?>
                <?= LoadingSpinner::renderNoResults() ?>

            <?php elseif (!$hasResults): ?>
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-slate-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                    </svg>
                    <h3 class="text-xl font-semibold text-white mb-2">Explore the Multiverse</h3>
                    <p class="text-slate-400 max-w-md mx-auto">
                        Use the search bar above to find your favorite characters from the Rick and Morty universe.
                    </p>
                </div>

            <?php else: ?>
                <!-- Character Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($characters as $character): ?>
                        <?= CharacterCard::render($character) ?>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8">
                    <?= Pagination::render($info, $currentPage, $filters) ?>
                </div>

                <p class="text-center text-sm text-slate-500 mt-4">
                    Showing <?= count($characters) ?> of <?= number_format($info['count']) ?> characters
                </p>
            <?php endif; ?>

        </section>
    </main>

    <footer class="border-t border-slate-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500">
                    Data provided by
                    <a href="https://rickandmortyapi.com" target="_blank" rel="noopener noreferrer"
                        class="text-green-400 hover:text-green-300 transition-colors">
                        The Rick and Morty API
                    </a>
                </p>
                <p class="text-xs text-slate-600">
                    Built with PHP 8+ & TailwindCSS
                </p>
            </div>
        </div>
    </footer>

    <script src="assets/js/app.js"></script>
</body>

</html>