<?php


// Instantiate the required services
use App\Services\AuthorizationService;
use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;

$databaseService = new DatabaseService('standard');
// Create a new administrator
$administratorModel = new AdministratorModel($databaseService);
$administratorModel->create($postData);
$userId = $administratorModel->getId();
$jwtService = new AuthorizationService();
$token = $jwtService->generateJWT($userId, 'admin');
$data = [
    'success' => true,
    'user' => $userId,
    'token' => $token,
];
header('Content-Type: application/json');
echo json_encode($data);

