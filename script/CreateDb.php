<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Repository\Db\Db;
use App\Util\EnvLoader;

// Load environment variables;
EnvLoader::load(__DIR__ . "/../.env");


$db = Db::getInstance();
$db->createTables();
