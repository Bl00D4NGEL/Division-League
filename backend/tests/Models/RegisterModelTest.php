<?php
namespace App\Tests\Models;

use App\Entity\User;
use App\Models\RegisterModel;
use App\Repository\UserRepository;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\Resource\RegisterRequest;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegisterModelTest extends TestCase
{
    /** @var EntityManager|MockObject */
    private $em;

    /** @var UserRepository|MockObject */
    private $userRepository;

    /** @var RegisterModel */
    private $registerModel;

    /** @var User|MockObject */
    private $user;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->registerModel = new RegisterModel($this->em, $this->userRepository);
        $this->user = $this->createMock(User::class);
    }


    public function testRegisterReturnsErrorResponseIfRequestIsInvalid(): void
    {
        /** @var RegisterRequest|MockObject $registerRequest */
        $registerRequest = $this->createMock(RegisterRequest::class);
        $registerRequest->expects($this->once())->method('isValid')->willReturn(false);

        $result = $this->registerModel->register($registerRequest);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedError = new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        $this->assertSame($expectedError->getContent(), $result->getContent());
    }

    public function testRegisterReturnsErrorResponseIfUserAlreadyExists(): void
    {
        $registerRequest = $this->createDummyRegisterRequest();
        $this->userRepository->expects($this->once())->method('findOneBy')->with(
            ['loginName' => $registerRequest->user]
        )->willReturn($this->user);
        $this->registerModel = new RegisterModel($this->em, $this->userRepository);

        $result = $this->registerModel->register($registerRequest);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedError = new ErrorResponse(RegisterModel::USER_ALREADY_EXISTS);
        $this->assertSame($expectedError->getContent(), $result->getContent());
    }

    public function testRegisterReturnsSuccessResponseOnAccountCreation(): void
    {
        $this->user->method('getId')->willReturn(1);
        $registerRequest = $this->createDummyRegisterRequest();
        $this->userRepository->expects($this->once())->method('createFrom')->with($registerRequest)->willReturn($this->user);

        $this->em->expects($this->once())->method('persist')->with($this->user);
        $this->em->expects($this->once())->method('flush');
        $this->registerModel = new RegisterModel($this->em, $this->userRepository);

        $result = $this->registerModel->register($registerRequest);

        $this->assertInstanceOf(SuccessResponse::class, $result);
        $expectedResponse = new SuccessResponse('New user created with id 1');
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    /**
     * @return RegisterRequest
     */
    private function createDummyRegisterRequest(): RegisterRequest
    {
        $registerRequest = new RegisterRequest();
        $registerRequest->user = 'user';
        $registerRequest->password = 'password';
        $registerRequest->role = User::ROLES[0];
        return $registerRequest;
    }
}
