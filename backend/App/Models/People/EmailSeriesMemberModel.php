<?php

namespace App\Models\People;

class EmailSeriesMemberModel {
    private $id;
    private $tid;
    private $cid;
    private $sequence;
    private $sent;

    public function __construct($id, $tid, $cid, $sequence = null, $sent = null) {
        $this->id = $id;
        $this->tid = $tid;
        $this->cid = $cid;
        $this->sequence = $sequence;
        $this->sent = $sent;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTid() { return $this->tid; }
    public function getCid() { return $this->cid; }
    public function getSequence() { return $this->sequence; }
    public function getSent() { return $this->sent; }
}

?>
