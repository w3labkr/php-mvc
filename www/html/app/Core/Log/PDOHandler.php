<?php

namespace App\Core\Log;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use PDO;

class PDOHandler extends AbstractProcessingHandler
{
    protected $pdo;
    protected $table;

    /**
     * Constructor.
     *
     * @param PDO    $pdo   PDO instance.
     * @param string $table The table name to store logs (default: 'logs').
     * @param int    $level The log level (default: \Monolog\Logger::DEBUG).
     * @param bool   $bubble Whether to bubble messages (default: true).
     */
    public function __construct(PDO $pdo, $table = 'logs', $level = \Monolog\Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
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
        // Merge the record's context with additional request details.
        $context = array_merge($record->context, [
            'ip' => $_SERVER['REMOTE_ADDR'], 
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'url' => $_SERVER['REQUEST_URI'],
            'method' => $_SERVER['REQUEST_METHOD'],
        ]);

        // Prepare the SQL statement to insert the log record.
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (channel, level, message, context, created_at) 
             VALUES (:channel, :level, :message, :context, NOW())"
        );

        // Execute the statement with the record's data.
        $stmt->execute([
            ':channel'  => $record->channel,
            ':level'    => $record->level->getName(),
            ':message'  => $record->formatted,
            ':context'  => json_encode($context),
        ]);
    }
}
