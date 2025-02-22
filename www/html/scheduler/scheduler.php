<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/constants.php';

use GO\Scheduler;

$scheduler = new Scheduler();

// scheduler/tasks 폴더 내의 모든 PHP 파일을 로드하여 task를 등록
$tasksDir = __DIR__ . '/tasks';

// 파일명이 "_"로 시작하지 않는 PHP 파일들만 로드
foreach (glob($tasksDir . '/*.php') as $taskFile) {
    if (strpos(basename($taskFile), '_') === 0) {
        continue;
    }
    $task = require $taskFile;
    if (is_callable($task)) {
        $task($scheduler);
    }
}

// 스케줄러 실행 (스케줄에 맞는 작업이 있다면 실행)
$scheduler->run();
