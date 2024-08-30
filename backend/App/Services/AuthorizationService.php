<?php

namespace App\Services;

class AuthorizationService
{
    public static function checkApiKey($apiKey): bool
    {
        return $apiKey === WORDPRESS_HL_API_KEY || $apiKey === VITE_APP_HL_API_KEY;
    }
}
