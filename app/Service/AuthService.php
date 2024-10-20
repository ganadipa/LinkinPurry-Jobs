<?php

namespace App\Service;
use Core\Repositories;
use \Exception;
use App\Model\User;
use App\Model\CompanyDetail;
use App\Util\Enum\UserRoleEnum;
use App\View\View;

class AuthService {
    public static function login(string $email, string $password): User {
        // Get the repository
        $userRepo = Repositories::$user;

        // find user by email
        $user = $userRepo->findByEmail($email);

        // if user not found
        if (isset($user)) {
            if (!password_verify($password, $user->password)) {
                throw new Exception('Password is incorrect');
            }

            $_SESSION['id'] = $user->user_id;
            return $user;
        }

        // No valid user
        throw new Exception('User not found');
    }

    public static function registerUser(string $role, string $name, string $email, string $password): User {
        // Get the repository
        $userRepo = Repositories::$user;

        // Check if the email is already registered
        if ($userRepo->findByEmail($email)) {
            throw new Exception('Email already registered');
        }

        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Create the user
        $user = new User($email, $password, UserRoleEnum::from($role), $name);

        // Save the user
        $userRepo->save($user);

        return $user;
    }

    public static function registerCompany(string $user_id, string $lokasi, string $about): CompanyDetail {
        // Get the repository
        $companyRepo = Repositories::$companyDetail;

        // Create the company
        $company = new CompanyDetail($user_id, $lokasi, $about);

        // insert the company
        $companyRepo->insert($company);

        return $company;
    }

    public static function self(string $user_id): ?User {
        // Get the repository
        $userRepo = Repositories::$user;

        // find user by id
        $user = $userRepo->getUserProfileById($user_id);

        return $user;
    }

    // Render the view with the layout auth.
    public static function render(string $view, array $vars = []): string {
        return View::render('Layout', 'Auth', array_merge_recursive($vars, 
                [
                    'content' => View::render('Page', $view, $vars),
                    'css' => ['auth/shared.css', 'text.css'],
                ]
        ));
    }

    public static function registerView(): string {
        return self::render('Register', [
            'css' =>['auth/register.css'],
            'js' => ['auth/register.js'],
            'title' => 'Register'
        ]);
    }

    public static function loginView(): string {
        return self::render('Login', [
        'css' =>['auth/login.css'],
        'js' => ['auth/login.js'],
        'title' => 'Login'
        ]);
    }
}