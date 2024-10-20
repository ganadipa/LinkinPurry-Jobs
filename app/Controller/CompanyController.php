<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\View\View;
use App\Service\CompanyService;
use App\Util\Enum\UserRoleEnum;

class CompanyController {
    
    public static function showCreateJobPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || $user->role === UserRoleEnum::JOBSEEKER) {
            echo '404';
            return;
        }

        $html = CompanyService::getCreateJobPage();
        $res->setBody($html);
        $res->send();
    }

    public static function showEditJobPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || $user->role === UserRoleEnum::JOBSEEKER) {
            echo '404';
            return;
        }

        $html = CompanyService::getEditJobPage();
        $res->setBody($html);
        $res->send();
    }

    public static function showProfile(Request $req, Response $res): void {
        $userId = (int) $req->getUriParams()['id'];
        $companyDetail = CompanyService::getCompanyDetailByUserId($userId);

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
        $updatedCompany = CompanyService::updateCompanyDetail($data);

        $res->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $updatedCompany
        ]);
        $res->send();
    }
}