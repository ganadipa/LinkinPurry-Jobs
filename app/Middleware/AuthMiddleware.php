<?php

namespace App\Middleware;
use App\Model\User;
use App\Http\Request;
use Core\Repositories;


class AuthMiddleware  implements IMiddleware{
    public function handle(Request $req): bool {
        $user_id = $req->getSessionValue('id', null);
        if ($user_id == null) {
            return true;
        }

        $userRepo = Repositories::$user;
        $user = $userRepo->getUserProfileById($user_id);
        if ($user == null) {
            return true;
        }

        $req->setUser($user);
        return true;
    }

    public function getMessage(): string {
        return "You are not logged in.";
    }
}