<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\View\View;
use App\Service\CompanyService;

class CompanyController {
    private static CompanyService $companyService;

    public function __construct() {
        self::$companyService = new CompanyService();
    }

    public static function showCompanyPage(Request $req, Response $res): void {
        $html = self::render('HomeCompany', [
            'css' => ['company/company.css'],
            'js' => ['company/script.js'],
            'title' => 'Company Page'
        ]);
        $res->setBody($html);
        $res->send();
    }

    public static function showJobPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || !isset($user->role)) {
            $res->redirect('/login', 302);
            $res->send();
            return;
        }

        if ($user->role == 'jobseeker') {
            $html = self::render('JobSeeker', [
                'css' => ['company/job.css'],
                'js' => ['company/job.js'],
                'title' => 'Jobs (Job Seeker)',
            ]);
        } else {
            $html = self::render('JobCompany', [
                'css' => ['company/job.css'],
                'js' => ['company/job.js'],
                'title' => 'Jobs (Company)',
            ]);
        }
        
        $res->setBody($html);
        $res->send();
    }
    
    public static function showCreateJobPage(Request $req, Response $res): void {
        $html = self::render('CreateJob', [
            'css' => ['company/create-job.css'],
            'js' => ['company/job-create.js'],
            'title' => 'Create Job',
            'ext_css' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'],
            'ext_js' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js']
        ]);
        $res->setBody($html);
        $res->send();
    }

    public static function showEditJobPage(Request $req, Response $res): void {
        // In a real application, you would fetch the job data based on an ID
        // For this example, we're using dummy data
        $jobData = [
            'title' => 'Frontend Developer',
            'company' => 'TechCorp Inc.',
            'workplaceType' => 'on-site',
            'location' => 'Makassar, South Sulawesi, Indonesia',
            'jobType' => 'full-time',
            'description' => '<p>Ini Headernya</p><ol><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Ini Satu</li><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Dua</li><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>TIga</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>List</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Haha</li></ol><p><u>Italic</u></p><p>Bold</p>',
            'attachments' => ['https://placehold.co/40x40', 'https://placehold.co/50x50']
        ];

        $html = self::render('EditJob', [
            'css' => ['company/create-job.css'],
            'js' => ['company/job-edit.js'],
            'title' => 'Edit Job',
            'ext_css' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'],
            'ext_js' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js'],
            'jobData' => $jobData
        ]);
        $res->setBody($html);
        $res->send();
    }

    public static function showProfile(Request $req, Response $res): void {
        $userId = (int) $req->getUriParams()['id'];
        $companyDetail = self::$companyService->getCompanyDetailByUserId($userId);

        if (!$companyDetail) {
            $res->json([
                'status' => 'error',
                'message' => 'Company detail not found'
            ]);
            $res->send();
            return;
        }

        $res->json([
            'status' => 'success',
            'data' => $companyDetail
        ]);
        $res->send();
    }

    public static function updateProfile(Request $req, Response $res): void {
        $data = json_decode($req->getPost(), true);
        $updatedCompany = self::$companyService->updateCompanyDetail($data);

        $res->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $updatedCompany
        ]);
        $res->send();
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