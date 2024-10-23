<?php

namespace App\Service;

use App\Http\Exception\UnauthorizedException;
use App\Model\User;
use App\View\View;
use Core\DirectoryAlias;
use Core\Repositories;
use Exception;

class JobService {
    public static function detailsFromJobSeekerPage(string $jobId, ?User $user): string {
        $lowonganRepo = Repositories::$lowongan;
        $companyRepo = Repositories::$companyDetail;
        $lamaranRepo = Repositories::$lamaran;
        $userRepo = Repositories::$user;
        $attachmentsRepo = Repositories::$attachmentLowongan;


        // user id might be null
        $userId = null;
        if ($user !== null) {
            $userId = $user->user_id;
        }

        $lowongan = $lowonganRepo->getById($jobId);
        $company = $companyRepo->getCompanyDetailByUserId($lowongan->company_id);
        $userCompany = $userRepo->getUserProfileById($lowongan->company_id);

        // If the user id is null, then lamaran is null
        $lamaran = null;
        if ($userId !== null) {
            $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($userId, $jobId);
        }
        $attachments = $attachmentsRepo->getAttachmentsByLowonganId($jobId);



        // Get the number of applicants
        $applicantsNumber = $lamaranRepo->getNumberOfApplicants($jobId);

        $message = '';
        if ($applicantsNumber === 0) {
            $message = 'No applicants yet';
        } else if ($applicantsNumber === 1) {
            $message = '1 applicant';
        } else if ($applicantsNumber < 10) {
            $message = 'Few applicants';
        } else if ($applicantsNumber < 50) {
            $message = '10 - 50 applicants';
        } else if ($applicantsNumber < 100) {
            $message = '50 - 100 applicants';
        } else {
            $message = 'Over 100 applicants';
        }

        $cvUrl = null;
        $videoUrl = null;

        if ($lamaran !== null) {
            if ($lamaran->cv_path !== null) {
                $cvUrl = "/job/".$lamaran->lowongan_id."/apply/".$lamaran->user_id."/cv";
            }

            if ($lamaran->video_path !== null) {
                $videoUrl = "/job/".$lamaran->lowongan_id."/apply/".$lamaran->user_id."/video";
            }
        }
        
        // print_r($attachment);
        return View::view('Page/Job/Jobseeker', 'Details', [
            'css' => [
                'job/details.css',
                'partials/company-card.css'
            ],
            'ext_css' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'],
            'js' => [
                'job/jobseeker/details.js'
            ],
            'title' => $lowongan->posisi . ' - ' . $userCompany->nama,
            'company' => [
                'name' => $userCompany->nama,
                'location' => $company->lokasi
            ],
            'job' => [
                'id' => $lowongan->lowongan_id,
                'description' => $lowongan->deskripsi,
                'created' => $lowongan->created_at->format('Y-m-d'),
                'location' => $lowongan->jenis_lokasi->value,
                'type' => $lowongan->jenis_pekerjaan->value,
                'title' => $lowongan->posisi,
                'images' => $attachments,
                'isOpen' => $lowongan->is_open,
            ],
            'applied' => $lamaran !== null,
            'submission' => [
                'cv' => $cvUrl,
                'video' => $videoUrl,
            ],
            'status' => $lamaran ? $lamaran->status->value : null,
            'reason' => $lamaran 
                ? ($lamaran->status->value !== 'waiting' 
                    ? $lamaran->status_reason 
                    : 'Wait for approval.') 
                : '',
            'numberOfApplicantsMessage' => $message,
            'user' => $user,

        ]);

    }

