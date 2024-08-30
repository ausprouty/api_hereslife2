<?php

use App\Controllers\Materials\TractController;

header('Content-Type: application/json');

$tractController = new TractController();
// Call the method to get the tracts
$data = $tractController->getTractsToView();

echo json_encode($data);