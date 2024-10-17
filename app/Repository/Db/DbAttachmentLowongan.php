<?php

namespace App\Repository\Db;
use App\Model\AttachmentLowongan;
use App\Repository\Interface\RAttachmentLowongan;
use \PDO;

class DbAttachmentLowongan implements RAttachmentLowongan {

    public function __construct(private PDO $db) {}

    public function createTable() {
        try {
            $this->db->exec('
                CREATE TABLE IF NOT EXISTS attachment_lowongan (
                    attachment_id INT PRIMARY KEY,
                    lowongan_id INT NOT NULL,
                    file_path VARCHAR(255) NOT NULL,
                    CONSTRAINT fk_lowongan_id
                        FOREIGN KEY (lowongan_id) 
                        REFERENCES lowongan(lowongan_id)
                        ON DELETE CASCADE
                )
            ');
            echo "Table 'attachment_lowongan' created successfully.\n";
        } catch (PDOException $e) {
            error_log('Create table error: ' . $e->getMessage());
            throw new Exception('Create table error. Please try again later.');
        }
    }

    public function deleteTable() {
        try {
            $this->db->exec('
                DROP TABLE IF EXISTS attachment_lowongan
            ');
        } catch (PDOException $e) {
            error_log('Delete table error: ' . $e->getMessage());
            throw new Exception('Delete table error. Please try again later.');
        }
    }

    public function save(AttachmentLowongan $attachmentLowongan): AttachmentLowongan {
        if (isset($attachmentLowongan->attachment_id)) {
            return $this->update($attachmentLowongan);
        } else {
            return $this->insert($attachmentLowongan);
        }
    }

    public function insert(AttachmentLowongan $attachmentLowongan): AttachmentLowongan {
        try {
            if (isset($attachmentLowongan->attachment_id)) {
                throw new Exception('Cannot insert attachment lowongan that already has attachment id');
            }

            $stmt = $this->db->prepare('
                INSERT INTO attachment_lowongan (lowongan_id, file_path)
                VALUES (:lowongan_id, :file_path)
            ');

            $stmt->execute([
                'lowongan_id' => $attachmentLowongan->lowongan_id,
                'file_path' => $attachmentLowongan->file_path,
            ]);

            $attachmentLowongan->attachment_id = (int) $this->db->lastInsertId();
            return $attachmentLowongan;
        } catch (PDOException $e) {
            error_log('Insert attachment lowongan error: ' . $e->getMessage());
            throw new Exception('Insert attachment lowongan error. Please try again later.');
        }
    }

    public function delete(int $attachmentId): AttachmentLowongan {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM attachment_lowongan
                WHERE attachment_id = :attachment_id
            ');

            $stmt->execute([
                'attachment_id' => $attachmentId,
            ]);

            $attachmentLowongan = new AttachmentLowongan();
            $attachmentLowongan->attachment_id = $attachmentId;
            return $attachmentLowongan;
        } catch (PDOException $e) {
            error_log('Delete attachment lowongan error: ' . $e->getMessage());
            throw new Exception('Delete attachment lowongan error. Please try again later.');
        }
    }

    public function update(AttachmentLowongan $attachmentId): AttachmentLowongan {
        try {
            // if attachment id is not set, throw error
            if (!isset($attachmentLowongan->attachment_id)) {
                throw new Exception('Cannot update attachment lowongan that does not have attachment id');
            }

            $stmt = $this->db->prepare('
                UPDATE attachment_lowongan
                SET lowongan_id = :lowongan_id, file_path = :file_path
                WHERE attachment_id = :attachment_id
            ');

            $stmt->execute([
                'lowongan_id' => $attachmentLowongan->lowongan_id,
                'file_path' => $attachmentLowongan->file_path,
                'attachment_id' => $attachmentLowongan->attachment_id,
            ]);

            return $attachmentLowongan;
        } catch (PDOException $e) {
            error_log('Update attachment lowongan error: ' . $e->getMessage());
            throw new Exception('Update attachment lowongan error. Please try again later.');
        }
    }
}