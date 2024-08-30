<?php
namespace App\Models\Materials;

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

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->tips = $data['tips'] ?? null;
        $this->foreign_title_1 = $data['foreign_title_1'] ?? null;
        $this->foreign_title_2 = $data['foreign_title_2'] ?? null;
        $this->lang1 = $data['lang1'] ?? '';
        $this->lang2 = $data['lang2'] ?? '';
        $this->format = $data['format'] ?? '';
        $this->audience = $data['audience'] ?? '';
        $this->contact = $data['contact'] ?? '';
        $this->filename = $data['filename'] ?? '';
        $this->category = $data['category'] ?? null;
        $this->downloads = $data['downloads'] ?? null;
        $this->active = $data['active'] ?? '';
        $this->active_date = $data['active_date'] ?? null;
        $this->size = $data['size'] ?? null;
        $this->print_size = $data['print_size'] ?? null;
        $this->ordered = $data['ordered'] ?? null;
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
