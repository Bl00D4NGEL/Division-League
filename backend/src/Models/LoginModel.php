<?php

namespace App\Models;

use App\Repository\UserRepository;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\Resource\LoginRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function login(LoginRequest $loginRequest): JsonResponse
    {
        if (!$loginRequest->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }
        $user = $this->userRepository->findOneBy(['loginName' => $loginRequest->user]);
        if ($user === null || !$user->verifyPassword($loginRequest->password)) {
            return new ErrorResponse(ErrorResponse::INVALID_CREDENTIALS_SENT);
        }
        return new SuccessResponse(
            [
                'isLoggedIn' => true,
                'user' => $user->asArray()
            ]
        );
    }
}