    public static function detailsFromCompanyPage(string $jobId, User $user): string {
        $lowonganRepo = Repositories::$lowongan;
        $companyRepo = Repositories::$companyDetail;
        $lamaranRepo = Repositories::$lamaran;
        $userRepo = Repositories::$user;
        $attachmentsRepo = Repositories::$attachmentLowongan;


        // user id might be null
        $userId = null;
        if ($user !== null) {
            $userId = $user->user_id;
        }

        $lowongan = $lowonganRepo->getById($jobId);
        $company = $companyRepo->getCompanyDetailByUserId($lowongan->company_id);
        $userCompany = $userRepo->getUserProfileById($lowongan->company_id);

        // If the user id is null, then lamaran is null
        $lamaran = null;
        if ($userId !== null) {
            $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($userId, $jobId);
        }
        $attachments = $attachmentsRepo->getAttachmentsByLowonganId($jobId);

        // Get the number of applicants
        $applicantsNumber = $lamaranRepo->getNumberOfApplicants($jobId);

        // Get applicants
        $applicants = $lamaranRepo->getApplicantsByLowonganId($jobId);

        // Formated applicants
        $formattedApplicants = [];
        foreach ($applicants as $applicant) {
            array_push($formattedApplicants,  [
                'id' => $applicant->user_id,
                'name' => $userRepo->getUserProfileById($applicant->user_id)->nama,
                'status' => $applicant->status->value,
            ]);
        }

        $message = '';
        if ($applicantsNumber === 0) {
            $message = 'No applicants yet';
        } else if ($applicantsNumber === 1) {
            $message = '1 applicant';
        } else if ($applicantsNumber < 10) {
            $message = 'Few applicants';
        } else if ($applicantsNumber < 50) {
            $message = '10 - 50 applicants';
        } else if ($applicantsNumber < 100) {
            $message = '50 - 100 applicants';
        } else {
            $message = 'Over 100 applicants';
        }


        return View::view('Page/Job/Company', 'Details', [
            'css' => [
                'job/details.css',
                'partials/company-card.css'
            ],
            'ext_css' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'],
            'js' => [
                'job/company/details.js'
            ],
            'title' => $lowongan->posisi . ' - ' . $userCompany->nama,
            'company' => [
                'name' => $userCompany->nama,
                'location' => $company->lokasi
            ],
            'job' => [
                'id' => $jobId,
                'description' => $lowongan->deskripsi,
                'created' => $lowongan->created_at->format('Y-m-d'),
                'location' => $lowongan->jenis_lokasi->value,
                'type' => $lowongan->jenis_pekerjaan->value,
                'title' => $lowongan->posisi,
                'images' => $attachments,
                'isOpen' => $lowongan->is_open,
            ],
            'applicants' => $formattedApplicants,
            'numberOfApplicantsMessage' => $message,
            'user' => $user,
        ]);
    }

    public static function applicationDetails(string $jobId, string $applicantId, User $user): string {
        $lowonganRepo = Repositories::$lowongan;
        $lamaranRepo = Repositories::$lamaran;
        $applicantRepo = Repositories::$user;

        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($applicantId, $jobId);
        if (!$lamaran) {
            return HomeService::errorPage($user, 'Application not found');
        } 

        $lowongan = $lowonganRepo->getById($jobId);
        if (!$lowongan) {
            return HomeService::errorPage($user, 'Job not found');
        }

        if ($lowongan->company_id != $user->user_id) {
            return HomeService::errorPage($user, 'You are not authorized to view this page');
        }


        $applicant = $applicantRepo->getUserProfileById($lamaran->user_id);
        
        // In a real application, you would fetch this data from a database
        $applicant = [
            'id' => $applicant->user_id,
            'name' => $applicant->nama,
            'email' => $applicant->email,
        ];

        $application = [
            'id' => $applicantId,
            'status' => $lamaran->status->value,
            'cv_url' => $lamaran->cv_path ?? '',
            'video_url' => $lamaran->video_path ?? '',
            'reason' => $lamaran->status_reason ?? '',
        ];

        // print_r($application);

        return View::view('Page/Job/Company', 'ApplicationDetails', [
            'css' => [
                'job/application-details.css',
            ],
            'js' => [
                'job/company/application-details.js'
            ],
            'title' => 'Application Details',
            'jobId' => $jobId,
            'applicant' => $applicant,
            'application' => $application,
            'ext_css' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'],
            'ext_js' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js'],
            'user' => $user,
        ]);
    }

