<?php


use App\Controllers\Materials\SpiritController;
use App\Models\Materials\SpiritModel;
use App\Services\DatabaseService;
use App\Services\ResourceService;

header('Content-Type: application/json');

// Instantiate the required services
$databaseService = new DatabaseService($database = 'standard');
$spiritModel = new SpiritModel($databaseService);
$resourceService = new ResourceService();

// Pass the services to the SpiritController
$spiritController = new SpiritController($spiritModel, $resourceService);

// Call the method to get the tracts
$data = $spiritController->getTitlesByLanguageName();
echo json_encode($data);
