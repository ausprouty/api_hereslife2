<?php

// Instantiate the required services

use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;
$databaseService = new DatabaseService('standard');
echo ('<br>AdminExists.php<br>');
// Verify if there is an administrator
$administratorModel = new AdministratorModel($databaseService);
$administratorExists = $administratorModel->exists();
echo ("value is $administratorExists");
return $administratorExists;
