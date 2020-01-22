<?php

namespace App\Tests\Models;

use App\Entity\Player;
use App\Models\PlayerModel;
use App\Repository\PlayerRepository;
use App\Resource\AddPlayerRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PlayerModelTest extends TestCase
{
    /** @var PlayerRepository|MockObject */
    private $playerRepository;

    /** @var EntityManager|MockObject */
    private $em;

    /** @var PlayerModel */
    private $playerModel;

    /** @var Player|MockObject */
    private $player;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->player = $this->createMock(Player::class);

        $this->buildPlayerModel();
    }

    public function testAddPlayerReturnsErrorResponseIfRequestIsInvalid()
    {
        /** @var AddPlayerRequest|MockObject $request */
        $request = $this->createMock(AddPlayerRequest::class);
        $request->expects($this->once())->method('isValid')->willReturn(false);

        $result = $this->playerModel->addPlayer($request);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedResponse = new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    public function testAddPlayerReturnsErrorResponseIfPlayerAlreadyExistsByName()
    {
        $request = $this->createDummyAddPlayerRequest();

        $this->playerRepository->expects($this->once())->method('findByName')->with($request->name)->willReturn($this->player);
        $this->buildPlayerModel();

        $result = $this->playerModel->addPlayer($request);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedResponse = new ErrorResponse(sprintf(ErrorResponse::PLAYER_DOES_ALREADY_EXIST, $request->name));
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    public function testAddPlayerReturnsErrorResponseIfPlayerAlreadyExistsByPlayerId()
    {
        $request = $this->createDummyAddPlayerRequest();

        $this->playerRepository->expects($this->once())->method('findByName')->with($request->name)->willReturn(null);
        $this->playerRepository->expects($this->once())->method('findByPlayerId')->with($request->playerId)->willReturn($this->player);

        $this->buildPlayerModel();

        $result = $this->playerModel->addPlayer($request);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedResponse = new ErrorResponse(sprintf(ErrorResponse::PLAYER_DOES_ALREADY_EXIST, $request->name));
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    public function testAddPlayerReturnsSuccessResponseIfPlayerIsCreated()
    {
        $request = $this->createDummyAddPlayerRequest();

        $this->player->expects($this->once())->method('getId')->willReturn(1);
        $this->playerRepository->expects($this->once())->method('findByName')->with($request->name)->willReturn(null);
        $this->playerRepository->expects($this->once())->method('findByPlayerId')->with($request->playerId)->willReturn(null);
        $this->playerRepository->expects($this->once())->method('createFrom')->with($request)->willReturn($this->player);

        $this->em->expects($this->once())->method('persist')->with($this->player);
        $this->em->expects($this->once())->method('flush');

        $this->buildPlayerModel();

        $result = $this->playerModel->addPlayer($request);

        $this->assertInstanceOf(SuccessResponse::class, $result);
        $expectedResponse = new SuccessResponse([
            'playerId' => 1
        ]);
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    public function testGetPlayerAllEmptyResponse()
    {
        $this->playerRepository->expects($this->once())->method('findAll')->willReturn([]);
        $this->buildPlayerModel();

        $result = $this->playerModel->getPlayerAll();

        $this->assertInstanceOf(SuccessResponse::class, $result);
        $expectedResponse = new SuccessResponse([]);
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    public function testGetPlayerAll()
    {
        /** @var Player|MockObject $player */
        $player = $this->createMock(Player::class);
        $player->expects($this->once())->method('asArray')->willReturn([
            'test' => 'value'
        ]);

        $this->playerRepository->expects($this->once())->method('findAll')->willReturn([$player]);
        $this->buildPlayerModel();

        $result = $this->playerModel->getPlayerAll();

        $this->assertInstanceOf(SuccessResponse::class, $result);
        $expectedResponse = new SuccessResponse([[
            'test' => 'value'
        ]]);
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    /**
     * @return AddPlayerRequest
     */
    private function createDummyAddPlayerRequest(): AddPlayerRequest
    {
        $request = new AddPlayerRequest();
        $request->name = 'name';
        $request->division = 'division';
        $request->playerId = 123;
        $request->league = 'league';
        return $request;
    }

    private function buildPlayerModel(): void
    {
        $this->playerModel = new PlayerModel($this->em, $this->playerRepository);
    }
}
