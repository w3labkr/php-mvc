<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    // Hold multiple PDO instances for different connections.
    private static $instances = [];

    /**
     * Get the PDO instance for a given connection name.
     *
     * This method retrieves the database configuration from config('database')
     * and creates a new PDO instance if one doesn't already exist for the given connection.
     *
     * @param string $connectionName The connection name (default: 'mysql').
     * @return PDO The PDO instance for the specified connection.
     * @throws PDOException If the configuration for the connection is not found.
     */
    public static function getInstance(string $connectionName = 'mysql') {
        if (!isset(self::$instances[$connectionName])) {
            $config = config('database');
            $connection = $config['connections'][$connectionName];

            if (!$connection) {
                throw new PDOException("Database configuration for connection '{$connectionName}' not found.");
            }

            $host = $connection['host'];
            $port = $connection['port'];
            $database = $connection['database'];
            $charset = $connection['charset'];
            $username = $connection['username'];
            $password = $connection['password'];

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instances[$connectionName] = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$instances[$connectionName];
    }
}
