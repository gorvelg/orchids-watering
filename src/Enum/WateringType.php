<?php

namespace App\Enum;

enum WateringType: string {
    case GROW = "grow";
    case WATER = "water";
    case BLOOM = "bloom";

    public function toFrench(): string
    {
        return match ($this) {
            self::GROW => 'grow',
            self::WATER => 'eau',
            self::BLOOM => 'bloom'
        };
    }


}
