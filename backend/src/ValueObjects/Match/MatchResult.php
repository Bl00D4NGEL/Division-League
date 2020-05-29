<?php declare(strict_types=1);

namespace App\ValueObjects\Match;

use DateTimeImmutable;

final class MatchResult
{
    private DateTimeImmutable $creationTime;
    private int $eloChange;

    public function __construct(int $eloChange)
    {
        $this->creationTime = new DateTimeImmutable();
        $this->eloChange = $eloChange;
    }

    public function creationTime(): DateTimeImmutable
    {
        return $this->creationTime;
    }

    public function eloChange(): int
    {
        return $this->eloChange;
    }
}
