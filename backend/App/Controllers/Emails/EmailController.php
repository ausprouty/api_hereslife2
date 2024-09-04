<?php

namespace App\Controllers\Emails;

use App\Models\Emails\EmailModel;

/**
 * The EmailController class handles operations related to emails,
 * such as retrieving, updating, and formatting email data for views.
 */
class EmailController {

    /**
     * @var EmailModel Instance of the EmailModel class.
     */
    private $emailModel;

    /**
     * Constructor for the EmailController class.
     *
     * @param EmailModel $emailModel Instance of EmailModel injected via dependency injection.
     */
    public function __construct(EmailModel $emailModel)
    {
        $this->emailModel = $emailModel;
    }

    /**
     * Get an email by series and sequence number.
     *
     * If no email is found, returns a blank template with the series and sequence pre-filled.
     *
     * @param string $series The series identifier.
     * @param int $sequence The sequence number within the series.
     * @return array The email record or a blank template.
     */
    public function getEmailBySeriesAndSequence($series, $sequence) {
        
        $emailRecord = $this->emailModel->findOneInSeries($series, $sequence);

        if ($emailRecord) {
            return $emailRecord;
        } else {
            // Return a blank record with series and sequence set
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

    /**
     * Update or create an email from the input data.
     *
     * If the email ID is null, a new email record is created.
     * Otherwise, the existing email record is updated.
     *
     * @param array $data The input data for the email (e.g., subject, body, etc.).
     * @return mixed The result of the create or update operation from EmailModel.
     */
    public function updateEmailFromInputData($data)
    {
        if ($data['id'] == null) {
            return $this->emailModel->create($data);
        }
        return $this->emailModel->update($data['id'], $data);
    }

    /**
     * Get a list of recent blog titles.
     *
     * @param int $number The number of blog titles to retrieve.
     * @return array The list of recent blog titles.
     */
    public function getRecentBlogTitles($number)
    {
        return $this->emailModel->getRecentBlogTitles($number);
    }

    /**
     * Retrieve an email by its ID.
     *
     * @param int $id The ID of the email to retrieve.
     * @return array The email record.
     */
    public function getEmailById($id)
    {
        return $this->emailModel->findById($id);
    }

    /**
     * Format an email for viewing in a specific template.
     *
     * This method replaces placeholders in the template with the actual email data.
     *
     * @param int $id The ID of the email to format.
     * @return array The email data with the formatted template.
     */
    public function formatForView($id)
    {
        $data = $this->emailModel->findById($id);
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
