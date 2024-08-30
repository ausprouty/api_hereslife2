<?php

use App\Controllers\Emails\EmailController;

$emailController = new EmailController();
$titles = $emailController->getRecentBlogTitles($number); // number from route
writeLog ('blogTitles-7', $titles);
$response = [
    'success' => true,
    'data' => $titles,
];

echo json_encode($response);
return;