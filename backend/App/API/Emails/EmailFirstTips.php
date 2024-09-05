<?php

use App\Controllers\Emails\EmailListMemberController;
use App\Models\Emails\EmailListMemberModel;
use App\Models\Emails\EmailModel;
use App\Services\EmailTipsService;
use App\Services\DatabaseService;
use App\Services\EmailService;


// Instantiate the service and controller
$databaseService = New DatabaseService();
$emailListMemberModel = New EmailListMemberModel($databaseService); 
$emailModel = New EmailModel($databaseService);   
$emailTipsService = New EmailTipsService($emailListMemberModel);
$emailListMemberController = New EmailListMemberController( $emailListMemberModel,$emailModel);

// Assuming a simple routing logic:
$emailListMemberController->processNewEmailTips();