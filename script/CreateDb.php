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
$db->createTables();
