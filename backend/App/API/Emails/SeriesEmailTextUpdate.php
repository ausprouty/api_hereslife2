<?php
use App\Controllers\Data\PostInputController;
use App\Controllers\Emails\EmailController;

header('Content-Type: application/json');
// only authenticated users can update the email series
$postInputController = new PostInputController();
$data = $postInputController->handlePost();

if ($data) { 
    $emailController = new EmailController();
    $data = $emailController->updateEmailFromInputData($data);
    // JSON encoding and output
    $response = [
        'success' => true,
    ]; 
} else {
    $response = [
        'success' => true,
        'message' => 'Invalid input data',
    ];
}

echo json_encode($response);