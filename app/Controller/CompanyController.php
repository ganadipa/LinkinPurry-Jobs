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
        $html = self::render('JobCompany', [
            'css' => ['company/job.css'],
            'js' => ['company/job.js'],
            'title' => 'Jobs'
        ]);
        $res->setBody($html);
        $res->send();
    }

    public static function showCreateJobPage(Request $req, Response $res): void {
        $html = self::render('CreateJob', [
            'css' => ['company/create-job.css'],
            'js' => ['company/create-job.js'],
            'title' => 'Create Job'
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