<?php
use App\Controllers\Emails\EmailController;



/**
 * Fetch and return an email by its ID in JSON format.
 *
 * This script is designed to be accessed by anyone to fetch an email by its ID
 * from the database and return it as a JSON response. The `EmailController`
 * is responsible for handling the request and fetching the data.
 *
 * @param int $id The ID of the email to fetch.
 * @return void Outputs the JSON-encoded response.
 */

// Instantiate the EmailController
$emailController = new EmailController();

// Fetch the email data by ID
$data = $emailController->getEmailById($id);

// Prepare the response array
$response = [
    'success' => true,  // Indicates successful fetching of the data
    'data' => $data,    // The fetched email data
];

// JSON encoding and output
header('Content-Type: application/json');
echo json_encode($response);
