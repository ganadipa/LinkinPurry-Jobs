<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Core\App;
use Core\Repositories;
use App\Util\EnvLoader;
use App\Http\Request;
use App\Repository\Db\Db;
use App\Middleware\AuthMiddleware;
use App\Repository\Local\LocalFileRepository;


// If the environment is not docker, then env is not automatically loaded
if ($_ENV['ENVIRONMENT'] !== 'docker') {
    EnvLoader::load(__DIR__ . "/../.env");
}

$db = Db::getInstance();
Repositories::$attachmentLowongan = $db->attachmentLowongan;
Repositories::$companyDetail = $db->companyDetail;
Repositories::$lamaran = $db->lamaran;
Repositories::$lowongan = $db->lowongan;
Repositories::$user = $db->user;
Repositories::$file = new LocalFileRepository();


// Instantiate the app
$app = new App();

// set global middlewares
$app->setGlobalMiddlewares([
    new AuthMiddleware()
]);

// Register routes
$app->registerRoutes();

// Register the dir aliases
$app->setDirectoryAliases();

// Objectify the request
$req = new Request();   
$req->setUri($_SERVER['REQUEST_URI']);

// Handle the request
$app->handleRequest($req);
