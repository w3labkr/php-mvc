<?php

use Monolog\Logger;
use App\Core\Database;
use App\Core\Log\PDOHandler;

/**
 * This function schedules a task to delete logs older than 3 months.
 * It runs daily at 2:00 AM and uses the database to remove outdated logs
 * and logs the process using Monolog.
 */
return function($scheduler) {
    // Schedule the task to run daily at 2:00 AM.
    $scheduler->call(function() {
        // Get the database instance.
        $db = Database::getInstance();

        // Create a new logger for the task, identified as 'delete_old_logs'.
        $logger = new Logger('delete_old_logs');
        $logger->pushHandler(new PDOHandler($db, 'logs', Logger::INFO));

        try {
            // Begin a database transaction to ensure atomicity.
            $db->beginTransaction();

            // Prepare and execute the SQL statement to delete logs older than 3 months.
            $stmt = $db->prepare("DELETE FROM logs WHERE datetime < DATE_SUB(NOW(), INTERVAL 3 MONTH)");
            $stmt->execute();
            
            // Get the number of rows deleted.
            $deleted = $stmt->rowCount();

            // Commit the transaction if successful.
            $db->commit();

            // Log the successful deletion with the number of rows deleted.
            $logger->info("Deleted old logs", ['deleted' => $deleted]);
        } catch (\Exception $e) {
            // Rollback the transaction if there was an error.
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            // Log the error message if the deletion fails.
            $logger->error("Error deleting old logs", ['error' => $e->getMessage()]);
        }
    })
    // Set the task to run daily at 2:00 AM.
    ->daily('02:00');
};
