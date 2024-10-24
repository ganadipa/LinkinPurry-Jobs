<?php

namespace App\Repository\Db;
use App\Model\Lamaran;
use App\Repository\Interface\RLamaran;
use App\Util\Enum\StatusLamaranEnum;
use \PDO;
use \PDOException;
use \Exception;
use DateTime;


class DbLamaran implements RLamaran {

    public function __construct(private PDO $db) {}

    public function createTable() {
        try {
            // Create the type constraint
            $this->db->exec('
                CREATE TYPE status_lamaran AS ENUM (
                    \''.StatusLamaranEnum::ACCEPTED->value.'\',
                    \''.StatusLamaranEnum::REJECTED->value.'\',
                    \''.StatusLamaranEnum::WAITING->value.'\'
                )
            ');

            // Create the table
            $this->db->exec('
                CREATE TABLE IF NOT EXISTS lamaran (
                    lamaran_id SERIAL PRIMARY KEY,
                    user_id INT NOT NULL,
                    lowongan_id INT NOT NULL,
                    cv_path VARCHAR(255),
                    video_path VARCHAR(255),
                    status status_lamaran NOT NULL,
                    status_reason VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    CONSTRAINT fk_user_id
                        FOREIGN KEY (user_id) 
                        REFERENCES users(user_id)
                        ON DELETE CASCADE,
                    CONSTRAINT fk_lowongan_id
                        FOREIGN KEY (lowongan_id) 
                        REFERENCES lowongan(lowongan_id)
                        ON DELETE CASCADE
                )
            ');


            echo "Table 'lamaran' created successfully.\n";
        } catch (PDOException $e) {
            error_log('Create table error: ' . $e->getMessage());
            throw new Exception('Create table error. Please try again later.');
        }
    }

    public function deleteTable() {
        try {
            $this->db->exec('
                DROP TABLE IF EXISTS lamaran
            ');

            $this->db->exec('
                DROP TYPE IF EXISTS status_lamaran
            ');
        } catch (PDOException $e) {
            error_log('Delete table error: ' . $e->getMessage());
            throw new Exception('Delete table error. Please try again later.');
        }
    }

    public function save(Lamaran $lamaran): Lamaran {
        if (isset($lamaran->lamaran_id)) {
            return $this->update($lamaran);
        } else {
            return $this->insert($lamaran);
        }
    }

    public function insert(Lamaran $lamaran): Lamaran {
        try {
            if (isset($lamaran->lamaran_id)) {
                throw new Exception('Cannot insert lamaran that already has lamaran id');
            }
        
            $stmt = $this->db->prepare('
                INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path, status, status_reason)
                VALUES (:user_id, :lowongan_id, :cv_path, :video_path, :status, :status_reason)
            ');

            $stmt->execute([
                'user_id' => $lamaran->user_id,
                'lowongan_id' => $lamaran->lowongan_id,
                'cv_path' => $lamaran->cv_path,
                'video_path' => $lamaran->video_path,
                'status' => $lamaran->status->value,
                'status_reason' => $lamaran->status_reason,
            ]);

            $lamaran->lamaran_id = (int) $this->db->lastInsertId();
            

            return $lamaran;
        } catch (PDOException $e) {
            error_log('Insert lamaran error: ' . $e->getMessage());
            throw new Exception('Insert lamaran error. Please try again later.');
        }
    }

    public function delete(int $lamaranId): void {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM lamaran
                WHERE lamaran_id = :lamaran_id
            ');

            $stmt->execute([
                'lamaran_id' => $lamaranId,
            ]);

        } catch (PDOException $e) {
            error_log('Delete lamaran error: ' . $e->getMessage());
            throw new Exception('Delete lamaran error. Please try again later.');
        }
    }

    public function update(Lamaran $lamaran): Lamaran {
        try {
            if (!isset($lamaran->lamaran_id)) {
                throw new Exception('Cannot update lamaran that does not have lamaran id');
            }

            $stmt = $this->db->prepare('
                UPDATE lamaran
                SET user_id = :user_id,
                    lowongan_id = :lowongan_id,
                    cv_path = :cv_path,
                    video_path = :video_path,
                    status = :status,
                    status_reason = :status_reason
                WHERE lamaran_id = :lamaran_id
            ');

            $stmt->execute([
                'user_id' => $lamaran->user_id,
                'lowongan_id' => $lamaran->lowongan_id,
                'cv_path' => $lamaran->cv_path,
                'video_path' => $lamaran->video_path,
                'status' => $lamaran->status->value,
                'status_reason' => $lamaran->status_reason,
                'lamaran_id' => $lamaran->lamaran_id,
            ]);

            return $lamaran;
        } catch (PDOException $e) {
            error_log('Update lamaran error: ' . $e->getMessage());
            throw new Exception('Update lamaran error. Please try again later.');
        }
    }

    public function getLamaranByUserIdAndJobId(int $userId, int $jobId): ?Lamaran {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM lamaran
                WHERE user_id = :user_id
                AND lowongan_id = :lowongan_id
            ');

            $stmt->execute([
                'user_id' => $userId,
                'lowongan_id' => $jobId,
            ]);

            $row = $stmt->fetch();
            if (!$row) {
                return null;
            }

            return new Lamaran(
                lamaran_id: (int) $row['lamaran_id'],
                user_id: (int) $row['user_id'],
                lowongan_id: (int) $row['lowongan_id'],
                cv_path: $row['cv_path'],
                video_path: $row['video_path'],
                status: StatusLamaranEnum::from($row['status']),
                status_reason: $row['status_reason'],
                created_at: new DateTime($row['created_at']),
            );
        } catch (PDOException $e) {
            error_log('Get lamaran error: ' . $e->getMessage());
            throw new Exception('Get lamaran error. Please try again later.');
        }
    }

    public function getNumberOfApplicants(int $jobId): int
    {
        try {
            $stmt = $this->db->prepare('
                SELECT COUNT(*) FROM lamaran
                WHERE lowongan_id = :lowongan_id
            ');

            $stmt->execute([
                'lowongan_id' => $jobId,
            ]);

            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Get number of applicants error: ' . $e->getMessage());
            throw new Exception('Get number of applicants error. Please try again later.');
        }
    }

    public function getApplicantsByLowonganId(int $jobId): array
    {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM lamaran
                WHERE lowongan_id = :lowongan_id
            ');

            $stmt->execute([
                'lowongan_id' => $jobId,
            ]);

            $rows = $stmt->fetchAll();
            $lamarans = [];
            foreach ($rows as $row) {
                $lamarans[] = new Lamaran(
                    lamaran_id: (int) $row['lamaran_id'],
                    user_id: (int) $row['user_id'],
                    lowongan_id: (int) $row['lowongan_id'],
                    cv_path: $row['cv_path'],
                    video_path: $row['video_path'],
                    status: StatusLamaranEnum::from($row['status']),
                    status_reason: $row['status_reason'],
                    created_at: new DateTime($row['created_at']),
                );
            }

            return $lamarans;
        } catch (PDOException $e) {
            error_log('Get applicants error: ' . $e->getMessage());
            throw new Exception('Get applicants error. Please try again later.');
        }
    }

    public function updateStatusApplicant(int $lamaranId, string $status, string $statusReason): void
    {
        try {
            $stmt = $this->db->prepare('
                UPDATE lamaran
                SET status = :status,
                    status_reason = :status_reason
                WHERE lamaran_id = :lamaran_id
            ');

            $stmt->execute([
                'status' => $status,
                'status_reason' => $statusReason,
                'lamaran_id' => $lamaranId,
            ]);
        } catch (PDOException $e) {
            error_log('Update status applicant error: ' . $e->getMessage());
            throw new Exception('Update status applicant error. Please try again later.');
        }
    }

    // Return a list of lamaran
    public function getLamaranByUserId(int $userId): array {
        try {
            $stmt = $this->db->prepare('
                SELECT l.lamaran_id, l.lowongan_id, l.status, l.created_at, lo.posisi
                FROM lamaran l
                JOIN lowongan lo ON l.lowongan_id = lo.lowongan_id
                WHERE l.user_id = :user_id
                ORDER BY l.created_at DESC
            ');
    
            $stmt->execute(['user_id' => $userId]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get lamaran by user id error: ' . $e->getMessage());
            throw new Exception('Get lamaran by user id error. Please try again later.');
        }
    }
}