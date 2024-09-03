<?php
namespace App\Utilities;

use App\Services\AuthorizationService;
use App\Utilities\ErrorHandler;

class RequestValidator
{
    public static function validateAdmin($postData, $context)
    {
        // Check if post data is provided
        if (!$postData) {
            ErrorHandler::handle('No data provided', "No post data provided in $context");
        }

        // Check if the user is authorized
        $authorizationService = new AuthorizationService();
        if (!$authorizationService->checkAuthorizationUserHeader()) {
            ErrorHandler::handle('Not Authorized', "Not authorized in $context");
        }
    }

    public static function validateUser($postData, $apiKey, $context)
    {
        // Check if post data is provided
        if (!$postData) {
            ErrorHandler::handle('No data provided', "No post data provided in $context");
        }
        // Check if the apiKey is authorized
        if (!$authorizationService::checkApiKey($apiKey)) {
            ErrorHandler::handle('Not Authorized', "Not authorized in $context");
        }
    }
}
