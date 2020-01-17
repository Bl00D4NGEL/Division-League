<?php

namespace App\Models;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\Resource\RegisterRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class RegisterModel
{
    private const USER_ALREADY_EXISTS = 'User already exists';
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        if (!$registerRequest->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }

        if ($this->doesUserAlreadyExist($registerRequest->user)) {
            return new ErrorResponse(self::USER_ALREADY_EXISTS);
        }

        $user = new User();
        $user->setLoginName($registerRequest->user)
            ->setRole($registerRequest->role)
            ->setPassword($registerRequest->password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        if ($user->getId() === null) {
            return new ErrorResponse(ErrorResponse::ERROR_PERSISTING_DATA);
        }

        return new SuccessResponse(sprintf('New user created with id %s', $user->getId()));
    }

    private function doesUserAlreadyExist(string $user) {
        return null !== $this->userRepository->findOneBy(['loginName' => $user]);
    }
}