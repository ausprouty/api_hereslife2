<?php

namespace App\Repositories;

use App\Models\Emails\EmailQueModel;
use App\Services\Database\DatabaseService;
use PDO;

class EmailQueueRepository {
    private $databaseService;

    /**
     * Constructor that injects the DatabaseService.
     *
     * @param DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }

    /**
     * Queue an email by inserting it into the database.
     *
     * @param EmailQueModel $emailQueModel
     * @return int Last inserted ID
     */
    public function queueEmail(EmailQueModel $emailQueModel) {
        $query = "INSERT INTO hl_email_que 
                  (delay_until, email_from, email_to, email_id, champion_id, subject, body, plain_text_only, headers, plain_text_body, template, params) 
                  VALUES 
                  (:delay_until, :email_from, :email_to, :email_id, :champion_id, :subject, :body, :plain_text_only, :headers, :plain_text_body, :template, :params)";
        
        $params = [
            ':delay_until' => $emailQueModel->getDelayUntil(),
            ':email_from' => $emailQueModel->getEmailFrom(),
            ':email_to' => $emailQueModel->getEmailTo(),
            ':email_id' => $emailQueModel->getEmailId(),
            ':champion_id' => $emailQueModel->getChampionId(),
            ':subject' => $emailQueModel->getSubject(),
            ':body' => $emailQueModel->getBody(),
            ':plain_text_only' => $emailQueModel->getPlainTextOnly(),
            ':headers' => $emailQueModel->getHeaders(),
            ':plain_text_body' => $emailQueModel->getPlainTextBody(),
            ':template' => $emailQueModel->getTemplate(),
            ':params' => $emailQueModel->getParams()
        ];

        $this->databaseService->executeUpdate($query, $params);
        return $this->databaseService->getLastInsertId();
    }

    /**
     * Update an existing email record.
     *
     * @param EmailQueModel $emailQueModel
     * @return bool
     */
    /**
 * Update an existing email record.
 *
 * @param int   $id   The ID of the email record to update.
 * @param array $data The data to update.
 * @return bool Returns true if the update was successful.
 */
public function updateEmail($id, $data): bool {
    if (!$id) {
        throw new Exception("ID is required for updating the email record.");
    }

    // Initialize the fields array and params
    $fields = [];
    $params = [':id' => $id];

    // Dynamically build the SET clause based on the provided data
    foreach ($data as $key => $value) {
        $fields[] = "$key = :$key";
        $params[":$key"] = $value;
    }

    // If no data fields were provided, throw an exception
    if (empty($fields)) {
        throw new Exception("No fields to update.");
    }

    // Construct the query
    $query = "UPDATE hl_email_que 
              SET " . implode(', ', $fields) . " 
              WHERE id = :id";

    // Execute the update query
    return $this->databaseService->executeUpdate($query, $params);
}


    /**
     * Delete an email from the queue.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEmail($id) {
        $query = "DELETE FROM hl_email_que WHERE id = :id";
        $params = [':id' => $id];
        return $this->databaseService->executeUpdate($query, $params);
    }

    /**
     * Track that an email was sent.
     *
     * @param int $emailId
     * @param int $championId
     */
    public function trackEmailSent($emailId, $championId) {
        $query = "INSERT INTO hl_email_tracking (email_id, champion_id, sent_at) VALUES (:email_id, :champion_id, NOW())";
        $params = [':email_id' => $emailId, ':champion_id' => $championId];
        $this->databaseService->executeUpdate($query, $params);
    }
}
