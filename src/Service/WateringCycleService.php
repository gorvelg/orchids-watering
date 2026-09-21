<?php

namespace App\Service;

use App\Entity\Orchid;
use App\Enum\WateringType;

class WateringCycleService
{
    private const CYCLE = [
        WateringType::GROW,
        WateringType::WATER,
        WateringType::BLOOM,
        WateringType::WATER,
    ];

    public function getNextStep(Orchid $orchid): int
    {
        $lastWatering = $orchid->getWaterings()->first();

        if (!$lastWatering) {
            return 0;
        }

        return ($lastWatering->getCycleStep() + 1) % count(self::CYCLE);
    }

    public function getNextType(Orchid $orchid): WateringType
    {
        return self::CYCLE[$this->getNextStep($orchid)];
    }
}
