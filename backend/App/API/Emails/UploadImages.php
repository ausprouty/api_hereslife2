<?php

use App\Controllers\Emails\ImageUploadController;



/**
 * Handle image upload with API key validation.
 *
 * This script processes an image upload request. It checks if a file is uploaded and if an API key
 * is provided and valid. Upon successful validation, the image is uploaded using the 
 * `ImageUploadController`, and the location of the uploaded file is returned as a JSON response.
 * If any validation fails, an error message is returned as JSON.
 * 
 * @param array $_FILES Contains the uploaded file data.
 * @param array $_POST Contains the API key and other form data.
 * 
 * @return void Outputs a JSON response indicating the location of the uploaded file or an error message.
 */

// Check if a file is uploaded
if (!isset($_FILES['file'])) {
    return error("No file uploaded");
}

// Check if the API key is provided
if (!$_POST['apiKey']) {
    return error("No API key provided");
}

// Validate the API key
if ($_POST['apiKey'] != VITE_APP_HL_API_KEY) { 
    return error("Invalid API key");
}

// Instantiate the ImageUploadController
$imageUploadController = new ImageUploadController();

// Upload the image and retrieve the filename
$filename = $imageUploadController->upload($_FILES['file']);

// Prepare the response with the uploaded file location
$response = [
    'location' => $filename,
];

// JSON encoding and output
header('Content-Type: application/json');
echo json_encode($response);

/**
 * Return an error message as a JSON response and terminate the script.
 *
 * This function handles errors by returning a JSON-encoded response containing
 * the error message and stopping script execution.
 *
 * @param string $message The error message to return.
 * 
 * @return void Outputs the error message as a JSON response and terminates the script.
 */
function error($message) {
    $response = [
        'success' => false,
        'message' => $message
    ];

    // Set content type and output the error response
    header('Content-Type: application/json');
    echo json_encode($response);
    die();
}
