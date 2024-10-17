<?php

namespace App\Repository\Db;
use \PDO;
use \PDOException;
use \Exception;
use App\Repository\Db\DbAttachmentLowongan;
use App\Repository\Db\DbCompanyDetail;
use App\Repository\Db\DbLamaran;
use App\Repository\Db\DbLowongan;
use App\Repository\Db\DbUser;


class Db {
    private static ?Db $instance = null;
    private PDO $pdo;

    public DbAttachmentLowongan $attachmentLowongan;
    public DbCompanyDetail $companyDetail;
    public DbLamaran $lamaran;
    public DbLowongan $lowongan;
    public DbUser $user;


    private function __construct() {
        $host = $_ENV['POSTGRES_HOST'];
        $port = $_ENV['POSTGRES_PORT'];
        $dbname = $_ENV['POSTGRES_DB'];
        $user = $_ENV['POSTGRES_USER'];
        $password = $_ENV['POSTGRES_PASSWORD'];
        

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new Exception('Database connection error. Please try again later.');
        }

        $this->attachmentLowongan = new DbAttachmentLowongan($this->pdo);
        $this->companyDetail = new DbCompanyDetail($this->pdo);
        $this->lamaran = new DbLamaran($this->pdo);
        $this->lowongan = new DbLowongan($this->pdo);
        $this->user = new DbUser($this->pdo);
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

        $this->lamaran->deleteTable();
        $this->attachmentLowongan->deleteTable();
        $this->lowongan->deleteTable();
        $this->companyDetail->deleteTable();
        $this->user->deleteTable();

        $this->user->createTable();
        $this->companyDetail->createTable();
        $this->lowongan->createTable();
        $this->attachmentLowongan->createTable();
        $this->lamaran->createTable();
    }

    public function getDbAttachmentLowongan(): DbAttachmentLowongan {
        return $this->attachmentLowongan;
    }

    public function getDbCompanyDetail(): DbCompanyDetail {
        return $this->companyDetail;
    }

    public function getDbLamaran(): DbLamaran {
        return $this->lamaran;
    }

    public function getDbLowongan(): DbLowongan {
        return $this->lowongan;
    }

    public function getDbUser(): DbUser {
        return $this->user;
    }
}
