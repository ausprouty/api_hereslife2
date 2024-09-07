<?php

namespace App\Utilities;

/**
 * Class ErrorHandler
 * 
 * Utility class to handle errors and log them as needed. 
 * It provides functionality to log errors to a file and return a structured JSON response for client-side error handling.
 */
class ErrorHandler
{
    /**
     * Handles an error by optionally logging it and returning a JSON response with an error message.
     * 
     * @param string $message The error message to return in the JSON response.
     * @param string|null $logMessage Optional message to be logged to a file.
     * @param string $logFile The file where the log should be written. Default is 'error_log.txt'.
     * 
     * This method:
     *  - Logs the error if a log message is provided.
     *  - Sends a JSON response indicating an error.
     *  - Terminates the script execution.
     */
    public static function handle($message, $logMessage = null, $logFile = 'error_log.txt')
    {
        // Log the error if logMessage is provided
        if ($logMessage) {
            self::logError($logMessage, $logFile);
        }
        
        // Prepare and send the error response as JSON
        $response = [
            'success' => 'FALSE',
            'message' => $message,
        ];
        header('Content-Type: application/json');
        echo json_encode($response);

        // Stop further script execution
        exit();
    }

    /**
     * Logs an error message to the specified file.
     * 
     * @param string $message The error message to log.
     * @param string $file The file where the log should be appended.
     * 
     * The logError method appends the error message to the specified file. Each error message is followed by a newline.
     */
    private static function logError($message, $file)
    {
        // Append the error message to the specified log file
        file_put_contents($file, $message . PHP_EOL, FILE_APPEND);
    }
}
