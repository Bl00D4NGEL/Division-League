<?php

namespace App\Repository;

use App\Entity\User;
use App\Resource\RegisterRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
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

    public function createFrom(RegisterRequest $registerRequest) {
        $user = new User();
        $user->setLoginName($registerRequest->user)
            ->setRole($registerRequest->role)
            ->setPassword($registerRequest->password);
        return $user;
    }
}
