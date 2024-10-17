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

    public function getUserProfileById(int $userId): ?User {
        try {
            $stmt = $this->db->prepare('
                SELECT user_id, email, role
                FROM users
                WHERE user_id = :user_id
            ');
    
            $stmt->execute([
                'user_id' => $userId,
            ]);
    
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$userData) {
                // throw new Exception("User with ID $userId not found.");
                return null;
            }
    
            return new User(
                user_id: (int) $userData['user_id'],
                email: $userData['email'],
                password: '',
                role: UserRoleEnum::from($userData['role']), 
                nama: ''
            );
        } catch (PDOException $e) {
            error_log('Get user profile error: ' . $e->getMessage());
            throw new Exception('Get user profile error. Please try again later.');
        }
    }    

    public function getAllUsers(): array {
        try {
            $stmt = $this->db->query('
                SELECT user_id, email, role
                FROM users
            ');
            
            $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = [];
            
            foreach ($usersData as $userData) {
                $user = new User(
                    user_id: (int) $userData['user_id'],
                    email: $userData['email'],
                    password: '', 
                    role: UserRoleEnum::from($userData['role']),
                    nama: ''
                );
                $users[] = $user;
            }
    
            return $users;
    
        } catch (PDOException $e) {
            error_log('Get all users error: ' . $e->getMessage());
            throw new Exception('Get all users error. Please try again later.');
        }
    }
    

    public function saveProfile(int $userId, string $email, string $password, UserRoleEnum $role): void {
        try {
            $stmt = $this->db->prepare('
                SELECT user_id
                FROM users
                WHERE user_id = :user_id
            ');
            $stmt->execute([
                'user_id' => $userId,
            ]);
            
            $existingUser = $stmt->fetch();
            
            if ($existingUser) {
                $stmt = $this->db->prepare('
                UPDATE users
                SET email = :email, password = :password, role = :role
                WHERE user_id = :user_id
                ');
                
                $stmt->execute([
                    'email' => $email,
                    'password' => $password,
                    'role' => $role->value,
                    'user_id' => $userId,
                ]);
                
                echo "User profile updated successfully<br>";
                
            } else {
                $stmt = $this->db->prepare('
                INSERT INTO users (email, password, role)
                VALUES (:email, :password, :role)
                ');
                
                $stmt->execute([
                    'email' => $email,
                    'password' => $password,
                    'role' => $role->value,
                ]);
                
                echo "User profile inserted successfully<br>";
            }
            
        } catch (PDOException $e) {
            error_log('Save profile error: ' . $e->getMessage());
            throw new Exception('Save profile error. Please try again later.');
        }
    }
    
    public function removeProfile(int $userId): bool {
        try {
            echo "Removing user profile with ID: $userId...<br>";
            
            // Prepare the delete statement
            $stmt = $this->db->prepare('
                DELETE FROM users
                WHERE user_id = :user_id
            ');
            
            // Execute the statement with the provided user ID
            $stmt->execute([
                'user_id' => $userId,
            ]);
            
            if ($stmt->rowCount() > 0) {
                // If a row was deleted, the profile was successfully removed
                echo "User profile with ID $userId removed successfully.<br>";
                return true;
            } else {
                // If no row was deleted, the user wasn't found
                echo "No user profile found with ID $userId.<br>";
                return false;
            }
    
        } catch (PDOException $e) {
            error_log('Remove profile error: ' . $e->getMessage());
            throw new Exception('Remove profile error. Please try again later.');
        }
    }
    
}