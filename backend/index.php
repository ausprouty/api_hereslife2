<?php
// index.php

// Load the appropriate environment configuration
require_once __DIR__ . '/App/Configuration/config.php'; // Load environment-specific config

// Load Debugging tools
require_once __DIR__ . '/App/Includes/writeLog.php'; 
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

use App\Middleware\PreflightMiddleware;
use App\Middleware\PostAuthorizationMiddleware;
use App\Middleware\CORSMiddleware;
use App\Middleware\FinalMiddleware;

// Middleware stack function to handle the middleware flow
function applyMiddleware($middlewares, $request) {
    $postInputController = null;
    $next = function($request, $postInputController) use (&$middlewares, &$next) {
        if (empty($middlewares)) {
            return $postInputController; // Return the PostInputController when no more middleware
        }
        $middleware = array_shift($middlewares);
        return $middleware->handle($request, function($request, $newPostInputController = null) use ($next, $postInputController) {
            // If a new PostInputController is provided, update the reference
            $postInputController = $newPostInputController ?: $postInputController;
            return $next($request, $postInputController);
        });
    };
    
    return $next($request, $postInputController);
}

// Define the middleware stack
$middlewares = [
    new PreflightMiddleware(),
    new CORSMiddleware(),
    new PostAuthorizationMiddleware(),
    new FinalMiddleware()
];

// Apply the middleware stack and capture the PostInputController
$postInputController = applyMiddleware($middlewares, $_SERVER);

// Pass the PostInputController to routes.php
if ($postInputController !== null) {
    // Use the sanitized data within routes.php
    $postData = $postInputController->getDataSet();
    // Pass the data to your routing logic as needed
}
// Main application logic or routing
require_once __DIR__ . '/routes.php';
