<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class HistoryRepository extends ServiceEntityRepository
{
    /** @var RosterRepository */
    private $rosterRepository;

    public function __construct(ManagerRegistry $registry, RosterRepository $rosterRepository)
    {
        parent::__construct($registry, History::class);
        $this->rosterRepository = $rosterRepository;
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
}
