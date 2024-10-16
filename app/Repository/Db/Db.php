<?php

namespace App\Repository\Db;
use \PDO;
use \PDOException;
use \Exception;


class Db {
    private static ?Db $instance = null;
    private PDO $pdo;

    private function __construct() {
        $host = $_ENV['ENVIRONMENT'] === 'docker' ? 'postgres-local' : 'localhost';
        $port = $_ENV['POSTGRES_PORT'];
        $dbname = $_ENV['POSTGRES_DB'];
        $user = $_ENV['POSTGRES_USER'];
        $password = $_ENV['POSTGRES_PASSWORD'];

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
        // echo "Connecting to database...\n" . $dsn . "<br>";
        // echo "User: $user<br>";
        // echo "Password: " . str_repeat('*', strlen($password)) . "<br>";

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            // $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Database connected successfully\n";
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            echo "Database connection error: " . $e->getMessage();
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

    public function createTables() {
        $attachmentLowongan = new DbAttachmentLowongan($this->pdo);
        $companyDetail = new DbCompanyDetail($this->pdo);
        $lamaran = new DbLamaran($this->pdo);
        $lowongan = new DbLowongan($this->pdo);
        $user = new DbUser($this->pdo);


        $lamaran->deleteTable();
        $attachmentLowongan->deleteTable();
        $lowongan->deleteTable();
        $companyDetail->deleteTable();
        $user->deleteTable();

        $user->createTable();

        $companyDetail->createTable();

        $lowongan->createTable();

        $attachmentLowongan->createTable();

        $lamaran->createTable();
    }
}
