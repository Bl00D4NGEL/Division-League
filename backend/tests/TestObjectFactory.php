<?php

namespace App\Tests;

use App\Entity\History;
use App\Entity\Player;
use App\Entity\Roster;
use App\Entity\Team;
use DateTime;

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
    ): Player
    {
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

    public static function createTeam(string $name): Team
    {
        $team = new Team();
        $team->setName($name);
        return $team;
    }

    public static function createRoster(int $teamId, int $playerId): Roster
    {
        $roster = new Roster();
        $roster->setTeam($teamId)
            ->setPlayer($playerId);
        return $roster;
    }

    public static function createHistory(
        int $winnerTeamId, int $loserTeamId, DateTime $createTime = null
    ): History
    {
        $history = new History();
        $history->setWinner($winnerTeamId)
            ->setLoser($loserTeamId)
            ->setWinnerGain(1)
            ->setLoserGain(1)
            ->setIsSweep(false);

        if (null === $createTime) {
            $createTime = new DateTime();
        }
        $history->setCreateTime($createTime);

        return $history;
    }
}
