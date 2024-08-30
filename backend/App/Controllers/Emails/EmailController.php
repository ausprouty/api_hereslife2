<?php

namespace App\Controllers\Emails;

use App\Models\Emails\EmailModel;

class EmailController {

    private $emailModel;

    public function __construct() {
        $this->emailModel = new EmailModel();
    }

    public function getEmailBySeriesAndSequence($series, $sequence) {
        
        $emailRecord = $this->emailModel->findOneInSeries($series, $sequence);

        if ($emailRecord) {
            return $emailRecord;
        } else {
            // Return a blank record with series set
            return [
                'id' => null,
                'subject' => 'What is the subject?',
                'body' => 'Edit this email',
                'plain_text_only' => false,
                'headers' => '',
                'template' => '',
                'series' => $series,
                'sequence' => $sequence,
                'params' => ''
            ];
        }
    }
    public function updateEmailFromInputData($data){
        if ($data['id'] == null) {
            return $this->emailModel->create($data);
        }
        return $this->emailModel->update($data['id'], $data);

    }
    public function getRecentBlogTitles($number){
        return $this->emailModel->getRecentBlogTitles($number);
    }
    public function getEmailById($id) {
        return $this->emailModel->findById($id);
    }
    public function formatForView($id) {
        $data =  $this->emailModel->findById($id);
        $template = file_get_contents(EMAIL_DEFAULT_TEMPLATE);
        $template = str_replace('{{subject}}', $data['subject'], $template);
        $template = str_replace('{{body}}', $data['body'], $template);
        $template = str_replace('{{header}}', EMAIL_HEADERIMAGE, $template);    
        $template = str_replace('{{signature}}', EMAIL_SIGNATURE, $template);
        $template = str_replace('{{author-bio}}', EMAIL_AUTHORBIO, $template);
        $template = str_replace('{{postscript}}', $data['postscript'], $template);
        $data['body'] = $template;  
        return $data;
    } 
}
