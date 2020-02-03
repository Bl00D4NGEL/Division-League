<?php

namespace App\Tests;

use App\Entity\Player;

class TestObjectFactory
{
    public static function createPlayer(
        string $name,
        ?int $playerId = 123,
        ?int $loses = 0,
        ?int $wins = 0,
        ?int $elo = 1000,
        ?string $division = 'division',
        ?string $league = 'league'
    ): Player {
        $player = new Player();
        $player->setName($name)
            ->setLoses($loses)
            ->setWins($wins)
            ->setElo($elo)
            ->setPlayerId($playerId)
            ->setDivision($division)
            ->setLeague($league);
        return $player;
    }
}
