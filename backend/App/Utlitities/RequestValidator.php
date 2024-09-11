<?php

namespace App\Utilities;

use App\Services\Security\AuthorizationService;
use App\Utilities\ErrorHandler;

/**
 * Class RequestValidator
 * 
 * This class is responsible for validating incoming requests for admin and user actions.
 * It checks the existence of required data (POST data) and authorization headers, ensuring
 * that only authorized requests are processed further.
 */
class RequestValidator
{
    /**
     * Validates that the request is authorized and that required data is provided for admin users.
     * 
     * @param array $postData The POST data to validate.
     * @param string $context A description of the context or location where the validation is happening (e.g., a specific controller or method).
     * 
     * This method checks for:
     * - The presence of POST data.
     * - A valid authorization header (checking for admin-level access).
     * 
     * If either validation fails, the method calls `ErrorHandler::handle()` to send an error response and halt execution.
     */
    public static function validateAdmin($postData, $context)
    {
        // Check if post data is provided
        if (!$postData) {
            ErrorHandler::handle('No data provided', "No post data provided in $context");
        }

        // Check if the user is authorized as an admin
        $authorizationService = new AuthorizationService();
        if (!$authorizationService->checkAuthorizationUserHeader()) {
            ErrorHandler::handle('Not Authorized', "Not authorized in $context");
        }
    }

    /**
     * Validates that the request is authorized based on the API key and that required data is provided for users.
     * 
     * @param array $postData The POST data to validate.
     * @param string $apiKey The API key to validate.
     * @param string $context A description of the context or location where the validation is happening (e.g., a specific controller or method).
     * 
     * This method checks for:
     * - The presence of POST data.
     * - A valid API key (checking against predefined API keys in the system).
     * 
     * If either validation fails, the method calls `ErrorHandler::handle()` to send an error response and halt execution.
     */
    public static function validateUser($postData, $apiKey, $context)
    {
        // Check if post data is provided
        if (!$postData) {
            ErrorHandler::handle('No data provided', "No post data provided in $context");
        }

        // Check if the provided API key is valid
        if (!AuthorizationService::checkApiKey($apiKey)) {
            ErrorHandler::handle('Not Authorized', "Not authorized in $context");
        }
    }
}
