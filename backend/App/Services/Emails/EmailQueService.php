<?php
namespace App\Services\Emails;
/*
Purpose: Acts as the main service for managing the email queue. This class will coordinate the process of gathering champions, constructing emails, and queuing them for sending.
Responsibilities:

    Initialize and manage the process of generating and queuing emails.
    Delegate specific tasks to other classes.
*/

class EmailQueueService {
    
    private $dayInSeconds;
    private $queSent;
    private $queSentFile = APP_FILEDIR .'Storage/Timestamps/QueSent.txt';
    private $newTipsSent;
    private $newTipsSentFile = APP_FILEDIR .'Storage/Timestamps/NewTipsSent.txt';

    public function __construct() {
        $this->dayInSeconds = 24 * 60 * 60;
        $this->queSent = $this->getQueSentTimestamp();
        $this->newTipsSent = $this->getNewTipsSentTimestamp();
    }

    // Getter for queSent
    public function getQueSentTimestamp() {
        if (file_exists($this->queSentFile)) {
            return (int)file_get_contents($this->queSentFile);
        }
        return 0;
    }

    // Setter for queSent
    public function setQueSentTimestamp($timestamp) {
        file_put_contents($this->queSentFile, $timestamp);
        $this->queSent = $timestamp; // Update the property as well
    }

    // Getter for newTipsSent
    private function getNewTipsSentTimestamp() {
        if (file_exists($this->newTipsSentFile)) {
            return (int)file_get_contents($this->newTipsSentFile);
        }
        return 0;
    }

    // Setter for newTipsSent
    private function setNewTipsSentTimestamp($timestamp) {
        file_put_contents($this->newTipsSentFile, $timestamp);
        $this->newTipsSent = $timestamp; // Update the property as well
    }
    
    /**
     * Processes the email queue to send newsletters and material tips.
     *
     * This method checks if enough time has passed since the last time the queue was processed.
     * If at least 14 minutes have passed, it proceeds to check if it's time to send new email
     * series members' queue or material tips. The method ensures that certain actions, like 
     * sending email series members' queue, are only performed once per day.
     *
     * - It first checks if 14 minutes have passed since the last queue processing (`$queSent`).
     * - If it's time, it further checks if a full day has passed since the last `newTipsSent` timestamp.
     * - If a day has passed, it sends the email series members' queue and updates the timestamp.
     * - If not, it proceeds to send initial material tips and emails.
     * - If it's not time to process the queue, it logs that it's not time to send emails.
     *
     * @return void
     */
    public function processQueue() {
        date_default_timezone_set('UTC');
        
        // Check if 14 minutes have passed since the last queue was processed
        if ((time() - $this->queSent) > (60 * 14)) {
            $yesterday = time() - $this->dayInSeconds;

            // Check if a full day has passed since the last new tips were sent
            if ((time() - $this->newTipsSent) > $this->dayInSeconds) {
                $this->emailSeriesMembersQueue();
                $this->setNewTipsSentTimestamp(time()); // Update the new tips sent timestamp
                return; // Exit early as sending email series members' queue takes a long time
            }

            // Send material tips and other emails
            $this->emailRequestInitialMaterialTips();
            $this->sendEmails();
        } else {
            // Log that it's not time to send emails yet
            $this->logNotTimeToSend();
        }
    }


    private function emailSeriesMembersQueue() {
        // Your logic here
    }

    private function emailRequestInitialMaterialTips() {
        // Your logic here
    }

    private function sendEmails() {
        // Your logic here
    }

    private function logNotTimeToSend() {
        // Log this information
        error_log('Not time to send');
    }

    private function getVariable($name, $default) {
        // Replace with actual variable retrieval logic
        return $default;
    }

    private function setVariable($name, $value) {
        // Replace with actual variable storage logic
    }
}
