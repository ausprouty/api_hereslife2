<?php

namespace App\Models\Emails;

use App\Services\DatabaseService;

class EmailQueModel {
    private $id;
    private $delay_until;
    private $email_from;
    private $email_to;
    private $email_id;
    private $champion_id;
    private $subject;
    private $body;
    private $plain_text_only;
    private $headers;
    private $plain_text_body;
    private $template;
    private $params;

    /**
     * Constructor that initializes the model.
     */
    public function __construct() {
        // We don't pass any data here since the repository handles it
    }

    /**
     * Set values for the model's properties.
     *
     * @param array $params Associative array of values to set.
     */
    public function setValues(array $params) {
        $defaults = [
            'id' => null,
            'delay_until' => 0,
            'email_from' => '',
            'email_to' => '',
            'email_id' => null,
            'champion_id' => null,
            'subject' => '',
            'body' => '',
            'plain_text_only' => 0,
            'headers' => '',
            'plain_text_body' => '',
            'template' => null,
            'params' => null,
        ];

        // Merge provided params with defaults
        $params = array_merge($defaults, $params);

        // Assign values to object properties
        foreach ($params as $key => $value) {
            if (!is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // Getters for the properties
    public function getId() { return $this->id; }
    public function getDelayUntil() { return $this->delay_until; }
    public function getEmailFrom() { return $this->email_from; }
    public function getEmailTo() { return $this->email_to; }
    public function getEmailId() { return $this->email_id; }
    public function getChampionId() { return $this->champion_id; }
    public function getSubject() { return $this->subject; }
    public function getBody() { return $this->body; }
    public function getPlainTextOnly() { return $this->plain_text_only; }
    public function getHeaders() { return $this->headers; }
    public function getPlainTextBody() { return $this->plain_text_body; }
    public function getTemplate() { return $this->template; }
    public function getParams() { return $this->params; }
}
