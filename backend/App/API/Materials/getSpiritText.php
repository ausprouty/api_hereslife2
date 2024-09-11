<?php

use App\Controllers\Materials\SpiritController;
use App\Models\Materials\SpiritModel;
use App\Services\Database\DatabaseService;
use App\Services\Materials\ResourceService;

/**
 * Retrieve and output Spirit text by language as a JSON response.
 *
 * This script is responsible for fetching Spirit-related text content based on the user's
 * selected language. The language parameter is provided by the router, and the script utilizes
 * several services and models to fetch the appropriate data. The results are returned as a 
 * JSON-encoded response.
 *
 * The SpiritController manages the interaction between the SpiritModel (which interfaces with
 * the database) and the ResourceService (which may handle additional resource-related functionality).
 * 
 * @global string $language The language identifier passed via the router.
 * 
 * @return void Outputs the Spirit text content as a JSON-encoded response.
 */

// Instantiate the required services
$databaseService = new DatabaseService($database = 'standard'); // Connect to the 'standard' database
$spiritModel = new SpiritModel($databaseService); // SpiritModel interacts with the database through the DatabaseService
$resourceService = new ResourceService(); // ResourceService provides additional resource-related functionality

// Pass the services to the SpiritController
$spiritController = new SpiritController($spiritModel, $resourceService);

// Call the method to get the Spirit text by language
// $language is supplied by the router
$data = $spiritController->getSpiritTextByLanguage($language);

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($data);

