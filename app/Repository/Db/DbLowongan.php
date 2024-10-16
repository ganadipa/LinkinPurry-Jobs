<?php

namespace App\Repository\Db;
use App\Model\Lowongan;
use App\Repository\Interface\RLowongan;
use App\Util\Enum\JenisLokasiEnum;
use \PDO;
use \PDOException;
use \Exception;


class DbLowongan implements RLowongan {

    public function __construct(private PDO $db) {}

    public function createTable() {
        try {
            // Create the type constraint for jenis_lokasi
            $this->db->exec('
                CREATE TYPE jenis_lokasi AS ENUM (
                    \''.JenisLokasiEnum::ON_SITE->value.'\',
                    \''.JenisLokasiEnum::HYBRID->value.'\',
                    \''.JenisLokasiEnum::REMOTE->value.'\'
                )
            ');

            // Create the table
            $this->db->exec('
                CREATE TABLE IF NOT EXISTS lowongan (
                    lowongan_id SERIAL PRIMARY KEY,
                    company_id INT NOT NULL,
                    posisi VARCHAR(255) NOT NULL,
                    deskripsi VARCHAR(255),
                    jenis_pekerjaan VARCHAR(255),
                    jenis_lokasi VARCHAR(50) NOT NULL CHECK (jenis_lokasi IN (\'on-site\', \'hybrid\', \'remote\')),
                    is_open BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    CONSTRAINT fk_company_id
                        FOREIGN KEY (company_id) 
                        REFERENCES company_detail(company_id)
                        ON DELETE CASCADE
                )
            ');

            echo "Table 'lowongan' created successfully.\n";
        } catch (PDOException $e) {
            error_log('Create table error: ' . $e->getMessage());
            throw new Exception('Create table error. Please try again later.');
        }
    }

    public function deleteTable() {
        try {
            $this->db->exec('
                DROP TABLE IF EXISTS lowongan
            ');

            $this->db->exec('
                DROP TYPE IF EXISTS jenis_lokasi
            ');
        } catch (PDOException $e) {
            error_log('Delete table error: ' . $e->getMessage());
            throw new Exception('Delete table error. Please try again later.');
        }
    }

    public function insert(Lowongan $lowongan): Lowongan {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi)
                VALUES (:company_id, :posisi, :deskripsi, :jenis_pekerjaan, :jenis_lokasi)
            ');

            $stmt->execute([
                'company_id' => $lowongan->company_id,
                'posisi' => $lowongan->posisi,
                'deskripsi' => $lowongan->deskripsi,
                'jenis_pekerjaan' => $lowongan->jenis_pekerjaan,
                'jenis_lokasi' => $lowongan->jenis_lokasi,
            ]);

            $lowongan->lowongan_id = (int) $this->db->lastInsertId();
            return $lowongan;
        } catch (PDOException $e) {
            error_log('Insert lowongan error: ' . $e->getMessage());
            throw new Exception('Insert lowongan error. Please try again later.');
        }
    }

    public function delete(int $lowonganId): bool {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM lowongan
                WHERE lowongan_id = :lowongan_id
            ');

            $stmt->execute([
                'lowongan_id' => $lowonganId,
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Delete lowongan error: ' . $e->getMessage());
            throw new Exception('Delete lowongan error. Please try again later.');
        }
    }

    public function update(int $lowonganId, array $data): Lowongan {
        try {
            $stmt = $this->db->prepare('
                UPDATE lowongan 
                SET posisi = :posisi, deskripsi = :deskripsi, jenis_pekerjaan = :jenis_pekerjaan, jenis_lokasi = :jenis_lokasi, is_open = :is_open, updated_at = NOW()
                WHERE lowongan_id = :lowongan_id
            ');
    
            $stmt->execute([
                'posisi' => $data['posisi'],
                'deskripsi' => $data['deskripsi'],
                'jenis_pekerjaan' => $data['jenis_pekerjaan'],
                'jenis_lokasi' => $data['jenis_lokasi'],
                'is_open' => $data['is_open'],
                'lowongan_id' => $lowonganId,
            ]);
    
            $stmt = $this->db->prepare('SELECT * FROM lowongan WHERE lowongan_id = :lowongan_id');
            $stmt->execute(['lowongan_id' => $lowonganId]);
            return $stmt->fetchObject(Lowongan::class);
        } catch (PDOException $e) {
            error_log('Update lowongan error: ' . $e->getMessage());
            throw new Exception('Update lowongan error. Please try again later.');
        }
    }    

    public function getById(int $lowonganId): Lowongan {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM lowongan
                WHERE lowongan_id = :lowongan_id
            ');

            $stmt->execute([
                'lowongan_id' => $lowonganId,
            ]);

            return $stmt->fetchObject(Lowongan::class);
        } catch (PDOException $e) {
            error_log('Get lowongan by ID error: ' . $e->getMessage());
            throw new Exception('Get lowongan by ID error. Please try again later.');
        }
    }
}