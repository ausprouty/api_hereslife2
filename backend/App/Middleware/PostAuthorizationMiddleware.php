<?php

namespace App\Middleware;

use App\Services\SanitizeInputService;
use App\Controllers\Data\PostInputController;
use App\Services\AuthorizationService;

class PostAuthorizationMiddleware {
    public function handle($request, $next) {
    
            // Check if the request method is POST
        $postInputController = std_object();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Clean input data
            writeLog('PostAuthorizationMiddleware-7', $_POST);
            $sanitizeInputService = new SanitizeInputService();
            $postInputController = new PostInputController($sanitizeInputService);
            // Check if the request is authorized
            writeLog('PostAuthorizationMiddleware-20', $postInputController->getDataSet());
            $authorized = AuthorizationService::checkAuthorizationHeader();
            if (!$authorized) {
                error_log('not authorized');
                http_response_code(401);
                return 'not authorized';
            }
        }
        writeLog('PostAuthorizationMiddleware-28', $postInputController->getDataSet());
        writeLog('PostAuthorizationMiddleware-passing', $postInputController);
        // Pass the $postInputController to the next step in the pipeline
        return $next($request, $postInputController);
    }
}
