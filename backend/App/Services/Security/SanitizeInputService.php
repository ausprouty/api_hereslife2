<?php

namespace App\Services\Security;
use \HTMLPurifier;

Class SanitizeInputService
{
    private $data;

    public function __construct()
    {
    }
    public function sanitize(array $data) : array
    {
        $sanitized = array();
        $sanitizedMailLists = array(); // Initialize the array here
        
        foreach ($data as $name => $value) {
            if (preg_match('/^mail_lists\[[^\]]+\]$/', $name)) {
                // If value is null, sanitize it differently or handle it specifically
                $sanitizedMailLists[] = is_null($value) ? null : filter_var($value, FILTER_SANITIZE_STRING);
            } else {
                switch ($name) {
                    case 'email':
                        // Allow null values or handle them specifically for emails
                        $sanitized[$name] = is_null($value) ? null : filter_var($value, FILTER_SANITIZE_EMAIL);
                        break;
                    case 'body':
                        // body is the content of emails and is not to be sanitized
                        $sanitized[$name] = is_null($value) ? null : $this->filterHtml($value);
                        break;
                    default:
                        // Handle generic fields, allowing null to be assigned
                        $sanitized[$name] = is_null($value) ? null : filter_var($value, FILTER_SANITIZE_STRING);
                        break;
                }
            }
        }
        
        // Check if there are any sanitized mail lists before using implode
        if (!empty($sanitizedMailLists)) {
            $sanitized['selected_mail_lists'] = implode(',', $sanitizedMailLists);
        } else {
            $sanitized['selected_mail_lists'] = null; // Or handle it differently if needed
        }
    return $sanitized; 
        //writeLogDebug("PostInputModel-49", $sanitized);
        
    }
    public function filterHtml($text){
        require_once APP_FILEDIR . '\Libraries\HtmlPurifierStandalone\HTMLPurifier.standalone.php';
       // Create an instance of the HTMLPurifier
        $purifier = new HTMLPurifier();
        // Sanitize the HTML input
        $clean_html = $purifier->purify($text);
        return $clean_html;
    }
}
