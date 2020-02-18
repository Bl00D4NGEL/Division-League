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
}
