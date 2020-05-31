<?php

namespace App\Repository;

use App\Entity\Proof;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Proof|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proof|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proof[]    findAll()
 * @method Proof[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProofRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proof::class);
    }

    /**
     * @param string[] $proofUrls
     * @return string[]
     */
    public function getProofsFromUrls(array $proofUrls): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select(
                'p.url'
            )
            ->where(
                $qb->expr()->in('p.url', $proofUrls)
            );

        $result = $qb->getQuery()->getArrayResult();
        if (0 < count($result)) {
            return array_map(static function(array $r) { return $r['url']; }, $result);
        }
        return [];
    }
}
