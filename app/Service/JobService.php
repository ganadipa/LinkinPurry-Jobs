<?php

namespace App\Service;

use App\Model\User;
use App\View\View;
use Core\DirectoryAlias;
use Core\Repositories;

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
        $applicants = $lamaranRepo->getNumberOfApplicants($jobId);

        $message = '';
        if ($applicants === 0) {
            $message = 'No applicants yet';
        } else if ($applicants === 1) {
            $message = '1 applicant';
        } else if ($applicants < 10) {
            $message = 'Few applicants';
        } else if ($applicants < 50) {
            $message = '10 - 50 applicants';
        } else if ($applicants < 100) {
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
            'js' => [
                'job/jobseeker/details.js'
            ],
            'title' => $lowongan->posisi . ' - ' . $userCompany->nama,
            'title' => $lowongan->posisi . ' - ' . $userCompany->nama,
            'company' => [
                'name' => $userCompany->nama,
                'location' => $company->lokasi,
                'name' => $userCompany->nama,
                'location' => $company->lokasi,
            ],
            'job' => [
                'id' => $lowongan->lowongan_id,
                'description' => $lowongan->deskripsi,
                'created' => $lowongan->created_at->format('Y-m-d'),
                'location' => $lowongan->jenis_lokasi->value,
                'type' => $lowongan->jenis_pekerjaan->value,
                'title' => $lowongan->posisi,
                'images' => [
                    "https://placehold.co/600x400",
                    "https://placehold.co/600x400",
                    "https://placehold.co/600x400",
                ],
                'isOpen' => $lowongan->is_open,
            ],
            'applied' => $lamaran !== null,
            'submission' => [
                'cv' => $cvUrl,
                'video' => $videoUrl,
            ],
            'status' => $lamaran ? $lamaran->status->value : null,
            'numberOfApplicantsMessage' => $message,
            'user' => $user,

        ]);

    }

    public static function detailsFromCompanyPage(string $jobId, User $user): string {
        return View::view('Page/Job/Company', 'Details', [
            'css' => [
                'job/details.css',
                'partials/company-card.css'
            ],
            'js' => [
                'job/company/details.js'
            ],
            'title' => 'Backend Engineer - Paper.id (Company)',
            'company' => [
                'name' => 'Paper.id',
                'location' => 'Jakarta, Indonesia',
            ],
            'job' => [
                'id' => 12,
                'description' => 'We are looking for a Backend Engineer to join our team. You will be responsible for maintaining the backend of our application and ensuring that it is always up and running. You will also be responsible for developing new features and improving existing ones. The ideal candidate will have experience working with Node.js and MongoDB. Experience with AWS is a plus.',
                'created' => '2021-08-01',
                'location' => 'Jakarta, Indonesia',
                'type' => 'Full-time',
                'title' => 'Backend Engineer',
                'images' => [
                    "https://placehold.co/600x400",
                    "https://placehold.co/600x400",
                    "https://placehold.co/600x400",
                ],
                'isOpen' => true,
            ],
            'applicants' => [
                [
                    'id' => 1,
                    'name' => 'Novelya Putri',
                    'status' => 'waiting',
                ],
                [
                    'id' => 2,
                    'name' => 'Nyoman Ganadipa',
                    'status' => 'accepted',
                ],
                [
                    'id' => 3,
                    'name' => 'Ahmad Mudabbir',
                    'status' => 'rejected',
                ],
            ],
            'numberOfApplicantsMessage' => 'Over 100 applicants',
            'user' => $user,
        ]);
    }

    public static function applicationDetails(string $jobId, string $applicationId, User $user): string {
        // In a real application, you would fetch this data from a database
        $applicant = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ];

        $application = [
            'id' => $applicationId,
            'status' => 'waiting', // waiting, accepted, rejected
            'cv_url' => DirectoryAlias::get('@public') . '/../../../../public/uploads/cv_' . $applicationId . '.pdf',
            'video_url' => DirectoryAlias::get('@public') . '/uploads/video_' . $applicationId . '.mp4',
            'reason' => '',
        ];

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
        return View::view('Page/Job/Jobseeker', 'Application', [
            'css' => [
                'job/application.css',
            ],
            'js' => [
                'job/jobseeker/application.js'
            ],
            'jobId' => $jobId,
            'title' => 'Apply for Backend Engineer - Paper.id',
            'user' => $user,
        ]);
    }

    public static function generateJobs(int $page, int $perPage, 
        string $q, array $jobType, array $locationType, string $sortOrder, User $user): array {
    
        $jobRepo = Repositories::$lowongan;

        if ($user === null || $user->role === 'JOBSEEKER') {
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
                'created' => $job->created_at->format('Y-m-d')
            ];
        }

        // for each job, get the company name by doing a query to the user table
        $userRepo = Repositories::$user;
        $companyRepo = Repositories::$companyDetail;
        foreach ($jobsRet as &$job) {
            $user = $userRepo->getUserProfileById($job['company_id']);
            $company = $companyRepo->getCompanyDetailByUserId($job['company_id']);
            $job['company'] = $user->nama;
            $job['location'] = $company->lokasi;
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
}