<?php

namespace App\Repository\Db;
use App\Model\Lamaran;
use App\Repository\Interface\RLamaran;
use App\Util\Enum\StatusLamaranEnum;
use \PDO;


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

    public function insert(Lamaran $lamaran): Lamaran {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path, status, status_reason)
                VALUES (:user_id, :lowongan_id, :cv_path, :video_path, :status, :status_reason)
            ');

            $stmt->execute([
                'user_id' => $lamaran->user_id,
                'lowongan_id' => $lamaran->lowongan_id,
                'cv_path' => $lamaran->cv_path,
                'video_path' => $lamaran->video_path,
                'status' => $lamaran->status,
                'status_reason' => $lamaran->status_reason,
            ]);

            $lamaran->lamaran_id = (int) $this->db->lastInsertId();
            return $lamaran;
        } catch (PDOException $e) {
            error_log('Insert lamaran error: ' . $e->getMessage());
            throw new Exception('Insert lamaran error. Please try again later.');
        }
    }

    public function delete(int $lamaranId): Lamaran {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM lamaran
                WHERE lamaran_id = :lamaran_id
            ');

            $stmt->execute([
                'lamaran_id' => $lamaranId,
            ]);

            $lamaran = new Lamaran();
            $lamaran->lamaran_id = $lamaranId;
            return $lamaran;
        } catch (PDOException $e) {
            error_log('Delete lamaran error: ' . $e->getMessage());
            throw new Exception('Delete lamaran error. Please try again later.');
        }
    }
}