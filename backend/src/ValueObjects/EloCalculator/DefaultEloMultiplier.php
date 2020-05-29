<?php declare(strict_types=1);

namespace App\ValueObjects\EloCalculator;

class DefaultEloMultiplier implements EloMultiplier
{
    public function getWinFactor(): float
    {
        return 1.25;
    }

    public function getLoseFactor(): float
    {
        return 0.75;
    }
}
