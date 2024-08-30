<?php
namespace App\Services;

use App\Controllers\Materials\MaterialController;
use App\Controllers\People\ChampionController;
use App\Controllers\Materials\DownloadController;
use InvalidArgumentException;



class UserMaterialService {

    protected $materialController;
    protected $championController;
    protected $downloadController;

    public function __construct(
  
        MaterialController $materialController,
        ChampionController $championController,
        DownloadController $downloadController
    ) {

        $this->materialController = $materialController;
        $this->championController = $championController;
        $this->downloadController = $downloadController;
    }

    public function handleUserMaterialDownload($data) {

        writeLog('downloadMaterialsUpdateUser-32',  $data);
        // Find the material ID and manage download
        $materialID = $this->processMaterial($data);
        writeLog('downloadMaterialsUpdateUser-34',  $materialID);
        if (!$materialID) {
            return $this->returnError('File not found');
        }

        // Update user details and last download date
        $userId = $this->updateUserDetails($data);
        writeLog('downloadMaterialsUpdateUser-41',  $userId );
        if (!$userId) {
            return $this->returnError('Failed to update user details');
        }

        // Handle mailing lists and download record
        $this->handleMailingLists($data, $userId, $materialID);

        // Return the file URL
        return $this->returnSuccess($data['file']);
    }

    protected function getInputData() {
        $input = $this->sanitizeInputService->sanitize();
        if (!$input['success']) {
            return null;
        }
        return $input['data'];
    }

    protected function processMaterial($data) {
        $materialID = $this->materialController->getIdByFileName($data['file']);
        if ($materialID) {
            $this->materialController->getAndIncrementDownloads($materialID);
        }
        return $materialID;
    }

    protected function updateUserDetails($data) {
        $userId = $this->championController->updateChampionFromForm($data);
        if ($userId) {
            $this->championController->updateLastDownloadDate($userId);
        }
        return $userId;
    }

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

    protected function splitMailingLists($selectedMailLists) {
        // Split the string by the comma delimiter
        $mailingListsArray = explode(',', $selectedMailLists);

        // Convert the array to an associative array with the value set to true
        return array_fill_keys($mailingListsArray, true);
    }

    protected function returnError($message) {
        return json_encode([
            'success' => false,
            'message' => $message
        ]);
    }

    protected function returnSuccess($file) {
        $file_url = URL_RESOURCES . $file;
        return json_encode(['success' => true, 'file_url' => $file_url]);
    }
}
