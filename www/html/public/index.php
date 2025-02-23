<?php

// Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// 상수 정의 로드
require_once __DIR__ . '/../config/constants.php';

// Dotenv를 이용해 .env 파일의 내용을 로드합니다.
$dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Load session configuration (sets default session lifetime to 30 days)
require_once CONFIG_PATH . '/session.php';

// Load application routes
require_once CONFIG_PATH . '/routes.php';