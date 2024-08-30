<?php

namespace App\Models\Emails;

class EmailSeriesModel {
    private $tid;
    private $max;
    private $active;

    public function __construct($tid, $max = null, $active = null) {
        $this->tid = $tid;
        $this->max = $max;
        $this->active = $active;
    }

    // Getters
    public function getTid() { return $this->tid; }
    public function getMax() { return $this->max; }
    public function getActive() { return $this->active; }
}

?>
