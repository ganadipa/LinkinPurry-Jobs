<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\ProfileService;

class ProfileController {

    // Show profile
    public static function showProfile(Request $req, Response $res): void {
        $user = $req->getUser();
        
        if ($user === null || $user->role->value !== 'company') {
            echo '404';
            return;
        }

        try {
            $html = ProfileService::getCompanyProfilePage($user->user_id);
            $res->setBody($html);
            $res->send();
        } catch (\Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            $res->send();
        }
    }

    // Update profile
    public static function updateProfile(Request $req, Response $res): void {
        $data = $req->getPost();
        
        try {
            ProfileService::updateCompanyProfile($data);

            $res->json([
                'status' => 'success',
                'message' => 'Profile updated successfully.'
            ]);
            $res->send();
        } catch (\Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            $res->send();
        }
    }
}
