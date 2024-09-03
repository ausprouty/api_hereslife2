<?php

Namespace App\Middleware;

class PreflightMiddleware {
    public function handle($request, $next) {
        writeLog('PreflightMiddleware-7', 'I am in PreflightMiddleware');
        // Fetch accepted origins from .env
        $acceptedOrigins = explode(',', ACCEPTED_ORIGINS);
        writeLog('PreflightMiddleware-10', $acceptedOrigins);   
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            writeLog('PreflightMiddleware-12', $_SERVER['HTTP_ORIGIN']);
            if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $acceptedOrigins)) {
                error_log('PreflightMiddleware-18: Origin allowed: ' . $_SERVER['HTTP_ORIGIN']);
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                header('Access-Control-Allow-Credentials: true');
                // Allow specific headers, including Content-Type, in the request
                header("Access-Control-Allow-Headers: Content-Type, Authorization, User-Authorization");
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
                header("HTTP/1.1 200 OK");
                exit;
            } else {
                error_log('PreflightMiddleware-27: Origin not allowed: ' . $_SERVER['HTTP_ORIGIN']);
                header("HTTP/1.1 403 Forbidden Source");
                exit;
            }
        }else{
            writeLog('PreflightMiddleware-32', 'No options request');
        }

        // Proceed to the next middleware or application logic
        return $next($request);
    }
}
