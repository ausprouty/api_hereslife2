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

    public function setValues(array $params) {
        $this->id = $params['id'] ?? null;
        $this->champion_id = $params['champion_id'] ?? null;
        $this->file_name = $params['file_name'] ?? null;
        $this->download_date = $params['download_date'] ?? time();
        $this->requested_tips = $params['requested_tips'] ?? null;
        $this->sent_tips = $params['sent_tips'] ?? null;
        $this->file_id = $params['file_id'] ?? 0;
        $this->elapsed_months = $params['elapsed_months'] ?? 0;
        $this->tip = $params['tip'] ?? null;
        $this->tip_detail = $params['tip_detail'] ?? null;
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

    public function insert() {
        $query = "INSERT INTO hl_downloads 
                  (champion_id, file_name, download_date, requested_tips, sent_tips, file_id, elapsed_months, tip, tip_detail)
                  VALUES 
                  (:champion_id, :file_name, :download_date, :requested_tips, :sent_tips, :file_id, :elapsed_months, :tip, :tip_detail)";
        
        $params = array(
            ':champion_id' => $this->champion_id,
            ':file_name' => $this->file_name,
            ':download_date' => $this->download_date,
            ':requested_tips' => $this->requested_tips,
            ':sent_tips' => $this->sent_tips,
            ':file_id' => $this->file_id,
            ':elapsed_months' => $this->elapsed_months,
            ':tip' => $this->tip,
            ':tip_detail' => $this->tip_detail
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

