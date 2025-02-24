<?php

namespace App\Core;

use App\Core\Database;
use Monolog\Logger;
use App\Core\Log\PDOHandler;
use App\Helpers\ApiResponse;

class Controller {
    protected $db;
    protected $logger;
    protected $response;
    // Default connection name and log table. Child classes may override these.
    protected $connectionName = 'mysql';
    protected $logTable = 'logs';

    /**
     * Base Controller constructor.
     *
     * Initializes the database connection and logger.
     * 
     * The database connection is selected based on the configuration value
     * 'database.default'. This value is passed as a parameter to Database::getInstance()
     * so that you can use a different database connection if needed.
     *
     * The logger's database table is configurable via the 'app.log_table' configuration
     * key. If not set, it defaults to 'logs'.
     */
    public function __construct() {
        // Get the PDO instance based on the selected connection.
        $this->db = Database::getInstance($this->connectionName);

        // Set up the logger with the configurable log table.
        $this->logger = new Logger(static::class);
        $this->logger->pushHandler(new PDOHandler($this->db, $this->logTable, Logger::INFO));

        // Create a new instance of the ApiResponse class to handle JSON and plain text responses.
        $this->response = new ApiResponse();
    }
}
