<?php

namespace App\Factory;

use App\Entity\Player;
use App\Entity\Roster;
use App\Entity\Team;
use App\Factory\Exceptions\PlayerIdRequiredForRosterException;
use App\Factory\Exceptions\TeamIdRequiredForRosterException;

class RosterFactory
{
    /**
     * @param Team $team
     * @param Player[] $players
     * @return Roster[]
     */
    public function createRostersFromTeamAndPlayers(Team $team, array $players): array
    {
        if (null === $team->getId()) {
            throw new TeamIdRequiredForRosterException();
        }
        $rosters = [];
        foreach ($players as $player) {
            if (null === $player->getId()) {
                throw new PlayerIdRequiredForRosterException();
            }
            $roster = new Roster();
            $roster
                ->setTeam($team->getId())
                ->setPlayer($player->getId());
            $rosters[] = $roster;
        }
        return $rosters;
    }
}
