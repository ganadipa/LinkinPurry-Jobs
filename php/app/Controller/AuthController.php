<?php

namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
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
        $html = AuthService::loginView();
        $res->setBody($html);
        $res->send();
    }

    // Register page
    public static function registerPage(Request $req, Response $res): void {
        $html = AuthService::registerView();
        $res->setBody($html);
        $res->send();
    }



    public static function login(Request $req, Response $res): void {
        try {
            // Validation of what is needed
            $emailValid = EmailValidator::validate($req->getPost('email', ''));
            $passwordValid = PasswordValidator::validate($req->getPost('password', ''));

            // if its too long (> 50 chars) 
            if (strlen($emailValid) > 50) {
                throw new BadRequestException('Email must be not more than 50 characters');
            }

            if (strlen($passwordValid) > 50) {
                throw new BadRequestException('Password must be not more than 50 characters');
            }


            
            // Get the valid user
            $user = AuthService::login($emailValid, $passwordValid);



            // Then the user is logged in
            $res->redirect('/');
            $res->send();
        } catch (HttpException $e) {
            // Either its a HttpException
            $res->redirect('/login?type=error&message=' . $e->getMessage());
            $res->send();
        } catch (Exception $e) {
            // Or its just no valid user
            $res->redirect('/login?type=error&message=' . $e->getMessage());
            $res->send();
        }

    }

    public static function register(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            if ($user !== null) {
                $res->json([
                    'status' => 'error',
                    'message' => 'Cannot register when logged in',
                    'data' => null
                ]);
                $res->send();
                return;
            }

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

            if (strlen($emailValid) > 50) {
                throw new BadRequestException('Email must be not more than 50 characters');
            }

            if (strlen($passwordValid) > 50) {
                throw new BadRequestException('Password must be not more than 50 characters');
            }

            if (strlen($nameValid) > 50) {
                throw new BadRequestException('Name must be not more than 50 characters');
            }

            if ($roleValid == 'company') {
                $location = $req->getPost('location', '');
                $about = $req->getPost('about', '');

                if (strlen($location) > 100) {
                    throw new BadRequestException('Location must be not more than 100 characters');
                }

                if (strlen($about) > 250) {
                    throw new BadRequestException('About must be not more than 250 characters');
                }
            }
    
            // Check if the password and confirm password are the same
            if ($passwordValid !== $confirmPassword) {
                throw new BadRequestException('Password and confirm password are not the same');
            }
    
            // Register the user
            $user = AuthService::registerUser($roleValid, $nameValid, $emailValid, $passwordValid);
    
            if ($roleValid == 'company') {
    
                $location = $req->getPost('location', '');
                $about = $req->getPost('about', '');
    
                AuthService::registerCompany($user->user_id, $location, $about);
            }
            
            AuthService::login($emailValid, $passwordValid);

            // Then the user is registered
            $res->redirect('/');
            $res->send();
        } catch (HttpException $e) {
            // Either its a classified HttpException
            $res->redirect('/register?type=error&message=' . $e->getMessage());
            $res->send();
        } catch (Exception $e) {
            // Or its just an ordinary exception    
            $res->redirect('/register?type=error&message=' . $e->getMessage());
            $res->send();
        }


    }

    public static function logout(Request $req, Response $res): void {
        // If the user is not logged in, then just return
        $user = $req->getUser();
        if ($user === null) {
            $res->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => null
            ]);
            
            $res->send();
            return;
        }

        session_destroy();

        $res->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
            'data' => null
        ]);

        $res->send();
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