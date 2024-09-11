<?php

use App\Services\Security\AuthorizationService;
use App\Models\People\AdministratorModel;
use App\Services\Database\DatabaseService;
use App\Utilities\ErrorHandler;

/**
 * Authenticate an administrator and generate a JWT token for authentication.
 *
 * This script handles administrator login by verifying the provided username and password.
 * If the credentials are valid, it generates a JWT token for the authenticated administrator
 * and returns the user ID and token in a JSON response. If authentication fails, an error message
 * is returned, and the user is prompted to re-enter their credentials.
 *
 * The script interacts with the following components:
 * - **AdministratorModel**: Handles the database query to verify the administrator's credentials.
 * - **DatabaseService**: Manages the connection to the 'standard' database.
 * - **AuthorizationService**: Generates the JWT token for the authenticated administrator.
 * - **ErrorHandler**: Handles errors such as failed authentication.
 *
 * @global array $postData The incoming request data containing the username and password.
 * 
 * @return void Outputs a JSON response with the user ID and JWT token upon successful authentication.
 */

// Set the database to be used
$databaseService = new DatabaseService('standard');

// Instantiate the AdministratorModel to verify the user
$administratorModel = new AdministratorModel($databaseService);

// Verify the administrator's credentials (username and password)
$userId = $administratorModel->verify($postData['username'], $postData['password']);  

// If verification fails, handle the error
if ($userId == 'FALSE') {
    ErrorHandler::handle('Not Authorized', 'Please re-enter your username and password');
}

// Instantiate the AuthorizationService to generate a JWT token
$jwtService = new AuthorizationService();

// Generate a JWT token for the verified administrator
$token = $jwtService->generateJWT($userId, 'admin');

// Prepare the response data with the user ID and token
$data = [
    'success' => 'TRUE',  // Indicates the login was successful
    'user' => $userId,    // The verified administrator's user ID
    'token' => $token,    // The generated JWT token for authentication
];

// Log the successful authentication and response data
writeLog('AdminCreate-21', $data);

// Set the content type to JSON and output the response
header('Content-Type: application/json');

// Output the response as JSON
echo json_encode($data);

