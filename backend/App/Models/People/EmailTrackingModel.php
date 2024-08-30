<?php

namespace App\Models\People;

class EmailTrackingModel {
    private $id;
    private $email_id;
    private $champion_id;
    private $date_sent;
    private $date_opened;
    private $date_clicked;
    private $params;

    public function __construct($id, $email_id, $champion_id, $date_sent, $date_opened = null, $date_clicked = null, $params = null) {
        $this->id = $id;
        $this->email_id = $email_id;
        $this->champion_id = $champion_id;
        $this->date_sent = $date_sent;
        $this->date_opened = $date_opened;
        $this->date_clicked = $date_clicked;
        $this->params = $params;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getEmailId() { return $this->email_id; }
    public function getChampionId() { return $this->champion_id; }
    public function getDateSent() { return $this->date_sent; }
    public function getDateOpened() { return $this->date_opened; }
    public function getDateClicked() { return $this->date_clicked; }
    public function getParams() { return $this->params; }
}

?>
