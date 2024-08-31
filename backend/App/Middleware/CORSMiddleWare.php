<?php
Namespace App\Middleware;

class CORSMiddleware {
    public function handle($request, $next) {
        // Set CORS headers
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");
        writeLog('CORSMiddleware-12', 'I set the headers');
        // Proceed to the next middleware or application logic
        return $next($request);
    }
}
