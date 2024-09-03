<?php
use App\Controllers\Data\PostInputController;
use App\Controllers\Emails\EmailController;
use App\Services\AuthorizationService;
use App\Utilities\ErrorHandler;


// only authenticated users can update the email series
if (!$postData) { 
    ErrorHandler::handle('No data provided', 'No post data provided in SeriesEmailTextUpdate');
} 
$authorizationService = new AuthorizationService();
$authorized = $authorizationService->checkAuthorizationUser();
if (!$authorized) {
    ErrorHandler::handle('Not Authorized', 'Not authorized in SeriesEmailTextUpdate');
}

// Update the email series
$emailController = new EmailController();
$data = $emailController->updateEmailFromInputData($postData);
// JSON encoding and output
$response = [
    'success' => 'TRUE',
    'message' => 'Record updated successfully',
]; 
header('Content-Type: application/json');
echo json_encode($response);


