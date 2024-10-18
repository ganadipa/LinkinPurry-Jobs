<?php

namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Validator\PositiveNumericValidator;
use App\Http\Exception\HttpException;
use App\Service\JobService;

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
    
    
            $res->setBody("Job application for job id: $id");
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