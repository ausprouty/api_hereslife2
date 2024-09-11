<?php
namespace App\Controllers\Materials;

use App\Models\Materials\SpiritModel;
use App\Services\Materials\ResourceService;

class SpiritController {

    private $spiritModel;
    private $resourceService;

    public function __construct(
        SpiritModel $spiritModel, 
        ResourceService $resourceService
        ) {
        $this->spiritModel = $spiritModel;
        $this->resourceService = $resourceService;
    }

    public function getTitlesByLanguageName() {
        return $this->spiritModel->getTitlesByLanguageName();
    }

    public function getByLanguageName($languageName) {
        return $this->spiritModel->getByLanguageName($languageName);
    }

    public function getSpiritTextByLanguage($languageName) {
        $lc_language = strtolower($languageName);
        $fileName = 'spirit/' . $lc_language . '/default.htm';
        return $this->resourceService->getResourceByFilename($fileName);
    }
}
