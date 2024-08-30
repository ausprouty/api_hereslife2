<?php

namespace App\Controllers\Data;

use App\Services\SanitizationService;


class PostInputModel
{
    private $postInputModel;
    private $authorizationService;
    private array $postData; 
    private array $sanitizedData;
    // Constructor Injection with type hinting
    public function __construct(
        SanitizeInputService $sanitizeInputService,)
    {
        $this->sanitizeInputService = $sanitizeInputService;
        $this->handlePost();
    }

    private function handlePost()
    {
        // Detect if the request is a JSON payload or a traditional form submission
        if ($this->isJsonRequest()) {
            // Handle JSON input
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);
            if (isset($data['data'])) {
                $this->postData = $data['data'];
            } else {
                // Handle missing data case
                $this->handleMissingData();
                return;
            }
        } elseif (isset($_POST['formData'])) {
            // Handle traditional form submission
            $this->postData = $_POST['formData'];
        } else {
            // Handle case where no form data is present
            $response =  $this->handleNoFormData();
            return $response;
        }

        // Now you can work with the sanitized data
        $this->sanitizedData = $this->sanitizeInputService->getSanitizedFormData($this->postData);
        return $this->sanitizedData;
        // Further processing of $sanitizedData...
    }

    public function getDataSet()
    {
        return $this->sanitizedData;
    }

    private function isJsonRequest()
    {
        // Check if the Content-Type header indicates a JSON request
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    private function handleNoFormData()
    {
        // Handle the situation when formData is not present
        $response =  "No form data provided.";
        return $response;
        // You can add more logic here as needed, such as logging or returning an error response.
    }

    private function handleMissingData()
    {
        // Handle the situation when JSON data is missing or malformed
        $response = "Malformed JSON data.";
        return $response;
        // You can add more logic here as needed, such as logging or returning an error response.
    }
}
