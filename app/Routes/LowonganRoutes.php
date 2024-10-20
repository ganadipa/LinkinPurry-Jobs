<?php

namespace App\Routes;

use App\Http\Request;
use App\Http\Response;
use \PDO;
use App\Controller\LowonganController;
use App\Service\LowonganService;
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
        $lowonganService = new LowonganService($lowonganRepo);
        $lowonganController = new LowonganController($lowonganService);


        
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        die("Failed to connect to the database. Please check your connection settings.");
    }
}
