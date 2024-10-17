<?php

namespace App\Service;
use Core\Repositories;
use \Exception;

class AuthService {
    public static function login(string $email, string $password): User {
        // Get the repository
        $userRepo = Repositories::$user;

        // find user by email
        $user = $userRepo->findByEmail($email);

        // if user not found
        if (isset($user) && password_verify($password, $user->password)) {
            $_SESSION['id'] = $user->user_id;
            return $user;
        }

        // No valid user
        throw new Exception('User not found');
    }
}