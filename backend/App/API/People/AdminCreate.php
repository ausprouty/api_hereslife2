<?php
use App\Services\SanitizeInputService;
use App\Controllers\Data\PostInputController;
use App\Services\AuthorizationService;
// clean input data 
$sanitizeInputService = new SanitizeInputService();
$postInputController = new PostInputController(
    $sanitizeInputService
);
$apiKey = $postInputController->getApiKey();
// check if the request is authorized
$authorized = AuthorizationService::checkApiKey($apiKey);
if (!$authorized){
    error_log('not authorized on line 15 of AdminCreate.php');
    http_response_code(401);
    return 'not authorized';
}

// Instantiate the required services
$databaseService = new DatabaseService($database = 'standard');
use App\Models\People\AdministratorModel;
$administratorModel = new AdministratorModel($databaseService);
$administratorModel->create($postInputController->getPostData());

