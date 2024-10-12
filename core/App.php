<?php

namespace Core;

use App\Controller\AuthController;
use App\Utils\RequestMethodEnum;
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

        $this->router->register(RequestMethodEnum::GET, '/:id', [AuthController::class, 'login'], [
            $redirectIfLoggedInMiddleware
        ]);

        $this->router->register(RequestMethodEnum::GET, '/:id/:nama', [AuthController::class, 'login'], [
            $redirectIfLoggedInMiddleware
        ]);
    }

    // The app handles the request by resolving the route
    public function handleRequest() {
        $this->router->resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_GET);
    }
}
