<?php

return function ($scheduler) {
    $scheduler->call(function () {
        error_log("[" . date('Y-m-d H:i:s') . "] Scheduler is running.");
    })
    ->everyMinute();
};
