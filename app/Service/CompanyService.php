<?php

namespace App\Service;

use App\Model\CompanyDetail;
use App\Model\User;
use App\Util\Enum\JenisLokasiEnum;
use App\View\View;
use Core\Repositories;

class CompanyService {

    public static function getCompanyDetailByUserId(int $userId): ?CompanyDetail {
        $companyDetailRepo = Repositories::$companyDetail;
        return $companyDetailRepo->getCompanyDetailByUserId($userId);
    }

    public static function updateCompanyDetail(array $data): CompanyDetail {
        $companyDetailRepo = Repositories::$companyDetail;
        $companyDetail = new CompanyDetail(
            $data['user_id'],
            $data['lokasi'],
            $data['about']
        );
        return $companyDetailRepo->update($companyDetail);
    }

    public static function getCompanyIdByJobId(int $jobId): int {
        $lowonganRepo = Repositories::$lowongan;
        return $lowonganRepo->getCompanyIdByJobId($jobId);
    }

    public static function getCreateJobPage(User $user) : string {
        return self::render('CreateJob', [
            'css' => ['company/create-job.css'],
            'js' => ['company/job-create.js'],
            'title' => 'Create Job',
            'ext_css' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'],
            'ext_js' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js'],
            'user' => $user,
        ]);
    }

    public static function getEditJobPage(User $user, string $jobId) : string {
        $lowonganRepo = Repositories::$lowongan;
        $companyDetailRepo = Repositories::$companyDetail;
        $attachmentRepo = Repositories::$attachmentLowongan;

        $lowongan = $lowonganRepo->getById($jobId);
        $company_detail = $companyDetailRepo->getCompanyDetailByUserId($user->user_id);
        $attachment = $attachmentRepo->getAttachmentsById($lowongan->lowongan_id);

        if (!$lowongan) {
            return 'Job not found';
        }
        
        $jobData = [
            'title' => $lowongan->posisi,
            'company' => $user->nama,
            'locationType' => $lowongan->jenis_lokasi,
            'location' => $company_detail->lokasi,
            'jobType' => $lowongan->jenis_pekerjaan,
            'description' => $lowongan->deskripsi,
            'attachments' => $attachment,
        ];

        return self::render('EditJob', [
            'css' => ['company/create-job.css'],
            'js' => ['company/job-edit.js'],
            'title' => $lowongan->posisi . ' at ' . $user->nama,
            'ext_css' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'],
            'ext_js' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js'],
            'jobData' => $jobData, 
            'user' => $user,
        ]);
    }


    private static function render(string $view, array $vars = []): string {
        return View::render('Layout', 'Main', array_merge_recursive($vars, 
            [
                'content' => View::render('Page', $view, $vars),
                'css' => ['company/shared.css'],
            ]
        ));
    }
}