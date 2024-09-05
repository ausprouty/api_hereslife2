<?php
namespace App\Models\Emails;

use App\Services\DatabaseService;
use PDO;

class EmailListMemberModel {

    private $databaseService;

    // Inject DatabaseService via the constructor
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    // Create a new record
    public function create($data) {
        $query = "INSERT INTO hl_email_list_members 
                    (list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed)
                  VALUES 
                    (:list_id, :list_name, :champion_id, :subscribed, :last_tip_sent, :last_tip_sent_time, :finished_all_tips, :unsubscribed)";
        $params = [
            ':list_id' => $data['list_id'],
            ':list_name' => $data['list_name'],
            ':champion_id' => $data['champion_id'],
            ':subscribed' => $data['subscribed'],
            ':last_tip_sent' => $data['last_tip_sent'],
            ':last_tip_sent_time' => $data['last_tip_sent_time'],
            ':finished_all_tips' => $data['finished_all_tips'],
            ':unsubscribed' => $data['unsubscribed']
        ];
        return $this->databaseService->executeUpdate($query, $params);
    }

    // Update an existing record
    public function update($id, $data) {
        $query = "UPDATE hl_email_list_members 
                  SET list_id = :list_id,
                      list_name = :list_name,
                      champion_id = :champion_id,
                      subscribed = :subscribed,
                      last_tip_sent = :last_tip_sent,
                      last_tip_sent_time = :last_tip_sent_time,
                      finished_all_tips = :finished_all_tips,
                      unsubscribed = :unsubscribed
                  WHERE id = :id";
        $params = [
            ':id' => $id,
            ':list_id' => $data['list_id'],
            ':list_name' => $data['list_name'],
            ':champion_id' => $data['champion_id'],
            ':subscribed' => $data['subscribed'],
            ':last_tip_sent' => $data['last_tip_sent'],
            ':last_tip_sent_time' => $data['last_tip_sent_time'],
            ':finished_all_tips' => $data['finished_all_tips'],
            ':unsubscribed' => $data['unsubscribed']
        ];
        return $this->databaseService->executeUpdate($query, $params);
    }

    // Delete a record
    public function delete($id) {
        $query = "DELETE FROM hl_email_list_members WHERE id = :id";
        $params = [':id' => $id];
        return $this->databaseService->executeUpdate($query, $params);
    }

    // Find a record by its ID
    public function findById($id) {
        $query = "SELECT id, list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed
                  FROM hl_email_list_members 
                  WHERE id = :id
                  LIMIT 1";
        $params = [':id' => $id];
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetch(PDO::FETCH_ASSOC);
    }

    // Find all records
    public function findAll() {
        $query = "SELECT id, list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed
                  FROM hl_email_list_members";
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find all members of a specific list
    public function findByListId($list_id) {
        $query = "SELECT id, list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed
                  FROM hl_email_list_members 
                  WHERE list_id = :list_id";
        $params = [':list_id' => $list_id];
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find all active (subscribed) members
    public function findSubscribedMembers() {
        $query = "SELECT id, list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed
                  FROM hl_email_list_members 
                  WHERE unsubscribed IS NULL";
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findNewRequestsForTips() {
        $query = "SELECT id, list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed
                  FROM hl_email_list_members
                  WHERE unsubscribed IS NULL
                  AND subscribed <= NOW() - INTERVAL 30 MINUTE
                  AND last_tip_sent IS NULL";
        
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
