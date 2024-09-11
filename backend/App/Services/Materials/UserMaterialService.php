<?php
namespace App\Services\Materials;

use App\Controllers\Materials\MaterialController;
use App\Controllers\People\ChampionController;
use App\Controllers\Materials\DownloadController;
use InvalidArgumentException;

/**
 * Class UserMaterialService
 *
 * This service manages user material downloads, updating user information,
 * and creating download records. It integrates the MaterialController,
 * ChampionController, and DownloadController.
 */
class UserMaterialService {

    protected $materialController;
    protected $championController;
    protected $downloadController;

    /**
     * UserMaterialService constructor.
     * 
     * @param MaterialController $materialController
     * @param ChampionController $championController
     * @param DownloadController $downloadController
     */
    public function __construct(
        MaterialController $materialController,
        ChampionController $championController,
        DownloadController $downloadController
    ) {
        $this->materialController = $materialController;
        $this->championController = $championController;
        $this->downloadController = $downloadController;
    }

    /**
     * Handles the process of downloading user materials.
     * 
     * @param array $data Contains user and file data (e.g., file name, user ID, mailing list selections).
     * @return string JSON-encoded response with either success or failure.
     */
    public function handleUserMaterialDownload($data) {
        writeLog('downloadMaterialsUpdateUser-32', $data);

        // Find the material ID and manage download
        $materialID = $this->processMaterial($data);
        writeLog('downloadMaterialsUpdateUser-34', $materialID);
        if (!$materialID) {
            return $this->returnError('File not found');
        }

        // Update user details and last download date
        $userId = $this->updateUserDetails($data);
        writeLog('downloadMaterialsUpdateUser-41', $userId);
        if (!$userId) {
            return $this->returnError('Failed to update user details');
        }

        // Handle mailing lists and download record
        $this->handleMailingLists($data, $userId, $materialID);

        // Return the file URL
        return $this->returnSuccess($data['file']);
    }

    /**
     * Processes the material by fetching its ID and incrementing its download count.
     * 
     * @param array $data The data array containing the file information.
     * @return int|null The material ID if found, otherwise null.
     */
    protected function processMaterial($data) {
        $materialID = $this->materialController->getIdByFileName($data['file']);
        if ($materialID) {
            $this->materialController->getAndIncrementDownloads($materialID);
        }
        return $materialID;
    }

    /**
     * Updates user details by processing the user form data and updating the last download date.
     * 
     * @param array $data The user data from the form.
     * @return int|null The user ID if the update is successful, otherwise null.
     */
    protected function updateUserDetails($data) {
        $userId = $this->championController->updateChampionFromForm($data);
        if ($userId) {
            $this->championController->updateLastDownloadDate($userId);
        }
        return $userId;
    }

    /**
     * Handles the mailing lists by splitting them, checking for requested tips,
     * and creating a download record.
     * 
     * @param array $data The user data including selected mailing lists.
     * @param int $userId The ID of the user.
     * @param int $materialID The ID of the material.
     * @return void
     */
    protected function handleMailingLists($data, $userId, $materialID) {
        $mailingLists = $this->splitMailingLists($data['selected_mail_lists']);
        $tips = $mailingLists['tips'] ? time() : null;

        $rawData = [
            'champion_id' => $userId,
            'file_name' => $data['file'],
            'file_id' => $materialID,
            'requested_tips' => $tips,
        ];
        $this->downloadController->createDownloadRecord($rawData);
    }

    /**
     * Splits a comma-separated mailing list string into an associative array.
     * 
     * @param string $selectedMailLists The mailing list string, delimited by commas.
     * @return array Associative array where keys are mailing list names, and values are true.
     */
    protected function splitMailingLists($selectedMailLists) {
        // Split the string by the comma delimiter
        $mailingListsArray = explode(',', $selectedMailLists);

        // Convert the array to an associative array with the value set to true
        return array_fill_keys($mailingListsArray, true);
    }

    /**
     * Returns an error response in JSON format.
     * 
     * @param string $message The error message to return.
     * @return string JSON-encoded error response.
     */
    protected function returnError($message) {
        return json_encode([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Returns a success response with the file URL in JSON format.
     * 
     * @param string $file The file name to include in the response.
     * @return string JSON-encoded success response with the file URL.
     */
    protected function returnSuccess($file) {
        $file_url = URL_RESOURCES . $file;
        return json_encode(['success' => true, 'file_url' => $file_url]);
    }
}
