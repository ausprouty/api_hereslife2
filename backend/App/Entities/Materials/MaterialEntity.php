<?php

namespace App\Entities\Materials;

use App\Services\Database\DatabaseService;

/**
 * Class MaterialEntity
 * 
 * Represents a material entity, encapsulating the data and behavior related to a material.
 */
class MaterialEntity {

    private $id;
    private $title;
    private $tips;
    private $foreign_title_1;
    private $foreign_title_2;
    private $lang1;
    private $lang2;
    private $format;
    private $audience;
    private $contact;
    private $filename;
    private $category;
    private $downloads;
    private $active;
    private $active_date;
    private $size;
    private $print_size;
    private $ordered;

    private $databaseService;

    /**
     * MaterialEntity constructor.
     * 
     * @param DatabaseService $databaseService - The database service used for executing queries.
     */
    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }

    /**
     * Sets the values for this entity.
     * 
     * @param array $data - An associative array of data to initialize the entity.
     */
    public function setValues(array $data) {
        $defaults = [
            'id' => null,
            'title' => '',
            'tips' => null,
            'foreign_title_1' => null,
            'foreign_title_2' => null,
            'lang1' => '',
            'lang2' => '',
            'format' => '',
            'audience' => '',
            'contact' => '',
            'filename' => '',
            'category' => null,
            'downloads' => null,
            'active' => '',
            'active_date' => null,
            'size' => null,
            'print_size' => null,
            'ordered' => null
        ];

        $data = array_merge($defaults, $data);

        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->tips = $data['tips'];
        $this->foreign_title_1 = $data['foreign_title_1'];
        $this->foreign_title_2 = $data['foreign_title_2'];
        $this->lang1 = $data['lang1'];
        $this->lang2 = $data['lang2'];
        $this->format = $data['format'];
        $this->audience = $data['audience'];
        $this->contact = $data['contact'];
        $this->filename = $data['filename'];
        $this->category = $data['category'];
        $this->downloads = $data['downloads'];
        $this->active = $data['active'];
        $this->active_date = $data['active_date'];
        $this->size = $data['size'];
        $this->print_size = $data['print_size'];
        $this->ordered = $data['ordered'];
    }

    /**
     * Inserts or updates the entity in the database.
     * 
     * @return bool - True on success, false on failure.
     */
    public function save() {
        $query = "INSERT INTO hl_materials 
                  (title, tips, foreign_title_1, foreign_title_2, lang1, lang2, format, audience, contact, filename, category, downloads, active, active_date, size, print_size, ordered)
                  VALUES 
                  (:title, :tips, :foreign_title_1, :foreign_title_2, :lang1, :lang2, :format, :audience, :contact, :filename, :category, :downloads, :active, :active_date, :size, :print_size, :ordered)
                  ON DUPLICATE KEY UPDATE
                  title = :title, tips = :tips, foreign_title_1 = :foreign_title_1, foreign_title_2 = :foreign_title_2, lang1 = :lang1, lang2 = :lang2, 
                  format = :format, audience = :audience, contact = :contact, filename = :filename, category = :category, downloads = :downloads, 
                  active = :active, active_date = :active_date, size = :size, print_size = :print_size, ordered = :ordered";
        
        $params = [
            ':title' => $this->title,
            ':tips' => $this->tips,
            ':foreign_title_1' => $this->foreign_title_1,
            ':foreign_title_2' => $this->foreign_title_2,
            ':lang1' => $this->lang1,
            ':lang2' => $this->lang2,
            ':format' => $this->format,
            ':audience' => $this->audience,
            ':contact' => $this->contact,
            ':filename' => $this->filename,
            ':category' => $this->category,
            ':downloads' => $this->downloads,
            ':active' => $this->active,
            ':active_date' => $this->active_date,
            ':size' => $this->size,
            ':print_size' => $this->print_size,
            ':ordered' => $this->ordered,
        ];

        return $this->databaseService->executeUpdate($query, $params);
    }

    /**
     * Combines setting values and saving the entity to the database.
     * 
     * @param array $data - The data to initialize and save the entity.
     * @return bool - True on success, false on failure.
     */
    public function create(array $data) {
        $this->setValues($data);
        return $this->save();
    }

    // Getters for each property...

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getTips() { return $this->tips; }
    public function getForeignTitle1() { return $this->foreign_title_1; }
    public function getForeignTitle2() { return $this->foreign_title_2; }
    public function getLang1() { return $this->lang1; }
    public function getLang2() { return $this->lang2; }
    public function getFormat() { return $this->format; }
    public function getAudience() { return $this->audience; }
    public function getContact() { return $this->contact; }
    public function getFilename() { return $this->filename; }
    public function getCategory() { return $this->category; }
    public function getDownloads() { return $this->downloads; }
    public function getActive() { return $this->active; }
    public function getActiveDate() { return $this->active_date; }
    public function getSize() { return $this->size; }
    public function getPrintSize() { return $this->print_size; }
    public function getOrdered() { return $this->ordered; }
}
