<?php

// Set default session lifetime to 30 days (2592000 seconds)
$lifetime = 30 * 24 * 60 * 60;

// Define secure session cookie parameters
$cookieParams = [
    'lifetime' => $lifetime,
    'path'     => '/',
    'domain'   => '', // Specify your domain if needed (e.g., 'example.com')
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), // Only send over HTTPS if available
    'httponly' => true, // Prevent JavaScript access to session cookie
    'samesite' => 'Lax' // Options: 'Strict' or 'Lax'
];

ini_set('session.gc_maxlifetime', $lifetime);
session_set_cookie_params($cookieParams);
session_start();
