<?php

namespace App\Models\Emails;
use App\Services\DatabaseService;
use PDO;

class EmailQueModel {
    private $databaseService;

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

    public function __construct(string $database, array $params = []) {
        writeLog('EmailQueModel-15', 'database: ' . $database);
        $this->databaseService = new DatabaseService($database);
    }
    public function create($params){
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

        $params = array_merge($defaults, $params);

        foreach ($params as $key => $value) {
            if (!is_null($value)) {
                $this->$key = $value;
            }
        }
    }
    public function save() {
        $query = "INSERT INTO hl_email_que 
                  (delay_until, email_from, email_to, email_id, champion_id, subject, body, plain_text_only, headers, plain_text_body, template, params) 
                  VALUES 
                  (:delay_until, :email_from, :email_to, :email_id, :champion_id, :subject, :body, :plain_text_only, :headers, :plain_text_body, :template, :params)";
        
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
    
        return $this->databaseService->executeUpdate($query, $params);
    }
    public function update() {
        $query = "UPDATE hhl_email_que 
                  SET delay_until = :delay_until, 
                      email_from = :email_from, 
                      email_to = :email_to, 
                      email_id = :email_id, 
                      champion_id = :champion_id, 
                      subject = :subject, 
                      body = :body, 
                      plain_text_only = :plain_text_only, 
                      headers = :headers, 
                      plain_text_body = :plain_text_body, 
                      template = :template, 
                      params = :params
                  WHERE id = :id";
        
        $params = [
            ':id' => $this->id,
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
    
        return $this->databaseService->executeUpdate($query, $params);
    }
    public function delete() {
        $query = "DELETE FROM hl_email_que WHERE id = :id";
        $params = [':id' => $this->id];
        return $this->databaseService->executeUpdate($query, $params);
    }   
    public function queEmails($champions, $letterId){
        $count = 0;
        foreach ($champions as $champion) {
            $count++;
            $params = array(
                'champion_id' => $champion['cid'],
                'email_id' => $letterId);
            $this->create($params);
            $this->save();
        }
        return $count .' emails qued';
    }
    // Getters
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

?>