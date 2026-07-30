<?php

/* Application configuration 

Central configuration file for the application. This file contains settings and parameters that are used throughout the application. It is recommended to keep this file secure and not expose it publicly. */

// Prevent direct access
if (!defined('APP_RUNNING')) {
    define('APP_RUNNING', true);
}

// API Configuration
define('RICK_AND_MORTY_API_BASE_URL', 'https://rickandmortyapi.com/api');
define('API_TIMEOUT', 10);

// Application Settings
define('ITEMS_PER_PAGE', 20);
define('MAX_VISIBLE_PAGES', 5);

// Error Handling
define('DISPLAY_ERRORS', false); // Set to true for development only
error_reporting(DISPLAY_ERRORS ? E_ALL : 0);
ini_set('display_errors', DISPLAY_ERRORS ? '1' : '0');
