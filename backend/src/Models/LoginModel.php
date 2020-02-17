<?php

namespace App\Models;

use App\Repository\UserRepository;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\Resource\LoginRequest;
use App\ValueObjects\SessionAuthorization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var SessionAuthorization */
    private $authorization;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, SessionAuthorization $authorization)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->authorization = $authorization;
    }

    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $this->authorization->unauthorize();
        if (!$loginRequest->isValid()) {
            return new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        }
        $user = $this->userRepository->findOneBy(['loginName' => $loginRequest->user]);
        if ($user === null || !$user->verifyPassword($loginRequest->password)) {
            return new ErrorResponse(ErrorResponse::INVALID_CREDENTIALS_SENT);
        }
        $this->authorization->authorize($user->getId());
        return new SuccessResponse(
            [
                'isLoggedIn' => true,
                'user' => $user->asArray(),
            ]
        );
    }

    public function auth(): JsonResponse
    {
        return new SuccessResponse([
            "isLoggedIn" => $this->authorization->isAuthorized(),
            "user" => $this->userRepository->find($this->authorization->getUserId())->asArray()
        ]);
    }
}
