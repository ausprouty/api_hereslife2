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
use App\Utilities\ErrorHandler;

header('Content-Type: application/json');

if (!$postData){
    ErrorHandler::handle('No data provided', 'No data provided');   
}

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


echo json_encode($response);
