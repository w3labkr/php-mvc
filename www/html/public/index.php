<?php

session_start(); // 세션 시작

// Composer autoloader 포함
require_once __DIR__ . '/../vendor/autoload.php';

// 상수 정의 로드
require_once __DIR__ . '/../app/Config/constants.php';

// Dotenv를 이용해 .env 파일의 내용을 로드합니다.
$dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// routes.php 파일 포함 (app/config 디렉토리 내)
require_once CONFIG_PATH . '/routes.php';