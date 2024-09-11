<?php
namespace App\Models\Emails;

use App\Services\Database\DatabaseService;
use App\Services\Debugging;
use PDO;

class EmailListMemberModel {

    private $databaseService;

    // Inject DatabaseService via the constructor
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    // This method sets the object's values and applies default values
    public function setValues($data) {
        // Define default values
        $defaults = [
            'list_id' => null,
            'list_name' => null,
            'champion_id' => null,
            'subscribed' => 0, // Default: not subscribed
            'last_tip_sent' => 0,
            'last_tip_sent_time' => null,
            'finished_all_tips' => 0,
            'unsubscribed' => 0
        ];

        // Merge provided data with defaults
        $data = array_merge($defaults, $data);

        // Set the object properties
        $this->list_id = $data['list_id'];
        $this->list_name = $data['list_name'];
        $this->champion_id = $data['champion_id'];
        $this->subscribed = $data['subscribed'];
        $this->last_tip_sent = $data['last_tip_sent'];
        $this->last_tip_sent_time = $data['last_tip_sent_time'];
        $this->finished_all_tips = $data['finished_all_tips'];
        $this->unsubscribed = $data['unsubscribed'];
    }

    // This method inserts the object's values into the database
    public function insert() {
        $query = "INSERT INTO hl_email_list_members 
                    (list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed)
                  VALUES 
                    (:list_id, :list_name, :champion_id, :subscribed, :last_tip_sent, :last_tip_sent_time, :finished_all_tips, :unsubscribed)";

        $params = [
            ':list_id' => $this->list_id,
            ':list_name' => $this->list_name,
            ':champion_id' => $this->champion_id,
            ':subscribed' => $this->subscribed,
            ':last_tip_sent' => $this->last_tip_sent,
            ':last_tip_sent_time' => $this->last_tip_sent_time,
            ':finished_all_tips' => $this->finished_all_tips,
            ':unsubscribed' => $this->unsubscribed
        ];

        // Execute the query
        return $this->databaseService->executeUpdate($query, $params);
    }

    // This method combines setValues and insert for creating a new record
    public function create($data) {
        // Set the object's values
        $this->setValues($data);

        // Insert the data into the database
        return $this->insert();
    }

    public function update($id, $data) {
        writeLogAppend('EmailListMemeberModel-78', $data);
        $fields = [];
        $params = [':id' => $id];
        // Dynamically build the query
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $query = "UPDATE hl_email_list_members 
                  SET " . implode(', ', $fields) . " 
                  WHERE id = :id";
        writeLogAppend('EmailListMemeberModel-90', $query);
        writeLogAppend('EmailListMemeberModel-91', $params);
        
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
                  AND last_tip_sent = 0";
        
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findNextRequestsForTips() {
        $query = "SELECT id, list_id, list_name, champion_id, subscribed, last_tip_sent, last_tip_sent_time, finished_all_tips, unsubscribed
                  FROM hl_email_list_members
                  WHERE unsubscribed IS NULL
                  AND last_tip_sent_time <= NOW() - INTERVAL 7 DAY
                  AND finished_all_tips IS NULL";
        
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
