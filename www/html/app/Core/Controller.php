<?php

namespace App\Core;

use Monolog\Logger;
use App\Core\Database;
use App\Core\Log\PDOHandler;
use App\Helpers\ApiResponse;

/**
 * Controller class serves as the base class for all controllers.
 * It provides functionality for initializing database connection,
 * setting up logging, and handling API responses.
 */
class Controller
{
    // Protected properties for database connection, logger, and response handler.
    protected $db;
    protected $logger;
    protected $response;

    // Default database connection name and log table.
    protected $connectionName = 'mysql';
    protected $logTable = 'logs';

    /**
     * The constructor initializes the database connection, logger,
     * and API response handler.
     */
    public function __construct()
    {
        // Establish the database connection using the specified connection name.
        $this->db = Database::getInstance($this->connectionName);

        // Initialize the logger using the current class name as the channel name.
        // This ensures that each controller has its own unique log channel.
        $this->logger = new Logger(static::class);

        // Set up the custom handler for the logger to store log entries in the database.
        // The log level is set to INFO and it will write to the specified log table.
        $handler = new PDOHandler($this->db, $this->logTable, Logger::INFO);
        $this->logger->pushHandler($handler);

        // Initialize the API response handler for standardized API responses.
        $this->response = new ApiResponse();
    }
}
