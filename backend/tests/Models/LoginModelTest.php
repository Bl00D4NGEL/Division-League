<?php

namespace App\Tests\Models;

use App\Entity\User;
use App\Models\LoginModel;
use App\Repository\UserRepository;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\Resource\LoginRequest;
use App\ValueObjects\SessionAuthorization;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoginModelTest extends TestCase
{
    /** @var EntityManager|MockObject */
    private $em;

    /** @var UserRepository|MockObject */
    private $userRepository;

    /** @var LoginModel */
    private $loginModel;

    /** @var SessionAuthorization|MockObject */
    private $sessionAuthorization;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->sessionAuthorization = $this->createMock(SessionAuthorization::class);
        $this->buildLoginModel();
    }

    public function testLoginShouldReturnErrorResponseIfRequestIsInvalid(): void
    {
        /** @var LoginRequest|MockObject $loginRequest */
        $loginRequest = $this->createMock(LoginRequest::class);
        $loginRequest->expects($this->once())->method('isValid')->willReturn(false);

        $this->sessionAuthorization->expects($this->once())->method('unauthorize');
        $this->buildLoginModel();

        $result = $this->loginModel->login($loginRequest);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedError = new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        $this->assertSame($expectedError->getContent(), $result->getContent());
    }

    public function testLoginShouldReturnInvalidCredentialsIfPasswordVerifyFails(): void
    {
        $loginRequest = new LoginRequest();
        $loginRequest->user = 'abc';
        $loginRequest->password = 'def';

        /** @var User|MockObject $user */
        $user = $this->createMock(User::class);
        $user
            ->expects($this->once())
            ->method('verifyPassword')
            ->with($loginRequest->password)
            ->willReturn(false);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([
                'loginName' => $loginRequest->user,
            ])
            ->willReturn($user);

        $this->sessionAuthorization->expects($this->once())->method('unauthorize');

        $this->buildLoginModel();

        $result = $this->loginModel->login($loginRequest);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedError = new ErrorResponse(ErrorResponse::INVALID_CREDENTIALS_SENT);
        $this->assertSame($expectedError->getContent(), $result->getContent());
    }

    public function testLoginShouldReturnInvalidCredentialsIfUserIsNotFound(): void
    {
        /** @var LoginRequest|MockObject $loginRequest */
        $loginRequest = $this->createMock(LoginRequest::class);
        $loginRequest->expects($this->once())->method('isValid')->willReturn(true);

        /** @var UserRepository|MockObject $userRepository */
        $this->userRepository->expects($this->once())->method('findOneBy')->with([
            'loginName' => null
        ])->willReturn(null);

        $this->sessionAuthorization->expects($this->once())->method('unauthorize');

        $this->buildLoginModel();

        $result = $this->loginModel->login($loginRequest);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedError = new ErrorResponse(ErrorResponse::INVALID_CREDENTIALS_SENT);
        $this->assertSame($expectedError->getContent(), $result->getContent());
    }

    public function testLoginSuccess(): void
    {
        $loginRequest = new LoginRequest();
        $loginRequest->user = 'abc';
        $loginRequest->password = 'abc';

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('verifyPassword')->willReturn(true);
        $user->expects($this->once())->method('asArray')->willReturn(['test' => 'value']);

        $this->userRepository->expects($this->once())->method('findOneBy')->with([
            'loginName' => 'abc'
        ])->willReturn($user);

        $this->sessionAuthorization->expects($this->once())->method('unauthorize');
        $this->sessionAuthorization->expects($this->once())->method('authorize');
        $this->buildLoginModel();

        $result = $this->loginModel->login($loginRequest);
        $this->assertInstanceOf(SuccessResponse::class, $result);
        $expectedError = new SuccessResponse([
            'isLoggedIn' => true,
            'user' => [
                'test' => 'value'
            ]
        ]);
        $this->assertSame($expectedError->getContent(), $result->getContent());
    }

    private function buildLoginModel(): void
    {
        $this->loginModel = new LoginModel($this->em, $this->userRepository, $this->sessionAuthorization);
    }
}
