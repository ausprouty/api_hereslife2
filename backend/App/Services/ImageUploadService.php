<?php

Namespace App\Services;

/**
 * Class ImageUploadService
 * 
 * Service class to handle image uploads.
 */
class ImageUploadService {
    /**
     * @var string $uploadDir The directory where uploaded images will be stored.
     * @var string $uploadUrl The base URL for accessing uploaded images.
     */
    private $uploadDir;
    private $uploadUrl;

    /**
     * Constructor.
     * 
     * @param string $uploadDir The directory where uploaded images will be stored.
     * @param string $uploadUrl The base URL for accessing uploaded images.
     */
    public function __construct($uploadDir, $uploadUrl) {
        $this->uploadUrl = $uploadUrl;
        $this->uploadDir = $uploadDir;
        
        // Create the upload directory if it doesn't exist.
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Handles the upload of an image file.
     * 
     * @param array $file The uploaded file from the $_FILES global array.
     * @return string The URL of the uploaded image.
     * @throws Exception If the upload fails or the file type is not allowed.
     * 
     * This method handles the upload process for an image file. It sanitizes the file name,
     * checks for valid file extensions, and moves the file to the specified upload directory.
     * If the upload is successful, it returns the URL of the uploaded file.
     * 
     * Supported file types: jpg, gif, png, jpeg.
     */
    public function uploadImage($file) {
        // Check if a file was uploaded and if there were no errors during the upload process
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $file['tmp_name'];  // Temporary file path
            $fileName = $file['name'];         // Original file name
            $fileNameCmps = explode(".", $fileName);  // Split the file name to extract extension
            $fileExtension = strtolower(end($fileNameCmps));  // Get the file extension

            // Sanitize the file name to avoid collisions
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            // Allowed file extensions
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

            // Check if the file has a valid extension
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $destPath = $this->uploadDir . $newFileName;  // Destination path

                // Move the uploaded file to the destination directory
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    return $this->uploadUrl . '/' . $newFileName;  // Return the URL of the uploaded file
                } else {
                    throw new Exception('Error moving the uploaded file');  // Throw an error if the file can't be moved
                }
            } else {
                // Throw an error if the file extension is not allowed
                throw new Exception('Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions));
            }
        } else {
            // Throw an error if no file was uploaded or there was an error during the upload process
            throw new Exception('No file uploaded or there was an upload error');
        }
    }
}
