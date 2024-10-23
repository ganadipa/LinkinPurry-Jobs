<?php

namespace App\Service;

use App\View\View;
use Core\Repositories;
use Error;

class ProfileService {

    public static function getCompanyProfilePage(int $userId): string {
        error_log("HIIII"); // debug
        error_log("USER?? " . $userId); // debug
        $companyDetailRepo = Repositories::$companyDetail;
        $userRepo = Repositories::$user;

        $companyDetail = $companyDetailRepo->getCompanyDetailByUserId($userId);
        error_log("COMPANY DETAIL?? " . print_r($companyDetail)); // debug
        if (!$companyDetail) {
            throw new \Exception('Company detail not found');
        }

        $user = $userRepo->getUserProfileById($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        return View::view('Page/Job/Company', 'Profile', [
            'css' => ['company/profile.css'],
            'js' => ['company/profile.js'],
            'title' => 'Company Profile - ' . $user->nama,
            'company' => [
                'name' => $user->nama,
                'location' => $companyDetail->lokasi,
                'about' => $companyDetail->about
            ]
        ]);
    }

    // Update profile company
    public static function updateCompanyProfile(array $data): void {
        print_r($data); // debug
        $companyDetailRepo = Repositories::$companyDetail;

        // Update detail company
        $companyDetailRepo->update(new \App\Model\CompanyDetail(
            $data['user_id'],
            $data['lokasi'],
            $data['about']
        ));
    }
}
