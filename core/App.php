<?php

namespace Core;

use App\Controller\AuthController;
use App\Util\Enum\RequestMethodEnum;
use App\Middleware\RedirectIfLoggedInMiddleware;
use App\Middleware\IMiddleware;


class App {
    private Router $router;

    public function __construct() {
        $this->router = new Router();
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

        // $this->router->register(RequestMethodEnum::GET, '/:id/:nama', [AuthController::class, 'login'], [
        //     $redirectIfLoggedInMiddleware
        // ]);
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
