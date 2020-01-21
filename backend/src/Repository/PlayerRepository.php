<?php

namespace App\Repository;

use App\Entity\Player;
use App\Resource\AddPlayerRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function createFrom(AddPlayerRequest $request): Player
    {
        return (new Player())
            ->setDivision($request->division)
            ->setElo($request->elo)
            ->setName($request->name)
            ->setPlayerId($request->playerId)
            ->setLeague($request->league)
            ->setWins($request->wins)
            ->setLoses($request->loses);
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

    /**
     * @param array $ids
     * @return Player[]|null
     */
    public function findMultipleByIds(array $ids): ?array
    {
        return $this->findBy([
            'id' => $ids
        ]);
    }
}
