<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GO\Scheduler;

// Create a new Scheduler instance.
$scheduler = new Scheduler();

// Define the directory containing the task files.
$tasksDir = __DIR__ . '/tasks';

// Loop through all PHP files in the scheduler/tasks directory.
foreach (glob($tasksDir . '/*.php') as $taskFile) {
    // Skip files that start with an underscore ('_').
    if (strpos(basename($taskFile), '_') === 0) {
        continue;
    }

    // Include the task file and get the task function.
    $task = require $taskFile;

    // If the task is callable, execute it and pass the scheduler instance.
    if (is_callable($task)) {
        $task($scheduler);
    }
}

// Run the scheduler to execute tasks that are due to run based on their schedule.
$scheduler->run();
