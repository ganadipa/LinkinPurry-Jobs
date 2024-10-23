<?php

namespace Core;

use App\Controller\AttachmentController;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\CompanyController;
use App\Controller\JobController;
use App\Controller\LamaranController;
use App\Controller\LowonganController;
use App\Controller\ProfileController;
use App\Controller\LamaranController;
use App\Util\Enum\RequestMethodEnum;
use App\Middleware\RedirectIfLoggedInMiddleware;
use App\Middleware\RedirectIfNotLoggedInMiddleware;
use App\Middleware\FilesMiddleware;
use App\Middleware\IMiddleware;


use App\Util\EnvLoader;
use App\Http\Request;
use App\Http\Response;
use App\Model\Lamaran;

class App {
    private Router $router;
    // private IRepository $repo;
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
        $redirectIfNotLoggedInMiddleware = new RedirectIfNotLoggedInMiddleware();
        $cvAndVideoMiddleware = new FilesMiddleware(['cv', 'video']);
        $imagesMiddleware = new FilesMiddleware('images');

        // Register the routes

        // Auth Routes (GET)
        $this->router->register(RequestMethodEnum::GET, '/login', [AuthController::class, 'loginPage'], [
            $redirectIfLoggedInMiddleware
        ]);

        $this->router->register(RequestMethodEnum::GET, '/register', [AuthController::class, 'registerPage'], [
            $redirectIfLoggedInMiddleware
        ]);

        // Login
        $this->router->register(RequestMethodEnum::POST, '/login', [AuthController::class, 'login'], [
        ]);

        // Register
        $this->router->register(RequestMethodEnum::POST, '/register', [AuthController::class, 'register'], [
        ]);

        // Api Routes
        {        

            // Auth Routes (POST)


            // Logout
            $this->router->register(RequestMethodEnum::POST, '/api/logout', [AuthController::class, 'logout'], [
            ]);

            // Gets the current user
            $this->router->register(RequestMethodEnum::GET, '/api/self', [AuthController::class, 'self'], [
            ]);

            // Get job details
            $this->router->register(RequestMethodEnum::GET, '/api/jobs' , [JobController::class, 'generateJobs'], [
            ]);
        }

        // A job routes
        $this->router->register(RequestMethodEnum::GET, '/job/:id', [JobController::class, 'jobdetails'], [
        ]);

        {
            $this->router->register(RequestMethodEnum::DELETE, '/job/:id', [JobController::class, 'deleteJob'], [
            ]);

            $this->router->register(RequestMethodEnum::GET, '/job/:id/apply', [JobController::class, 'jobapplication'], [
                $redirectIfNotLoggedInMiddleware
            ]);

            $this->router->register(RequestMethodEnum::POST, '/job/:id/apply', [JobController::class, 'applyjob'], [
                $redirectIfNotLoggedInMiddleware,
                $cvAndVideoMiddleware
            ]);

            $this->router->register(RequestMethodEnum::POST, '/job/:id/togglestatus', [JobController::class, 'updateStatusJob'], [
                
            ]);

            $this->router->register(RequestMethodEnum::GET, '/job/:jobId/apply/:userId/cv', [JobController::class, 'appliedCV'], [
                
            ]);

            $this->router->register(RequestMethodEnum::GET, '/job/:jobId/apply/:userId/video', [JobController::class, 'appliedVideo'], [
                
            ]);

            $this->router->register(RequestMethodEnum::GET, '/company/job/:jobId/application/:applicantId', [JobController::class, 'applicationDetails'], [
                
            ]);

            $this->router->register(RequestMethodEnum::POST, '/company/job/:jobId/application/:applicantId/accept', [LamaranController::class, 'acceptApplication'], [
                
            ]);

            $this->router->register(RequestMethodEnum::POST, '/company/job/:jobId/application/:applicantId/reject', [LamaranController::class, 'rejectApplication'], [
                
            ]);
        }



            
        // Home Page Routes
        $this->router->register(RequestMethodEnum::GET, '/', [HomeController::class, 'showHomePage']);
        // {
        //     $this->router->register(RequestMethodEnum::GET, '/home/page', [HomeController::class, 'showHomePage']);
        //     $this->router->register(RequestMethodEnum::GET, '/home/:id', [HomeController::class, 'showProfile']);
        //     $this->router->register(RequestMethodEnum::POST, '/home/add/:id', [HomeController::class, 'addProfile']);
        //     $this->router->register(RequestMethodEnum::DELETE, '/home/remove/:id', [HomeController::class, 'removeProfile']);
        // }

        // Company Page Routes
        // $this->router->register(RequestMethodEnum::GET, '/company', [CompanyController::class, 'showCompanyPage']);
        {
            // $this->router->register(RequestMethodEnum::GET, '/company/:id', [CompanyController::class, 'showProfile']);
            // $this->router->register(RequestMethodEnum::GET, '/company/job', [CompanyController::class, 'showJobPage']);
            $this->router->register(RequestMethodEnum::GET, '/company/job/create', [CompanyController::class, 'showCreateJobPage']);
            $this->router->register(RequestMethodEnum::GET, '/company/job/:id/edit', [CompanyController::class, 'showEditJobPage']); 
            // $this->router->register(RequestMethodEnum::POST, '/company/update', [CompanyController::class, 'updateProfile']);
        }

        // Client Page Routes
        $this->router->register(RequestMethodEnum::GET, '/attachment', [AttachmentController::class, 'getList']);

        // Lowongan routes
        // Route to get a lowongan
        $this->router->register(RequestMethodEnum::GET, '/lowongan', [LowonganController::class, 'getList']);
        {
            // Route to create a lowongan
            $this->router->register(RequestMethodEnum::POST, '/lowongan/create', [LowonganController::class, 'create'], [
                $imagesMiddleware
            ]);
    
            // Route to update a lowongan
            $this->router->register(RequestMethodEnum::POST, '/lowongan/update/:id', [LowonganController::class, 'update']);
    
            // Route to delete a lowongan
            $this->router->register(RequestMethodEnum::POST, '/lowongan/delete/:id', [LowonganController::class, 'delete']);
        }

        // Route for Company Profile
        $this->router->register(RequestMethodEnum::GET, '/profile', [ProfileController::class, 'showProfile']);
        $this->router->register(RequestMethodEnum::GET, '/profile/update', [ProfileController::class, 'updateProfile']);

        // Route for Lamaran History
        $this->router->register(RequestMethodEnum::GET, '/job-seeker/history', [LamaranController::class, 'showHistoryPage']);
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
        DirectoryAlias::set('@uploads', __DIR__ . '/../uploads');
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
