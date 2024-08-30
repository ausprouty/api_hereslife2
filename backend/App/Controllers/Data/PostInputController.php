<?php

namespace App\Controllers\Data;

use App\Services\SanitizeInputService;


class PostInputController
{
    private $postInputModel;
    private $authorizationService;
    private array $postData; 
    private array $sanitizedData;
    // Constructor Injection with type hinting
    public function __construct(
        SanitizeInputService $sanitizeInputService)
    {
        $this->sanitizeInputService = $sanitizeInputService;
        writeLog('PostInputController-20', 'started');
        $this->handlePost();
    }

    private function handlePost(): array
    {
        // Detect if the request is a JSON payload or a traditional form submission
        if ($this->isJsonRequest()) {   // Handle JSON input
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);
    
            // most of the time data will be in the 'data' key, 
            // but sometimes will be in the root.  Use the apiKey key to determine    
            if (isset($data['data'])) {
                $this->postData = $data['data'];
            } elseif (isset($data['apiKey'])) {
                $this->postData = $data;
            } else {                        
                // Handle missing data case
                $response = $this->handleMissingData();
                return $response;
            }
        } elseif (isset($_POST['formData']) && is_array($_POST['formData'])) { 
            // Handle traditional form submission
            $this->postData = [];
            foreach ($_POST['formData'] as $field) {
                if (isset($field['name']) && isset($field['value'])) {
                    $this->postData[$field['name']] = $field['value'];
                }
            }
        } else {
                // Handle case where no form data is present
                $response =  $this->handleNoFormData();
                return $response;
            
        }

        // Now you can work with the sanitized data
        $this->sanitizedData = $this->sanitizeInputService->sanitize($this->postData);
        return $this->sanitizedData;
        // Further processing of $sanitizedData...
    }

    public function getDataSet()
    {
        $data = $this->sanitizedData ?? [];
        if (isset($data['apiKey'])) {
            unset($data['apiKey']);
        }
        return $data;
    }

    public function getApiKey()
    {
        return $this->sanitizedData['apiKey'] ?? null;
    }

    private function isJsonRequest()
    {
        // Check if the Content-Type header indicates a JSON request
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    private function handleNoFormData()
    {
        // Handle the situation when formData is not present
        $response =  array('success'=>false,'message'=>"No form data provided.");
        return $response;    }

    private function handleMissingData()
    {
        // Handle the situation when JSON data is missing or malformed
        $response =  array('success'=>false,'message'=>"Malformed JSON data.");
        return $response;
        // You can add more logic here as needed, such as logging or returning an error response.
    }
}
