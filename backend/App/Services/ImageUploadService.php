<?php

Namespace App\Services;

class ImageUploadService {
    private $uploadDir;
    private $uploadUrl;

    public function __construct($uploadDir,$uploadUrl) {
        $this->uploadUrl = $uploadUrl;
        $this->uploadDir = $uploadDir;
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        
    }

    public function uploadImage($file) {
        
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Sanitize the file name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            // Allowed file types
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

            if (in_array($fileExtension, $allowedfileExtensions)) {
                $destPath = $this->uploadDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    return $this->uploadUrl .'/'. $newFileName;
                } else {
                    throw new Exception('Error moving the uploaded file');
                }
            } else {
                throw new Exception('Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions));
            }
        } else {
            throw new Exception('No file uploaded or there was an upload error');
        }
    }
}

