<?php

Namespace App\Middleware;

class PreflightMiddleware {
    public function handle($request, $next) {
        writeLog('PreflightMiddleware-12', 'I am in PreflightMiddleware');
        // Fetch accepted origins from .env
        $acceptedOrigins = explode(',', getenv('ACCEPTED_ORIGINS'));

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $acceptedOrigins)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                header('Access-Control-Allow-Credentials: true');
                header("HTTP/1.1 200 OK");
                exit;
            } else {
                header("HTTP/1.1 403 Forbidden Source");
                exit;
            }
        }

        // Proceed to the next middleware or application logic
        return $next($request);
    }
}
