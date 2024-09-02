<?php

namespace App\Services;

use \Firebase\JWT\JWT;

class AuthorizationService
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = JWT_SECRET_KEY;
    }

    public function generateJWT($userId, $authLevel = 'user')
    {
        $payload = [
            'iss' => BACKEND_URL,                 // Issuer
            'iat' => time(),                      // Issued at
            'exp' => time() + (24 * 60 * 60),     // Expiration time (1 day)
            'data' => [
                'user_id' => $userId,             // User ID or any other identifier
                'auth_level' => $authLevel        // Authorization level
            ]
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function verifyJWT($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($this->secretKey, 'HS256'));
            return (array) $decoded->data;  // Return the decoded data as an associative array
        } catch (Exception $e) {
            // Handle errors, e.g., log the error, return null, etc.
            return null;
        }
    }

    public static function checkApiKey($apiKey): bool
    {
        return $apiKey === WORDPRESS_HL_API_KEY || $apiKey === VITE_APP_HL_API_KEY;
    }
}
