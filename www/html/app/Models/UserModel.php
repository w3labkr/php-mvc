<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
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
            $this->db->commit();
            return $userId;
        } catch (\Exception $e) {
            $this->db->rollBack();
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
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // 사용자 삭제 (DELETE) - 트랜잭션 적용
    public function deleteUser($id) {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
