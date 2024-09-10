<?php

namespace App\Controllers\Materials;

use App\Models\Materials\DownloadModel;
use DateTime;

class DownloadController {
    private DownloadModel $downloadModel;
    private string $database;

    public function __construct(DownloadModel $downloadModel, string $database = 'standard') {
        $this->downloadModel = $downloadModel;
        $this->database = $database;
    }

    // Create a new download entry
    public function createDownloadRecord(array $rawData): DownloadModel {
        $download = new DownloadModel($this->database);
        $download->setValues($rawData);
        $download->download_date = Now(); // Set the download date to the current date and time
        $download->elapsed_months = $this->getElapsedMonthsSinceCentury(); // Calculate the elapsed months since the year 2000
        $download->insert(); // Save the model instance to the database
        return $download;
    }

    private function getElapsedMonthsSinceCentury(): int {
        $startDate = new DateTime('2000-01-01');
        $currentDate = new DateTime();
        $interval = $startDate->diff($currentDate);
        return ($interval->y * 12) + $interval->m;
    }

    // Example: Method to retrieve a download by its ID
    public function getDownloadById(int $id): ?DownloadModel {
        // Code to retrieve the download record from the database using the $id
        // and return a DownloadModel instance
        // Example:
        // return $this->model->findById($id);
    }
}
