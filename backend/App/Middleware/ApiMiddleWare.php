<?php

namespace App\Middleware;

use App\Services\SanitizeInputService;
use App\Controllers\Data\PostInputController;
use App\Services\AuthorizationService;

class ApiMiddleware {
    public static function handle($request, $next) {
        // Clean input data
        $sanitizeInputService = new SanitizeInputService();
        $postInputController = new PostInputController($sanitizeInputService);

        // Check if the request is authorized
        $authorized = AuthorizationService::checkApiKey($postInputController->getApiKey());
        if (!$authorized) {
            error_log('not authorized');
            http_response_code(401);
            return 'not authorized';
        }

        // Pass the $postInputController to the next step in the pipeline
        return $next($request, $postInputController);
    }
}
