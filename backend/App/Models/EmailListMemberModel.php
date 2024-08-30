<?php
class SubscriptionModel {
    private $id;
    private $list_id;
    private $champion_id;
    private $subscribed;
    private $unsubscribed;

    public function __construct($params = []) {
        $this->id = $params['id'] ?? null;
        $this->list_id = $params['list_id'] ?? null;
        $this->champion_id = $params['champion_id'] ?? null;
        $this->subscribed = $params['subscribed'] ?? null;
        $this->unsubscribed = $params['unsubscribed'] ?? null;
    }

    // Getter and Setter methods for all properties
    public function getId() {
        return $this->id;
    }

    public function getListId() {
        return $this->list_id;
    }

    public function getChampionId() {
        return $this->champion_id;
    }

    public function getSubscribed() {
        return $this->subscribed;
    }

    public function getUnsubscribed() {
        return $this->unsubscribed;
    }

    // Setter methods (if needed)
    public function setId($id) {
        $this->id = $id;
    }

    public function setListId($list_id) {
        $this->list_id = $list_id;
    }

    public function setChampionId($champion_id) {
        $this->champion_id = $champion_id;
    }

    public function setSubscribed($subscribed) {
        $this->subscribed = $subscribed;
    }

    public function setUnsubscribed($unsubscribed) {
        $this->unsubscribed = $unsubscribed;
    }

    public function insert() {
        $query = "INSERT INTO hl_subscriptions 
                  (list_id, champion_id, subscribed, unsubscribed)
                  VALUES 
                  (:list_id, :champion_id, :subscribed, :unsubscribed)";
        
        $params = array(
            ':list_id' => $this->list_id,
            ':champion_id' => $this->champion_id,
            ':subscribed' => $this->subscribed,
            ':unsubscribed' => $this->unsubscribed
        );
        
        writeLog('subscription insert query', $params);
        
        // Execute the query
        $this->databaseService->executeQuery($query, $params);
    }
    
    public function update() {
        $query = "UPDATE hl_subscriptions 
                  SET list_id = :list_id,
                      champion_id = :champion_id,
                      subscribed = :subscribed,
                      unsubscribed = :unsubscribed
                  WHERE id = :id";
        
        $params = array(
            ':list_id' => $this->list_id,
            ':champion_id' => $this->champion_id,
            ':subscribed' => $this->subscribed,
            ':unsubscribed' => $this->unsubscribed,
            ':id' => $this->id // Ensure $this->id is set for the update
        );
        
        writeLog('subscription update query', $params);
        
        // Execute the query
        $this->databaseService->executeQuery($query, $params);
    }
    
    
    
}
