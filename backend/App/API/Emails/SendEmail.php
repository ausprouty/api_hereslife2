<?php
use App\Controllers\Data\PostInputController;
use App\Controllers\Emails\EmailController;
use App\Services\SanitizeInputService;
use App\Services\EmailServices\Smtp2go\Smtp2GoMailerService;

header('Content-Type: application/json'); // Set header before any output

writeLog('SendEmail.php-8', 'start');
$autoloaders = spl_autoload_functions();
writeLog('SendEmail.php-10', $autoloaders);

$sanitizeInputService = new SanitizeInputService();
$postInputController = new PostInputController($sanitizeInputService);
$data = $postInputController->handlePost();
// set bcc to empty string if not set
if (!isset($data['bcc'])) {
    $bcc = '';
} else {
    $bcc = $data['bcc'];
}
writeLogDebug("SendEmail-8", $data);

$mailer = new Smtp2GoMailerService('bob@hereslife.com', 'Bob Prouty', STMP_API_KEY);

$email_response = $mailer->sendEmail(
    $data['address'], 
    'Test Name', 
    $data['subject'], 
    $data['body'],
    $bcc
);

$response = [
    'success' => $email_response,
    'data' => 'Email sent',
];

echo json_encode($response);
return;
