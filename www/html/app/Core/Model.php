<?php

namespace App\Core;

use Monolog\Logger;
use App\Core\Database;
use App\Core\Log\PDOHandler;

/**
 * Model class is the base class for all models.
 * It is responsible for initializing the database connection
 * and setting up logging functionality for the models.
 */
class Model
{
    // Protected properties to store the database connection and logger instance.
    protected $db;
    protected $logger;

    // Default database connection name and log table name.
    protected $connectionName = 'mysql';
    protected $logTable = 'logs';

    /**
     * The constructor initializes the database connection and logger.
     *
     * This method sets up the database connection using the provided connection name
     * and initializes the logger with the model's class name. It also sets up a
     * custom log handler to store logs in the database.
     */
    public function __construct()
    {
        // Initialize the database connection using the specified connection name.
        $this->db = Database::getInstance($this->connectionName);

        // Initialize the logger using the current class name as the channel name.
        // This allows each model to have its own logging channel.
        $this->logger = new Logger(static::class);

        // Create a custom log handler to write logs to the specified log table.
        $handler = new PDOHandler($this->db, $this->logTable, Logger::INFO);

        // Push the handler to the logger to handle database logging.
        $this->logger->pushHandler($handler);
    }
}
