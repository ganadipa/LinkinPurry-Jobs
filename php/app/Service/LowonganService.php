<?php

namespace App\Service;

use App\Http\Exception\ForbiddenException;
use App\Model\AttachmentLowongan;
use App\Model\File;
use App\Model\Lowongan;
use App\Util\Enum\JenisLokasiEnum;
use Core\Repositories;
use App\Repository\Interface\RLowongan;
use App\Util\Enum\JobTypeEnum;
use Exception;

class LowonganService {
    public static function createLowongan(array $inputData): Lowongan {

        // Buat objek Lowongan baru
        $lowongan = new Lowongan(
            $inputData['company_id'],
            $inputData['posisi'],
            $inputData['deskripsi'],
            JobTypeEnum::from($inputData['jenis_pekerjaan']),
            JenisLokasiEnum::from($inputData['jenis_lokasi']),
            new \DateTime(),
            new \DateTime()
        );

        // Simpan lowongan ke database
        $lowonganRepo = Repositories::$lowongan;
        $lowonganInserted = $lowonganRepo->insert($lowongan);


        $fileRepo = Repositories::$file;
        $attachmentLowonganRepo = Repositories::$attachmentLowongan;
        
        $files = [];
        if (!isset($inputData['images'])) {
            return $lowonganInserted;
        }
        foreach ($inputData['images'] as $image) {
            $file = new File(
                $image['name'],
                pathinfo($image['name'], PATHINFO_EXTENSION),
                $image['type'],
                (int) $image['size'],
                $image['tmp_name']
            );
            
            // Simpan gambar ke storage
            $fileRepo->save($file);

            array_push($files, $file);

            // Insert to attachment_lowongan table
            $attachmentLowongan = new AttachmentLowongan(
                $lowonganInserted->lowongan_id,
                $file->absolutePath
            );

            $attachmentLowonganRepo->insert($attachmentLowongan);
        }

        return $lowonganInserted;
    }

    public static function updateLowongan(int $jobId, array $postData): Lowongan {
        // Ambil lowongan yang ada berdasarkan ID dari database
        $lowonganRepo = Repositories::$lowongan;
        $existingLowongan = $lowonganRepo->getById($jobId);

        if (!$existingLowongan) {
            throw new Exception("Lowongan not found.");
        }

        // If the job is not owned by the company, throw an error
        if ($existingLowongan->company_id != $postData['company_id']) {
            throw new ForbiddenException("You are not authorized to update this job.");
        }

        // OK!  We're good to go.

        // Update field lowongan yang ada dengan data baru
        $updatedLowongan = new Lowongan(
            $existingLowongan->company_id,
            $postData['posisi'] ?? $existingLowongan->posisi,
            $postData['deskripsi'] ?? $existingLowongan->deskripsi,
            JobTypeEnum::from($postData['jenis_pekerjaan'] ?? $existingLowongan->jenis_pekerjaan),
            JenisLokasiEnum::from($postData['jenis_lokasi'] ?? $existingLowongan->jenis_lokasi),
            $existingLowongan->created_at,
            new \DateTime() ,
            $existingLowongan->lowongan_id,
            $existingLowongan->is_open
        );

        $lowonganRepo->update($jobId, $updatedLowongan);


        // Then if images is empty, we're done.
        if (!isset($postData['images']) || count($postData['images']) === 0) {
            return $updatedLowongan;
        }


        // Otherwise, we have to channge the attchments of this job to the new ones.
        $fileRepo = Repositories::$file;
        $attachmentLowonganRepo = Repositories::$attachmentLowongan;

        // Delete all existing attachments
        $attachments = $attachmentLowonganRepo->deleteByLowonganId($jobId);

        // Also delete the files from storage
        foreach ($attachments as $attachment) {
            $fileRepo->delete($attachment->file_path);
        }

        // Insert the new attachments
        $files = [];
        foreach ($postData['images'] as $image) {
            $file = new File(
                $image['name'],
                pathinfo($image['name'], PATHINFO_EXTENSION),
                $image['type'],
                (int) $image['size'],
                $image['tmp_name']
            );
            
            // Simpan gambar ke storage
            $fileRepo->save($file);

            array_push($files, $file);

            // Insert to attachment_lowongan table
            $attachmentLowongan = new AttachmentLowongan(
                $jobId,
                $file->absolutePath
            );

            $attachmentLowonganRepo->insert($attachmentLowongan);
        }

        return $updatedLowongan;
    }

    public static function deleteLowongan(int $id): void {
        $lowonganRepo = Repositories::$lowongan;
        $lowonganRepo->delete($id);
    }

    public static function getLowonganList(int $page, int $limit, ?string $posisi, ?string $jenisPekerjaan, ?string $jenisLokasi, ?string $search): array {
        $lowonganRepo = Repositories::$lowongan;
        return $lowonganRepo->getList($page, $limit, $posisi, $jenisPekerjaan, $jenisLokasi, $search);
    }
}
