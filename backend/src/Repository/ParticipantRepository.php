<?php

namespace App\Repository;

use App\Entity\History;
use App\Entity\Participant;
use App\Entity\Player;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    /**
     * @param Player $player
     * @return DateTimeInterface[]
     */
    public function getHistoryTimesForPlayer(Player $player): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select(
                [
                    'h.creationTime',
                ]
            )
            ->join(Player::class, 'pl', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('pl.id', 'p.player'),
                $qb->expr()->eq('pl.id', ':playerId')
            ))
            ->join(History::class, 'h', Join::WITH, $qb->expr()->eq('h.id', 'p.history'))
            ->setParameter('playerId', $player->getId());

        return array_map(static function(array $result) {
            return $result['creationTime'];
        }, $qb->getQuery()->getArrayResult());
    }
}
