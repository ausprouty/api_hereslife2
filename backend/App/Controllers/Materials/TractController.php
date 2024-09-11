<?php
Namespace App\Controllers\Materials;

use App\Services\Database\DatabaseService;
use PDO;


class TractController {
    private $databaseService;

    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }
    public function getTractsToView() {
        $query = "SELECT lang1 FROM hl_materials
            WHERE active = :active 
            AND category = :category
            AND format = :format
            AND lang2 = :lang2
            ORDER BY lang1 ASC";
         $params = [
            ':active' => 'YES', 
            ':category' => 'Tracts', 
            ':format' => 'VIEW', 
            ':lang2' => 'English']; 
        $results = $this->databaseService->executeQuery($query, $params);
        $data =  $results->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function getTractsMonolingual() {
        $query = "SELECT distinct title, foreign_title_1 FROM hl_materials
            WHERE active LIKE :active 
            AND filename LIKE :filename
            ORDER BY title ASC";
         $params = [':active' => 'YES', 
            ':active' => 'YES', 
            ':filename' => 'tracts-monolingual%'];
        $results = $this->databaseService->executeQuery($query, $params);
        $data =  $results->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function getTractsBilingualEnglish() {
        $query = "SELECT distinct title  FROM hl_materials
            WHERE active = :active 
            AND category = :category
            AND lang2 = :lang2
            ORDER BY title ASC";
         $params = [':active' => 'YES', 
            ':active' => 'YES', 
            ':category' => 'Tracts',
            ':lang2' => 'English']; 
        $results = $this->databaseService->executeQuery($query, $params);
        $data =  $results->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    // Add more query methods as needed
}

