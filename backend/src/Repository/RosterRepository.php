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
    /** @var PlayerRepository */
    private $playerRepository;

    public function __construct(ManagerRegistry $registry, PlayerRepository $playerRepository)
    {
        parent::__construct($registry, Roster::class);
        $this->playerRepository = $playerRepository;
    }

    /**
     * @param Team $team
     * @return Player[]
     */
    public function getPlayersForTeam(Team $team): array
    {
        $players = [];
        $result = $this->findBy(['team' => $team->getId()]);
        if (null !== $result) {
            foreach ($result as $roster) {
                $player = $this->playerRepository->find($roster->getPlayer());
                if (null !== $player) {
                    $players[] = $player;
                }
            }
        }
        return $players;
    }
}
