<?php

namespace App\Service;

use App\Model\Lowongan;
use App\Util\Enum\JenisLokasiEnum;
use Core\Repositories;
use App\Repository\Interface\RLowongan;
use Exception;

class LowonganService {
    public static function createLowongan(array $inputData): Lowongan {
        $requiredKeys = ['company_id', 'posisi', 'deskripsi', 'jenis_pekerjaan', 'jenis_lokasi'];
        
        // Validasi input data
        foreach ($requiredKeys as $key) {
            if (!isset($inputData[$key])) {
                throw new Exception("Missing required field: $key");
            }
        }

        // Buat objek Lowongan baru
        $lowongan = new Lowongan(
            $inputData['company_id'],
            $inputData['posisi'],
            $inputData['deskripsi'],
            $inputData['jenis_pekerjaan'],
            JenisLokasiEnum::from($inputData['jenis_lokasi']),
            new \DateTime(),
            new \DateTime()
        );

        // Simpan lowongan ke database
        $lowonganRepo = Repositories::$lowongan;
        $lowonganRepo->insert($lowongan);

        return $lowongan;
    }

    public static function updateLowongan(int $id, array $postData): Lowongan {
        // Ambil lowongan yang ada berdasarkan ID dari database
        $lowonganRepo = Repositories::$lowongan;
        $existingLowongan = $lowonganRepo->getById($id);
        if (!$existingLowongan) {
            throw new Exception("Lowongan not found.");
        }

        // Update field lowongan yang ada dengan data baru
        $updatedLowongan = new Lowongan(
            $existingLowongan->company_id,
            $postData['posisi'] ?? $existingLowongan->posisi,
            $postData['deskripsi'] ?? $existingLowongan->deskripsi,
            $postData['jenis_pekerjaan'] ?? $existingLowongan->jenis_pekerjaan,
            JenisLokasiEnum::from($postData['jenis_lokasi'] ?? $existingLowongan->jenis_lokasi->value),
            $existingLowongan->created_at,
            new \DateTime()  // Set updated_at to current time
        );

        $lowonganRepo->update($id, $updatedLowongan);

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
