<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\LowonganService;
use Exception;

class LowonganController {
    public static function create(Request $req, Response $res): void {
        try {
            $inputJson = file_get_contents('php://input');
            $inputData = json_decode($inputJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON input');
            }

            
            $lowongan = LowonganService::createLowongan($inputData);

            $res->json([
                'status' => 'success',
                'message' => 'Lowongan created successfully.',
                'data' => $lowongan
            ]);
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function update(Request $req, Response $res): void {
        try {
            $id = $req->getUriParamsValue('id', null);
            $inputJson = file_get_contents('php://input');
            $postData = json_decode($inputJson, true);

            if (!isset($id)) {
                throw new Exception("Lowongan ID is required.");
            }

            $updatedLowongan = LowonganService::updateLowongan($id, $postData);

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

    public static function delete(Request $req, Response $res): void {
        try {
            $id = $req->getUriParamsValue('id', null);

            if (!isset($id)) {
                throw new Exception("Lowongan ID is required.");
            }

            LowonganService::deleteLowongan($id);

            $res->json([
                'status' => 'success',
                'message' => 'Lowongan deleted successfully.'
            ]);
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function getList(Request $req, Response $res): void {
        try {
            $page = $req->getQueryParam('page', 1);
            $limit = $req->getQueryParam('limit', 10);
            $posisi = $req->getQueryParam('posisi', null);
            $jenisPekerjaan = $req->getQueryParam('jenis_pekerjaan', null);
            $jenisLokasi = $req->getQueryParam('jenis_lokasi', null);
            $search = $req->getQueryParam('search', null);

            $lowonganList = LowonganService::getLowonganList($page, $limit, $posisi, $jenisPekerjaan, $jenisLokasi, $search);
            // print_r($lowonganList);
            $res->json([
                'status' => 'success',
                'message' => 'Lowongan list retrieved successfully.',
                'data' => $lowonganList
            ]);

            $res->send();
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);

            $res->send();
        }
    }
}
