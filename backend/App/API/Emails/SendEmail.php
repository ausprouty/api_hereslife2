<?php
use App\Controllers\Data\PostInputController;
use App\Controllers\Emails\EmailController;
use App\Services\SanitizeInputService;
use App\Services\EmailServices\Smtp2go\Smtp2GoMailerService;
use App\Utilities\ErrorHandler;

$autoloaders = spl_autoload_functions();

// only authenticated users can update the email series
if (!$postData) { 
    ErrorHandler::handle('No data provided', 'No post data provided in SendEmail');
} 
$authorizationService = new AuthorizationService();
$authorized = $authorizationService->checkAuthorizationUser();
if (!$authorized) {
    ErrorHandler::handle('Not Authorized', 'Not authorized in SendEmail');
}

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
