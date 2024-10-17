<?php

use App\Controller\LowonganController;
use App\Repository\Db\DbLowongan;
use App\Util\Enum\RequestMethodEnum;

$host = $_ENV['ENVIRONMENT'] === 'docker' ? 'postgres-local' : 'localhost';
$port = $_ENV['POSTGRES_PORT'];
$dbname = $_ENV['POSTGRES_DB'];
$user = $_ENV['POSTGRES_USER'];
$password = $_ENV['POSTGRES_PASSWORD'];
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
$db = new PDO($dsn, $user, $password);

$lowonganRepo = new DbLowongan($db);
$lowonganController = new LowonganController($lowonganRepo);

// Route to create a lowongan
$router->register(RequestMethodEnum::POST, '/lowongan/create', function($data) use ($lowonganController) {
    $lowonganController->create($data['params']);
});

// Route to update a lowongan
$router->register(RequestMethodEnum::POST, '/lowongan/update/:id', function($data) use ($lowonganController) {
    $id = $data['params']['id'];
    $lowonganController->update($id, $_POST);  
});

// Route to delete a lowongan
$router->register(RequestMethodEnum::POST, '/lowongan/delete/:id', function($data) use ($lowonganController) {
    $id = $data['params']['id'];
    $lowonganController->delete($id);
});

