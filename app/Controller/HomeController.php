<?php

namespace App\Controller;

class HomeController {
    public static function home() {
        echo "Home";
    }

    public static function showProfile($params) {
        echo "Show Profile with id: " . $params['params']['id'];
    }

    public static function showHomePage() {
        $viewPath = dirname(__DIR__) . '/View/HomeView.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View not found";
        }
    }
}
