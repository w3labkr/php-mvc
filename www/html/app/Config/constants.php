<?php

// 프로젝트 루트 절대 경로 (app/Config 폴더에서 두 단계 상위가 프로젝트 루트)
define('BASE_PATH', realpath(__DIR__ . '/../../'));

// app 디렉토리 경로
define('APP_PATH', BASE_PATH . '/app');

// public 디렉토리 경로
define('PUBLIC_PATH', BASE_PATH . '/public');

// views 디렉토리 경로
define('VIEWS_PATH', APP_PATH . '/Views');

// config 디렉토리 경로
define('CONFIG_PATH', APP_PATH . '/Config');

// core 디렉토리 경로
define('CORE_PATH', APP_PATH . '/Core');
