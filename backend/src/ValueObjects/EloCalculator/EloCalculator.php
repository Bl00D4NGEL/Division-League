<?php

namespace App\ValueObjects\EloCalculator;

class EloCalculator
{
    private const MAX_K_FACTOR = 16;

    public function calculateEloChange(int $winnerElo, int $loserElo): int {
        return ceil($this->getKFactor($winnerElo, $loserElo) * $this->calculateWinChance($winnerElo, $loserElo));
    }

    private function getKFactor(int $winnerElo, int $loserElo): float
    {
        $kFactor = ($loserElo + $winnerElo) / 100;
        if ($kFactor < self::MAX_K_FACTOR) {
            $kFactor = self::MAX_K_FACTOR;
        }
        return $kFactor;
    }

    private function calculateWinChance(int $winnerElo, int $loserElo)
    {
        return 1 - $this->getQPointsFor($winnerElo) / ($this->getQPointsFor($winnerElo) + $this->getQPointsFor($loserElo));
    }

    private function getQPointsFor(int $elo)
    {
        return 10 ** ($elo / 400);
    }
}
