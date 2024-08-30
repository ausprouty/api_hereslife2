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
