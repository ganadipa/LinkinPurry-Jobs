<?php

namespace App\Controller;

use App\Http\Exception\BadRequestException;
use App\Http\Exception\ForbiddenException;
use App\Http\Exception\HttpException;
use App\Http\Exception\UnauthorizedException;
use App\Http\Request;
use App\Http\Response;
use App\Service\LowonganService;
use App\Validator\NotNullValidator;
use App\Validator\PositiveNumericValidator;
use Exception;

class LowonganController {
    public static function create(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            if ($user === null) {
                throw new UnauthorizedException('You are not authorized to create a job.');
            }

            if ($user->role->value !== 'company') {
                throw new ForbiddenException('You are not authorized to create a job.');
            }
            
            $images = $req->getPost('images', null);
            $company_id = $req->getPost('company_id', null);
            $posisi = $req->getPost('posisi', null);
            $deskripsi = $req->getPost('deskripsi', null);
            $jenis_pekerjaan = $req->getPost('jenis_pekerjaan', null);
            $jenis_lokasi = $req->getPost('jenis_lokasi', null);



            // Validate required fields
            $validatedCompanyId = NotNullValidator::validate($company_id);
            $validatedPosisi = NotNullValidator::validate($posisi);
            $validatedJenisLokasi = NotNullValidator::validate($jenis_lokasi);
            $validatedJenisPekerjaan = NotNullValidator::validate($jenis_pekerjaan);

            if ($deskripsi === null || $deskripsi == '<p></p>') {
                throw new BadRequestException("Job description is required.");
            }


            $inputData = [
                'images' => $images,
                'company_id' => $validatedCompanyId,
                'posisi' => $validatedPosisi,
                'deskripsi' => $deskripsi,
                'jenis_pekerjaan' => $validatedJenisPekerjaan,
                'jenis_lokasi' => $validatedJenisLokasi
            ];

            
            $lowongan = LowonganService::createLowongan($inputData);

            $res->json([
                'status' => 'success',
                'message' => 'Lowongan created successfully.',
                'data' => [
                    'lowongan_id' => $lowongan->lowongan_id
                ]
            ]);

            $res->send();
        } catch (HttpException $e) {
            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
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

    public static function update(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            if ($user === null) {
                throw new UnauthorizedException('You are not authorized to update a job.');
            }

            if ($user->role->value !== 'company') {
                throw new ForbiddenException('You are not authorized to update a job.');
            }

            $id = $req->getUriParamsValue('id', null);
            
            $images = $req->getPost('images', null);
            $company_id = $req->getPost('company_id', null);
            $posisi = $req->getPost('posisi', null);
            $deskripsi = $req->getPost('deskripsi', null);
            $jenis_pekerjaan = $req->getPost('jenis_pekerjaan', null);
            $jenis_lokasi = $req->getPost('jenis_lokasi', null);

            // Validate required fields
            $validatedCompanyId = NotNullValidator::validate($company_id);
            $validatedPosisi = NotNullValidator::validate($posisi);
            $validatedJenisLokasi = NotNullValidator::validate($jenis_lokasi);
            $validatedJenisPekerjaan = NotNullValidator::validate($jenis_pekerjaan);
            $validatedId = PositiveNumericValidator::validate($id);


            if ($deskripsi === null || $deskripsi == '<p></p>') {
                throw new BadRequestException("Job description is required.");
            }

            if (!isset($id)) {
                throw new Exception("Lowongan ID is required.");
            }

            $postData = [
                'images' => $images,
                'company_id' => $validatedCompanyId,
                'posisi' => $validatedPosisi,
                'deskripsi' => $deskripsi,
                'jenis_pekerjaan' => $validatedJenisPekerjaan,
                'jenis_lokasi' => $validatedJenisLokasi
            ];

            $updatedLowongan = LowonganService::updateLowongan($validatedId, $postData);

            $res->json([
                'status' => 'success',
                'message' => 'Lowongan updated successfully.',
                'data' => [
                    'lowongan_id' => $updatedLowongan->lowongan_id
                ]
            ]);

            $res->send();
        } catch (HttpException $e) {
            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
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
