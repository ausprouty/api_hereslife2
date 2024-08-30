<?php

namespace App\Models\People;

use App\Services\DatabaseService;
use PDO;
use Exception;
use App\Services\ChampionEmailAddressService;

class ChampionModel {
    private $databaseService;
    private $championEmailAddressService;

    private $cid;
    private $first_name;
    private $surname;
    private $title;
    private $organization;
    private $address;
    private $suburb;
    private $state;
    private $postcode;
    private $country;
    private $phone;
    private $sms;
    private $email;
    private $gender;
    private $double_opt_in_date;
    private $first_email_date;
    private $last_open_date;
    private $consider_dropping_date;
    private $first_download_date;
    private $last_download_date;
    private $last_email_date;

    public function __construct($configType = 'standard', $cid = null, $first_name = null, $surname = null, $title = null, $organization = null, $address = null, $suburb = null, $state = null, $postcode = '', $country = null, $phone = null, $sms = null, $email = null, $gender = null, $double_opt_in_date = null, $first_email_date = null, $last_open_date = null, $consider_dropping_date = null, $first_download_date = null, $last_download_date = null, $last_email_date = null) {
        writeLog('hl_champion controller-33', 'configType: ' . $configType);
        $this->databaseService = new DatabaseService($configType = 'standard');
        
        $this->cid = $cid;
        $this->first_name = $first_name;
        $this->surname = $surname;
        $this->title = $title;
        $this->organization = $organization;
        $this->address = $address;
        $this->suburb = $suburb;
        $this->state = $state;
        $this->postcode = $postcode;
        $this->country = $country;
        $this->phone = $phone;
        $this->sms = $sms;
        $this->email = $email;
        $this->gender = $gender;
        $this->double_opt_in_date = $double_opt_in_date;
        $this->first_email_date = $first_email_date;
        $this->last_open_date = $last_open_date;
        $this->consider_dropping_date = $consider_dropping_date;
        $this->first_download_date = $first_download_date;
        $this->last_download_date = $last_download_date;
        $this->last_email_date = $last_email_date;
    }
    public function updateChampionFromFormData($formData) {
        // Map form fields to model properties
        $fieldsMap = [
            'first_name' => 'first_name',
            'surname' => 'surname',
            'title' => 'title',
            'organization' => 'organization',
            'address' => 'address',
            'suburb' => 'suburb',
            'state' => 'state',
            'postcode' => 'postcode',
            'country' => 'country',
            'phone' => 'phone',
            'sms' => 'sms',
            'email' => 'email',
            'gender' => 'gender',
            'double_opt_in_date' => 'double_opt_in_date',
            'first_email_date' => 'first_email_date',
            'last_open_date' => 'last_open_date',
            'consider_dropping_date' => 'consider_dropping_date',
            'first_download_date' => 'first_download_date',
            'last_download_date' => 'last_download_date',
            'last_email_date' => 'last_email_date'
        ];
    
        // Loop through form data and update model fields
        foreach ($formData as $formField => $value) {
            if (array_key_exists($formField, $fieldsMap)) {
                $property = $fieldsMap[$formField];
                $this->$property = $value;
            }
        }
        writeLog('hl_champion controller-84', $this);
        // Save or update the model in the database
        $this->save(); // Implement the save method to persist changes
    }
    
