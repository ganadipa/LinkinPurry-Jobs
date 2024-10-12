<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Core\App;


// Prepare

// Instantiate the app
$app = new App();

// Register routes
$app->registerRoutes();

// Handle the request
/**
 *  param:
 *  1. $_SERVER['REQUEST_URI'] as the request path
 *  2. $_SERVER['REQUEST_METHOD'] as the request method
 *  3. $_GET as the query parameters
 * 
 *  */ 
$app->handleRequest($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $_GET);
