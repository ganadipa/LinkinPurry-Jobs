<?php

namespace App\Controller;

use App\Http\Exception\ForbiddenException;
use App\Http\Request;
use App\Http\Response;
use App\Validator\PositiveNumericValidator;
use App\Http\Exception\HttpException;
use App\Http\Exception\UnauthorizedException;
use App\Service\CompanyService;
use App\Service\JobService;
use App\Service\LamaranService;
use \Exception;
use App\Util\Enum\JobTypeEnum;
use App\Util\Enum\JenisLokasiEnum;
use App\Validator\ArrayValidator;

class JobController {
    public static function jobdetails(Request $req, Response $res): void {
        try {
            // Get the needed value
            $user = $req->getUser();
            
            
            $id = $req->getUriParamsValue('id', null);
            
            $validatedId = PositiveNumericValidator::validate($id);

            // User might be null
            if ($user == null ||  $user->role->value == 'jobseeker') {
                $html = JobService::detailsFromJobSeekerPage($validatedId, $user);
            } else {
                $html = JobService::detailsFromCompanyPage($validatedId, $user);
            }

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
    
            // Because using the redirect if not logged in middleware, the user will always be not null
            $user = $req->getUser();

            if (!isset($id)) {
                throw new Exception ('Job not found');
            }
    
            // Validate
            $validatedId = PositiveNumericValidator::validate($id);

            $html = JobService::application($id, $user);
    
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

    
            if (!isset($cv)) {
                throw new Exception ('CV must be uploaded');
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

    public static function applicationDetails(Request $req, Response $res): void {
        try {
            $user = $req->getUser();
            if ($user == null || $user->role == 'jobseeker') {
                throw new UnauthorizedException('You must login as a company');
            }

            $jobId = $req->getUriParamsValue('jobId', null);
            $applicantId = $req->getUriParamsValue('applicantId', null);

            if (!isset($jobId) || !isset($applicantId)) {
                throw new Exception('Job or application not found');
            }

            $validatedJobId = PositiveNumericValidator::validate($jobId);
            $validatedUserId = PositiveNumericValidator::validate($applicantId);
            
            $html = JobService::applicationDetails($validatedJobId, $validatedUserId, $user);

            $res->setBody($html);
            $res->send();

        } catch (HttpException $e) {
            $res->setStatusCode($e->getStatusCode());
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
            $res->send();
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
            $res->send();
        }
    }

    public static function generateJobs(Request $req, Response $res): void {    
        $user = $req->getUser();

        $q = $req->getQueryParam('q') ?? '';
        $jobType = $req->getQueryParam('job-type') ?? ['full-time', 'part-time', 'internship'];
        $locationType = $req->getQueryParam('location-type') ?? [
            'on-site', 'hybrid', 'remote'
        ];
        $sortOrder = $req->getQueryParam('sort-order') ?? 'desc';

        // Validate each query parameter
        $jobType = ArrayValidator::validate($jobType);
        $locationType = ArrayValidator::validate($locationType);

        foreach ($jobType as $type) {
            // If not in array then just remove it
            if (!in_array($type, [JobTypeEnum::FULL_TIME->value, JobTypeEnum::PART_TIME->value, JobTypeEnum::INTERNSHIP->value])) {
                // remove
                $jobType = array_filter($jobType, function($job) use ($type) {
                    return $job !== $type;
                });
            }
        }

        foreach ($locationType as $type) {
            if (!in_array($type, [JenisLokasiEnum::ON_SITE->value, JenisLokasiEnum::HYBRID->value, JenisLokasiEnum::REMOTE->value])) {
                $locationType = array_filter($locationType, function($location) use ($type) {
                    return $location !== $type;
                });
            }
        }

        // Make the jobtype and location type as enum
        $jobType = array_map(function($type) {
            return JobTypeEnum::from($type);
        }, $jobType);

        $locationType = array_map(function($type) {
            return JenisLokasiEnum::from($type);
        }, $locationType);


        $page = $req->getQueryParam('page', 1);
        $perPage = 10;

        $jobs = JobService::generateJobs($page, $perPage, 
            $q, $jobType, $locationType, $sortOrder, $user
        );

        $res->json($jobs);
        $res->send();
    }

    public static function appliedCV(Request $req, Response $res) {
        try {

            $user = $req->getUser();
            if ($user == null) {
                throw new UnauthorizedException('You must login first');
            }

            // Get the needed value
            $jobId = $req->getUriParamsValue('jobId', null);
            $userId = $req->getUriParamsValue('userId', null);

            if ($jobId == null || $userId == null) {
                throw new Exception('Job not found');
            }

            $companyId = CompanyService::getCompanyIdByJobId($jobId);

            
            // Validate 
            $validatedJobId = PositiveNumericValidator::validate($jobId);
            $validatedUserId = PositiveNumericValidator::validate($userId);
            
            if ($user->user_id !== $validatedUserId && $user->user_id !== $companyId) {
                throw new ForbiddenException('You are not allowed to access this resource');
            }

            $path = JobService::getCVPath($validatedJobId, $validatedUserId);

            $res->pdf($path);
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

    public static function appliedVideo(Request $req, Response $res) {
        try {

            $user = $req->getUser();
            if ($user == null) {
                throw new UnauthorizedException('You must login first');
            }

            // Get the needed value
            $jobId = $req->getUriParamsValue('jobId', null);
            $userId = $req->getUriParamsValue('userId', null);

            if ($jobId == null || $userId == null) {
                throw new Exception('Job not found');
            }

            $companyId = CompanyService::getCompanyIdByJobId($jobId);

            
            // Validate 
            $validatedJobId = PositiveNumericValidator::validate($jobId);
            $validatedUserId = PositiveNumericValidator::validate($userId);
            
            if ($user->user_id !== $validatedUserId && $user->user_id !== $companyId) {
                throw new ForbiddenException('You are not allowed to access this resource');
            }

            $path = JobService::getVideoPath($validatedJobId, $validatedUserId);

            $res->video($path);
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

    public static function updateStatusJob(Request $req, Response $res) {
        try {
            $user = $req->getUser();
            if ($user == null || $user->role == 'jobseeker') {
                throw new UnauthorizedException('You must login as a company');
            }

            $jobId = $req->getUriParamsValue('id', null);

            if ($jobId == null) {
                throw new Exception('Job not found');
            }

            $validatedJobId = PositiveNumericValidator::validate($jobId);
            // $validatedIsOpen = filter_var($isOpen, FILTER_VALIDATE_BOOLEAN);

            JobService::updateStatusJob($validatedJobId);

            $res->json([
                'status' => 'success',
                'message' => 'Job status updated successfully',
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

    public static function deleteJob(Request $req, Response $res) {
        try {
            $user = $req->getUser();
            if ($user == null || $user->role == 'jobseeker') {
                throw new UnauthorizedException('You must login as a company');
            }

            $jobId = $req->getUriParamsValue('id', null);

            if ($jobId == null) {
                throw new Exception('Job not found');
            }

            $validatedJobId = PositiveNumericValidator::validate($jobId);

            JobService::deleteJob($validatedJobId);

            $res->json([
                'status' => 'success',
                'message' => 'Job deleted successfully',
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
}