    public function save() {
        if (!$this->email) {
            return;
        }
        // Check if the record already exists
        $query = "SELECT cid FROM hl_champions WHERE email = :email ORDER BY cid LIMIT 1";
        $params = array(':email' => $this->email);
        writeLog('hl_champion controller-101', $params);
        $results = $this->databaseService->executeQuery($query, $params);
        $existingRecord = $results->fetch(PDO::FETCH_ASSOC);
    
        if ($existingRecord) {
            writeLog('hl_champion controller-108', 'existing record');
            // update cid
            $this->cid = $existingRecord['cid'];
            // Update existing record
            $query = "UPDATE hl_champions 
                      SET first_name = :first_name, 
                          surname = :surname, 
                          title = :title, 
                          organization = :organization, 
                          address = :address, 
                          suburb = :suburb, 
                          state = :state, 
                          postcode = :postcode, 
                          country = :country, 
                          phone = :phone, 
                          sms = :sms, 
                          gender = :gender, 
                          double_opt_in_date = :double_opt_in_date, 
                          first_email_date = :first_email_date, 
                          last_open_date = :last_open_date, 
                          consider_dropping_date = :consider_dropping_date, 
                          first_download_date = :first_download_date, 
                          last_download_date = :last_download_date, 
                          last_email_date = :last_email_date
                      WHERE cid = :cid";
            $params = array(
                ':cid' => $existingRecord['cid'],
                ':first_name' => $this->first_name,
                ':surname' => $this->surname,
                ':title' => $this->title,
                ':organization' => $this->organization,
                ':address' => $this->address,
                ':suburb' => $this->suburb,
                ':state' => $this->state,
                ':postcode' => $this->postcode,
                ':country' => $this->country,
                ':phone' => $this->phone,
                ':sms' => $this->sms,
                ':gender' => $this->gender,
                ':double_opt_in_date' => $this->double_opt_in_date,
                ':first_email_date' => $this->first_email_date,
                ':last_open_date' => $this->last_open_date,
                ':consider_dropping_date' => $this->consider_dropping_date,
                ':first_download_date' => $this->first_download_date,
                ':last_download_date' => $this->last_download_date,
                ':last_email_date' => $this->last_email_date,
            );
        } else {
            // Insert new record
            writeLog('hl_champion controller-155', 'new record');
            $query = "INSERT INTO hl_champions 
                      (first_name, surname, title, organization, address, suburb, state, postcode, country, phone, sms, email, gender, double_opt_in_date, first_email_date, last_open_date, consider_dropping_date, first_download_date, last_download_date, last_email_date)
                      VALUES 
                      (:first_name, :surname, :title, :organization, :address, :suburb, :state, :postcode, :country, :phone, :sms, :email, :gender, :double_opt_in_date, :first_email_date, :last_open_date, :consider_dropping_date, :first_download_date, :last_download_date, :last_email_date)";
            $params = array(
                ':first_name' => $this->first_name,
                ':surname' => $this->surname,
                ':title' => $this->title,
                ':organization' => $this->organization,
                ':address' => $this->address,
                ':suburb' => $this->suburb,
                ':state' => $this->state,
                ':postcode' => $this->postcode,
                ':country' => $this->country,
                ':phone' => $this->phone,
                ':sms' => $this->sms,
                ':email' => $this->email,
                ':gender' => $this->gender,
                ':double_opt_in_date' => $this->double_opt_in_date,
                ':first_email_date' => $this->first_email_date,
                ':last_open_date' => $this->last_open_date,
                ':consider_dropping_date' => $this->consider_dropping_date,
                ':first_download_date' => $this->first_download_date,
                ':last_download_date' => $this->last_download_date,
                ':last_email_date' => $this->last_email_date,
            );
        }

        // Execute the query
        $this->databaseService->executeQuery($query, $params);
        // update cid
        $this->cid = $this->getCidByEmail($this->email);
    }
    public function loadByCid($cid) {
        $query = "SELECT * FROM hl_champions WHERE cid = :cid LIMIT 1";
        $params = array(':cid' => $cid);
        $results = $this->databaseService->executeQuery($query, $params);
        $data = $results->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Populate the object with data
            foreach ($data as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->$field = $value;
                }
            }
        } else {
            throw new Exception("No record found with the given cid.");
        }
    }
    public function loadByEmail($email) {
        $query = "SELECT * FROM hl_champions WHERE email = :email LIMIT 1";
        $params = array(':email' => $email);
        $results = $this->databaseService->executeQuery($query, $params);
        $data = $results->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Populate the object with data
            foreach ($data as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->$field = $value;
                }
            }
        } else {
            throw new Exception("No record found with the given email.");
        }
    }
    public function getCidByEmail($email) {
        $query = "SELECT cid
                  FROM hl_champions 
                  WHERE email = :email 
                  LIMIT 1"; 
        $params = array(            
            ':email' => $email
        );
        $results = $this->databaseService->executeQuery($query, $params);
        $data =  $results->fetchColumn();
        writeLog('hl_champion controller', $data);
        return $data;
    }
    public function updateLastDownloadDate($userId) {
        $this->loadByCid($userId);
        $this->last_download_date = time();
        if ($this->first_download_date == 0 || $this->first_download_date == null) {
            $this->first_download_date = time();
        }
        $this->save();
    }

    // Getters
    public function getCid() { return $this->cid; }
    public function getFirstName() { return $this->first_name; }
    public function getSurname() { return $this->surname; }
    public function getTitle() { return $this->title; }
    public function getOrganization() { return $this->organization; }
    public function getAddress() { return $this->address; }
    public function getSuburb() { return $this->suburb; }
    public function getState() { return $this->state; }
    public function getPostcode() { return $this->postcode; }
    public function getCountry() { return $this->country; }
    public function getPhone() { return $this->phone; }
    public function getSms() { return $this->sms; }
    public function getEmail() { return $this->email; }
    public function getGender() { return $this->gender; }
    public function getDoubleOptInDate() { return $this->double_opt_in_date; }
    public function getFirstEmailDate() { return $this->first_email_date; }
    public function getLastOpenDate() { return $this->last_open_date; }
    public function getConsiderDroppingDate() { return $this->consider_dropping_date; }
    public function getFirstDownloadDate() { return $this->first_download_date; }
    public function getLastDownloadDate() { return $this->last_download_date; }
    public function getLastEmailDate() { return $this->last_email_date; }
    
    public function getChampionEmails($code) {
        $this->championEmailAddressService = new ChampionEmailAddressService();
        return $this->championEmailAddressService->getChampionEmails($code);
    }
}   