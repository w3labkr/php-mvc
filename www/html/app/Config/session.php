<?php

// Set default session lifetime to 30 days (2592000 seconds)
$lifetime = 30 * 24 * 60 * 60;
ini_set('session.gc_maxlifetime', $lifetime);
session_set_cookie_params($lifetime);
session_start();