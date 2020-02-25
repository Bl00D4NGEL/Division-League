<?php

namespace App\ValueObjects\EloCalculator;

class EloCalculator
{
    private const MAX_K_FACTOR = 16;

    /** @var int */
    private $winnerElo;

    /** @var int */
    private $loserElo;
    /** @var EloMultiplier */
    private $eloMultiplier;

    public function __construct(int $winnerElo, int $loserElo, EloMultiplier $eloMultiplier)
    {
        $this->winnerElo = $winnerElo;
        $this->loserElo = $loserElo;
        $this->eloMultiplier = $eloMultiplier;
    }

    public function getEloChangeForLoser(): int
    {
        return -ceil($this->getKFactor() * $this->calculateLoseChance() * $this->eloMultiplier->getLoseFactor());
    }

    public function getEloChangeForWinner(): int
    {
        return ceil($this->getKFactor() * $this->calculateWinChance() * $this->eloMultiplier->getWinFactor());
    }

    private function getKFactor(): float
    {
        $kFactor = ($this->loserElo + $this->winnerElo) / 100;
        if ($kFactor < self::MAX_K_FACTOR) {
            $kFactor = self::MAX_K_FACTOR;
        }
        return $kFactor;
    }

    private function calculateWinChance()
    {
        return 1 - $this->getQPointsFor($this->winnerElo) / ($this->getQPointsFor($this->winnerElo) + $this->getQPointsFor($this->loserElo));
    }

    private function calculateLoseChance() {
        return $this->calculateWinChance();
    }

    private function getQPointsFor(int $elo)
    {
        return 10 ** ($elo / 400);
    }
}
