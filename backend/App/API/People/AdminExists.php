<?php

use App\Models\People\AdministratorModel;
use App\Services\Database\DatabaseService;

/**
 * Verify the existence of an administrator and return the result as a JSON response.
 *
 * This script checks if an administrator exists in the system by interacting with the
 * `AdministratorModel` through the `DatabaseService`. The result is returned as a
 * JSON-encoded response, indicating whether an administrator exists.
 *
 * The script utilizes the following components:
 * - **AdministratorModel**: Handles the query to verify if an administrator exists in the database.
 * - **DatabaseService**: Manages the connection to the 'standard' database for querying administrator data.
 *
 * @return void Outputs a JSON response containing the existence of the administrator.
 */

// Instantiate the DatabaseService with the 'standard' database
$databaseService = new DatabaseService('standard');

// Verify if there is an administrator in the system
$administratorModel = new AdministratorModel($databaseService);
$administratorExists = $administratorModel->exists(); // Returns true if an administrator exists, otherwise false

// Prepare the response data with the existence status
$response = [
    'success' => true,            // Indicates the request was successfully processed
    'data' => $administratorExists, // Boolean indicating whether an administrator exists
];

// Output the response as JSON
echo json_encode($response);

