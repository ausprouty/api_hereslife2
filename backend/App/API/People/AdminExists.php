<?php


// Instantiate the required services

use App\Models\People\AdministratorModel;
use App\Services\DatabaseService;
$databaseService = new DatabaseService('standard');

// Verify if there is an administrator
$administratorModel = new AdministratorModel($databaseService);
$administratorExists = $administratorModel->exists();
echo $administratorExists;
return $administratorExists;
