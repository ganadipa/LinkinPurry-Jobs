<?php

namespace App\Repository\Db;
use App\Model\CompanyDetail;
use App\Repository\Interface\RCompanyDetail;
use Error;
use \PDO;
use \PDOException;
use \Exception;


class DbCompanyDetail implements RCompanyDetail {

    public function __construct(private PDO $db) {}

    public function createTable() {
        try {
            $this->db->exec('
                CREATE TABLE IF NOT EXISTS company_detail (
                    user_id SERIAL PRIMARY KEY,
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

            return $companyDetail;
        } catch (PDOException $e) {
            error_log('Insert company detail error: ' . $e->getMessage());
            throw new Exception('Insert company detail error. Please try again later.');
        }
    }

    public function delete(int $userId): CompanyDetail {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM company_detail
                WHERE user_id = :user_id
            ');

            $stmt->execute([
                'user_id' => $userId,
            ]);

            $companyDetail = $stmt->fetchObject(CompanyDetail::class);
            return $companyDetail;
        } catch (PDOException $e) {
            error_log('Delete company detail error: ' . $e->getMessage());
            throw new Exception('Delete company detail error. Please try again later.');
        }
    }

    public function update(CompanyDetail $companyDetail): CompanyDetail {
        try {
            $stmt = $this->db->prepare('
                UPDATE company_detail
                SET lokasi = :lokasi, about = :about
                WHERE user_id = :user_id
            ');

            $stmt->execute([
                'user_id' => $companyDetail->user_id,
                'lokasi' => $companyDetail->lokasi,
                'about' => $companyDetail->about,
            ]);

            return $companyDetail;
        } catch (PDOException $e) {
            error_log('Update company detail error: ' . $e->getMessage());
            throw new Exception('Update company detail error. Please try again later.');
        }
    }

    public function getCompanyDetailByUserId(int $userId): CompanyDetail {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM company_detail
                WHERE user_id = :user_id
            ');

            $stmt->execute([
                'user_id' => $userId,
            ]);

            $row = $stmt->fetch();
            if (!$row) {
                echo $userId;
                throw new Exception('Company detail not found');
            }

            // error_log("Di db company detail: " . print_r($));

            return new CompanyDetail(
                user_id: $row['user_id'],
                lokasi: $row['lokasi'],
                about: $row['about'],
            );
        } catch (PDOException $e) {
            error_log('Get company detail error: ' . $e->getMessage());
            throw new Exception('Get company detail error. Please try again later.');
        }
    }
}