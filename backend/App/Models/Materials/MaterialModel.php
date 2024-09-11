<?php
namespace App\Models\Materials;

use App\Services\Database\DatabaseService;
use PDO;

class MaterialModel {

    private $databaseService;

    public function __construct($database = 'standard') {
        writeLog('MaterialModel-15', 'database: ' . $database);
        $this->databaseService = new DatabaseService($database);
    }

    public function findByFileName($filename) {
        $query = "SELECT id, title, tips, foreign_title_1, foreign_title_2, lang1, lang2, format, audience, contact, filename, category, downloads, active, active_date, size, print_size, ordered
                  FROM hl_materials 
                  WHERE filename = :filename
                  LIMIT 1";
        $params = [':filename' => $filename];
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetch(PDO::FETCH_ASSOC);
    }
    public function findById($id) {
        $query = "SELECT id, title, tips, foreign_title_1, foreign_title_2, lang1, lang2, format, audience, contact, filename, category, downloads, active, active_date, size, print_size, ordered
                  FROM hl_materials 
                  WHERE id = :id
                  LIMIT 1";
        $params = [':id' => $id];
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetch(PDO::FETCH_ASSOC);
    }
    
    public function incrementDownloads($id) {
        $query = "UPDATE hl_materials 
                  SET downloads = downloads + 1 
                  WHERE id = :id";
        $params = [':id' => $id];
        return $this->databaseService->executeUpdate($query, $params);
    }
    // Example getter methods for specific fields can be implemented as needed
}
