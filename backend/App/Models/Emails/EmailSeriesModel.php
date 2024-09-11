<?php

namespace App\Models\Emails;

use App\Services\Database\DatabaseService;

/**
 * EmailSeriesModel
 *
 * This class represents an email series, including its ID (tid), maximum sequence (max),
 * and active status. It provides methods for setting and retrieving values related to an email series.
 */
class EmailSeriesModel {
    private $tid;
    private $max;
    private $active;

    private $databaseService;

    /**
     * Constructor with Dependency Injection for DatabaseService.
     *
     * @param DatabaseService $databaseService Dependency injection for database operations.
     */
    public function __construct(DatabaseService $databaseService) {
        $this->databaseService = $databaseService;
    }

    /**
     * Set values for the EmailSeriesModel, applying default values if necessary.
     *
     * @param array $data Associative array containing the data to set.
     */
    public function setValues(array $data) {
        $defaults = [
            'tid' => null,
            'max' => null,
            'active' => null,
        ];

        // Merge provided data with defaults
        $data = array_merge($defaults, $data);

        // Assign values to properties
        $this->tid = $data['tid'];
        $this->max = $data['max'];
        $this->active = $data['active'];
    }

    /**
     * Insert a new email series into the database.
     *
     * @return int The last inserted ID.
     */
    public function insert() {
        $query = "INSERT INTO hl_email_series (tid, max, active) 
                  VALUES (:tid, :max, :active)";

        $params = [
            ':tid' => $this->tid,
            ':max' => $this->max,
            ':active' => $this->active
        ];

        // Execute the query and return the last insert ID
        $this->databaseService->executeUpdate($query, $params);
        return $this->databaseService->getLastInsertId();
    }

    /**
     * Update an existing email series in the database.
     *
     * @return bool Returns true if the update was successful.
     */
    public function update() {
        $query = "UPDATE hl_email_series 
                  SET max = :max, active = :active 
                  WHERE tid = :tid";

        $params = [
            ':tid' => $this->tid,
            ':max' => $this->max,
            ':active' => $this->active
        ];

        // Execute the update and return the result
        return $this->databaseService->executeUpdate($query, $params);
    }

    /**
     * Create a new email series or update an existing one if the tid is set.
     *
     * @param array $data Associative array containing the data to create or update.
     * @return int|null The ID of the created or updated email series.
     */
    public function create(array $data) {
        $this->setValues($data);

        if ($this->tid) {
            $this->update();
        } else {
            return $this->insert();
        }

        return $this->tid;
    }

    // Getters for accessing properties

    /**
     * Get the tid of the email series.
     *
     * @return int|null The tid of the email series.
     */
    public function getTid() {
        return $this->tid;
    }

    /**
     * Get the max sequence of the email series.
     *
     * @return int|null The max sequence of the email series.
     */
    public function getMax() {
        return $this->max;
    }

    /**
     * Get the active status of the email series.
     *
     * @return bool|null The active status of the email series.
     */
    public function getActive() {
        return $this->active;
    }
}
