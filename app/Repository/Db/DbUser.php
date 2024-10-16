<?php

namespace App\Repository\Db;
use App\Model\User;
use App\Repository\Interface\RUser;
use \PDO;
use App\Util\Enum\UserRoleEnum;


class DbUser implements RUser {

    public function __construct(private PDO $db) {}

    public function createTable() {
        
        try {
            // Create the type constraint
            $this->db->exec('
                CREATE TYPE user_role AS ENUM (
                    \''.UserRoleEnum::JOBSEEKER->value.'\',
                    \''.UserRoleEnum::COMPANY->value.'\'
                )
            ');

            // Create the table
            $this->db->exec('
                CREATE TABLE IF NOT EXISTS users (
                    user_id SERIAL PRIMARY KEY,
                    email VARCHAR(255) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    role user_role NOT NULL
                )
            ');

            echo "Table users created successfully";
        } catch (PDOException $e) {
            error_log('Create table error: ' . $e->getMessage());
            throw new Exception('Create table error. Please try again later.');
        }
    }

    public function deleteTable() {
        try {
            $this->db->exec('
                DROP TABLE IF EXISTS users
            ');

            $this->db->exec('
                DROP TYPE IF EXISTS user_role
            ');
        } catch (PDOException $e) {
            error_log('Delete table error: ' . $e->getMessage());
            throw new Exception('Delete table error. Please try again later.');
        }
    }

    public function insert(User $user): User {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO users (email, password, role)
                VALUES (:email, :password, :role)
            ');

            $stmt->execute([
                'email' => $user->email,
                'password' => $user->password,
                'role' => $user->role->value,
            ]);

            $user->user_id = (int) $this->db->lastInsertId();
            return $user;
        } catch (PDOException $e) {
            error_log('Insert user error: ' . $e->getMessage());
            throw new Exception('Insert user error. Please try again later.');
        }
    }

    public function delete(int $userId): User {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM users
                WHERE user_id = :user_id
            ');

            $stmt->execute([
                'user_id' => $userId,
            ]);

            $user = new User();
            $user->user_id = $userId;
            return $user;
        } catch (PDOException $e) {
            error_log('Delete user error: ' . $e->getMessage());
            throw new Exception('Delete user error. Please try again later.');
        }
    }
}