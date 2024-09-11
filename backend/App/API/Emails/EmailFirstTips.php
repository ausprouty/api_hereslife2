<?php

use App\Controllers\Emails\EmailListMemberController;
use App\Models\Emails\EmailListMemberModel;
use App\Models\Emails\EmailModel;
use App\Services\Emails\EmailTipsService;
use App\Services\Database\DatabaseService;
use App\Services\Emails\EmailService;
use App\Models\Emails\EmailQueModel;

/**
 * Initialize necessary services and models for handling email tips processing.
 */

// Instantiate the DatabaseService
$databaseService = new DatabaseService();

// Instantiate the EmailListMemberModel with a DatabaseService dependency
$emailListMemberModel = new EmailListMemberModel($databaseService); 

// Instantiate the EmailModel with a DatabaseService dependency
$emailModel = new EmailModel($databaseService); 

// Instantiate the EmailQueModel with a DatabaseService dependency
$emailQueModel = new EmailQueModel($databaseService);  

// Instantiate the EmailTipsService using the EmailListMemberModel
$emailTipsService = new EmailTipsService($emailListMemberModel);

// Instantiate the EmailListMemberController with required models
$emailListMemberController = new EmailListMemberController(
    $emailListMemberModel, 
    $emailModel, 
    $emailQueModel
);

// Process new email tips and log an error if it fails
$result = $emailListMemberController->processNewEmailTips();
if (!$result) {
    error_log ("Error processing email tips.");
}
echo $result . " email tips processed successfully.";
