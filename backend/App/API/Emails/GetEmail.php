<?php
use App\Controllers\Emails\EmailController;

header('Content-Type: application/json');
// anyone can access this database to receive the emails in question
$emailController = new EmailController();
$data = $emailController->getEmailById($id);
// JSON encoding and output
$response = [
    'success' => true,
    'data' => $data,
];

echo json_encode($response);