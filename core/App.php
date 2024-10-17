<?php

namespace Core;

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\CompanyController;
use App\Util\Enum\RequestMethodEnum;
use App\Middleware\RedirectIfLoggedInMiddleware;
use App\Middleware\IMiddleware;
use App\Repository\Db\Db;
use App\Util\EnvLoader;

class App {
    private Router $router;

    public function __construct() {
        $this->router = new Router();
    }

    // Load the environment variables
    public function loadEnv(string $path): void {
        EnvLoader::load($path);
    }

    // Register the routes
    public function registerRoutes() {
        // Define the needed middlewares 
        $redirectIfLoggedInMiddleware = new RedirectIfLoggedInMiddleware();


        // Register the routes
        $this->router->register(RequestMethodEnum::GET, '/', [AuthController::class, 'login'], [
            $redirectIfLoggedInMiddleware
        ]);

        // $this->router->register(RequestMethodEnum::GET, '/:id', [AuthController::class, 'login'], [
        //     $redirectIfLoggedInMiddleware
        // ]);

        $this->router->register(RequestMethodEnum::GET, '/:id/profile', [AuthController::class, 'login'], [
            $redirectIfLoggedInMiddleware
        ]);
        
        // Home Page Routes
        $this->router->register(RequestMethodEnum::GET, '/home', [HomeController::class, 'home']);
        {
            $this->router->register(RequestMethodEnum::GET, '/home/page', [HomeController::class, 'showHomePage']);
            $this->router->register(RequestMethodEnum::GET, '/home/:id', [HomeController::class, 'showProfile']);
            $this->router->register(RequestMethodEnum::POST, '/home/add/:id', [HomeController::class, 'addProfile']);
            $this->router->register(RequestMethodEnum::DELETE, '/home/remove/:id', [HomeController::class, 'removeProfile']);
        }

        // Company Page Routes
        $this->router->register(RequestMethodEnum::GET, '/company', [CompanyController::class, 'showCompanyPage']);
        {
            $this->router->register(RequestMethodEnum::GET, '/company/:id', [CompanyController::class, 'showProfile']);
            $this->router->register(RequestMethodEnum::POST, '/company/update', [CompanyController::class, 'updateProfile']);
        }

        // Client Page Routes
        $this->router->register(RequestMethodEnum::GET, '/client', [HomeController::class, 'clientPage']);
    }

    // The app handles the request by resolving the route
    public function handleRequest(string $requestUri, string $requestMethod, array $queryParams): void {
        $this->router->resolve($requestMethod, $requestUri, $queryParams);
    }

    // Prepare the db connection
    public function prepareDbConnection() {
        Db::getInstance();
    }
}
