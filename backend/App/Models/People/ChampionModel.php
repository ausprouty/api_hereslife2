<?php

namespace App\Models\People;

class ChampionModel
{
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

    // Constructor doesn't handle data, use setValues for that
    public function __construct(array $data = [])
    {
        // Use setValues to initialize the object
        $this->setValues($data);
    }

    // Set values for the object, applying default values
    public function setValues(array $data)
    {
        $defaults = [
            'cid' => null,
            'first_name' => '',
            'surname' => '',
            'title' => '',
            'organization' => '',
            'address' => '',
            'suburb' => '',
            'state' => '',
            'postcode' => '',
            'country' => '',
            'phone' => '',
            'sms' => '',
            'email' => '',
            'gender' => null,
            'double_opt_in_date' => null,
            'first_email_date' => null,
            'last_open_date' => null,
            'consider_dropping_date' => null,
            'first_download_date' => null,
            'last_download_date' => null,
            'last_email_date' => null,
        ];

        // Merge provided data with defaults
        $data = array_merge($defaults, $data);

        // Set object properties using the merged array
        foreach ($data as $field => $value) {
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }
    }

    // Getters and setters for each property

    public function getCid() { return $this->cid; }
    public function setCid($cid) { $this->cid = $cid; }

    public function getFirstName() { return $this->first_name; }
    public function setFirstName($first_name) { $this->first_name = $first_name; }

    public function getSurname() { return $this->surname; }
    public function setSurname($surname) { $this->surname = $surname; }

    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }

    public function getOrganization() { return $this->organization; }
    public function setOrganization($organization) { $this->organization = $organization; }

    public function getAddress() { return $this->address; }
    public function setAddress($address) { $this->address = $address; }

    public function getSuburb() { return $this->suburb; }
    public function setSuburb($suburb) { $this->suburb = $suburb; }

    public function getState() { return $this->state; }
    public function setState($state) { $this->state = $state; }

    public function getPostcode() { return $this->postcode; }
    public function setPostcode($postcode) { $this->postcode = $postcode; }

    public function getCountry() { return $this->country; }
    public function setCountry($country) { $this->country = $country; }

    public function getPhone() { return $this->phone; }
    public function setPhone($phone) { $this->phone = $phone; }

    public function getSms() { return $this->sms; }
    public function setSms($sms) { $this->sms = $sms; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getGender() { return $this->gender; }
    public function setGender($gender) { $this->gender = $gender; }

    public function getDoubleOptInDate() { return $this->double_opt_in_date; }
    public function setDoubleOptInDate($double_opt_in_date) { $this->double_opt_in_date = $double_opt_in_date; }

    public function getFirstEmailDate() { return $this->first_email_date; }
    public function setFirstEmailDate($first_email_date) { $this->first_email_date = $first_email_date; }

    public function getLastOpenDate() { return $this->last_open_date; }
    public function setLastOpenDate($last_open_date) { $this->last_open_date = $last_open_date; }

    public function getConsiderDroppingDate() { return $this->consider_dropping_date; }
    public function setConsiderDroppingDate($consider_dropping_date) { $this->consider_dropping_date = $consider_dropping_date; }

    public function getFirstDownloadDate() { return $this->first_download_date; }
    public function setFirstDownloadDate($first_download_date) { $this->first_download_date = $first_download_date; }

    public function getLastDownloadDate() { return $this->last_download_date; }
    public function setLastDownloadDate($last_download_date) { $this->last_download_date = $last_download_date; }

    public function getLastEmailDate() { return $this->last_email_date; }
    public function setLastEmailDate($last_email_date) { $this->last_email_date = $last_email_date; }
}
