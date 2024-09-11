<?php

use App\Services\Security\AuthorizationService;
use App\Models\People\AdministratorModel;
use App\Services\Database\DatabaseService;

/**
 * Create a new administrator and generate a JWT token for authentication.
 *
 * This script is responsible for creating a new administrator in the database using
 * the provided `$postData`, and then generating a JWT token for the newly created administrator.
 * The result, including the user ID and JWT token, is returned as a JSON-encoded response.
 *
 * The script interacts with the following components:
 * - **AdministratorModel**: Manages database interactions for creating an administrator and retrieving the administrator's ID.
 * - **DatabaseService**: Handles the connection to the database, specifically the 'standard' database in this case.
 * - **AuthorizationService**: Responsible for generating a JWT token for the newly created administrator.
 *
 * The script logs the input `$postData` and the result for tracking purposes.
 *
 * @global array $postData Contains the data used to create a new administrator, such as name, email, and password.
 * 
 * @return void Outputs a JSON response containing the new administrator's ID and JWT token.
 */

// Log the input data for tracking purposes
writeLog('AdminCreate-7', $postData);

// Instantiate the DatabaseService with the 'standard' database
$databaseService = new DatabaseService('standard');

// Create a new administrator using the provided data
$administratorModel = new AdministratorModel($databaseService);
$administratorModel->create($postData);

// Retrieve the ID of the newly created administrator
$userId = $administratorModel->getId();

// Generate a JWT token for the new administrator
$jwtService = new AuthorizationService();
$token = $jwtService->generateJWT($userId, 'admin');

// Prepare the response data, including the user ID and JWT token
$data = [
    'success' => 'TRUE', // Indicates the operation was successful
    'user' => $userId,   // The newly created administrator's user ID
    'token' => $token,   // The generated JWT token for authentication
];

// Log the output data for tracking purposes
writeLog('AdminCreate-21', $data);

// Set the content type to JSON and output the response
header('Content-Type: application/json');

// Output the response as JSON
echo json_encode($data);

