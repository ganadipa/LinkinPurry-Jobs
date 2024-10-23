<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\LamaranService;

class LamaranController {
    
    public static function showHistoryPage(Request $req, Response $res): void {
        $user = $req->getUser();

        if ($user === null || $user->role->value !== 'jobseeker') {
            echo '404';
            return;
        }

        try {
            $html = LamaranService::getLamaranHistory($user);
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
}
