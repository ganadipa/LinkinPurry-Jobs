<?php

namespace App\Middleware;
use App\Http\Request;

class RedirectIfNotLoggedInMiddleware implements IMiddleware {
    public function handle(Request $req): bool {
        $user = $req->getUser();
        if (!isset($user)) {
            header("Location: /login");
            exit;

            return false;
        }

        // True means move on to the next middleware.
        return true;
    }

    public function getMessage(): string {
        return "You are not logged in.";
    }
}