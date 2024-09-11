<?php

namespace App\Services\Security;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Exception;

/**
 * AuthorizationService handles JWT-based authorization and API key validation.
 * 
 * This service is responsible for:
 * - Generating and verifying JWT tokens.
 * - Checking if a user has a valid JWT and ensuring they have the appropriate access level.
 * - Validating API keys sent via request headers.
 */
class AuthorizationService
{
    private $secretKey;

    /**
     * AuthorizationService constructor.
     * Initializes the service with the secret key used for JWT encoding and decoding.
     */
    public function __construct()
    {
        $this->secretKey = JWT_SECRET_KEY;  // Fetches the secret key from configuration
    }

    /**
     * Generates a JWT token with user data.
     * 
     * @param int $userId      The user ID for the JWT payload.
     * @param string $authLevel The authorization level (default: 'user').
     * @return string           Encoded JWT.
     */
    public function generateJWT(int $userId, string $authLevel = 'user'): string
    {
        $payload = [
            'iss' => BACKEND_URL,               // Issuer URL
            'iat' => time(),                    // Issued at timestamp
            'exp' => time() + (24 * 60 * 60),   // Expires in 24 hours
            'data' => [
                'user_id' => $userId,           // User ID in payload
                'auth_level' => $authLevel      // Authorization level
            ]
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256'); // Encode with HS256 algorithm
    }

    /**
     * Verifies a JWT token and decodes its data.
     * 
     * @param string $jwt The JWT to verify.
     * @return array|null The decoded payload data or null if invalid.
     */
    public function verifyJWT(string $jwt): ?array
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            return (array) $decoded->data;  // Return data as an associative array
        } catch (Exception $e) {
            // Handle JWT verification errors (log, return null, etc.)
            return null;
        }
    }

    /**
     * Checks if the 'User-Authorization' header contains a valid JWT with admin rights.
     * 
     * @return bool True if the JWT is valid and the user has admin access, false otherwise.
     */
    public function checkAuthorizationUserHeader(): bool
    {
        $headers = getallheaders();
        if (!isset($headers['User-Authorization'])) {
            return false;
        }

        $authHeader = $headers['User-Authorization'];
        $jwt = str_replace('Bearer ', '', $authHeader);
        $decoded = $this->verifyJWT($jwt);

        if (!$decoded) {
            return false;
        }

        return $decoded['auth_level'] === 'admin';
    }

    /**
     * Validates the 'Authorization' header for an API key.
     * 
     * @return bool True if the API key is valid, false otherwise.
     */
    public static function checkAuthorizationHeader(): bool
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return false;
        }

        $authHeader = $headers['Authorization'];
        $apiKey = str_replace('Bearer ', '', $authHeader);
        return self::checkApiKey($apiKey);
    }

    /**
     * Validates the provided API key.
     * 
     * @param string $apiKey The API key to validate.
     * @return bool True if the API key matches known keys, false otherwise.
     */
    public static function checkApiKey(string $apiKey): bool
    {
        return $apiKey === WORDPRESS_HL_API_KEY || $apiKey === VITE_APP_HL_API_KEY;
    }
}
