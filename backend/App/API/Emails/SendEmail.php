<?php

use App\Services\Emails\EmailServices\Smtp2go\Smtp2GoMailerService;
use App\Utilities\RequestValidator;

/**
 * Send an email using the Smtp2GoMailerService after validating the request.
 * 
 * This script is responsible for sending an email using the Smtp2GoMailerService.
 * It validates the incoming request to ensure that the user has the necessary
 * administrative rights. If the 'bcc' field is not set, it defaults to an empty string.
 * Finally, it returns a JSON-encoded response indicating success or failure.
 * 
 * @param array $postData Contains the email data: 'address', 'subject', 'body', and optionally 'bcc'.
 * 
 * @return void Outputs a JSON response indicating the result of the email sending operation.
 */

// Validate the request and ensure the user is an admin
RequestValidator::validateAdmin($postData, 'SendEmail');

// Set 'bcc' to an empty string if not provided in the request
if (!isset($postData['bcc'])) {
    $bcc = '';
} else {
    $bcc = $postData['bcc'];
}
writeLogDebug("SendEmail-8", $postData);

// Instantiate the Smtp2GoMailerService with sender details and API key
$mailer = new Smtp2GoMailerService('bob@hereslife.com', 'Bob Prouty', STMP_API_KEY);

// Send the email using the Smtp2GoMailerService
$email_response = $mailer->sendEmail(
    $postData['address'],  // Recipient address
    'Test Name',           // Recipient name (hardcoded as 'Test Name' for now)
    $postData['subject'],  // Email subject
    $postData['body'],     // Email body
    $bcc                   // Optional BCC address
);

// Prepare the response array
$response = [
    'success' => $email_response,  // Indicates whether the email was sent successfully
    'data' => 'Email sent',        // Response message
];

// Set content type to JSON and output the response
header('Content-Type: application/json');
echo json_encode($response);
return;
