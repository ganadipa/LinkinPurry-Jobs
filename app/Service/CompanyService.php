<?php

namespace App\Service;

use App\Model\CompanyDetail;
use App\Model\User;
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

        $lowongan = $lowonganRepo->getById($jobId);
        $company_detail = $companyDetailRepo->getCompanyDetailByUserId($user->user_id);

        if (!$lowongan) {
            return 'Job not found';
        }
        
        $jobData = [
            'title' => $lowongan->posisi . ' at ' . $user->nama,
            'company' => $user->nama,
            'workplaceType' => 'on-site',
            'location' => 'Makassar, South Sulawesi, Indonesia',
            'jobType' => 'full-time',
            'description' => '<p>Ini Headernya</p><ol><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Ini Satu</li><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Dua</li><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>TIga</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>List</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Haha</li></ol><p><u>Italic</u></p><p>Bold</p>',
            'attachments' => ['https://placehold.co/40x40', 'https://placehold.co/50x50']
        ];

        return self::render('EditJob', [
            'css' => ['company/create-job.css'],
            'js' => ['company/job-edit.js'],
            'title' => 'Edit Job',
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