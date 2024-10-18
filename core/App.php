<?php

namespace Core;

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\CompanyController;
use App\Controller\JobController;

use App\Util\Enum\RequestMethodEnum;
use App\Middleware\RedirectIfLoggedInMiddleware;
use App\Middleware\IMiddleware;

use App\Repository\Db\Db;

use App\Util\EnvLoader;
use App\Http\Request;
use App\Http\Response;

class App {
    private Router $router;
    private IRepository $repo;
    public static array $globalMiddlewares = [];

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

        // Root, for home page
        // $this->router->register(RequestMethodEnum::GET, '/', [AuthController::class, 'currentUserInfo']);

        // Auth Routes (GET)
        $this->router->register(RequestMethodEnum::GET, '/login', [AuthController::class, 'loginPage'], [
            $redirectIfLoggedInMiddleware
        ]);

        $this->router->register(RequestMethodEnum::GET, '/register', [AuthController::class, 'registerPage'], [
            $redirectIfLoggedInMiddleware
        ]);

        // Api Routes
        {        

            // Auth Routes (POST)

            // Login
            $this->router->register(RequestMethodEnum::POST, '/api/login', [AuthController::class, 'login'], [
            ]);

            // Register
            $this->router->register(RequestMethodEnum::POST, '/api/register', [AuthController::class, 'register'], [
            ]);

            // Logout
            $this->router->register(RequestMethodEnum::POST, '/api/logout', [AuthController::class, 'logout'], [
            ]);

            // Gets the current user
            $this->router->register(RequestMethodEnum::GET, '/api/self', [AuthController::class, 'self'], [
            ]);
        }

        // A job routes
        $this->router->register(RequestMethodEnum::GET, '/job/:id', [JobController::class, 'jobdetails'], [
            // Redirect to /login if not logged in
            // Not implemented yet
        ]);

        {
            $this->router->register(RequestMethodEnum::GET, '/job/:id/apply', [JobController::class, 'jobapplication'], [
                // Redirect to /login if not logged in
                // Not implemented yet
            ]);
        }



            
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
    public function handleRequest(Request $req): void {
        // Apply the global middlewares
        foreach (self::$globalMiddlewares as $middleware) {
            $ok = $middleware->handle($req);
            if (!$ok) {
                $res = new Response();
                $res->json([
                    'status' => 'error',
                    'message' => $middleware->getMessage(),
                    'data' => null
                ]);
                $res->send();
                return;
            }
        }

        // then resolve the route
        $this->router->resolve($req);
    }

    // Set the directory aliases
    public function setDirectoryAliases(): void {
        DirectoryAlias::set('@core', __DIR__);
        DirectoryAlias::set('@app', __DIR__ . '/../app');
        DirectoryAlias::set('@public', __DIR__ . '/../public');
        DirectoryAlias::set('@view', __DIR__ . '/../app/View');
    }

    // set the global middlewares
    public function setGlobalMiddlewares(array $middlewares): void {
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof IMiddleware) {
                array_push(self::$globalMiddlewares, $middleware);
            }
        }
    }
}
