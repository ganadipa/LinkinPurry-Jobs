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
                    jenis_lokasi jenis_lokasi NOT NULL,
                    is_open BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    CONSTRAINT fk_company_id
                        FOREIGN KEY (company_id) 
                        REFERENCES users(user_id)
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

    public function save(Lowongan $lowongan): Lowongan {
        if (isset($lowongan->lowongan_id)) {
            return $this->update($lowongan);
        } else {
            return $this->insert($lowongan);
        }
    }

    public function insert(Lowongan $lowongan): Lowongan {
        try {
            if (isset($lowongan->lowongan_id)) {
                throw new Exception('Cannot insert lowongan that already has lowongan id');
            }

            $stmt = $this->db->prepare('
                INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi)
                VALUES (:company_id, :posisi, :deskripsi, :jenis_pekerjaan, :jenis_lokasi)
            ');

            $stmt->execute([
                'company_id' => $lowongan->company_id,
                'posisi' => $lowongan->posisi,
                'deskripsi' => $lowongan->deskripsi,
                'jenis_pekerjaan' => $lowongan->jenis_pekerjaan,
                'jenis_lokasi' => $lowongan->jenis_lokasi->value,
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

    public function update(Lowongan $lowongan): Lowongan {
        try {
            if (!isset($lowongan->lowongan_id)) {
                throw new Exception('Cannot update lowongan that does not have lowongan id');
            }

            $stmt = $this->db->prepare('
                UPDATE lowongan
                SET company_id = :company
                posisi = :posisi,
                deskripsi = :deskripsi,
                jenis_pekerjaan = :jenis_pekerjaan,
                jenis_lokasi = :jenis_lokasi
                WHERE lowongan_id = :lowongan_id
            ');

            $stmt->execute([
                'company_id' => $lowongan->company_id,
                'posisi' => $lowongan->posisi,
                'deskripsi' => $lowongan->deskripsi,
                'jenis_pekerjaan' => $lowongan->jenis_pekerjaan,
                'jenis_lokasi' => $lowongan->jenis_lokasi->value,
                'lowongan_id' => $lowongan->lowongan_id,
            ]);

            return $lowongan;
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

    public function getPaginatedJobs(int $page, int $limit, string $search = '', string $jenisPekerjaan = '', string $jenisLokasi = ''): array {
        $offset = ($page - 1) * $limit;
    
        $sql = '
            SELECT * FROM lowongan
            WHERE 1=1
        ';
    
        // Add search query
        if (!empty($search)) {
            $sql .= ' AND posisi ILIKE :search';
        }
    
        // Add filters
        if (!empty($jenisPekerjaan)) {
            $sql .= ' AND jenis_pekerjaan = :jenis_pekerjaan';
        }
        
        if (!empty($jenisLokasi)) {
            $sql .= ' AND jenis_lokasi = :jenis_lokasi';
        }
    
        $sql .= ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset';
    
        try {
            $stmt = $this->db->prepare($sql);
    
            // Bind params
            if (!empty($search)) {
                $stmt->bindValue(':search', '%' . $search . '%');
            }
    
            if (!empty($jenisPekerjaan)) {
                $stmt->bindValue(':jenis_pekerjaan', $jenisPekerjaan);
            }
    
            if (!empty($jenisLokasi)) {
                $stmt->bindValue(':jenis_lokasi', $jenisLokasi);
            }
    
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, Lowongan::class);
        } catch (PDOException $e) {
            error_log('Error fetching paginated jobs: ' . $e->getMessage());
            throw new Exception('Error fetching paginated jobs');
        }
    }
    
    public function countJobs(string $search = '', string $jenisPekerjaan = '', string $jenisLokasi = ''): int {
        $sql = 'SELECT COUNT(*) FROM lowongan WHERE 1=1';
    
        if (!empty($search)) {
            $sql .= ' AND posisi ILIKE :search';
        }
    
        if (!empty($jenisPekerjaan)) {
            $sql .= ' AND jenis_pekerjaan = :jenis_pekerjaan';
        }
    
        if (!empty($jenisLokasi)) {
            $sql .= ' AND jenis_lokasi = :jenis_lokasi';
        }
    
        try {
            $stmt = $this->db->prepare($sql);
    
            // Bind params
            if (!empty($search)) {
                $stmt->bindValue(':search', '%' . $search . '%');
            }
    
            if (!empty($jenisPekerjaan)) {
                $stmt->bindValue(':jenis_pekerjaan', $jenisPekerjaan);
            }
    
            if (!empty($jenisLokasi)) {
                $stmt->bindValue(':jenis_lokasi', $jenisLokasi);
            }
    
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error counting jobs: ' . $e->getMessage());
            throw new Exception('Error counting jobs');
        }
    }    
}