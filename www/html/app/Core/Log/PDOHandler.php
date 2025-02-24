<?php

namespace App\Core\Log;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

/**
 * PDOHandler class is a custom Monolog handler that stores log records in a database.
 * It extends the AbstractProcessingHandler to process and store log records.
 */
class PDOHandler extends AbstractProcessingHandler
{
    // PDO instance for database interaction
    protected $pdo;
    
    // Table name to store logs (default: 'logs')
    protected $table;

    /**
     * Constructor.
     *
     * @param \PDO    $pdo   PDO instance for interacting with the database.
     * @param string  $table The table name to store logs (default: 'logs').
     * @param int     $level The log level (default: \Monolog\Logger::DEBUG).
     * @param bool    $bubble Whether to bubble messages (default: true).
     */
    public function __construct(\PDO $pdo, $table = 'logs', $level = \Monolog\Logger::DEBUG, bool $bubble = true)
    {
        // Call the parent constructor to set the log level and bubble setting.
        parent::__construct($level, $bubble);
        
        // Assign the provided PDO instance and table name to the class properties.
        $this->pdo = $pdo;
        $this->table = $table;
    }

    /**
     * Writes a log record to the database.
     *
     * This method is called by the Monolog framework for each log record.
     * It merges additional context (such as IP, user agent, URL, and HTTP method)
     * and inserts the log record into the specified database table.
     *
     * @param LogRecord $record The log record.
     */
    protected function write(LogRecord $record): void
    {
        // Merge the record's context with additional request details such as IP, user agent, URL, and method.
        $context = array_merge($record->context, [
            'ip' => $_SERVER['REMOTE_ADDR'], 
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'url' => $_SERVER['REQUEST_URI'],
            'method' => $_SERVER['REQUEST_METHOD'],
        ]);

        // Prepare the SQL statement to insert the log record into the database.
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (channel, level, message, context, created_at) 
             VALUES (:channel, :level, :message, :context, NOW())"
        );

        // Execute the prepared statement with the data from the log record.
        $stmt->execute([
            ':channel'  => $record->channel,
            ':level'    => $record->level->getName(),
            ':message'  => $record->formatted,
            ':context'  => json_encode($context),
        ]);
    }
}
