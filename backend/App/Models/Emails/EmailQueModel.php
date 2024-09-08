<?php

namespace App\Models\Emails;

use App\Services\DatabaseService;
use Exception;
use \PDO;

/**
 * EmailQueModel
 *
 * This class represents an email queue item in the system, allowing emails to be scheduled and processed.
 * It provides methods for creating, reading, updating, and deleting email queue entries in the database.
 */
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
    
    private $databaseService;

    /**
     * Constructor that initializes the model and injects DatabaseService.
     *
     * @param DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
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

    
    /**
     * Create a new email queue entry in the database.
     *
     * @return int|null The ID of the newly inserted record.
     * @throws Exception if the query execution fails.
     */
    public function create() {
        $query = "INSERT INTO hl_email_que (delay_until, email_from, email_to, email_id, champion_id, subject, body, plain_text_only, headers, plain_text_body, template, params)
                  VALUES (:delay_until, :email_from, :email_to, :email_id, :champion_id, :subject, :body, :plain_text_only, :headers, :plain_text_body, :template, :params)";
        
        $params = [
            ':delay_until' => $this->delay_until,
            ':email_from' => $this->email_from,
            ':email_to' => $this->email_to,
            ':email_id' => $this->email_id,
            ':champion_id' => $this->champion_id,
            ':subject' => $this->subject,
            ':body' => $this->body,
            ':plain_text_only' => $this->plain_text_only,
            ':headers' => $this->headers,
            ':plain_text_body' => $this->plain_text_body,
            ':template' => $this->template,
            ':params' => $this->params
        ];

        $this->databaseService->executeUpdate($query, $params);
        return $this->databaseService->getLastInsertId();
    }

    /**
     * Read an email queue entry from the database based on the given ID.
     *
     * @param int $id The ID of the email queue entry to read.
     * @return array The email queue record data.
     * @throws Exception if the query execution fails.
     */
    public function read(int $id): array {
        $query = "SELECT * FROM hl_email_que WHERE id = :id";
        $params = [':id' => $id];
        
        return $this->databaseService->executeQuery($query, $params)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update an existing email queue entry in the database.
     *
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception if the query execution fails.
     */
    public function update(): bool {
        if (!$this->id) {
            throw new Exception("ID is required for updating the record.");
        }

        $query = "UPDATE hl_email_que 
                  SET delay_until = :delay_until, email_from = :email_from, email_to = :email_to, email_id = :email_id, champion_id = :champion_id,
                      subject = :subject, body = :body, plain_text_only = :plain_text_only, headers = :headers, plain_text_body = :plain_text_body,
                      template = :template, params = :params
                  WHERE id = :id";
        
        $params = [
            ':delay_until' => $this->delay_until,
            ':email_from' => $this->email_from,
            ':email_to' => $this->email_to,
            ':email_id' => $this->email_id,
            ':champion_id' => $this->champion_id,
            ':subject' => $this->subject,
            ':body' => $this->body,
            ':plain_text_only' => $this->plain_text_only,
            ':headers' => $this->headers,
            ':plain_text_body' => $this->plain_text_body,
            ':template' => $this->template,
            ':params' => $this->params,
            ':id' => $this->id
        ];

        return $this->databaseService->executeUpdate($query, $params);
    }

    /**
     * Delete an email queue entry from the database.
     *
     * @param int $id The ID of the email queue entry to delete.
     * @return bool True if the deletion was successful, false otherwise.
     * @throws Exception if the query execution fails.
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM hl_email_que WHERE id = :id";
        $params = [':id' => $id];

        return $this->databaseService->executeUpdate($query, $params);
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
