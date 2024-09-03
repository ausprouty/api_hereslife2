<?php

// Instantiate the required services
use App\Services\AuthorizationService;
use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;
use App\Utilities\ErrorHandler;

// set the database to be used
$databaseService = new DatabaseService('standard');
$administratorModel = new AdministratorModel($databaseService);
// verify the user
$userId = $administratorModel->verify($postData['username'], $postData['password']);  
if ($userId == 'FALSE') {
    ErrorHandler::handle('Not Authorized', 'Please re-enter your username and password');
}
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





