<?php

/*
TimeService

Purpose: Provides time-related utilities to manage and format time calculations consistently across the application.
Responsibilities:
    Calculate time thresholds (e.g., 30 minutes ago, 7 days ago).
    Handle timezone settings.
*/

namespace App\Services; // Add the namespace to ensure proper autoloading

use RuntimeException;

class TimeService {

    public function __construct() {
        date_default_timezone_set('UTC');
    }

    public function getTimeThreshold($minutesAgo) {
        return strtotime("- $minutesAgo minutes");
    }

    public function getDaysAgo($days) {
        return strtotime("- $days days");
    }

    public function getTimestamp($variableName, $default = 0) {
        $baseDir = realpath(APP_FILEDIR . 'Storage/Timestamps/');
        
        // Sanitize the filename and construct the full path
        $variableFile = $baseDir . '/' . basename($variableName) . '.txt';

        // Ensure the file exists
        if (file_exists($variableFile)) {
            return (int)file_get_contents($variableFile);
        }

        // If the file doesn't exist, return the default value
        return $default;
    }

    public function setTimestamp($variableName, $timestamp = null) {
        $baseDir = realpath(APP_FILEDIR . 'Storage/Timestamps/');
        
        // Sanitize the filename
        $sanitizedFileName = basename($variableName) . '.txt';
        $variableFile = $baseDir . '/' . $sanitizedFileName;
    
        // Verify the filename doesn't match common system files or invalid patterns
        if (preg_match('/\b(passwd|shadow|hosts)\b/i', $sanitizedFileName)) {
            throw new RuntimeException('Invalid file path or name');
        }
    
        // Verify that the final path is within the base directory
        if (strpos(realpath($variableFile), $baseDir) !== 0) {
            throw new RuntimeException('Invalid file path');
        }
    
        // If no timestamp is provided, use the current time
        if ($timestamp === null) {
            $timestamp = time();
        }
    
        // Write the timestamp to the file
        if (file_put_contents($variableFile, $timestamp) === false) {
            throw new RuntimeException('Failed to write timestamp');
        }
    }
    
}
