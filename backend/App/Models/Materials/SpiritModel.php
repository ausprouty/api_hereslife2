<?php

namespace App\Models\Materials;

use App\Services\DatabaseService;
use PDO;

class SpiritModel {
    private $id;
    private $name;
    private $webpage;
    private $images;
    private $hlId;
    private $valid;
    private $promo;

    private $databaseService;

    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }
        // Set values with defaults for missing parameters
    public function setValues(array $data) {
        $defaults = [
            'id' => null,
            'name' => '',
            'webpage' => '',
            'images' => '',
            'hlId' => null,
            'valid' => 1,  // Assume valid by default
            'promo' => null,
        ];

        // Merge provided params with defaults
        $data = array_merge($defaults, $data);

        // Assign values to object properties
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->webpage = $data['webpage'];
        $this->images = $data['images'];
        $this->hlId = $data['hlId'];
        $this->valid = $data['valid'];
        $this->promo = $data['promo'];
    }

    // Insert the object's values into the database
    public function insert() {
        $query = "INSERT INTO hl_spirits (name, webpage, images, hlId, valid, promo)
                    VALUES (:name, :webpage, :images, :hlId, :valid, :promo)";
        
        $params = [
            ':name' => $this->name,
            ':webpage' => $this->webpage,
            ':images' => $this->images,
            ':hlId' => $this->hlId,
            ':valid' => $this->valid,
            ':promo' => $this->promo,
        ];

        // Execute the query with the parameters
        return $this->databaseService->executeUpdate($query, $params);
    }
    
        
    
    // Save method to decide between insert and update based on object state
    public function save() {
        if (isset($this->id)) {
            return $this->update($this->id);
        } else {
            return $this->insert();
        }
    }
    // Update an existing record
    public function update($id) {
        $query = "UPDATE hl_spirits 
                    SET name = :name, webpage = :webpage, images = :images, hlId = :hlId, valid = :valid, promo = :promo
                    WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':name' => $this->name,
            ':webpage' => $this->webpage,
            ':images' => $this->images,
            ':hlId' => $this->hlId,
            ':valid' => $this->valid,
            ':promo' => $this->promo,
        ];

        // Execute the query
        return $this->databaseService->executeUpdate($query, $params);
    }

    public function getTitlesByLanguageName() {
        $query = "SELECT languageName FROM hl_spirit ORDER BY languageName";
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByLanguageName($languageName) {
        $query = "SELECT * FROM hl_spirit WHERE languageName = :languageName";
        $params = [':languageName' => $languageName];
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetchAll(PDO::FETCH_OBJ);
    }

    // Method to populate model properties from a database record
    public function loadFromRecord($record) {
        $this->id = $record->id;
        $this->name = $record->name;
        $this->webpage = $record->webpage;
        $this->images = $record->images;
        $this->hlId = $record->hlId;
        $this->valid = $record->valid;
        $this->promo = $record->promo;
    }

    // Getters for the properties
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getWebpage(): string {
        return $this->webpage;
    }

    public function getImages(): array {
        return $this->images;
    }

    public function getHlId(): int {
        return $this->hlId;
    }

    public function isValid(): ?bool {
        return $this->valid;
    }

    public function getPromo(): ?string {
        return $this->promo;
    }
}
