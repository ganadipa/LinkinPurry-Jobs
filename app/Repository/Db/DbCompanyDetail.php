<?php

namespace App\Repository\Db;
use App\Model\CompanyDetail;
use App\Repository\Interface\RCompanyDetail;
use \PDO;


class DbCompanyDetail implements RCompanyDetail {

    public function __construct(private PDO $db) {}

    public function createTable() {
        try {
            $this->db->exec('
                CREATE TABLE IF NOT EXISTS company_detail (
                    company_id SERIAL PRIMARY KEY,
                    user_id INT NOT NULL,
                    lokasi VARCHAR(255),
                    about VARCHAR(255),
                    CONSTRAINT fk_user_id
                        FOREIGN KEY (user_id) 
                        REFERENCES users(user_id)
                        ON DELETE CASCADE
                )
            ');
            echo "Table company_detail created successfully";
        } catch (PDOException $e) {
            error_log('Create table error: ' . $e->getMessage());
            throw new Exception('Create table error. Please try again later.');
        }
    }

    public function deleteTable() {
        try {
            $this->db->exec('
                DROP TABLE IF EXISTS company_detail
            ');
        } catch (PDOException $e) {
            error_log('Delete table error: ' . $e->getMessage());
            throw new Exception('Delete table error. Please try again later.');
        }
    }
    

    public function insert(CompanyDetail $companyDetail): CompanyDetail {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO company_detail (user_id, lokasi, about)
                VALUES (:user_id, :lokasi, :about)
            ');

            $stmt->execute([
                'user_id' => $companyDetail->user_id,
                'lokasi' => $companyDetail->lokasi,
                'about' => $companyDetail->about,
            ]);

            $companyDetail->company_id = (int) $this->db->lastInsertId();
            return $companyDetail;
        } catch (PDOException $e) {
            error_log('Insert company detail error: ' . $e->getMessage());
            throw new Exception('Insert company detail error. Please try again later.');
        }
    }

    public function delete(int $companyId): CompanyDetail {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM company_detail
                WHERE company_id = :company_id
            ');

            $stmt->execute([
                'company_id' => $companyId,
            ]);

            $companyDetail = new CompanyDetail();
            $companyDetail->company_id = $companyId;
            return $companyDetail;
        } catch (PDOException $e) {
            error_log('Delete company detail error: ' . $e->getMessage());
            throw new Exception('Delete company detail error. Please try again later.');
        }
    }
}