<?php

/**
 * Handle image uploads for TinyMCE.
 *
 * This script processes an image upload from the TinyMCE editor. It validates the uploaded file,
 * sanitizes the filename, and moves the file to the designated directory. After a successful upload,
 * it returns the URL of the uploaded image in JSON format. If there are any errors during the upload,
 * appropriate error messages are logged, and HTTP status codes are returned.
 * 
 * @global string $imageFolder The folder where images will be saved.
 * @global string $uploadUrl The base URL to access the uploaded images.
 * 
 * @return void Outputs a JSON response with the image location or an error message.
 */

// Set the folder to save images and the base URL for uploaded images
$imageFolder = ROOT_IMAGES_EMAILS; 
$uploadUrl = URL_IMAGES_EMAILS;

// Log post data and error reporting
writeLog('UploadImagesTinyMce-11', print_r($_POST, true));
error_reporting(E_ALL);
error_log('origin ' . $_SERVER['HTTP_ORIGIN']);
error_log('Raw Input: ' . file_get_contents('php://input'));
error_log('Post: ' . print_r($_POST, true));

// Get the uploaded file
reset($_FILES);
$temp = current($_FILES);

// Check if a file was uploaded
if (is_uploaded_file($temp['tmp_name'])) {
    error_log('UploadImagesTinyMce-15: ' . $temp['tmp_name']);

    /*
      If your script needs to receive cookies, set images_upload_credentials: true
      in the TinyMCE configuration and enable the following headers.
    */
    header('Access-Control-Allow-Credentials: true');
    header('P3P: CP="There is no P3P policy."');

    // Sanitize the file name to prevent any unwanted characters
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        error_log('UploadImagesTinyMce-25: invalid file name');
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }

    // Verify the file extension (only gif, jpg, and png are allowed)
    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), ['gif', 'jpg', 'png'])) {
        error_log('UploadImagesTinyMce-32: invalid file extension');
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

    // Create a unique filename by appending a timestamp and replacing spaces with underscores
    $filename = $temp['name'];
    $suffix = strrpos($filename, '.');
    $filename = substr($filename, 0, $suffix) . '_' . time() . substr($filename, $suffix);
    $filename = str_replace(' ', '_', $filename);

    // Move the uploaded file to the designated folder
    $filetowrite = $imageFolder . $filename;
    move_uploaded_file($temp['tmp_name'], $filetowrite);

    // Determine the base URL (HTTP or HTTPS)
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
    $baseurl = $protocol . $_SERVER["HTTP_HOST"] . rtrim(dirname($_SERVER['REQUEST_URI']), "/") . "/";

    // Log the uploaded file URL and return the location in JSON format
    error_log('UploadImagesTinyMce-50: ' . $uploadUrl . '/' . $filename);
    echo json_encode(['location' => $uploadUrl . '/' . $filename]);
} else {
    // Log the error and return a 500 server error
    error_log('UploadImagesTinyMce-53: server error');
    header("HTTP/1.1 500 Server Error");
}
