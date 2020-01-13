<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Roster;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Roster|null find($id, $lockMode = null, $lockVersion = null)
 * @method Roster|null findOneBy(array $criteria, array $orderBy = null)
 * @method Roster[]    findAll()
 * @method Roster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RosterRepository extends ServiceEntityRepository
{
    /** @var TeamRepository */
    private $teamRepository;

    /** @var PlayerRepository */
    private $playerRepository;

    public function __construct(ManagerRegistry $registry, TeamRepository $teamRepository, PlayerRepository $playerRepository)
    {
        parent::__construct($registry, Roster::class);
        $this->teamRepository = $teamRepository;
        $this->playerRepository = $playerRepository;
    }

    /**
     * @param int $playerId
     * @return Team[]
     */
    public function getTeamsForPlayer(int $playerId): array
    {
        $teams = [];
        $result = $this->findBy(['player' => $playerId]);
        if ($result !== null) {
            foreach ($result as $roster) {
                $team = $this->teamRepository->find($roster->getTeam());
                foreach ($this->getPlayersForTeam($team->getId()) as $player) {
                    $team->addPlayer($player);
                }
                $teams[] = $team;
            }
        }
        return $teams;
    }

    /**
     * @param int $teamId
     * @return Player[]
     */
    public function getPlayersForTeam(int $teamId): array
    {
        $players = [];
        $result = $this->findBy(['team' => $teamId]);
        if ($result !== null) {
            foreach ($result as $roster) {
                $players[] = $this->playerRepository->find($roster->getPlayer());
            }
        }
        return $players;
    }

    public function getSoloTeamForPlayer(Player $player): ?Team
    {
        $soloTeam = null;
        foreach ($this->getTeamsForPlayer($player->getId()) as $team) {
            if ($team->isSoloTeam()) {
                $soloTeam = $team;
                break;
            }
        }
        return $soloTeam;
    }

    /**
     * @param Player $player
     * @return Team
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createSoloTeamForPlayer(Player $player): Team
    {
        $team = new Team();
        $team->setName($player->getName());
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();

        $soloRoster = new Roster();
        $soloRoster
            ->setPlayer($player->getId())
            ->setTeam($team->getId());
        $this->getEntityManager()->persist($soloRoster);
        return $team;
    }


    /**
     * @param int[] $playerIds
     * @return Team|null
     */
    public function getTeamForPlayers(array $playerIds): ?Team
    {
        if (count($playerIds) === 1) {
            return $this->getSoloTeamForPlayer($this->playerRepository->find($playerIds[0]));
        }
        $qb = $this->createQueryBuilder('r');
        $qb->select('r')
            ->where($qb->expr()->in('r.player', $playerIds))
            ->groupBy('r.team')
            ->having(
                $qb->expr()->andX(
                    $qb->expr()->eq(
                        $qb->expr()->count('r.player'),
                        count($playerIds)
                    ),
                    $qb->expr()->eq(
                        'SUM(r.player)',
                        array_sum($playerIds)
                    )
                )
            );

        /** @var Roster[]|null $result */
        $result = $qb->getQuery()->execute();
        if ($result === null || count($result) === 0 || count($result) > 1) {
            // TODO: If the result is > 1 something has gone wrong
            // Add logging
            return null;
        }
        return $this->teamRepository->find($result[0]->getTeam());
    }

    /**
     * @param array $playerIds
     * @param string|null $teamName
     * @return Team
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createTeamForPlayers(array $playerIds, ?string $teamName): Team
    {
        $players = [];
        foreach ($playerIds as $playerId) {
            $players[] = $this->playerRepository->find($playerId);
        }
        if ($teamName === null || empty($teamName)) {
            $teamName = implode(', ', array_map(static function (Player $player) {
                return $player->getName();
            }, $players));
        }

        $team = new Team();
        $team->setName($teamName);
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();

        /** @var Player $player */
        foreach ($players as $player) {
            $team->addPlayer($player);
            $roster = new Roster();
            $roster->setTeam($team->getId())
                ->setPlayer($player->getId());
            $this->getEntityManager()->persist($roster);
        }
        return $team;
    }
}
