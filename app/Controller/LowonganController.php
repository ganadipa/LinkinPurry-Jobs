<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
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
    public function create(Request $req, Response $res): void {
        try {
            // Dapatkan input JSON
            $inputJson = file_get_contents('php://input');
            $inputData = json_decode($inputJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $res->json([
                    'status' => 'error',
                    'message' => 'Invalid JSON input'
                ]);
                return;
            }

            // Validasi input data
            $requiredKeys = ['company_id', 'posisi', 'deskripsi', 'jenis_pekerjaan', 'jenis_lokasi'];
            foreach ($requiredKeys as $key) {
                if (!isset($inputData[$key])) {
                    $res->json([
                        'status' => 'error',
                        'message' => "Missing required field: $key"
                    ]);
                    return;
                }
            }

            error_log(print_r($inputData, true)); // debug

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
            error_log('Data berhasil disimpan.'); // debug
            $res->json([
                'status' => 'success',
                'message' => 'Lowongan created successfully.'
            ]);

        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // Update Lowongan
    public function update(Request $req, Response $res): void {
        try {
            $id = $req->getUriParamsValue('id', null);
            $inputJson = file_get_contents('php://input');
            $postData = json_decode($inputJson, true);
    
            if (!isset($id)) {
                throw new Exception("Lowongan ID is required.");
            }
    
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
    
            $res->json([
                'status' => 'success',
                'message' => 'Lowongan updated successfully.',
                'data' => $updatedLowongan
            ]);
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }    

    // Delete Lowongan
    public function delete(Request $req, Response $res): void {
        try {
            $id = $req->getUriParamsValue('id', null);

            if (!isset($id)) {
                throw new Exception("Lowongan ID is required.");
            }

            $this->lowonganRepo->delete($id);

            $res->json([
                'status' => 'success',
                'message' => 'Lowongan deleted successfully.'
            ]);
            $res->send();
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            $res->send();
        }
    }

    public function getList(Request $req, Response $res): void {
        try {
            $page = $req->getQueryParam('page', 1); // Default halaman 1
            $limit = $req->getQueryParam('limit', 10); // Default 10 item per halaman
            // Filter
            $posisi = $req->getQueryParam('posisi', null); 
            $jenisPekerjaan = $req->getQueryParam('jenis_pekerjaan', null); 
            $jenisLokasi = $req->getQueryParam('jenis_lokasi', null); 
    
            // Search
            $search = $req->getQueryParam('search', null);

            $lowonganList = $this->lowonganRepo->getList($page, $limit, $posisi, $jenisPekerjaan, $jenisLokasi, $search);
            
            error_log("LIST"); // debug
            error_log(print_r($lowonganList, true)); // debug

            $res->json([
                'status' => 'success',
                'data' => $lowonganList
            ]);
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        error_log('Respons JSON dikirim: ' . json_encode($lowonganList, JSON_PRETTY_PRINT)); // debug
    }    
}
