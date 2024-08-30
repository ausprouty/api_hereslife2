<?php
// index.php

// Error reporting based on environment
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Include necessary files
require_once __DIR__ . '/App/Configuration/my-autoload.inc.php';
require_once __DIR__ . '/Vendor/autoload.php';

use App\Middleware\EnvironmentMiddleware;
use App\Middleware\PreflightMiddleware;
use App\Middleware\PostMiddleware;
use App\Middleware\CORSMiddleware;
use App\Middleware\AuthorizationMiddleware;
use App\Middleware\FinalMiddleware;

// Error reporting and environment setup could be managed in EnvironmentMiddleware
// Any other setup files needed can be included here

// Middleware stack function to handle the middleware flow
function applyMiddleware($middlewares, $request) {
    $next = function($request) use (&$middlewares, &$next) {
        if (empty($middlewares)) {
            return null; // Or some default response
        }
        $middleware = array_shift($middlewares);
        return $middleware->handle($request, $next);
    };
    
    return $next($request);
}

// Define the middleware stack
$middlewares = [
    new EnvironmentMiddleware(),
    new PreflightMiddleware(),
    new CORSMiddleware(),
    new PostMiddleware(),
    new AuthorizationMiddleware(),
    new FinalMiddleware()
];

// Apply the middleware stack
applyMiddleware($middlewares, $_SERVER);

// Main application logic or routing
require_once __DIR__ . '/router.php';
