<?php declare(strict_types=1);

namespace App\ValueObjects\Match;

final class Team
{
    /** @var Player[] */
    private array $players;

    /**
     * @param Player[] $players
     */
    public function __construct(array $players)
    {
        $this->players = $players;
    }

    public function getAverageElo(): int
    {
        return (int)ceil(
            array_sum(
                array_map(function (Player $player) {
                    return $player->elo();
                }, $this->players)
            ) / count($this->players)
        );
    }
}
