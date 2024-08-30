<?php
namespace App\Services;

class UrlService{
    static function getUrl($url){
        // Initialize cURL session
        $ch = curl_init($url);
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set timeout in seconds
        
        // Execute cURL request
        $response = curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Output or process the contents
            return $response;
        }
        
        // Close cURL session
        curl_close($ch);
    }
}    
