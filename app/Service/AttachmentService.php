<?php

namespace App\Service;

use App\Model\AttachmentLowongan;
use Core\Repositories;
use Exception;


class AttachmentService {
    public static function createAttachment(array $inputData): AttachmentLowongan {
        $requiredKeys = ['lowongan_id', 'file_path'];
        
        // Validasi input data
        foreach ($requiredKeys as $key) {
            if (!isset($inputData[$key])) {
                throw new Exception("Missing required field: $key");
            }
        }

        // Buat objek AttachmentLowongan baru
        $attachmentLowongan = new AttachmentLowongan(
            $inputData['lowongan_id'],
            $inputData['file_path']
        );

        // Simpan attachment lowongan ke database
        $attachmentLowonganRepo = Repositories::$attachmentLowongan;
        $attachmentLowonganRepo->insert($attachmentLowongan);

        return $attachmentLowongan;
    }

    public static function updateAttachment(int $id, array $postData): AttachmentLowongan {
        // Ambil attachment lowongan yang ada berdasarkan ID dari database
        $attachmentLowonganRepo = Repositories::$attachmentLowongan;
        $existingAttachmentLowongan = $attachmentLowonganRepo->getById($id);
        if (!$existingAttachmentLowongan) {
            throw new Exception("Attachment Lowongan not found.");
        }

        // Update field attachment lowongan yang ada dengan data baru
        $updatedAttachmentLowongan = new AttachmentLowongan(
            $existingAttachmentLowongan->lowongan_id,
            $postData['file_path'] ?? $existingAttachmentLowongan->file_path
        );

        $attachmentLowonganRepo->update($updatedAttachmentLowongan);

        return $updatedAttachmentLowongan;
    }

    public static function deleteAttachment(int $id): void {
        $attachmentLowonganRepo = Repositories::$attachmentLowongan;
        $attachmentLowonganRepo->delete($id);
    }

    public static function getAttachmentList(): array {
        $attachmentLowonganRepo = Repositories::$attachmentLowongan;
        return $attachmentLowonganRepo->getList();
    }

    public static function getAttachmentPath(int $id): string {
        $attachmentLowonganRepo = Repositories::$attachmentLowongan;
        $attachment = $attachmentLowonganRepo->getById($id);
        if (!$attachment) {
            throw new Exception("Attachment not found.");
        }

        return $attachment->file_path;
    }
}
