<?php

Namespace App\Middleware;

class CORSMiddleware {
    public function handle($request, $next) {
        // Fetch accepted origins from .env
        $acceptedOrigins = explode(',', ACCEPTED_ORIGINS);
   

        // Check if the request origin is in the list of accepted origins
        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $acceptedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            header("Access-Control-Allow-Credentials: true");
            writeLog('CORSMiddleware-12', 'I set the headers for allowed origin: ' . $_SERVER['HTTP_ORIGIN']);
        } else {
            writeLog('CORSMiddleware-12', 'Origin not allowed: ' . $_SERVER['HTTP_ORIGIN']);
        }

        // Proceed to the next middleware or application logic
        return $next($request);
    }
}
