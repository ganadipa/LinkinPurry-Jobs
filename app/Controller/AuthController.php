<?php

namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\View\View;
use Core\Repositories;
use App\Validator\EmailValidator;
use App\Validator\PasswordValidator;
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
                    'css' => ['auth/shared.css', 'typography.css'],
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
                'data' => [

                ]
            ]);
            $res->send();
            return;
        }

        // Then there exist a valid user
        $res->redirect('/dashboard');
    }

    public static function register(Request $req, Response $res): void {
        // Register logic       
    }



}