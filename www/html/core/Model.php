<?php

class Model {
    protected $db;
    public function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        try {
            $this->db = new PDO(dsn: "mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'], username: $config['DB_USER'], password: $config['DB_PASS']);           
            $this->db->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}