<?php

namespace App\Controller;

use \App\Repository\Db\Db;
use \App\Repository\Db\DbUser;
use \App\View\View;
use \PDOException;
use \App\Util\Enum\UserRoleEnum;
use \App\Http\Request;
use \App\Http\Response;

class HomeController {
    public static function home() {
        $viewPath = dirname(__DIR__) . '/View/HomePage.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View not found";
        }
    }

    public static function showProfile($params) {
        try {
            $user_id = (int) $params['params']['id'];
            echo "User id: $user_id<br>";

            // Use the singleton instance of Db to get the PDO connection
            $dbInstance = Db::getInstance();
            $db = $dbInstance->getConnection();
            $dbUser = new DbUser($db);

            $user = $dbUser->getUserProfileById($user_id);
            if ($user) {
                echo "User ID: {$user->user_id}<br>";
                echo "Email: {$user->email}<br>";
                echo "Role: {$user->role->value}<br>";
            } else {
                echo "User not found<br>";
            }

            echo "<br>Show All Users<br>";
            $users = $dbUser->getAllUsers();
            foreach ($users as $user) {
                echo "User ID: {$user->user_id}<br>";
                echo "Email: {$user->email}<br>";
                echo "Role: {$user->role->value}<br>";
                echo "<br>";
            }

        } catch (PDOException $e) {
            error_log('Show profile error: ' . $e->getMessage());
            echo 'Error: ' . $e->getMessage() . '<br>';
        }
    }    

    public static function addProfile($params) {
        try {
            $user_id = (int) $params['params']['id'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'] === 'COMPANY' ? UserRoleEnum::COMPANY : UserRoleEnum::JOBSEEKER;

            $dbInstance = Db::getInstance();
            $db = $dbInstance->getConnection();
            $dbUser = new DbUser($db);

            $dbUser->saveProfile($user_id, $email, $password, $role);

            echo json_encode(['success' => true, 'message' => 'User profile added/updated successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function removeProfile($params) {
        try {
            $user_id = (int) $params['params']['id'];

            $dbInstance = Db::getInstance();
            $db = $dbInstance->getConnection();
            $dbUser = new DbUser($db);

            $removed = $dbUser->removeProfile($user_id);
            if ($removed) {
                echo json_encode(['success' => true, 'message' => 'User profile removed successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // public static function clientPage() {
    //     $viewPath = dirname(__DIR__) . '/View/ClientView.php';
    //     if (file_exists($viewPath)) {
    //         require_once $viewPath;
    //     } else {
    //         echo "View not found";
    //     }
    // }

    public static function showHomePage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || $user->role === UserRoleEnum::JOBSEEKER) {
            $html = self::render('HomeJobSeeker', [
                'css' => ['home/home.css'],
                'js' => ['home/home.js'],
                'title' => 'Home Page (Job Seeker)',
            ]);
        } else {
            $html = self::render('HomeCompany', [
                'css' => ['home/home.css'],
                'js' => ['home/home.js'],
                'title' => 'Home Page (Company)',
            ]);
        }
        
        $res->setBody($html);
        $res->send();
    }

    private static function render(string $view, array $vars = []): string {
        return View::render('Layout', 'Main', array_merge_recursive($vars, 
            [
                'content' => View::render('Page', $view, $vars),
                'css' => ['company/shared.css'],
            ]
        ));
    }
}
