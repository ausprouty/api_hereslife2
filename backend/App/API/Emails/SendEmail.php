<?php

use App\Services\EmailServices\Smtp2go\Smtp2GoMailerService;
use App\Utilities\RequestValidator;

// Validate request and authorization
RequestValidator::validateAdmin($postData, 'SendEmail');

$autoloaders = spl_autoload_functions();

// set bcc to empty string if not set
if (!isset($postData['bcc'])) {
    $bcc = '';
} else {
    $bcc = $postData['bcc'];
}
writeLogDebug("SendEmail-8", $postData);

$mailer = new Smtp2GoMailerService('bob@hereslife.com', 'Bob Prouty', STMP_API_KEY);

$email_response = $mailer->sendEmail(
    $postData['address'], 
    'Test Name', 
    $postData['subject'], 
    $postData['body'],
    $bcc
);

$response = [
    'success' => $email_response,
    'data' => 'Email sent',
];
header('Content-Type: application/json'); // Set header before any output
echo json_encode($response);
return;
