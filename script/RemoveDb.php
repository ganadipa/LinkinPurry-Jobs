<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Repository\Db\Db;
use App\Util\EnvLoader;

// Load environment variables;
if (getenv('ENVIRONMENT') !== 'docker') {
    EnvLoader::load(__DIR__ . "/../.env.local");
}

// Create the db tables;
$db = Db::getInstance();

// Gets the .sql of ../database/delete.sql
$sql = file_get_contents(__DIR__ . "/../database/delete.sql");

// Split the SQL into individual statements by semicolons
$statements = explode(";", $sql);

// Execute each statement separately
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        try {
            $db->getConnection()->exec($statement);
        } catch (PDOException $e) {
            echo "Error executing migration $migration: " . $e->getMessage();
        }
    }
}
