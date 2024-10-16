<?php

namespace App\Controller;

use App\Model\Lowongan;
use App\Repository\Interface\RLowongan;
use App\Util\Enum\JenisLokasiEnum;
use Exception;

class LowonganController {
    private RLowongan $lowonganRepo;

    public function __construct(RLowongan $lowonganRepo) {
        $this->lowonganRepo = $lowonganRepo;
    }

    // Create Lowongan
    public function create(array $data): void {
        try {
            // make a Lowongan object
            $lowongan = new Lowongan(
                0,
                $data['company_id'],
                $data['posisi'],
                $data['deskripsi'],
                $data['jenis_pekerjaan'],
                JenisLokasiEnum::from($data['jenis_lokasi']),
                true, // default open
                new \DateTime(),
                new \DateTime()
            );
            // save to database
            $this->lowonganRepo->insert($lowongan);
            echo "Lowongan created successfully.\n";
        } catch (Exception $e){
            echo "Lowongan creation failed: " . $e->getMessage() . "\n";
        }
    }

    // Update Lowongan
    public function update(int $id, array $data): void {
        try {
            $this->lowonganRepo->update($id, $data);
            echo "Lowongan updated successfully.\n";
        } catch (Exception $e){
            echo "Lowongan update failed: " . $e->getMessage() . "\n";
        }
    }

    // Delete Lowongan
    public function delete(int $id): void {
        try {
            $this->lowonganRepo->delete($id);
            echo "Lowongan deleted successfully.\n";
        } catch (Exception $e){
            echo "Lowongan deletion failed: " . $e->getMessage() . "\n";
        }
    }

    // Get Lowongan by ID
    public function getLowonganById(int $id): ?Lowongan {
        try {
            return $this->lowonganRepo->getById($id);
        } catch (Exception $e){
            echo "Get Lowongan by ID failed: " . $e->getMessage() . "\n";
            return null;
        }
    }
}