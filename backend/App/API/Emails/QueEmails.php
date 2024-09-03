<?php
/* this file is called from the front end to queue a series of emails 
   and expects the following parameters:
        letterId: from hl_email
        groupCode: pass to ChampionEmailAddressService

*/


use App\Controllers\Emails\EmailQueController;
use App\Models\People\ChampionModel;
use App\Utilities\RequestValidator;


// Validate request and authorization
RequestValidator::validateAdmin($postData, 'QueEmails');

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

header('Content-Type: application/json');
echo json_encode($response);
