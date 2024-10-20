<?php

namespace App\Routes;

use App\Http\Request;
use App\Http\Response;
use \PDO;
use App\Controller\LowonganController;
use \PDOException;
use App\Repository\Db\DbLowongan;
use App\Util\Enum\RequestMethodEnum;

function registerLowonganRoutes($router) {
    try {
        $host = $_ENV['POSTGRES_HOST'];
        $port = $_ENV['POSTGRES_PORT'];
        $dbname = $_ENV['POSTGRES_DB'];
        $user = $_ENV['POSTGRES_USER'];
        $password = $_ENV['POSTGRES_PASSWORD'];

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
        $db = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $lowonganRepo = new DbLowongan($db);
        $lowonganController = new LowonganController($lowonganRepo);

        // Route to create a lowongan
        $router->register(RequestMethodEnum::POST, '/lowongan/create', function(Request $req, Response $res) use ($lowonganController) {
            $lowonganController->create($req, $res);
        });

        // Route to update a lowongan
        $router->register(RequestMethodEnum::POST, '/lowongan/update/:id', function(Request $req, Response $res) use ($lowonganController) {
            $lowonganController->update($req, $res);
        });

        // Route to delete a lowongan
        $router->register(RequestMethodEnum::POST, '/lowongan/delete/:id', function(Request $req, Response $res) use ($lowonganController) {
            $lowonganController->delete($req, $res);
        });
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        die("Failed to connect to the database. Please check your connection settings.");
    }
}
