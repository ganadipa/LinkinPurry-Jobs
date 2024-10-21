<?php

namespace App\Repository\Db;
use App\Model\Lowongan;
use App\Repository\Interface\RLowongan;
use App\Util\Enum\JenisLokasiEnum;
use App\Util\Enum\JobTypeEnum;
use Error;
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
            return $this->update($lowongan->lowongan_id, $lowongan);
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
                'jenis_pekerjaan' => $lowongan->jenis_pekerjaan->value,
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

    public function update(int $lowonganId, Lowongan $lowongan): Lowongan {
        try {
            $stmt = $this->db->prepare('
                UPDATE lowongan
                SET company_id = :company_id,
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
                'jenis_pekerjaan' => $lowongan->jenis_pekerjaan->value,
                'jenis_lokasi' => $lowongan->jenis_lokasi->value,
                'lowongan_id' => $lowonganId,
            ]);

            if ($stmt->rowCount() > 0) {
                error_log("Update berhasil dengan ID: " . $lowongan->lowongan_id);
            } else {
                error_log("Tidak ada baris yang diupdate.");
            }            

            return $lowongan;
        } catch (PDOException $e) {
            error_log('Update lowongan error: ' . $e->getMessage());
            throw new Exception('Update lowongan error. Please try again later.');
        }
    }    

    public function getById(int $lowonganId): Lowongan {
        $sql = "SELECT * FROM lowongan WHERE lowongan_id = :lowongan_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':lowongan_id' => $lowonganId]);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$result) {
            return null;  
        }
    
        return new Lowongan(
            $result['company_id'],
            $result['posisi'],
            $result['deskripsi'],
            JobTypeEnum::from($result['jenis_pekerjaan']),
            JenisLokasiEnum::from($result['jenis_lokasi']),
            new \DateTime($result['created_at']),
            new \DateTime($result['updated_at']),
            $result['lowongan_id']
        );
    }

    public function getList(int $page, int $limit, ?string $posisi, ?string $jenisPekerjaan, ?string $jenisLokasi, ?string $search): array {
        $offset = ($page - 1) * $limit;
        $query = 'SELECT * FROM lowongan WHERE 1=1';
        $params = [];
    
        // Filter berdasarkan posisi
        if ($posisi) {
            $query .= ' AND posisi ILIKE :posisi';
            $params['posisi'] = "%$posisi%";
        }
    
        // Filter berdasarkan jenis pekerjaan
        if ($jenisPekerjaan) {
            $query .= ' AND jenis_pekerjaan = :jenis_pekerjaan';
            $params['jenis_pekerjaan'] = $jenisPekerjaan;
        }
    
        // Filter berdasarkan jenis lokasi
        if ($jenisLokasi) {
            $query .= ' AND jenis_lokasi = :jenis_lokasi';
            $params['jenis_lokasi'] = $jenisLokasi;
        }

        if ($search) {
            $query .= ' AND (posisi ILIKE :search OR deskripsi ILIKE :search)';
            $params['search'] = '%' . $search . '%';
        }
    
        // Tambahkan limit dan offset untuk pagination
        $query .= ' LIMIT :limit OFFSET :offset';
        $params['limit'] = $limit;
        $params['offset'] = $offset;
    
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get lowongan list error: ' . $e->getMessage());
            throw new Exception('Failed to retrieve lowongan list.');
        }
    }    

    public function getJobs(int $page, int $perPage
        , string $q, array $jobType, array $locationType, string $sortOrder
    ): array {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $conditions = [];
    
        // Search Query
        if (!empty($q)) {
            $conditions[] = "(posisi ILIKE :q)";
            $params[':q'] = '%' . $q . '%';
        }
    
        // Job Type Filter
        if (!empty($jobType)) {
            $jobTypePlaceholders = [];
            foreach ($jobType as $index => $type) {
                $key = ":jobType$index";
                $jobTypePlaceholders[] = $key;
                $params[$key] = $type;
            }
            $conditions[] = "jenis_pekerjaan IN (" . implode(', ', $jobTypePlaceholders) . ")";
        }
    
        // Location Type Filter
        if (!empty($locationType)) {
            $locationTypePlaceholders = [];
            foreach ($locationType as $index => $location) {
                $key = ":locationType$index";
                $locationTypePlaceholders[] = $key;
                $params[$key] = $location;
            }
            $conditions[] = "jenis_lokasi IN (" . implode(', ', $locationTypePlaceholders) . ")";
        }
    
        // Construct WHERE Clause
        $where = '';
        if (!empty($conditions)) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }
    
        // Handle Sort Order
        $allowedSortOrders = ['asc', 'desc'];
        $sortOrder = strtolower($sortOrder);
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'asc'; // Default sort order
        }
    
        // Final SQL Query
        $sql = "
            SELECT *
            FROM lowongan
            $where
            ORDER BY created_at $sortOrder
            LIMIT :limit OFFSET :offset
        ";
    
        $stmt = $this->db->prepare($sql);
    
        // Bind Parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
        // Execute and Fetch
        $stmt->execute();
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Map to Lowongan Models
        $jobModels = [];
        foreach ($jobs as $job) {
            $jobModel = new Lowongan(
                $job['company_id'],
                $job['posisi'],
                $job['deskripsi'],
                JobTypeEnum::from($job['jenis_pekerjaan']),
                JenisLokasiEnum::from($job['jenis_lokasi']),
                new \DateTime($job['created_at']),
                new \DateTime($job['updated_at']),
                $job['lowongan_id']
            );
    
            $jobModels[] = $jobModel;
        }
    
        return $jobModels;
    }
}