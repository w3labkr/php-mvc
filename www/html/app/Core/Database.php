<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            // 설정 파일에서 데이터베이스 연결 정보를 불러옵니다.
            $config = require CONFIG_PATH . '/database.php';

            // DSN 구성: host와 dbname 사용
            $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset=utf8mb4";

            // 사용자명과 비밀번호
            $username = $config['DB_USER'];
            $password = $config['DB_PASS'];

            // 기본 PDO 옵션 설정
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
