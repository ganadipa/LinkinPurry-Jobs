<?php

namespace App\Repository\Db;

class Db {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = getenv('POSTGRES_HOST');
        $port = getenv('POSTGRES_PORT');
        $dbname = getenv('POSTGRES_DB');
        $user = getenv('POSTGRES_USER');
        $password = getenv('POSTGRES_PASSWORD');

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new Exception('Database connection error. Please try again later.');
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
