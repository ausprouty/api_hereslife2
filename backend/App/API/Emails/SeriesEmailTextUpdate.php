<?php
use App\Controllers\Data\PostInputController;
use App\Controllers\Emails\EmailController;
use App\Utilities\RequestValidator;



// Validate request and authorization
RequestValidator::validateAdmin($postData, 'SeriesEmailTextUpdate');

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


