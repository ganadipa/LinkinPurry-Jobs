<?php 

namespace App\Middleware;
use App\Http\Request;

class RedirectIfLoggedInMiddleware implements IMiddleware {
    public function handle(Request $req): bool {
        $user = $req->getUser();
        if (isset($user)) {
            header("Location: /");
            exit;

            return false;
        }

        // True means move on to the next middleware.
        return true;
    }

    public function getMessage(): string {
        return "You are already logged in.";
    }
}