<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Core\App;
use Core\Repositories;
use App\Util\EnvLoader;
use App\Http\Request;
use App\Repository\Db\Db;
use App\Middleware\AuthMiddleware;


// If prefix is /public/index.php, then remove it
$requestUri = $_SERVER['REQUEST_URI'];
if (strpos($requestUri, '/public/index.php') === 0) {
    $requestUri = substr($requestUri, strlen('/public/index.php'));
}

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
$req->setUri($requestUri);

// Handle the request
$app->handleRequest($req);
