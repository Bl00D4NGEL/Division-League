<?php

namespace App\Repository;

use App\Entity\User;
use App\NullObjects\NullUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param mixed $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return User|object
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        if (null === $id) {
            return new NullUser();
        }
        return parent::find($id, $lockMode, $lockVersion);
    }

}
