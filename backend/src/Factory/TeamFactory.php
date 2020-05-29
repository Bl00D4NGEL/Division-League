<?php

namespace App\Factory;

use App\Entity\Player;
use App\Entity\Team;
use App\Factory\Exceptions\PlayerNotFoundException;
use App\Repository\PlayerRepository;
use App\Repository\RosterRepository;
use App\Repository\TeamRepository;

class TeamFactory
{
    /** @var RosterRepository */
    private $rosterRepository;

    /** @var TeamRepository */
    private $teamRepository;

    /** @var PlayerRepository */
    private $playerRepository;

    public function __construct(RosterRepository $rosterRepository, TeamRepository $teamRepository, PlayerRepository $playerRepository)
    {
        $this->rosterRepository = $rosterRepository;
        $this->teamRepository = $teamRepository;
        $this->playerRepository = $playerRepository;
    }

    public function createFromId(int $teamId): ?Team
    {
        $team = $this->teamRepository->find($teamId);
        if (null === $team) {
            return null;
        }

        $team->addPlayers($this->rosterRepository->getPlayersForTeam($team));
        return $team;
    }

    /**
     * @param int[] $playerIds
     * @return Team
     * @throws PlayerNotFoundException
     */
    public function createTeamFromPlayerIds(array $playerIds): Team
    {
        $players = [];
        foreach ($playerIds as $playerId) {
            $player = $this->playerRepository->find($playerId);
            if (null === $player) {
                throw new PlayerNotFoundException($playerId);
            }
            $players[] = $player;
        }

        $teamName = implode(', ', array_map(static function (Player $player) {
            return $player->getName();
        }, $players));

        $team = new Team();
        $team->setName($teamName);
        $team->addPlayers($players);
        return $team;
    }
}
