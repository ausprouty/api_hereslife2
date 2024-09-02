<?php


// Instantiate the required services

use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;

$databaseService = new DatabaseService('standard');

// Create a new administrator

$administratorModel = new AdministratorModel($databaseService);
$administratorModel->create($postData);
$userId = $administratorModel->getId();
writeLog('AdminCreate-35', $userId);
$token = $administratorModel->getToken($userId, 'admin');
writeLog('AdminCreate-37', $token);
$data = [
    'message' => 'Administrator created successfully',
    'id' => $userId,
    'token' => $token,
];
writeLog('AdminCreate-42', $data);
echo json_encode($data);

