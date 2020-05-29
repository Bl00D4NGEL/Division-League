<?php

declare(strict_types=1);

namespace App\ValueObjects\EloMultiplier;

interface EloMultiplier
{
    public function getWinFactor(): float;
    public function getLoseFactor(): float;
}
