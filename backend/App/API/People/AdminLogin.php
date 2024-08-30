<?php

use App\Services\SanitizeInputService;
use App\Controllers\Data\PostInputController;
use App\Services\AuthorizationService;
// clean input data 
$sanitizeInputService = new SanitizeInputService();
$postInputController = new PostInputController(
    $sanitizeInputService
);
// check if the request is authorized
$authorized = AuthorizationService::checkApiKey($postInputController->getApiKey());
if (!$authorized){
    error_log('not authorized');
    http_response_code(401);
    return 'not authorized';
}

