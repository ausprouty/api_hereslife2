<?php


use App\Controllers\Materials\DownloadController;
use App\Controllers\Materials\MaterialController;
use App\Controllers\People\ChampionController;
use App\Models\Materials\DownloadModel;
use App\Repositories\ChampionRepository;
use App\Services\UserMaterialService;


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


// Initialize dependencies for DownloadModel
$downloadModel = new DownloadModel($database = 'standard');
$downloadController = new DownloadController($downloadModel , $database);
$materialController = new MaterialController();
$championRepository = new ChampionRepository($database = 'standard');
$championController = new ChampionController($championRepository);
$userMaterialService = new UserMaterialService(
    $materialController,
    $championController,
    $downloadController
);
writeLog('downloadMaterialsUpdateUser-40',  $postInputController->getDataSet());
header('Content-Type: application/json');
echo $userMaterialService->handleUserMaterialDownload($postInputController->getDataSet());
