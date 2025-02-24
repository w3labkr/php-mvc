<?php

namespace App\Models;

use App\Core\Model;

/**
 * UserModel class is responsible for interacting with the 'users' table in the database.
 * It provides methods for creating, updating, deleting users and managing user-related data.
 */
class UserModel extends Model {

    /**
     * Constructor: Initializes the parent Model class.
     * It calls the parent constructor to set up the database connection and logger.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Find a user by their email.
     * 
     * @param string $email
     * @return array|null
     * @throws \PDOException
     */
    public function findByEmail(string $email) {
        try {
            // Begin a transaction to ensure the operation is atomic.
            $this->db->beginTransaction();
            // Prepare the SQL query to find a user by email.
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            // Fetch the user record.
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            // Commit the transaction.
            $this->db->commit();
            
            return $user;
        } catch (\PDOException $e) {
            // Rollback the transaction if there was an error.
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log the error and throw the exception.
            $this->logger->error("Failed to find user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new user.
     * 
     * @param string $name
     * @param string $email
     * @param string $password
     * @return int
     * @throws \PDOException
     */
    public function createUser(string $name, string $email, string $password) {
        try {
            // Begin a transaction.
            $this->db->beginTransaction();
            // Prepare the SQL query to insert a new user.
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            ]);
            // Get the last inserted user ID.
            $userId = (int) $this->db->lastInsertId();
            // Commit the transaction.
            $this->db->commit();
            
            return $userId;
        } catch (\PDOException $e) {
            // Rollback the transaction if there was an error.
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log the error and throw the exception.
            $this->logger->error("Failed to create user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update the user's name.
     * 
     * @param int $id
     * @param string $name
     * @throws \PDOException
     */
    public function updateUserName(int $id, string $name) {
        try {
            // Begin a transaction.
            $this->db->beginTransaction();
            // Prepare the SQL query to update the user's name.
            $stmt = $this->db->prepare("UPDATE users SET name = :name WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'id'   => $id
            ]);
            // Commit the transaction.
            $this->db->commit();
        } catch (\PDOException $e) {
            // Rollback the transaction if there was an error.
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log the error and throw the exception.
            $this->logger->error("Failed to update user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete a user.
     * 
     * @param int $id
     * @throws \PDOException
     */
    public function deleteUser(int $id) {
        try {
            // Begin a transaction.
            $this->db->beginTransaction();
            // Prepare the SQL query to delete the user by ID.
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            // Commit the transaction.
            $this->db->commit();
        } catch (\PDOException $e) {
            // Rollback the transaction if there was an error.
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log the error and throw the exception.
            $this->logger->error("Failed to delete user", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Set the reset password token for a user.
     * 
     * @param string $email
     * @param string $token
     * @param string $expires
     * @return bool
     * @throws \PDOException
     */
    public function setResetPasswordToken(string $email, string $token, string $expires) {
        try {
            // Begin a transaction.
            $this->db->beginTransaction();
            // Prepare the SQL query to update the reset password token.
            $stmt = $this->db->prepare("UPDATE users SET reset_password_token = :token, reset_password_token_expires = :expires WHERE email = :email");
            $result = $stmt->execute([
                'token' => $token,
                'expires' => $expires,
                'email' => $email
            ]);
            // Commit the transaction.
            $this->db->commit();

            return $result;
        } catch (\PDOException $e) {
            // Rollback the transaction if there was an error.
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log the error and throw the exception.
            $this->logger->error("Failed to set reset password token", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Find a user by reset password token.
     * 
     * @param string $token
     * @return array|null
     * @throws \PDOException
     */
    public function findByResetPasswordToken(string $token) {
        try {
            // Begin a transaction.
            $this->db->beginTransaction();
            // Prepare the SQL query to find the user by reset password token.
            $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_password_token = :token LIMIT 1");
            $stmt->execute(['token' => $token]);
            // Fetch the user record.
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            // Commit the transaction.
            $this->db->commit();

            return $user;
        } catch (\PDOException $e) {
            // Rollback the transaction if there was an error.
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log the error and throw the exception.
            $this->logger->error("Failed to find user by reset token", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Reset a user's password.
     * 
     * @param string $token
     * @param string $password
     * @return bool
     * @throws \PDOException
     */
    public function resetPassword(string $token, string $password) {
        try {
            // Begin a transaction.
            $this->db->beginTransaction();
            // Hash the new password.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Prepare the SQL query to reset the password.
            $stmt = $this->db->prepare("UPDATE users SET password = :password, reset_password_token = NULL WHERE reset_password_token = :token");
            $result = $stmt->execute(['password' => $hashedPassword, 'token' => $token]);
            // Commit the transaction.
            $this->db->commit();

            return $result;
        } catch (\PDOException $e) {
            // Rollback the transaction if there was an error.
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log the error and throw the exception.
            $this->logger->error("Failed to reset password", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
