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
        // 데이터베이스 연결
        $this->db = Database::getInstance();

        // 로그 기록은 INFO 레벨 이상 테이블에 저장
        $this->logger = new Logger('UserModel');
        $this->logger->pushHandler(new PDOHandler($this->db, 'logs', Logger::INFO));
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
            // 트랜잭션 시작
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

            // 트랜잭션 커밋
            $this->db->commit();

            // 사용자 생성 로그 기록
            $this->logger->info("Created user", ['userId' => $userId, 'email' => $email]);

            return $userId;
        } catch (\Exception $e) {
            // 오류 발생 시 롤백 및 에러 로그 기록
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $this->logger->error("Failed to create user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // 사용자 정보 수정 (UPDATE) - 예시: 이름 수정, 트랜잭션 적용
    public function updateUserName($id, $name) {
        try {
            // 트랜잭션 시작
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("UPDATE users SET name = :name WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'id'   => $id
            ]);

            // 트랜잭션 커밋
            $this->db->commit();

            // 수정 작업에 대한 로그 기록
            $this->logger->info("Updated user", ['userId' => $id, 'newName' => $name]);
        } catch (\Exception $e) {
            // 오류 발생 시 롤백 및 에러 로그 기록
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $this->logger->error("Failed to update user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // 사용자 삭제 (DELETE) - 트랜잭션 적용
    public function deleteUser($id) {
        try {
            // 트랜잭션 시작
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);

            // 트랜잭션 커밋
            $this->db->commit();

            // 삭제 작업에 대한 로그 기록
            $this->logger->info("Deleted user", ['userId' => $id]);
        } catch (\Exception $e) {
            // 오류 발생 시 롤백 및 에러 로그 기록
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $this->logger->error("Failed to delete user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
