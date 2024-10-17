<?php

namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\View\View;
use Core\Repositories;
use App\Validator\EmailValidator;
use App\Validator\PasswordValidator;
use App\Validator\UserRoleValidator;
use App\Service\AuthService;
use \Exception;


class AuthController {
    public static function loginPage(Request $req, Response $res): void {
        $html = self::render('login', [
            'css' =>['auth/login.css'],
            'js' => ['auth/login.js'],
            'title' => 'Login'
        ]);
        $res->setBody($html);
        $res->send();
    }

    public static function registerPage(Request $req, Response $res): void {
        $html = self::render('register', [
            'css' => ['auth/register.css'],
            'js' => ['auth/register.js'],
            'title' => 'Register'
        ]);
        $res->setBody($html);
        $res->send();
    }

    private static function render(string $view, array $vars = []): string {
        return View::render('Layout', 'auth', array_merge_recursive($vars, 
                [
                    'content' => View::render('Page', $view, $vars),
                    'css' => ['auth/shared.css', 'text.css'],
                ]
        ));
    }

    public static function login(Request $req, Response $res): void {
        // Validation of what is needed
        $emailValid = EmailValidator::validate($req->getPost('email', ''));
        $passwordValid = PasswordValidator::validate($req->getPost('password', ''));

        // Get the valid user
        try {
            $user = AuthService::login($emailValid, $passwordValid);
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
            $res->send();
            return;
        }


        // Then there exist a valid user
        $res->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'data' => [
                'user_id' => $user->user_id,
                'role' => $user->role,
                'name' => $user->nama,
                'email' => $user->email,
            ]
        ]);

        $res->send();
    }

    public static function register(Request $req, Response $res): void {
        $role = $req->getPost('userType', '');
        $name = $req->getPost('name', '');
        $email = $req->getPost('email', '');
        $password = $req->getPost('password', '');
        $confirmPassword = $req->getPost('confirmPassword', '');
        
        // Validation of what is needed
        $emailValid = EmailValidator::validate($email);
        $passwordValid = PasswordValidator::validate($password);
        $nameValid = $name;
        $roleValid = UserRoleValidator::validate($role);

        // Check if the password and confirm password are the same
        if ($passwordValid !== $confirmPassword) {
            $res->json([
                'status' => 'error',
                'message' => 'Password and Confirm Password do not match',
                'data' => null
            ]);
            $res->send();
            return;
        }

        // Register the user
        try {
            $user = AuthService::registerUser($roleValid, $nameValid, $emailValid, $passwordValid);

            if ($roleValid == 'company') {

                $location = $req->getPost('location', '');
                $about = $req->getPost('about', '');

                $company = AuthService::registerCompany($user->user_id, $location, $about);
            }
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
            $res->send();
            return;
        }

        // Then the user is registered
        $res->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user_id' => $user->user_id,
            ]
        ]);

        $res->send();

    }

    public static function logout(Request $req, Response $res): void {
        session_destroy();
    }

    public static function self(Request $req, Response $res): void {
        $user_id = $req->getSessionValue('id', null);
        
        if (!isset($user_id)) {
            $res->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => null
            ]);
            $res->send();
            return;
        }

        $user = AuthService::self($user_id);
        if (!isset($user)) {
            $res->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => null
            ]);
            $res->send();
            return;
        }

        $res->json([
            'status' => 'success',
            'message' => 'User found',
            'data' => [
                'user_id' => $user->user_id,
                'role' => $user->role,
                'name' => $user->nama,
                'email' => $user->email,
            ]
        ]);

        $res->send();
    }

    public static function currentUserInfo(Request $req, Response $res): void {
        $user = $req->getUser();
        $res->json(
            [
                'status' => 'success',
                'message' => 'User found',
                'data' => [
                    'user_id' => $user->user_id,
                    'role' => $user->role,
                    'name' => $user->nama,
                    'email' => $user->email,
                ]
            ]
        );

        $res->send();
    }





}