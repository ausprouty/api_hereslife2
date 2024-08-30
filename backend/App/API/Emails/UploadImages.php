<?php


use App\Controllers\Emails\ImageUploadController;

header('Content-Type: application/json');

if (!isset($_FILES['file']) ) {
    return error("No file uploaded");
}
if (!$_POST['apiKey']) {
    return error("No API key provided");
}
if ($_POST['apiKey'] != VITE_APP_HL_API_KEY){ 
    return error("Invalid API key");
}
          
$imageUploadController = new ImageUploadController();
$filename = $imageUploadController->upload($_FILES['file']);
$response = [
    'location' => $filename,
];

echo json_encode($response);
    
function error($message){
    $response = [
        'success' => false,
        'message' => $message
    ];
    eader('Content-Type: application/json');
    echo json_encode($response);
    die();      
}


