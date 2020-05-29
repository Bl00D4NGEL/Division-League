<?php declare(strict_types=1);

namespace App\ValueObjects\EloCalculator;

final class EloCalculationResult
{
    private int $eloGain;
    private int $eloLoss;

    public function __construct(int $eloGain, int $eloLoss)
    {
        $this->eloGain = $eloGain;
        $this->eloLoss = $eloLoss;
    }

    public function eloGain(): int
    {
        return $this->eloGain;
    }

    public function eloLoss(): int
    {
        return $this->eloLoss;
    }
}
