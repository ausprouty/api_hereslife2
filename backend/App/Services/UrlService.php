<?php
namespace App\Services;

/**
 * Class UrlService
 * 
 * Service class to handle URL requests using cURL.
 */
class UrlService {

    /**
     * Retrieves the content from a specified URL using cURL.
     *
     * @param string $url The URL to fetch.
     * @return string|false The response content from the URL or false on failure.
     * 
     * This method initializes a cURL session, sets the necessary options, and retrieves
     * the content from the provided URL. It handles any errors that occur during the cURL request.
     * The response content is returned if the request is successful; otherwise, it prints the error.
     */
    static function getUrl($url) {
        // Initialize cURL session
        $ch = curl_init($url);
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set timeout in seconds (30 seconds)
        
        // Execute cURL request
        $response = curl_exec($ch);
        
        // Check for cURL errors
        if (curl_errno($ch)) {
            // Output the cURL error message
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Return the response if no error
            return $response;
        }
        
        // Close the cURL session
        curl_close($ch);
    }
}    
