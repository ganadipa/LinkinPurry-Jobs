<?php

namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\View\View;
use App\Validator\EmailValidator;
use App\Validator\PasswordValidator;
use App\Http\Exception\BadRequestException;
use App\Validator\UserRoleValidator;
use App\Service\AuthService;
use App\Http\Exception\HttpException;
use Exception;


class AuthController {
    // Login page
    public static function loginPage(Request $req, Response $res): void {
        $html = self::render('Login', [
            'css' =>['auth/login.css'],
            'js' => ['auth/login.js'],
            'title' => 'Login'
        ]);
        $res->setBody($html);
        $res->send();
    }

    // Register page
    public static function registerPage(Request $req, Response $res): void {
        $html = self::render('Register', [
            'css' => ['auth/register.css'],
            'js' => ['auth/register.js'],
            'title' => 'Register'
        ]);
        $res->setBody($html);
        $res->send();
    }

    // Render the view with the layout auth.
    private static function render(string $view, array $vars = []): string {
        return View::render('Layout', 'Auth', array_merge_recursive($vars, 
                [
                    'content' => View::render('Page', $view, $vars),
                    'css' => ['auth/shared.css', 'text.css'],
                ]
        ));
    }

    public static function login(Request $req, Response $res): void {
        try {
            // Validation of what is needed
            $emailValid = EmailValidator::validate($req->getPost('email', ''));
            $passwordValid = PasswordValidator::validate($req->getPost('password', ''));


            // Get the valid user
            $user = AuthService::login($emailValid, $passwordValid);

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
        } catch (HttpException $e) {
            // Either its a HttpException

            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
            $res->send();
        } catch (Exception $e) {
            // Or its just no valid user

            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
            $res->send();
        }

    }

    public static function register(Request $req, Response $res): void {
        try {
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
                throw new BadRequestException('Password and confirm password are not the same');
            }
    
            // Register the user
            $user = AuthService::registerUser($roleValid, $nameValid, $emailValid, $passwordValid);
    
            if ($roleValid == 'company') {
    
                $location = $req->getPost('location', '');
                $about = $req->getPost('about', '');
    
                $company = AuthService::registerCompany($user->user_id, $location, $about);
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
        } catch (HttpException $e) {
            // Either its a classified HttpException

            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);

            $res->send();
        } catch (Exception $e) {
            // Or its just an ordinary exception

            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);

            $res->send();
        }


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