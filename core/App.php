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

        /**
         * Login page
         * 
         * @route /login
         * @method GET
         * 
         * must be redirected if logged in
         */
        $this->router->register(RequestMethodEnum::GET, '/login', [AuthController::class, 'loginPage'], [
            $redirectIfLoggedInMiddleware
        ]);

        /**
         * Register page
         * 
         * @route /register
         * @method GET
         * 
         * must be redirected if logged in
         */
        $this->router->register(RequestMethodEnum::GET, '/register', [AuthController::class, 'registerPage'], [
            $redirectIfLoggedInMiddleware
        ]);

        /**
         * Login action
         * 
         * @route /login
         * @method POST
         * 
         * must be not logged in
         */
        $this->router->register(RequestMethodEnum::POST, '/login', [AuthController::class, 'login'], [
        ]);

        // Register
        /**
         * Register action
         * 
         * @route /register
         * @method POST
         * 
         * must be not logged in
         */
        $this->router->register(RequestMethodEnum::POST, '/register', [AuthController::class, 'register'], [
        ]);

        // Api Routes
        {        

            // Auth Routes (POST)


            // Logout
            /**
             * Logout action
             * 
             * @route /api/logout
             * @method POST
             * 
             * must be logged in
             */
            $this->router->register(RequestMethodEnum::POST, '/api/logout', [AuthController::class, 'logout'], [
            ]);

            // Gets the current user
            /**
             * Get the current user
             * 
             * @route /api/self
             * @method GET
             * 
             * must be logged in
             * otherwise will return an error message
             */
            $this->router->register(RequestMethodEnum::GET, '/api/self', [AuthController::class, 'self'], [
            ]);

            /**
             * Get a list of job
             * 
             * @route /api/jobs
             * @method GET
             * 
             * must be logged in
             * 
             */
            $this->router->register(RequestMethodEnum::GET, '/api/jobs' , [JobController::class, 'generateJobs'], [
            ]);
        }

        // A job routes
        /**
         * Get a job details (PAGE)
         * 
         * @route /job/:id
         * 
         * Can be accessed by anyone
         */
        $this->router->register(RequestMethodEnum::GET, '/job/:id', [JobController::class, 'jobdetails'], [
            
        ]);

        {

            /**
             * Delete a job
             * 
             * @route /job/:id
             * 
             * Must be a company, and the job must be owned by the company
             */
            $this->router->register(RequestMethodEnum::DELETE, '/job/:id', [JobController::class, 'deleteJob'], [
            ]);

            /**
             * Apply a job (Page)
             * 
             * @route /job/:id/apply
             * 
             * Must be a jobseeker and that job must not be applied yet
             * else if not logged in will be redirected to login page
             * otherwise 404.
             */
            $this->router->register(RequestMethodEnum::GET, '/job/:id/apply', [JobController::class, 'jobapplication'], [
                $redirectIfNotLoggedInMiddleware
            ]);

            /**
             * Apply a job
             * 
             * @route /job/:id/apply
             * @method POST
             * 
             * Must be a jobseeker and that job must not be applied yet
             * else if not logged in will be redirected to login page
             * otherwise 404.
             */
            $this->router->register(RequestMethodEnum::POST, '/job/:id/apply', [JobController::class, 'applyjob'], [
                $redirectIfNotLoggedInMiddleware,
                $cvAndVideoMiddleware
            ]);

            /**
             * Toggle the status of a job
             * 
             * @route /job/:id/togglestatus
             * @method POST
             *  
             * Must be a company and the job must be owned by the company
             */
            $this->router->register(RequestMethodEnum::POST, '/job/:id/togglestatus', [JobController::class, 'updateStatusJob'], [
                
            ]);

            /**
             * Get the applied CV (page)
             * 
             * @route /job/:jobId/apply/:userId/cv
             * @method GET
             * 
             * Must be a company and the job must be owned by the company
             * Or must be a jobseeker and the job must be applied by the jobseeker
             * Otherwise 404
             */
            $this->router->register(RequestMethodEnum::GET, '/job/:jobId/apply/:userId/cv', [JobController::class, 'appliedCV'], [
                $redirectIfNotLoggedInMiddleware
            ]);

            /**
             * Get the applied video (page)
             * 
             * @route /job/:jobId/apply/:userId/video
             * @method GET
             * 
             * Must be a company and the job must be owned by the company
             * Or must be a jobseeker and the job must be applied by the jobseeker
             * Otherwise 404
             */
            $this->router->register(RequestMethodEnum::GET, '/job/:jobId/apply/:userId/video', [JobController::class, 'appliedVideo'], [
                $redirectIfNotLoggedInMiddleware
            ]);


            /**
             * Get the application details (Page)
             * 
             * @route /company/job/:jobId/application/:applicantId
             * @method GET
             * 
             * Must be a company and the job must be owned by the company
             * Otherwise 404
             */
            $this->router->register(RequestMethodEnum::GET, '/company/job/:jobId/application/:applicantId', [JobController::class, 'applicationDetails'], [
                $redirectIfNotLoggedInMiddleware
            ]);

            /**
             * Accept an application
             * 
             * @route /company/job/:jobId/application/:applicantId/accept
             * @method POST
             * 
             * Must be a company and the job must be owned by the company
             * Otherwise forbidden
             */
            $this->router->register(RequestMethodEnum::POST, '/company/job/:jobId/application/:applicantId/accept', [LamaranController::class, 'acceptApplication'], [
                
            ]);

            /**
             * Reject an application
             * 
             * @route /company/job/:jobId/application/:applicantId/reject
             * @method POST
             * 
             * Must be a company and the job must be owned by the company
             * Otherwise forbidden
             */
            $this->router->register(RequestMethodEnum::POST, '/company/job/:jobId/application/:applicantId/reject', [LamaranController::class, 'rejectApplication'], [
                
            ]);
        }



            
        // Home Page Routes
        /**
         * Home Page (Page)
         * 
         * @route /
         * @method GET
         * 
         * Can be accessed by anyone
         */
        $this->router->register(RequestMethodEnum::GET, '/', [HomeController::class, 'showHomePage']);

        {
            /**
             * Get the form to create a job (Page)
             * 
             * @route /company/job/create
             * @method GET
             * 
             * Only company can access this page
             * If not logged in will be redirected to login page
             * Otherwise 404
             */
            $this->router->register(RequestMethodEnum::GET, '/company/job/create', [CompanyController::class, 'showCreateJobPage'], [
                $redirectIfNotLoggedInMiddleware
            ]);

            /**
             * Get the form to edit a job (Page)
             * 
             * @route /company/job/:id/edit
             * @method GET
             * 
             * Only company can access this page
             * If not logged in will be redirected to login page
             * Otherwise 404
             */
            $this->router->register(RequestMethodEnum::GET, '/company/job/:id/edit', [CompanyController::class, 'showEditJobPage'], [
                $redirectIfNotLoggedInMiddleware
            ]); 
        }

        // Lowongan routes
        {
            // Route to create a lowongan
            /**
             * Create a lowongan
             * 
             * @route /lowongan/create
             * @method POST
             * 
             * Only company can access this resource
             * otherwise forbidden
             */
            $this->router->register(RequestMethodEnum::POST, '/lowongan/create', [LowonganController::class, 'create'], [
                $imagesMiddleware
            ]);
    
            // Route to update a lowongan
            /**
             * Update a lowongan
             * 
             * @route /lowongan/update/:id
             * @method POST
             * 
             * Only company that owns the lowongan can access this resource
             * otherwise forbidden
             */
            $this->router->register(RequestMethodEnum::POST, '/lowongan/update/:id', [LowonganController::class, 'update'], [
                $imagesMiddleware
            ]);
    
            // Route to delete a lowongan
            /**
             * Delete a lowongan
             * 
             * @route /lowongan/delete/:id
             * @method POST
             * 
             * Only company that owns the lowongan can access this resource
             * otherwise forbidden
             */
            $this->router->register(RequestMethodEnum::POST, '/lowongan/delete/:id', [LowonganController::class, 'delete']);
        }

        // Route for Company Profile

        /**
         * Show the company profile (Page)
         * 
         * @route /profile
         * @method GET
         * 
         * Only company can access this page
         * If not logged in will be redirected to login page
         * Otherwise 404
         * 
         */
        $this->router->register(RequestMethodEnum::GET, '/profile', [ProfileController::class, 'showProfile'], [
            $redirectIfNotLoggedInMiddleware
        ]);

        /**
         * Update the company profile
         * 
         * @route /profile/update
         * @method POST
         * 
         * Only company can access this page
         * Otherwise forbidden
         */
        $this->router->register(RequestMethodEnum::POST, '/profile/update', [ProfileController::class, 'updateProfile']);    

        // Route for Lamaran History
        /**
         * Show the jobseeker history (Page)
         * 
         * @route /jobseeker/history
         * @method GET
         * 
         * Only jobseeker can access this page
         * If not logged in will be redirected to login page
         * Otherwise 404
         */
        $this->router->register(RequestMethodEnum::GET, '/jobseeker/history', [LamaranController::class, 'showHistoryPage'], [
            $redirectIfNotLoggedInMiddleware
        ]);

        /**
         * Get an attachment of a lowongan
         * 
         * @route /attachmentlowongan/:attachmentId
         * @method GET
         * 
         * Can be accessed by anyone
         */
        $this->router->register(RequestMethodEnum::GET, '/attachmentlowongan/:attachmentId', [AttachmentController::class, 'getPublicAttachment']);
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
