<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use Monolog\Logger;
use App\Core\Log\PDOHandler;

class UserModel {
    private $db;
    private $logger;

    public function __construct() {
        $this->db = Database::getInstance();

        // Monolog Logger 생성
        $this->logger = new Logger('UserModel');
        // 커스텀 PDOHandler를 추가하여 로그를 DB에 기록 (로그 레벨 INFO 이상)
        $pdoHandler = new PDOHandler($this->db, 'logs');
        $this->logger->pushHandler($pdoHandler);
    }

    // 이메일로 사용자 조회
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 사용자 생성 (INSERT) - 트랜잭션 적용
    public function createUser($name, $email, $password) {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare(
                "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)"
            );
            $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            ]);
            $userId = $this->db->lastInsertId();

            // 사용자 생성 로그 기록 (로그 데이터는 DB의 logs 테이블에 저장)
            $this->logger->info("Created user", ['userId' => $userId, 'email' => $email]);

            $this->db->commit();
            return $userId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->logger->error("Failed to create user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // 사용자 정보 수정 (UPDATE) - 예시: 이름 수정, 트랜잭션 적용
    public function updateUserName($id, $name) {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("UPDATE users SET name = :name WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'id'   => $id
            ]);

            $this->logger->info("Updated user", ['userId' => $id, 'newName' => $name]);

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->logger->error("Failed to update user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // 사용자 삭제 (DELETE) - 트랜잭션 적용
    public function deleteUser($id) {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);

            $this->logger->info("Deleted user", ['userId' => $id]);

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->logger->error("Failed to delete user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
