<?php


namespace App\ValueObjects\EloCalculator;


class SweepEloMultiplier implements EloMultiplier
{

    public function getWinFactor(): float
    {
        return 1.5;
    }

    public function getLoseFactor(): float
    {
        return 0.875;
    }
}