    public static function application(string $jobId, User $user): string {
        $job = Repositories::$lowongan->getById($jobId);
        if (!$job) {
            return HomeService::errorPage($user, 'Job not found');
        }

        if ($job->is_open === false) {
            return HomeService::errorPage($user, 'Job is closed');
        }
        
        $lamaran = Repositories::$lamaran->getLamaranByUserIdAndJobId($user->user_id, $jobId);
        if ($lamaran) {
            return HomeService::errorPage($user, 'You have already applied for this job');
        }

        $companyDetail = Repositories::$companyDetail->getCompanyDetailByUserId($job->company_id);
        $company = Repositories::$user->getUserProfileById($job->company_id);

        return View::view('Page/Job/Jobseeker', 'Application', [
            'css' => [
                'job/application.css',
            ],
            'js' => [
                'job/jobseeker/application.js'
            ],
            'job' => [
                'id' => $jobId,
                'title' => $job->posisi,
                'company' => $company->nama,
                'location' => $companyDetail->lokasi,
            ],
            'title' => 'Apply for ' . $job->posisi,
            'user' => $user,
        ]);
    }

    public static function generateJobs(int $page, int $perPage, 
        string $q, array $jobType, array $locationType, string $sortOrder, ?User $user): array {
    
        $jobRepo = Repositories::$lowongan;

        if ($user === null || $user->role->value === 'jobseeker') {
            $jobs = $jobRepo->getJobs($page, $perPage, 
            $q, $jobType, $locationType, $sortOrder);
        } else {
            $jobs = $jobRepo->getJobsByCompany($user->user_id, $page, $perPage, 
            $q, $jobType, $locationType, $sortOrder);
        }


        

        $jobsRet = [];
        foreach ($jobs as $job) {
            $jobsRet[] = [
                'id' => $job->lowongan_id,
                'title' => $job->posisi,
                'company_id' => $job->company_id,
                'created' => $job->created_at->format('Y-m-d'),
                'is_open' => $job->is_open
            ];
        }

        // for each job, get the company name by doing a query to the user table
        $userRepo = Repositories::$user;
        $companyRepo = Repositories::$companyDetail;
        $lowonganRepo = Repositories::$lowongan;
        foreach ($jobsRet as &$job) {
            $user = $userRepo->getUserProfileById($job['company_id']);
            $company = $companyRepo->getCompanyDetailByUserId($job['company_id']);
            $job['company'] = $user->nama;
            $job['location'] = $company->lokasi;
            $job['is_open'] = $lowonganRepo->getById($job['id'])->is_open;
        }



        return $jobsRet;
    }

    public static function getCVPath(int $jobid, int $userId ): string {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($userId, $jobid);
        return $lamaran->cv_path;
    }

    public static function getVideoPath(int $jobid, int $userId ): string {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($userId, $jobid);
        return $lamaran->video_path;
    }

    public static function updateStatusJob(string $jobId, string $userId): void {
        $jobRepo = Repositories::$lowongan;
        $job = $jobRepo->getById($jobId);

        if ($job->company_id !=  $userId) {
            throw new UnauthorizedException('You are not authorized to update this job');
        }


        $jobRepo = Repositories::$lowongan;
        $isOpen = $jobRepo->getById($jobId)->is_open;
        $jobRepo->updateStatusJob($jobId, !$isOpen);
    }

    public static function deleteJob(string $jobId, string $userId): void {
        $jobRepo = Repositories::$lowongan;

        $job = $jobRepo->getById($jobId);
        if ($job->company_id != $userId) {
            throw new UnauthorizedException('You are not authorized to delete this job');
        }

        $jobRepo->deleteJob($jobId);
    }
}