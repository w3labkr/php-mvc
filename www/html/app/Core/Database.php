<?php

namespace App\Core;

/**
 * The Database class manages database connections using the Singleton pattern.
 * It ensures that only one instance of a database connection is created and reused.
 */
class Database {
    // Stores instances of database connections, keyed by the connection name
    private static $instances = [];

    /**
     * Get a database connection instance.
     * 
     * This method returns a single instance of the database connection based on the
     * connection name. If the connection has not been established, it will be created.
     * 
     * @param string $connectionName The name of the database connection (defaults to 'mysql').
     * 
     * @return \PDO The PDO instance representing the database connection.
     * 
     * @throws \PDOException If the database connection configuration is invalid or the connection fails.
     */
    public static function getInstance(string $connectionName = 'mysql') {
        // If the connection instance does not already exist, create it
        if (!isset(self::$instances[$connectionName])) {
            // Load the database configuration from the application's config
            $config = config('database');
            $connection = $config['connections'][$connectionName];

            // If no configuration for the specified connection is found, throw an exception
            if (!$connection) {
                throw new \PDOException("Database configuration for connection '{$connectionName}' not found.");
            }

            // Extract the configuration parameters for the database connection
            $host = $connection['host'];
            $port = $connection['port'];
            $database = $connection['database'];
            $charset = $connection['charset'];
            $username = $connection['username'];
            $password = $connection['password'];

            // Create the DSN (Data Source Name) for the database connection
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";

            // Set the PDO options for error handling and default fetch mode
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,  // Fetch rows as associative arrays
                \PDO::ATTR_EMULATE_PREPARES => false,  // Disable emulation of prepared statements
            ];

            try {
                // Create the PDO instance and store it in the instances array
                self::$instances[$connectionName] = new \PDO($dsn, $username, $password, $options);
            } catch (\PDOException $e) {
                // If the connection fails, display the error message and stop execution
                die("Database connection failed: " . $e->getMessage());
            }
        }

        // Return the existing PDO instance for the specified connection
        return self::$instances[$connectionName];
    }
}
