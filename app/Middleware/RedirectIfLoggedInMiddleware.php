<?php 

namespace App\Middleware;

class RedirectIfLoggedInMiddleware implements IMiddleware {
    public function handle(): bool {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        // True means move on to the next middleware.
        return true;
    }
}