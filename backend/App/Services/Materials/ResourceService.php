<?php
namespace App\Services\Materials;

class ResourceService {
    public function getResourceByFilename($fileName) {
        $fileLocation = RESOURCE_DIR . $fileName;
        if (file_exists($fileLocation)) {
            return file_get_contents($fileLocation);
        } else {
            return $fileLocation . ' is not available.';
        }
    } 
}
