<?php

Namespace App\Middleware;

class EnvironmentMiddleware {
    public function handle($request, $next) {
        // Check the server name
        $serverName = $_SERVER['SERVER_NAME'];

        if ($serverName === 'localhost') {
            // Local environment
            error_log('Including local environment configuration');
            require_once __DIR__ . '/App/Configuration/.env.local.php';
        } else {
            // Remote environment
            error_log('Including remote environment configuration');
            require_once __DIR__ . '/App/Configuration/.env.remote.php';
        }
        // Proceed to the next middleware or main application logic
        return $next($request);
    }
}