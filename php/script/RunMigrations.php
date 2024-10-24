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
$conn = $db->getConnection();

// Get the first parameter;
$param = $argv[1] ?? 0;


// Get all the files inside the ../database/migrations folder;
$migrations = scandir(__DIR__ . "/../database/migrations");

// Remove the first two elements from the array;
array_shift($migrations);
array_shift($migrations);

print_r($migrations);


// Run migrations from $param and above
foreach ($migrations as $migration) {
    // the migrations will be xxx.sql where xxx is the migration number
    $migrationNumber = (int) explode(".", $migration)[0];

    if ($migrationNumber >= $param) {
        // Read the SQL file
        $sql = file_get_contents(__DIR__ . "/../database/migrations/$migration");

        // Split the SQL into individual statements by semicolons
        $statements = explode(";", $sql);

        // Execute each statement separately
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                try {
                    $conn->exec($statement);
                } catch (PDOException $e) {
                    echo "Error executing migration $migration: " . $e->getMessage();
                }
            }
        }

        echo "Migration $migration executed successfully.\n";
    }
}


