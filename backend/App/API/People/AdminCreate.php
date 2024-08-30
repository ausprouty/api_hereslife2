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

use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;
$databaseService = new DatabaseService('standard');


// Create a new administrator
$administratorModel = new AdministratorModel($databaseService);
$data = $postInputController->getDataSet();
writeLog('AdminCreate-34', $data);
$administratorModel->create($data);

