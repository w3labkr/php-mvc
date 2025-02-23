<?php

// Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load constant definitions
require_once __DIR__ . '/../config/constants.php';

// Load the .env file using Dotenv
$dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Disable error display in production mode
if ($_ENV['APP_ENV'] === 'production') {
    ini_set('display_errors', '0');
    error_reporting(0);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// Load session configuration (sets default session lifetime to 30 days)
require_once CONFIG_PATH . '/session.php';

// Load application routes
require_once CONFIG_PATH . '/routes.php';