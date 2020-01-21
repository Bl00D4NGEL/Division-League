<?php

namespace App\Repository;

use App\DataObjects\Match;
use App\Entity\History;
use App\Resource\AddHistoryRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    /** @var RosterRepository */
    private $rosterRepository;
    /** @var PlayerRepository */
    private $playerRepository;

    public function __construct(ManagerRegistry $registry, RosterRepository $rosterRepository, PlayerRepository $playerRepository)
    {
        parent::__construct($registry, History::class);
        $this->rosterRepository = $rosterRepository;
        $this->playerRepository = $playerRepository;
    }

    /**
     * @param History $history
     * @param int $limit
     * @return mixed
     */
    public function findWithHistoryEntity(History $history, int $limit = 0)
    {
        $queryBuilder = $this->createQueryBuilder('h');

        foreach ($history->asArray() as $field => $value) {
            if (null !== $value) {
                $queryBuilder->andWhere('h.' . $field . ' = :' . $field)
                    ->setParameter($field, $value);
            }
        }

        if (0 !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @param int $limit The maximum amount of entries to return
     * @return History[]
     */
    public function findLastEntries(int $limit = 100): array
    {
        return $this->findBy([], ['id' => 'DESC'], $limit);
    }

    /**
     * @param AddHistoryRequest $request
     * @return Match
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createMatchFrom(AddHistoryRequest $request): Match {
        $match = new Match($this->rosterRepository, $this->playerRepository);
        $match
            ->setWinner($request->winner, $request->winnerTeamName)
            ->setLoser($request->loser, $request->loserTeamName)
            ->setProofUrl($request->proofUrl);
        return $match;
    }
}
