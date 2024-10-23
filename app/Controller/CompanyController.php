<?php

namespace App\Controller;

use App\Http\Exception\ForbiddenException;
use App\Http\Exception\HttpException;
use App\Http\Request;
use App\Http\Response;
use App\Service\CompanyService;
use App\Service\HomeService;
use App\Util\Enum\UserRoleEnum;
use Exception;

class CompanyController {
    
    public static function showCreateJobPage(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            if ($user === null) {
                $res->redirect('/login');
                $res->send();
                return;
            } 
    
    
            if ($user->role === UserRoleEnum::JOBSEEKER) {
                throw new ForbiddenException('You are not authorized to create a job.');
                return;
            }
    
            $html = CompanyService::getCreateJobPage($user);
            $res->setBody($html);
            $res->send();
        } catch (ForbiddenException $e) {
            $res->setBody(HomeService::errorPage($user, $e->getMessage()));
            $res->send();
        } catch (Exception $e) {
            $res->setBody(HomeService::errorPage($user, $e->getMessage()));
            $res->send();
        } catch (HttpException $e) {
            $res->setBody(HomeService::errorPage($user, $e->getMessage()));
            $res->send();
        }

    }

    public static function showEditJobPage(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            $jobId = (int) $req->getUriParams()['id'];
    
            if ($user === null) {
                $res->redirect('/login');
                $res->send();
                return;
            }
    
            if ($user->role === UserRoleEnum::JOBSEEKER) {
                throw new ForbiddenException('You are not authorized to edit a job.');
                return;
            }
    
            $html = CompanyService::getEditJobPage($user, $jobId);
            $res->setBody($html);
            $res->send();
        } catch (ForbiddenException $e) {
            $res->setBody(HomeService::errorPage($user, $e->getMessage()));
            $res->send(); 
        } catch (HttpException $e) {
            $res->setBody(HomeService::errorPage($user, $e->getMessage()));
            $res->send();
        } catch (Exception $e) {
            $res->setBody(HomeService::errorPage($user, $e->getMessage()));
            $res->send();
        }
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