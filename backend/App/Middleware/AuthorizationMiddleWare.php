<?php

namespace App\Middleware;

class AuthorizationMiddleware {
    public function handle($request, $next) {
        // Always return true for authorization (i.e., always allow)
        $isAuthorized = true;

        if ($isAuthorized) {
            // Proceed to the next middleware or application logic
            return $next($request);
        } else {
            // If not authorized, you might redirect or return a 403 error
            header('HTTP/1.1 403 Forbidden');
            echo 'Access denied.';
            exit;
        }
    }
}
