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
use App\Http\Request;

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
        $this->router->register(RequestMethodEnum::GET, '/home/page', [HomeController::class, 'showHomePage']);
        $this->router->register(RequestMethodEnum::GET, '/home/:id', [HomeController::class, 'showProfile']);

        // Company Page Routes
        $this->router->register(RequestMethodEnum::GET, '/company/:id/profile', [CompanyController::class, 'showProfile'], [
            $redirectIfLoggedInMiddleware
        ]); 
    }

    // The app handles the request by resolving the route
    public function handleRequest(Request $req): void {
        $this->router->resolve($req);
    }

    // Prepare the db connection
    public function prepareDbConnection() {
        Db::getInstance();
    }

    // Set the directory aliases
    public function setDirectoryAliases(): void {
        DirectoryAlias::set('@core', __DIR__);
        DirectoryAlias::set('@app', __DIR__ . '/../app');
        DirectoryAlias::set('@public', __DIR__ . '/../public');
        DirectoryAlias::set('@view', __DIR__ . '/../app/View');
    }
}
