<?php

/*
TimeService

Purpose: Provides time-related utilities to manage and format time calculations consistently across the application.
Responsibilities:
    Calculate time thresholds (e.g., 30 minutes ago, 7 days ago).
    Handle timezone settings.

*/

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
        
        // Construct the full path and sanitize it
        $filePath = realpath($baseDir . '/' . basename($this->queSentFile));
        $variableFile = $baseDir . '/' . basename($variableName) . '.txt';
        
        // Ensure the file is within the base directory and exists
        if (strpos($filePath, $baseDir) === 0 && file_exists($variableFile)) {
            return (int)file_get_contents($variableFile);
        }
        // If the file is outside the base directory or doesn't exist, return the default value
        return $default;
    }

    public function setTimestamp($variableName, $timestamp = null) {
        $baseDir = realpath(APP_FILEDIR . 'Storage/Timestamps/');
        
        // Construct the full path and sanitize it
        $filePath = realpath($baseDir . '/' . basename($this->queSentFile));
        $variableFile = $baseDir . '/' . basename($variableName) . '.txt';
        
        // Ensure the file path is within the base directory
        if (strpos($filePath, $baseDir) === 0) {
            // If no timestamp is provided, use the current time
            if ($timestamp === null) {
                $timestamp = time();
            }
    
            // Write the timestamp to the file
            file_put_contents($variableFile, $timestamp);
            $this->queSent = $timestamp; // Optionally update the property if needed
        } else {
            // Optionally, handle the case where the file path is invalid
            throw new \RuntimeException('Invalid file path');
        }
    }
}