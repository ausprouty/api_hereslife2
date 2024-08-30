<?php
/* this file is called from the front end to queue a series of emails 
   and expects the following parameters:
        letterId: from hl_email
        groupCode: pass to ChampionEmailAddressService
        apiKey: for security
*/

use App\Controllers\Data\PostInputController;
use App\Controllers\Emails\EmailQueController;
use App\Models\People\ChampionModel;
use App\Models\Data\PostInputModel;
use App\Services\AuthorizationService;
use App\Services\SanitizeInputService;

header('Content-Type: application/json');

// Instantiate the necessary objects
$postInputModel = new PostInputModel();  // Assuming this has a default constructor
$sanitizeInputService = new SanitizeInputService();


// Instantiate the PostInputController with its dependencies
$postInputController = new PostInputController($sanitizeInputService);

// Handle the POST request
$data = $postInputController->handlePost();

if ($data) { 
    $authorizationService = new AuthorizationService();
    // Get champions matching the group code
    $championModel = new ChampionModel();
    $champions = $championModel->getChampionEmails($data['groupCode']);

    // Queue the emails
    $emailQueController = new EmailQueController();
    $result = $emailQueController->queEmails($champions, $data['letterId']);
 
    // JSON encoding and output
    $response = [
        'success' => true,
        'message' => $result,
    ]; 
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid input data',
    ];
}

echo json_encode($response);
