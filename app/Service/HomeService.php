<?php

namespace App\Service;

use App\Model\User;
use App\Service;
use App\Util\Enum\JobTypeEnum;
use App\Util\Enum\JenisLokasiEnum;
use App\View\View;
use Core\Repositories;

class HomeService {
    
    public static function render(string $view, array $vars = []): string {
        return View::render('Layout', 'Main', array_merge_recursive($vars, 
            [
                'content' => View::render('Page', $view, $vars),
                'css' => ['company/shared.css'],
            ]
        ));
    }

    public static function getHomeJobSeekerPage(
        string $q, array $jobType, array $locationType, string $sortOrder, ?User $user
    ) {
        $jobtype = [
            'full-time' => in_array(JobTypeEnum::FULL_TIME, $jobType),
            'part-time' => in_array(JobTypeEnum::PART_TIME, $jobType),
            'internship' => in_array(JobTypeEnum::INTERNSHIP, $jobType),
        ];

        $locationtype = [
            'on-site' => in_array(JenisLokasiEnum::ON_SITE, $locationType),
            'hybrid' => in_array(JenisLokasiEnum::HYBRID, $locationType),
            'remote' => in_array(JenisLokasiEnum::REMOTE, $locationType),
        ];

        $lowonganRepo = Repositories::$lowongan;

        return self::render('HomeJobSeeker', [
            'css' => ['home/home.css'],
            'js' => ['home/jobseeker.js'],
            'title' => 'Home Page (Job Seeker)',
            'numberOfJobs' => $lowonganRepo->getNumberOfJobs(),
            'filter' => [
                'q' => $q,
                'jobType' => $jobtype,
                'locationType' => $locationtype,
                'sortOrder' => $sortOrder,
                
            ],
            'user' => $user,
        ]);
    }

    public static function getHomeCompanyPage(
        string $q, array $jobType, array $locationType, string $sortOrder, User $user
    ) {
        $jobtype = [
            'full-time' => in_array(JobTypeEnum::FULL_TIME, $jobType),
            'part-time' => in_array(JobTypeEnum::PART_TIME, $jobType),
            'internship' => in_array(JobTypeEnum::INTERNSHIP, $jobType),
        ];

        $locationtype = [
            'on-site' => in_array(JenisLokasiEnum::ON_SITE, $locationType),
            'hybrid' => in_array(JenisLokasiEnum::HYBRID, $locationType),
            'remote' => in_array(JenisLokasiEnum::REMOTE, $locationType),
        ];

        $lowonganRepo = Repositories::$lowongan; 

        return self::render('HomeCompany', [
            'css' => ['home/home.css'],
            'js' => ['home/company.js'],
            'title' => 'Home Page (Company)',
            'numberOfJobs' => $lowonganRepo->getNumberOfJobsPostedByCompany($user->user_id),
            'filter' => [
                'q' => $q,
                'jobType' => $jobtype,
                'locationType' => $locationtype,
                'sortOrder' => $sortOrder,
                
            ],
            'user' => $user,
        ]);
    }
}