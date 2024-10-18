<?php

namespace App\Service;
use App\View\View;
use Core\DirectoryAlias;
use Core\Repositories;

class JobService {
    public static function detailsFromJobSeekerPage(string $jobId): string {


        return View::view('Page/Job/Jobseeker', 'Details', [
            'css' => [
                'job/details.css',
                'partials/company-card.css'
            ],
            'js' => [
                'job/jobseeker/details.js'
            ],
            'title' => 'Backend Engineer - Paper.id',
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
            'applied' => false,
            'submission' => [
                'cv' => 'h',
                'video' => 'h',
            ],
            'status' => '',
            'numberOfApplicantsMessage' => 'Over 100 applicants',
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



    
}