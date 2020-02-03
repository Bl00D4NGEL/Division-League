<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    /** @var RosterRepository */
    private $rosterRepository;

    public function __construct(ManagerRegistry $registry, RosterRepository $rosterRepository)
    {
        parent::__construct($registry, Team::class);
        $this->rosterRepository = $rosterRepository;
    }

    public function getSoloTeamForPlayer(Player $player): ?Team
    {
        $soloTeam = null;
        foreach ($this->getTeamsForPlayer($player) as $team) {
            if ($team->isSoloTeam()) {
                $soloTeam = $team;
                break;
            }
        }
        return $soloTeam;
    }

    /**
     * @param Player $player
     * @return Team[]
     */
    public function getTeamsForPlayer(Player $player): array
    {
        $teams = [];
        $result = $this->rosterRepository->findBy(['player' => $player->getId()]);
        if (null !== $result) {
            foreach ($result as $roster) {
                $team = $this->buildTeam($roster->getTeam());
                if (null !== $team && 0 < count($team->getPlayers())) {
                    $teams[] = $team;
                }
            }
        }
        return $teams;
    }

    private function buildTeam(int $teamId): ?Team
    {
        $team = $this->find($teamId);
        if (null === $team) {
            return null;
        }

        $team->addPlayers($this->rosterRepository->getPlayersForTeam($team));
        return $team;
    }
}
