<?php

/**
 * Handle image uploads from a TinyMCE editor or any other file input.
 *
 * This script processes an uploaded image, verifies its validity, and moves it to
 * the designated folder. The script responds with a JSON object containing the image's
 * location, or returns an error if the upload fails or the file is invalid.
 *
 * @global array $accepted_origins An array of accepted origin URLs to protect against cross-origin requests.
 * @global string $imageFolder The folder path where images are uploaded.
 *
 * @return void Outputs a JSON response with the image location or an error message.
 */


// Define the folder where images will be saved
$imageFolder = ROOT_IMAGES_EMAILS;

// Check if a file has been uploaded
reset($_FILES);
$temp = current($_FILES);

if (is_uploaded_file($temp['tmp_name'])) {

    // Sanitize the input to ensure the file name is valid
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }

    // Verify the file extension (only allow gif, jpg, png)
    $valid_extensions = array("gif", "jpg", "png");
    $file_extension = strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $valid_extensions)) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

    // Define the path to write the uploaded file
    $filetowrite = $imageFolder . $temp['name'];
    move_uploaded_file($temp['tmp_name'], $filetowrite);

    // Determine the base URL for the response
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
    $baseurl = $protocol . $_SERVER["HTTP_HOST"] . rtrim(dirname($_SERVER['REQUEST_URI']), "/") . "/";
   
    // Set the content type to JSON
    header('Content-Type: application/json');
    
    // Respond to the successful upload with the file location in JSON format
    echo json_encode(['location' => $baseurl . $filetowrite]);

} else {
    // Handle failure to upload the file
    header("HTTP/1.1 500 Server Error");
}
