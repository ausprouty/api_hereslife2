<?php
namespace App\Models\Emails;

use App\Services\DatabaseService;
use PDO;

class EmailModel {

    private $databaseService;

    // Inject DatabaseService via the constructor
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }
    
    public function create($data) {
        $query = "INSERT INTO hl_emails (subject, body, plain_text_only, headers, template, series, sequence, params)
                  VALUES (:subject, :body, :plain_text_only, :headers, :template, :series, :sequence, :params)";
        $params = [
            ':subject' => $data['subject'],
            ':body' => $data['body'],
            ':plain_text_only' => $data['plain_text_only'],
            ':headers' => $data['headers'],
            ':template' => $data['template'],
            ':series' => $data['series'],
            ':sequence' => $data['sequence'],
            ':params' => $data['params']
        ];
        return $this->databaseService->executeUpdate($query, $params);
    }

    public function update($id, $data) {
        $query = "UPDATE hl_emails 
                  SET subject = :subject, 
                      body = :body, 
                      plain_text_only = :plain_text_only, 
                      headers = :headers, 
                      template = :template, 
                      series = :series, 
                      sequence = :sequence, 
                      params = :params
                  WHERE id = :id";
        $params = [
            ':id' => $id,
            ':subject' => $data['subject'],
            ':body' => $data['body'],
            ':plain_text_only' => $data['plain_text_only'],
            ':headers' => $data['headers'],
            ':template' => $data['template'],
            ':series' => $data['series'],
            ':sequence' => $data['sequence'],
            ':params' => $data['params']
        ];
        return $this->databaseService->executeUpdate($query, $params);
    }

    public function delete($id) {
        $query = "DELETE FROM hl_emails WHERE id = :id";
        $params = [':id' => $id];
        return $this->databaseService->executeUpdate($query, $params);
    }
    public function findById($id) {
        $query = "SELECT id, subject, body, plain_text_only, headers, template, series, sequence, params
                  FROM hl_emails 
                  WHERE id = :id
                  LIMIT 1";
        $params = [':id' => $id];
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetch(PDO::FETCH_ASSOC);
    }
    public function findAll() {
        $query = "SELECT id, subject, body, plain_text_only, headers, template, series, sequence, params
                  FROM hl_emails";
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findOneInSeries($series, $sequence) {
        $query = "SELECT id, subject, body, plain_text_only, headers, template, series, sequence, params
                  FROM hl_emails 
                  WHERE series = :series
                  AND sequence = :sequence
                  LIMIT 1";
        $params = [':series' => $series, ':sequence' => $sequence];
        $results = $this->databaseService->executeQuery($query, $params);
        return $results->fetch(PDO::FETCH_ASSOC);
    }
    public function getRecentBlogTitles($number) {
        $int = (int)$number;
        $query = "SELECT subject, id
                  FROM hl_emails
                  WHERE series = 'blog'                 
                  ORDER BY id DESC
                  LIMIT $int";
        // Directly include the limit value
        $query = str_replace(':number', $int, $query);
        $results = $this->databaseService->executeQuery($query);
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }
}
