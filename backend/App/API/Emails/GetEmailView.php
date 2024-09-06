<?php
use App\Controllers\Emails\EmailController;

/**
 * Fetch and return a formatted email by its ID in JSON format.
 *
 * This script allows anyone to access the email data in the database
 * and return it formatted for view. The `EmailController` is responsible
 * for handling the request and formatting the email data. The output is returned
 * as a JSON-encoded response, with a `success` status and the formatted data.
 *
 * @param int $id The ID of the email to fetch and format.
 * @return void Outputs the JSON-encoded response.
 */

// Instantiate the EmailController
$emailController = new EmailController();

// Fetch and format the email data by ID for view
$data = $emailController->formatForView($id);

// Prepare the response array
$response = [
    'success' => true,  // Indicates successful formatting and fetching of the email data
    'data' => $data,    // The formatted email data
];
header('Content-Type: application/json');
// JSON encoding and output
echo json_encode($response);
