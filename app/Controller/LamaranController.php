<?php

namespace App\Controller;

use App\Http\Exception\ForbiddenException;
use App\Http\Exception\HttpException;
use App\Service\LamaranService;
use App\Repositories;
use App\Http\Request;
use App\Http\Response;
use App\Model\Lamaran;
use Exception;
use App\Validator\PositiveNumericValidator;

class LamaranController {
    public static function acceptApplication(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            if ($user === null || $user->role->value !== 'company') {
                throw new ForbiddenException('You are not authorized to accept this application.');
            }

            $status = $req->getPost('status', null);
            $reason = $req->getPost('reason', null);

            $jobId = $req->getUriParamsValue('jobId', null);
            $applicantId = $req->getUriParamsValue('applicantId', null);

            $jobId = PositiveNumericValidator::validate($jobId);
            $applicantId = PositiveNumericValidator::validate($applicantId);

            LamaranService::acceptApplication($jobId, $applicantId, $reason, $user->user_id);

            $res->json([
                'status' => 'success',
                'message' => 'Application accepted successfully.',
                'data' => [
                    'status' => $status,
                    'reason' => $reason
                ]
            ]);

        } catch(Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);

            $res->send();
        }

    }

    public static function rejectApplication(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            if ($user === null || $user->role->value !== 'company') {
                throw new ForbiddenException('You are not authorized to reject this application.');
            }

            $status = $req->getPost('status', null);
            $reason = $req->getPost('reason', null);

            $jobId = $req->getUriParamsValue('jobId', null);
            $applicantId = $req->getUriParamsValue('applicantId', null);

            $jobId = PositiveNumericValidator::validate($jobId);
            $applicantId = PositiveNumericValidator::validate($applicantId);

            LamaranService::rejectApplication($jobId, $applicantId, $reason, $user->user_id);

            $res->json([
                'status' => 'success',
                'message' => 'Application rejected successfully.',
                'data' => [
                    'status' => $status,
                    'reason' => $reason
                ]
            ]);

        } catch(Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);

            $res->send();
        }

    }

    public static function showHistoryPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null) {
            $res->redirect('/login');
            $res->send();
            return;
        }



        try {
            if ($user->role->value !== 'jobseeker') {
                throw new ForbiddenException('You are not authorized to view this page.');
                return;
            }

            $html = LamaranService::getLamaranHistory($user);
            $res->setBody($html);
            $res->send();
        } catch (HttpException $e) {
            $res->setBody($e->getMessage());
            $res->send();
        } catch (\Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            $res->send();
        }
    }
}
