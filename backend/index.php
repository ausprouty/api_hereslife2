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
use App\Controllers\PostInputController;
use App\Middleware\PreflightMiddleware;
use App\Middleware\PostAuthorizationMiddleware;
use App\Middleware\CORSMiddleware;
use App\Middleware\FinalMiddleware;

// Middleware stack function to handle the middleware flow
function applyMiddleware($middlewares, $request) {
    // Initialize with null to indicate no PostInputController yet
    $postInputController = null;
    
    // Define the next function to process the middleware stack
    $next = function($request, $postInputController) use (&$middlewares, &$next) {
        // Check if there are no more middlewares to process
        if (empty($middlewares)) {
            // Return the PostInputController, whether null or an instance
            return $postInputController;
        }
        
        // Get the next middleware in the stack
        $middleware = array_shift($middlewares);
        
        // Process the middleware, passing the request and the controller
        return $middleware->handle($request, function($request, $newPostInputController = null) use ($next, $postInputController) {
            // If the middleware returned a new PostInputController, use it
            if ($newPostInputController instanceof PostInputController) {
                $postInputController = $newPostInputController;
            }
            // Continue to the next middleware
            return $next($request, $postInputController);
        });
    };
    
    // Start the middleware processing chain
    return $next($request, $postInputController);
}

// Define and apply the middleware stack
$middlewares = [
    new PreflightMiddleware(),
    new CORSMiddleware(),
    new PostAuthorizationMiddleware(),
    new FinalMiddleware()
];

// Apply the middleware stack and retrieve the PostInputController
$postInputController = applyMiddleware($middlewares, $_SERVER);

// Check if PostInputController was successfully created and passed
if ($postInputController instanceof PostInputController) {
    // Use the sanitized data from the PostInputController
    $postData = $postInputController->getDataSet();
} else {
    // Handle the case where no PostInputController was created
    $postData = null;
}

// Main application logic or routing
require_once __DIR__ . '/routes.php';
