<?php
use App\Controllers\Emails\EmailController;



/**
 * Fetch and return an email based on the series and sequence number in JSON format.
 *
 * This script allows anyone to access the database and fetch an email by providing
 * the series and sequence number. The `EmailController` is responsible for handling
 * the request and fetching the relevant email. The output is returned as a 
 * JSON-encoded response.
 *
 * @param string $series The name of the email series to retrieve.
 * @param int $sequence The sequence number of the email in the series.
 * 
 * @return void Outputs the JSON-encoded response with the fetched email data.
 */

// Instantiate the EmailController
$emailController = new EmailController();

// Fetch the email data by series and sequence
$data = $emailController->getEmailBySeriesAndSequence($series, $sequence);

// Prepare the response array
$response = [
    'success' => true,  // Indicates successful fetching of the email
    'data' => $data,    // The fetched email data
];

// JSON encoding and output
header('Content-Type: application/json');
echo json_encode($response);
