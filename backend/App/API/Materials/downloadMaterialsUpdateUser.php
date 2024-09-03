<?php


use App\Controllers\Materials\DownloadController;
use App\Controllers\Materials\MaterialController;
use App\Controllers\People\ChampionController;
use App\Models\Materials\DownloadModel;
use App\Repositories\ChampionRepository;
use App\Services\UserMaterialService;
use App\Utilities\RequestValidator;


// arrives with $postData

// Validate request and authorization
$apiKey = $postInputController->getApiKey();
RequestValidator::validateUser($postData, $apiKey, 'DownloadMaterialsUpdateUser');


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
