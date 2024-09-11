<?php

use App\Controllers\Materials\SpiritController;
use App\Models\Materials\SpiritModel;
use App\Services\Database\DatabaseService;
use App\Services\Materials\ResourceService;



/**
 * Retrieve and output tract titles by language as a JSON response.
 *
 * This script handles the retrieval of tract titles based on the language name. It utilizes
 * various services and models to fetch the required data from the database. The results
 * are returned as a JSON-encoded response, making it easy for front-end applications to
 * consume and display the available tracts by language.
 *
 * The script works with the following components:
 * - **SpiritController**: Handles the logic for retrieving tract titles by interacting with the model and resource services.
 * - **SpiritModel**: Interfaces with the database to query for tract-related data.
 * - **DatabaseService**: Manages the database connection and handles queries.
 * - **ResourceService**: Provides additional functionality related to resource management, if needed.
 *
 * The method `getTitlesByLanguageName()` is called to fetch the titles of the tracts available in the specified language.
 *
 * @return void Outputs a JSON response containing the tract titles.
 */

// Instantiate the required services
$databaseService = new DatabaseService($database = 'standard'); // Connect to the 'standard' database for tract data
$spiritModel = new SpiritModel($databaseService); // SpiritModel interacts with the database to fetch tract data
$resourceService = new ResourceService(); // ResourceService provides additional resource-related functionality if needed

// Pass the services to the SpiritController
$spiritController = new SpiritController($spiritModel, $resourceService);

// Call the method to get the tract titles by language
$data = $spiritController->getTitlesByLanguageName();

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($data);

