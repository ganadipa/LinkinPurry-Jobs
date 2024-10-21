<?php

namespace App\Service;
use App\View\View;
use Core\DirectoryAlias;
use Core\Repositories;

class JobService {
    public static function detailsFromJobSeekerPage(string $jobId): string {
        $lowongan = Repositories::$lowongan->getById($jobId);
        if (!$lowongan) {
            return '<h1>Job not found</h1>';
        }

        $user = Repositories::$user->getUserProfileById($lowongan->company_id);
        $detail_company = Repositories::$companyDetail->getCompanyDetailByUserId($lowongan->company_id);
        $attachment = Repositories::$attachmentLowongan->getAttachmentsById($lowongan->lowongan_id);
        
        // print_r($attachment);
        return View::view('Page/Job/Jobseeker', 'Details', [
            'css' => [
                'job/details.css',
                'partials/company-card.css'
            ],
            'js' => [
                'job/jobseeker/details.js'
            ],
            'title' => $lowongan->posisi . ' - ' . $user->nama,
            'company' => [
                'name' => $user->nama,
                'location' => $detail_company->lokasi,
            ],
            'job' => [
                'id' => $lowongan->lowongan_id,
                'description' => $lowongan->deskripsi,
                'created' => $lowongan->created_at->format('Y-m-d'),
                'location' => 'Jakarta, Indonesia',
                'type' => $lowongan->jenis_pekerjaan,
                'title' => $lowongan->posisi,
                'images' => $attachment,
                'isOpen' => $lowongan->is_open,
            ],
            'applied' => true,
            'submission' => [
                'cv' => 'h',
                'video' => 'h',
            ],
            'status' => 'accepted',
            'numberOfApplicantsMessage' => 'Over 100 applicants',
        ]);

    }

    public static function detailsFromCompanyPage(string $jobId): string {
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
        ]);
    }

    public static function applicationDetails(string $jobId, string $applicationId): string {
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
            'ext_js' => ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js']
        ]);
    }

    public static function application(string $jobId): string {
        return View::view('Page/Job/Jobseeker', 'Application', [
            'css' => [
                'job/application.css',
            ],
            'js' => [
                'job/jobseeker/application.js'
            ],
            'title' => 'Apply for Backend Engineer - Paper.id',
        ]);
    }

    public static function generateJobs(int $page, int $perPage, 
        string $q, array $jobType, array $locationType, string $sortOrder): array {
    
        $jobRepo = Repositories::$lowongan;
        $jobs = $jobRepo->getJobs($page, $perPage, 
            $q, $jobType, $locationType, $sortOrder);

        


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
}