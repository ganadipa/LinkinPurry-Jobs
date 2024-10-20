<?php

namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Validator\PositiveNumericValidator;
use App\Http\Exception\HttpException;
use App\Http\Exception\UnauthorizedException;
use App\Service\JobService;
use App\Service\LamaranService;
use \Exception;

class JobController {
    public static function jobdetails(Request $req, Response $res): void {
        try {
            // Get the needed value
            $id = $req->getUriParamsValue('id', null);

            if (!isset($id)) {
                throw new Exception ('Job not found');
            }

            // Validate
            $validatedId = PositiveNumericValidator::validate($id);

            $html = JobService::detailsFromJobSeekerPage($id);
            

            $res->setBody($html);
            $res->send();

        } catch (HttpException $e) {
            // Either its a classified HttpException

            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);

            $res->send();

        } catch (Exception $e) {
            // Or its just an ordinary exception

            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);

            $res->send();
        }

    }

    public static function jobapplication(Request $req, Response $res): void {
        try {
            // Get the needed value
            $id = $req->getUriParamsValue('id', null);
    
            if (!isset($id)) {
                throw new Exception ('Job not found');
            }
    
            // Validate
            $validatedId = PositiveNumericValidator::validate($id);

            $html = JobService::application($id);
    
            $res->setBody($html);
            $res->send(); 
        } catch (HttpException $e) {
            // Either its a classified HttpException
    
            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
    
            $res->send();
    
        } catch (Exception $e) {
            // Or its just an ordinary exception
    
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
    
            $res->send();
        }
    }

    public static function applyjob(Request $req, Response $res) {
        try {
            // Get the needed value
            $cv = $req->getPost('cv', null);
            $video = $req->getPost('video', null);
            $lowongan_id = $req->getUriParamsValue('id', null);

            if ($req->getUser() == null) {
                throw new UnauthorizedException('You must login first');
            }

            $user_id = $req->getUser()->user_id;

    
            if (!isset($cv) || !isset($video)) {
                throw new Exception ('CV or Video not found');
            }

            $lamaran_id = LamaranService::applyJob($lowongan_id, $user_id, $cv, $video);
    

            $res->json([
                'status' => 'success',
                'message' => 'Job applied successfully',
                'data' => [
                    'lamaran_id' => $lamaran_id,
                ]
            ]);
    
            $res->send();
        } catch (HttpException $e) {
            // Either its a classified HttpException
    
            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
    
            $res->send();
    
        } catch (HttpException $e) {
            // Either its a classified HttpException
    
            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
    
            $res->send();
    
        } catch (Exception $e) {
            // Or its just an ordinary exception
    
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
    
            $res->send();
        }
    }

    public static function generateJobs(Request $req, Response $res): void {
        $page = $req->getQueryParam('page', 1);
        $perPage = 10;

        $jobs = [];
        for ($i = 0; $i < $perPage; $i++) {
            $jobs[] = JobService::generateJob(($page - 1) * $perPage + $i + 1);
        }

        $res->json($jobs);
        $res->send();
    }
}