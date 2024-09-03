<?php

// Instantiate the required services
use App\Services\AuthorizationService;
use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;
writeLog('AdimnCreate-7', $postData);
$databaseService = new DatabaseService('standard');
// Create a new administrator
$administratorModel = new AdministratorModel($databaseService);
$administratorModel->create($postData);
$userId = $administratorModel->getId();
$jwtService = new AuthorizationService();
$token = $jwtService->generateJWT($userId, 'admin');
$data = [
    'success' => 'TRUE',
    'user' => $userId,
    'token' => $token,
];
header('Content-Type: application/json');
writeLog('AdimnCreate-21', $data);
echo json_encode($data);

