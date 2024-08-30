<?php

namespace App\Repositories;

use App\Models\People\ChampionModel;
use App\Services\DatabaseService;
use PDO;
use Exception;

class ChampionRepository
{
    private $databaseService;

    public function __construct( $database = 'standard')
    {
        writeLog('ChampionRepository-15', 'database: ' . $database);
        $this->databaseService = new DatabaseService ($database);
    }

    public function findByEmail($email): ?ChampionModel
    {
        $query = "SELECT * FROM hl_champions WHERE email = :email LIMIT 1";
        $params = [':email' => $email];
        $results = $this->databaseService->executeQuery($query, $params);
        $data = $results->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new ChampionModel($data);
        }

        return null;
    }
    public function findByCid($cid): ?ChampionModel
    {
        $query = "SELECT * FROM hl_champions WHERE cid = :cid LIMIT 1";
        $params = [':cid' => $cid];
        $results = $this->databaseService->executeQuery($query, $params);
        $data = $results->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new ChampionModel($data);
        }

        return null;
    }

    public function save(ChampionModel $champion)
    {
        if ($champion->getCid()) {
            // Update existing record
            $query = "UPDATE hl_champions SET 
                      first_name = :first_name, 
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
                      email = :email, 
                      gender = :gender, 
                      double_opt_in_date = :double_opt_in_date, 
                      first_email_date = :first_email_date, 
                      last_open_date = :last_open_date, 
                      consider_dropping_date = :consider_dropping_date, 
                      first_download_date = :first_download_date, 
                      last_download_date = :last_download_date, 
                      last_email_date = :last_email_date 
                      WHERE cid = :cid";

            $params = [
                ':cid' => $champion->getCid(),
                ':first_name' => $champion->getFirstName(),
                ':surname' => $champion->getSurname(),
                ':title' => $champion->getTitle(),
                ':organization' => $champion->getOrganization(),
                ':address' => $champion->getAddress(),
                ':suburb' => $champion->getSuburb(),
                ':state' => $champion->getState(),
                ':postcode' => $champion->getPostcode(),
                ':country' => $champion->getCountry(),
                ':phone' => $champion->getPhone(),
                ':sms' => $champion->getSms(),
                ':email' => $champion->getEmail(),
                ':gender' => $champion->getGender(),
                ':double_opt_in_date' => $champion->getDoubleOptInDate(),
                ':first_email_date' => $champion->getFirstEmailDate(),
                ':last_open_date' => $champion->getLastOpenDate(),
                ':consider_dropping_date' => $champion->getConsiderDroppingDate(),
                ':first_download_date' => $champion->getFirstDownloadDate(),
                ':last_download_date' => $champion->getLastDownloadDate(),
                ':last_email_date' => $champion->getLastEmailDate(),
            ];
        } else {
            // Insert new record
            $query = "INSERT INTO hl_champions 
                      (first_name, surname, title, organization, address, suburb, state, postcode, country, phone, sms, email, gender, double_opt_in_date, first_email_date, last_open_date, consider_dropping_date, first_download_date, last_download_date, last_email_date)
                      VALUES 
                      (:first_name, :surname, :title, :organization, :address, :suburb, :state, :postcode, :country, :phone, :sms, :email, :gender, :double_opt_in_date, :first_email_date, :last_open_date, :consider_dropping_date, :first_download_date, :last_download_date, :last_email_date)";

            $params = [
                ':first_name' => $champion->getFirstName(),
                ':surname' => $champion->getSurname(),
                ':title' => $champion->getTitle(),
                ':organization' => $champion->getOrganization(),
                ':address' => $champion->getAddress(),
                ':suburb' => $champion->getSuburb(),
                ':state' => $champion->getState(),
                ':postcode' => $champion->getPostcode(),
                ':country' => $champion->getCountry(),
                ':phone' => $champion->getPhone(),
                ':sms' => $champion->getSms(),
                ':email' => $champion->getEmail(),
                ':gender' => $champion->getGender(),
                ':double_opt_in_date' => $champion->getDoubleOptInDate(),
                ':first_email_date' => $champion->getFirstEmailDate(),
                ':last_open_date' => $champion->getLastOpenDate(),
                ':consider_dropping_date' => $champion->getConsiderDroppingDate(),
                ':first_download_date' => $champion->getFirstDownloadDate(),
                ':last_download_date' => $champion->getLastDownloadDate(),
                ':last_email_date' => $champion->getLastEmailDate(),
            ];
        }

        $this->databaseService->executeQuery($query, $params);
    }
}
