<?php

namespace App\Controller;

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
            $status = $req->getPost('status', null);
            $reason = $req->getPost('reason', null);

            print_r($_POST);

            $jobId = $req->getUriParamsValue('jobId', null);
            $applicantId = $req->getUriParamsValue('applicantId', null);

            $jobId = PositiveNumericValidator::validate($jobId);
            $applicantId = PositiveNumericValidator::validate($applicantId);

            LamaranService::acceptApplication($jobId, $applicantId, $reason);

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
            $status = $req->getPost('status', null);
            $reason = $req->getPost('reason', null);

            $jobId = $req->getUriParamsValue('jobId', null);
            $applicantId = $req->getUriParamsValue('applicantId', null);

            $jobId = PositiveNumericValidator::validate($jobId);
            $applicantId = PositiveNumericValidator::validate($applicantId);

            LamaranService::rejectApplication($jobId, $applicantId, $reason);

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
}
<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\LamaranService;

class LamaranController {
    
    public static function showHistoryPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || $user->role->value !== 'jobseeker') {
            echo '404';
            return;
        }

        try {
            $html = LamaranService::getLamaranHistory($user);
            $res->setBody($html);
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
