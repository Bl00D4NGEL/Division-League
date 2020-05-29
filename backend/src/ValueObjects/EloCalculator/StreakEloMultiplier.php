<?php declare(strict_types=1);

namespace App\ValueObjects\EloCalculator;

final class StreakEloMultiplier implements EloMultiplier
{
    private const STREAK_WIN_MULTIPLIER = 0.1;
    private const STREAK_LOSE_MULTIPLIER = 0.05;

    private EloMultiplier $baseMultiplier;
    private int $streakLength;

    public function __construct(EloMultiplier $baseMultiplier, int $streakLength)
    {
        $this->baseMultiplier = $baseMultiplier;
        $this->streakLength = $streakLength;
    }

    public function getWinFactor(): float
    {
        return $this->baseMultiplier->getWinFactor() * (1 + $this->streakLength * self::STREAK_WIN_MULTIPLIER);
    }

    public function getLoseFactor(): float
    {
        return $this->baseMultiplier->getLoseFactor() * (1 + $this->streakLength * self::STREAK_LOSE_MULTIPLIER);
    }
}
