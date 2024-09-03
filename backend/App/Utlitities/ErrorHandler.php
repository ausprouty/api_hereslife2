<?php

namespace App\Utilities;

class ErrorHandler
{
    public static function handle($message, $logMessage = null, $logFile = 'error_log.txt')
    {
        if ($logMessage) {
            self::logError($logMessage, $logFile);
        }
        
        $response = [
            'success' => 'FALSE',
            'message' => $message,
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(); // Stop further execution
    }

    private static function logError($message, $file)
    {
        // Implement your logging mechanism here
        file_put_contents($file, $message . PHP_EOL, FILE_APPEND);
    }
}
