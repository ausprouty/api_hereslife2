<?php

namespace App\Middleware;

use App\Services\SanitizeInputService;
use App\Controllers\Data\PostInputController;
use App\Services\AuthorizationService;

class PostAuthorizationMiddleware {

    static public function getDataSet() {
    
            // Check if the request method is POST
        $dataSet = [];
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
            $dataSet = $postInputController->getDataSet();
        }
        return $dataSet;
    }
}
