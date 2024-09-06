<?php

use App\Controllers\Materials\DownloadController;
use App\Controllers\Materials\MaterialController;
use App\Controllers\People\ChampionController;
use App\Models\Materials\DownloadModel;
use App\Repositories\ChampionRepository;
use App\Services\UserMaterialService;
use App\Utilities\RequestValidator;

/**
 * Handle a request to update user materials and process a material download.
 *
 * This script processes a request to update a user's material download information. It starts by
 * validating the request and user authorization using the API key provided in the request data.
 * After validation, it initializes the necessary controllers, repositories, and services to handle
 * the download. The user's material download request is then processed, and the results are returned
 * as a JSON-encoded response.
 * 
 * @global array $postData The incoming request data, containing the user details and the download information.
 * 
 * @return void Outputs a JSON response indicating the result of the user's material download process.
 */

// Validate request and authorization
$apiKey = $postInputController->getApiKey();
RequestValidator::validateUser($postData, $apiKey, 'DownloadMaterialsUpdateUser');

// Initialize dependencies for DownloadModel and related controllers
$downloadModel = new DownloadModel($database = 'standard');
$downloadController = new DownloadController($downloadModel, $database);
$materialController = new MaterialController();
$championRepository = new ChampionRepository($database = 'standard');
$championController = new ChampionController($championRepository);

// Instantiate the UserMaterialService with necessary controllers
$userMaterialService = new UserMaterialService(
    $materialController,
    $championController,
    $downloadController
);

// Log the request data
writeLog('downloadMaterialsUpdateUser-40',  $postData);

// Set the content type to JSON and output the result of the user material download process
header('Content-Type: application/json');
echo $userMaterialService->handleUserMaterialDownload($postInputController->getDataSet());

