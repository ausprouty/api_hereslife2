<?php

// Instantiate the required services
use App\Services\AuthorizationService;
use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;

writeLog('AdminLogin-7', $postData);
$databaseService = new DatabaseService('standard');
$administratorModel = new AdministratorModel($databaseService);

$userId = $administratorModel->verify($postData['username'], $postData['password']);  
if ($userId == 'FALSE') {
    notAuthorized();
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

function notAuthorized() {
    $data = [
        'success' => 'FALSE',
        'message' => 'Invalid username or password',
    ];
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}



