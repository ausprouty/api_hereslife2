<?php

namespace App\Models\Emails;

class EmailBlockModel {
    private $email;

    public function __construct($email = '') {
        $this->email = $email;
    }

    // Getter
    public function getEmail() { return $this->email; }
}

?>
