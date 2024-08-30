<?php
namespace App\Controllers\Materials;

use App\Models\Materials\MaterialModel;
use Exception;

class MaterialController {

    private $model;

    public function __construct() {
        $this->model = new MaterialModel();
    }
    public function getIdByFileName($fileName) {
        try {
            $material = $this->model->findByFileName($fileName);
            if (!$material) {
                throw new Exception("Material not found");
            }
            return $material['id'];
        } catch (Exception $e) {
            writeLogError('MaterialController::getIdByFileName', $e->getMessage());
            return null;
        }
    }
    public function getMaterialById($id) {
        try {
            $material = $this->model->findById($id);
            if (!$material) {
                throw new Exception("Material not found");
            }
            return $material;
        } catch (Exception $e) {
            writeLogError('MaterialController::getMaterialById', $e->getMessage());
            return null;
        }
    }

    public function incrementMaterialDownloads($id) {
        try {
            $result = $this->model->incrementDownloads($id);
            if (!$result) {
                throw new Exception("Failed to increment downloads");
            }
            return true;
        } catch (Exception $e) {
            writeLogError('MaterialController::incrementMaterialDownloads', $e->getMessage());
            return false;
        }
    }

    public function getAndIncrementDownloads($id) {
        try {
            // Retrieve the material by ID
            $material = $this->getMaterialById($id);
            if ($material) {
                // Increment the downloads count
                $this->incrementMaterialDownloads($id);
                return $material;
            } else {
                throw new Exception("Material not found");
            }
        } catch (Exception $e) {
            writeLogError('MaterialController::getAndIncrementDownloads', $e->getMessage());
            return null;
        }
    }
}
