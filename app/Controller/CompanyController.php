<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\View\View;
use App\Service\CompanyService;
use App\Util\Enum\UserRoleEnum;

class CompanyController {
    private static CompanyService $companyService;

    public function __construct() {
        self::$companyService = new CompanyService();
    }
    
    public static function showCreateJobPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || $user->role === UserRoleEnum::JOBSEEKER) {
            echo '404';
            return;
        }

        $html = self::$companyService->getCreateJobPage();
        $res->setBody($html);
        $res->send();
    }

    public static function showEditJobPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || $user->role === UserRoleEnum::JOBSEEKER) {
            echo '404';
            return;
        }

        $html = self::$companyService->getEditJobPage();
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
}