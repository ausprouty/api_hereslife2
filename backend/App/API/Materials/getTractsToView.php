<?php

use App\Controllers\Materials\TractController;

/**
 * Retrieve and output tracts for viewing as a JSON response.
 *
 * This script handles the retrieval of tracts that are ready to be viewed by the user.
 * It calls the `TractController` to fetch the list of tracts and returns the data as
 * a JSON-encoded response, making it consumable by front-end applications or APIs.
 *
 * @return void Outputs a JSON-encoded response containing the tract data.
 */

// Instantiate the TractController
$tractController = new TractController();

// Call the method to get the list of tracts for viewing
$data = $tractController->getTractsToView();

// Set the content type to JSON and output the tract data
header('Content-Type: application/json');
echo json_encode($data);

