<?php

namespace App\Repository;

use App\ValueObjects\Match;
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

    public function __construct(ManagerRegistry $registry, RosterRepository $rosterRepository)
    {
        parent::__construct($registry, History::class);
        $this->rosterRepository = $rosterRepository;
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
        $match = new Match();

        $match
            ->setWinner($this->rosterRepository->getOrCreateTeam($request->winner, $request->winnerTeamName))
            ->setLoser($this->rosterRepository->getOrCreateTeam($request->loser, $request->loserTeamName))
            ->setProofUrl($request->proofUrl);
        return $match;
    }
}
