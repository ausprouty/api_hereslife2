<?php

namespace App\Controllers\Emails;

use App\Models\Emails\EmailQueModel;

class EmailQueController {

    private $emailQueModel;

    public function __construct() {
        $this->emailQueModel = new EmailQueModel();
        
    }
    public function queEmails(array $champions, int $letterId) {
        $result = $this->emailQueModel->queEmails($champions, $letterId);
        return $result;
    }
}