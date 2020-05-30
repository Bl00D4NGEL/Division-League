<?php

namespace App\Repository;

use App\Entity\Player;
use App\Factory\Exceptions\PlayerNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findById(int $id): ?Player
    {
        return $this->findOneBy([
            'id' => $id
        ]);
    }

    public function findByName(string $name): ?Player
    {
        return $this->findOneBy([
            'name' => $name
        ]);
    }

    public function findByPlayerId(int $playerId): ?Player
    {
        return $this->findOneBy([
            'playerId' => $playerId
        ]);
    }

    /**
     * @param int[] $ids
     * @return Player[]|null
     */
    public function findMultipleByIds(array $ids): ?array
    {
        return $this->findBy([
            'id' => $ids
        ]);
    }

    /**
     * @param int $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws Exception
     */
    public function deleteById(int $id): void
    {
        $player = $this->find($id);
        if (null === $player) {
            throw new PlayerNotFoundException($id);
        }
        $player->setDeleted(true);
        $this->getEntityManager()->persist($player);
        $this->getEntityManager()->flush();
    }

    public function getCurrentlyActivePlayers(): ?array {
        return $this->findBy([
            'deleted' => 0
        ]);
    }
}
