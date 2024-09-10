<?php

namespace App\Controllers\People;

use App\Repositories\ChampionRepository;
use App\Models\People\Champion;

class ChampionController
{
    private $championRepository;

    public function __construct(ChampionRepository $championRepository)
    {
        $this->championRepository = $championRepository;
    }

    public function updateChampionFromForm($formData)
    {
        $champion = $this->championRepository->findByEmail($formData['email']);

        if (!$champion) {
            $champion = new Champion($formData);
        } else {
            // Update the existing champion with form data
            foreach ($formData as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (method_exists($champion, $setter)) {
                    $champion->$setter($value);
                }
            }
        }

        $this->championRepository->save($champion);
        return $champion->getCid();
    }

    public function updateLastDownloadDate($userId)
    {
        $champion = $this->championRepository->findByCid($userId);
        if ($champion) {
            $champion->setLastDownloadDate(Now());
            if (!$champion->getFirstDownloadDate()) {
                $champion->setFirstDownloadDate(Now());
            }
            $this->championRepository->save($champion);
        }
    }
}
