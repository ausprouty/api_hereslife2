<?php

namespace App\Controllers\Data;

use App\Services\Security\SanitizeInputService;

class PostInputController
{
    private array $postData = []; // Initialize as an empty array
    private array $sanitizedData = []; // Initialize as an empty array
    private $sanitizeInputService;

    public function __construct(SanitizeInputService $sanitizeInputService)
    {
        $this->sanitizeInputService = $sanitizeInputService;
        writeLog('PostInputController-20', 'started');
        $this->handlePost();
    }

    private function handlePost(): array
    {
        if ($this->isJsonRequest()) {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            // Assign to postData only if data is not null
            $this->postData = isset($data['data']) ? $data['data'] : ($data ?? []);
        } elseif (isset($_POST['formData']) && is_array($_POST['formData'])) {
            $this->postData = [];
            foreach ($_POST['formData'] as $field) {
                if (isset($field['name']) && isset($field['value'])) {
                    $this->postData[$field['name']] = $field['value'];
                }
            }
        } else {
            // If no form data is present
            return $this->handleNoFormData();
        }

        // Sanitize input data
        $this->sanitizedData = $this->sanitizeInputService->sanitize($this->postData);
        return $this->sanitizedData;
    }

    public function getDataSet(): array
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

    private function isJsonRequest(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    private function handleNoFormData(): array
    {
        return ['success' => false, 'message' => "No form data provided."];
    }

    private function handleMissingData(): array
    {
        return ['success' => false, 'message' => "Malformed JSON data."];
    }
}
