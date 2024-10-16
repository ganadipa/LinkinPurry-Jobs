<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Core\App;
use App\Util\EnvLoader;
use App\Http\Request;


// If prefix is /public/index.php, then remove it
$requestUri = $_SERVER['REQUEST_URI'];
if (strpos($requestUri, '/public/index.php') === 0) {
    $requestUri = substr($requestUri, strlen('/public/index.php'));
}

// If the environment is not docker, then env is not automatically loaded
if ($_ENV['ENVIRONMENT'] !== 'docker') {
    EnvLoader::load(__DIR__ . "/../.env");
}



// Instantiate the app
$app = new App();

// Register routes
$app->registerRoutes();

// Register the dir aliases
$app->setDirectoryAliases();

// Prepare the db connection
$app->prepareDbConnection();

// Objectify the request
$req = new Request();   
$req->setUri($requestUri);

// Handle the request
$app->handleRequest($req);
