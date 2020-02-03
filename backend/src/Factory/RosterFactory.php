<?php

namespace App\Factory;

use App\Entity\Player;
use App\Entity\Roster;
use App\Entity\Team;
use Exception;

class RosterFactory
{
    /**
     * @param Team $team
     * @param Player[] $players
     * @return Roster[]
     * @throws Exception
     */
    public function createRostersFromTeamAndPlayers(Team $team, array $players): array {
        if (null === $team->getId()) {
            throw new Exception('Team needs to have an id for a roster to be able to be created');
        }
        $rosters = [];
        foreach ($players as $player) {
            if (null === $player->getId()) {
                throw new Exception('Player needs to have an id for a roster to be able to be created');
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
