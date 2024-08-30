<?php

Namespace App\Controllers\Emails;


use App\Services\ImageUploadService;

class ImageUploadController {
    private $imageUploadService;

    public function __construct() {
        // Assume these values could be determined dynamically
        $uploadDir = ROOT_IMAGES_EMAILS; // This could be passed from a configuration or environment variable
        $uploadUrl = URL_IMAGES_EMAILS;
        $this->imageUploadService = new ImageUploadService($uploadDir, $uploadUrl);
    }

    public function upload($file) {
        try {
    
            $imageUrl = $this->imageUploadService->uploadImage($file);
            return  $imageUrl;
        } catch (Exception $e) {
            http_response_code(400);
            return (['error' => $e->getMessage()]);
        }
    }
}
