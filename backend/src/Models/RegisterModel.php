<?php

namespace App\Models;

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\Resource\RegisterRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterModel
{
    public const USER_ALREADY_EXISTS = 'User already exists';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserFactory */
    private $userFactory;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, UserFactory $userFactory)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        if (!$registerRequest->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }

        if ($this->doesUserAlreadyExist($registerRequest->user)) {
            return new ErrorResponse(self::USER_ALREADY_EXISTS);
        }

        $user = $this->userFactory->createFromRequest($registerRequest);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new SuccessResponse(sprintf('New user created with id %s', $user->getId()));
    }

    private function doesUserAlreadyExist(string $user): bool {
        return null !== $this->userRepository->findOneBy(['loginName' => $user]);
    }
}
