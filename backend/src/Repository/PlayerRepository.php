<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @param int $id
     * @return Player
     */
    public function findById(int $id): ?Player
    {
        return $this->findOneBy([
            'id' => $id
        ]);
    }

    /**
     * @param string $name
     * @return Player
     */
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
}
