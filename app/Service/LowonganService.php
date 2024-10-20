<?php

namespace App\Service;

use App\Model\Lowongan;
use App\Repository\Interface\RLowongan;
use App\Util\Enum\JenisLokasiEnum;
use Exception;

class LowonganService {
    private RLowongan $lowonganRepo;

    public function __construct(RLowongan $lowonganRepo) {
        $this->lowonganRepo = $lowonganRepo;
    }

    public function createLowongan(array $inputData): Lowongan {
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
        $this->lowonganRepo->insert($lowongan);

        return $lowongan;
    }

    public function updateLowongan(int $id, array $postData): Lowongan {
        // Ambil lowongan yang ada berdasarkan ID dari database
        $existingLowongan = $this->lowonganRepo->getById($id);
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

        $this->lowonganRepo->update($id, $updatedLowongan);

        return $updatedLowongan;
    }

    public function deleteLowongan(int $id): void {
        $this->lowonganRepo->delete($id);
    }

    public function getLowonganList(int $page, int $limit, ?string $posisi, ?string $jenisPekerjaan, ?string $jenisLokasi, ?string $search): array {
        return $this->lowonganRepo->getList($page, $limit, $posisi, $jenisPekerjaan, $jenisLokasi, $search);
    }
}
