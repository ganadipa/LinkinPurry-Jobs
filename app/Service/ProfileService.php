<?php

namespace App\Service;

use App\View\View;
use Core\Repositories;
use Error;

class ProfileService {

    public static function getCompanyProfilePage(int $userId): string {
        error_log("USER?? " . $userId); // debug
        $companyDetailRepo = Repositories::$companyDetail;
        $userRepo = Repositories::$user;

        $companyDetail = $companyDetailRepo->getCompanyDetailByUserId($userId);
        error_log("COMPANY DETAIL?? " . print_r($companyDetail, true)); // debug
        if (!$companyDetail) {
            throw new \Exception('Company detail not found');
        }

        $user = $userRepo->getUserProfileById($userId);
        error_log("USER:" . print_r($user, true)); // debug
        if (!$user) {
            throw new \Exception('User not found');
        }

        return View::view('Page/Job/Company', 'Profile', [
            'css' => ['company/profile.css'],
            'js' => ['company/profile.js'],
            'title' => 'Company Profile - ' . $user->nama,
            'company' => [
                'name' => $user->nama,
                'email' => $user->email,
                'location' => $companyDetail->lokasi,
                'about' => $companyDetail->about
            ],
            'companyDetail' => $companyDetail,
            'user' => $user
        ]);
    }

    // Update profile company
    public static function updateCompanyProfile(array $data): void {
        error_log("UPDATE PROFILE: " . print_r($data, true)); // debug
        $companyDetailRepo = Repositories::$companyDetail;
        $userRepo = Repositories::$user;

        // Update detail company
        $companyDetailRepo->update(new \App\Model\CompanyDetail(
            $data['user_id'],
            $data['lokasi'],
            $data['about']
        ));

        // Update user
        $userRepo->updateNameEmail($data['user_id'], $data['email'], $data['name']);
    }
}
