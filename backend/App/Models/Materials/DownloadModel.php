<?php

namespace App\Models\Materials;

use App\Services\DatabaseService;

class DownloadModel {

    private $databaseService;

    public $id;
    public $champion_id;
    public $file_name;
    public $download_date;
    public $requested_tips;
    public $sent_tips;
    public $file_id;
    public $elapsed_months;
    public $tip;
    public $tip_detail;

    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }

    // Set default values and assign input values to the object properties
    public function setValues($params) {
        // Define default values
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

    // Insert the object's values into the database
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

        // Execute the query with the parameters
        return $this->databaseService->executeUpdate($query, $params);
    }

    // Combines setting values and saving the object to the database
    public function create($params) {
        // Set the values from the provided params
        $this->setValues($params);
        
        // Save the object to the database
        return $this->save();
    }

    public function update() {
        $query = "UPDATE hl_downloads 
                  SET champion_id = :champion_id,
                      file_name = :file_name,
                      download_date = :download_date,
                      requested_tips = :requested_tips,
                      sent_tips = :sent_tips,
                      file_id = :file_id,
                      elapsed_months = :elapsed_months,
                      tip = :tip,
                      tip_detail = :tip_detail
                  WHERE id = :id";
        
        $params = array(
            ':champion_id' => $this->champion_id,
            ':file_name' => $this->file_name,
            ':download_date' => $this->download_date,
            ':requested_tips' => $this->requested_tips,
            ':sent_tips' => $this->sent_tips,
            ':file_id' => $this->file_id,
            ':elapsed_months' => $this->elapsed_months,
            ':tip' => $this->tip,
            ':tip_detail' => $this->tip_detail,
            ':id' => $this->id
        );
        // Execute the query and return the result
        return $this->databaseService->executeQuery($query, $params);
    }

    

    // Getters
    public function getId() { return $this->id; }
    public function getChampionId() { return $this->champion_id; }
    public function getFileName() { return $this->file_name; }
    public function getDownloadDate() { return $this->download_date; }
    public function getRequestedTips() { return $this->requested_tips; }
    public function getSentTips() { return $this->sent_tips; }
    public function getFileId() { return $this->file_id; }
    public function getElapsedMonths() { return $this->elapsed_months; }
    public function getTip() { return $this->tip; }
    public function getTipDetail() { return $this->tip_detail; }
}

