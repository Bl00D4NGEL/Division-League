<?php

namespace App\ValueObjects\EloCalculator;

class EloCalculator
{
    private const MAX_K_FACTOR = 16;

    private EloMultiplier $eloMultiplier;

    public function __construct(EloMultiplier $eloMultiplier)
    {
        $this->eloMultiplier = $eloMultiplier;
    }

    public function calculate(int $winnerElo, int $loserElo): EloCalculationResult {
        return new EloCalculationResult(
            $this->getEloChangeForWinner($winnerElo, $loserElo),
            $this->getEloChangeForLoser($winnerElo, $loserElo)
        );
    }

    private function getEloChangeForLoser(int $winnerElo, int $loserElo): int
    {
        return -ceil($this->getKFactor($winnerElo, $loserElo) * $this->calculateLoseChance($winnerElo, $loserElo) * $this->eloMultiplier->getLoseFactor());
    }

    private function getEloChangeForWinner(int $winnerElo, int $loserElo): int
    {
        return ceil($this->getKFactor($winnerElo, $loserElo) * $this->calculateWinChance($winnerElo, $loserElo) * $this->eloMultiplier->getWinFactor());
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

    private function calculateLoseChance(int $winnerElo, int $loserElo) {
        return $this->calculateWinChance($winnerElo, $loserElo);
    }

    private function getQPointsFor(int $elo)
    {
        return 10 ** ($elo / 400);
    }
}
