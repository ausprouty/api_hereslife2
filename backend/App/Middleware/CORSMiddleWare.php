<?php

Namespace App\Middleware;

class CORSMiddleware {
    public function handle($request, $next) {
        // Fetch accepted origins from .env
        $acceptedOrigins = explode(',', ACCEPTED_ORIGINS);
   
        // Check if the request has an Origin header
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = $_SERVER['HTTP_ORIGIN'];

            // Check if the request origin is in the list of accepted origins
            if (in_array($origin, $acceptedOrigins)) {
                header('Access-Control-Allow-Origin: ' . $origin);
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
                header("Access-Control-Allow-Headers: Content-Type, Authorization");
                header("Access-Control-Allow-Credentials: true");
                writeLog('CORSMiddleware-12', 'I set the headers for allowed origin: ' . $origin);
            } else {
                writeLogError('CORSMiddleware-12', 'Origin not allowed: ' . $origin);
            }
        } else {
            // Handle requests without an Origin header (e.g., non-CORS requests)
            writeLogError('CORSMiddleware-12', 'No Origin header present in the request.');
        }

        // Proceed to the next middleware or application logic
        return $next($request);
    }
}
