<?php declare(strict_types=1);

namespace App\ValueObjects\Match;

final class Player
{
    private int $elo;

    public function __construct(int $elo)
    {
        $this->elo = $elo;
    }

    public function elo(): int
    {
        return $this->elo;
    }
}
