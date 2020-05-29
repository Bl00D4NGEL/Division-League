<?php declare(strict_types=1);

namespace App\ValueObjects\Match;

use DateTimeImmutable;

final class MatchResult
{
    private int $winnerGain;
    private int $loserLoss;
    private DateTimeImmutable $creationTime;

    public function __construct(int $winnerGain, int $loserLoss)
    {
        $this->winnerGain = $winnerGain;
        $this->loserLoss = $loserLoss;
        $this->creationTime = new DateTimeImmutable();
    }

    public function winnerGain(): int
    {
        return $this->winnerGain;
    }

    public function loserLoss(): int
    {
        return $this->loserLoss;
    }

    public function creationTime(): DateTimeImmutable
    {
        return $this->creationTime;
    }
}
