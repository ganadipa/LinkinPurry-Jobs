<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\ProfileService;
use Error;

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
        $name = $req->getPost('name');
        $email = $req->getPost('email');
        $lokasi = $req->getPost('location');
        $about = $req->getPost('about');

        $user = $req->getUser();

        // debug
        error_log("NAME: " . $name);
        error_log("EMAIL: " . $email);
        error_log("LOKASI: " . $lokasi);
        error_log("ABOUT: " . $about);
    
        if (!$name || !$email || !$lokasi || !$about) {
            $res->json([
                'status' => 'error',
                'message' => 'Incomplete data. Please fill in all fields.'
            ]);
            $res->send();
            return;
        }
    
        try {
            ProfileService::updateCompanyProfile([
                'user_id' => $user->user_id,
                'name' => $name,
                'email' => $email,
                'lokasi' => $lokasi,
                'about' => $about
            ]);
    
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
