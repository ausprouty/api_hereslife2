<?php
use App\Controllers\Data\PostInputController;
use App\Controllers\Emails\EmailController;
use App\Utilities\RequestValidator;

/**
 * Update an email in the series based on input data.
 *
 * This script processes a request to update an email in an email series. It validates
 * that the request is authorized by checking if the user is an admin. If validation passes,
 * it updates the email with the provided data using the `EmailController`. The script
 * returns a JSON response indicating the success of the update.
 * 
 * @param array $postData The input data for the email update, including fields such as
 *                        'series', 'sequence', 'subject', and 'body'.
 * 
 * @return void Outputs a JSON-encoded response indicating success.
 */

// Validate request and check admin authorization
RequestValidator::validateAdmin($postData, 'SeriesEmailTextUpdate');

// Update the email in the series using the EmailController
$emailController = new EmailController();
$data = $emailController->updateEmailFromInputData($postData);

// Prepare the response array indicating success
$response = [
    'success' => 'TRUE',                  // Status of the update operation
    'message' => 'Record updated successfully',  // Confirmation message
];

// Set content type to JSON and output the response
header('Content-Type: application/json');
echo json_encode($response);
