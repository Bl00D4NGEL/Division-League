<?php

namespace App\Tests\Models;

use App\Factory\RosterFactory;
use App\Factory\TeamFactory;
use App\Tests\TestObjectFactory;
use App\ValueObjects\HistoryFormatter;
use App\Entity\Team;
use App\Models\HistoryModel;
use App\Repository\HistoryRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HistoryModelTest extends TestCase
{
    const WINNER_IDS = [1];
    const LOSER_IDS = [2];

    /** @var EntityManager|MockObject */
    private $em;

    /** @var HistoryRepository|MockObject */
    private $historyRepository;

    /** @var HistoryFormatter|MockObject */
    private $historyFormatter;

    /** @var HistoryModel */
    private $historyModel;

    /** @var TeamFactory|MockObject */
    private $teamFactory;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->historyRepository = $this->createMock(HistoryRepository::class);
        $this->historyFormatter = $this->createMock(HistoryFormatter::class);
        $this->teamFactory = $this->createMock(TeamFactory::class);
        $this->buildHistoryModel();
    }

    public function testAddHistoryReturnsErrorResponseOnInvalidRequest(): void
    {
        /** @var AddHistoryRequest|MockObject $request */
        $request = $this->createMock(AddHistoryRequest::class);
        $request->expects($this->once())->method('isValid')->willReturn(false);

        $result = $this->historyModel->addHistory($request);

        $this->assertInstanceOf(ErrorResponse::class, $result);
        $expectedResponse = new ErrorResponse(ErrorResponse::INVALID_DATA_SENT);
        $this->assertSame($expectedResponse->getContent(), $result->getContent());
    }

    public function testAddHistoryReturnsSuccessResponse(): void
    {
        $request = $this->createDummyAddHistoryRequest();

        /** @var Team|MockObject $winnerTeam */
        $winnerTeam = $this->createMock(Team::class);
        $winnerTeam->method('getId')->willReturn(1);
        $winnerTeam->method('getPlayers')->willReturn([TestObjectFactory::createPlayer('Player 1')]);
        /** @var Team|MockObject $loserTeam */
        $loserTeam = $this->createMock(Team::class);
        $loserTeam->method('getId')->willReturn(2);
        $loserTeam->method('getPlayers')->willReturn([TestObjectFactory::createPlayer('Player21')]);
        $this->teamFactory->expects($this->at(0))->method('createTeamFromPlayerIds')->with(self::WINNER_IDS)->willReturn($winnerTeam);
        $this->teamFactory->expects($this->at(1))->method('createTeamFromPlayerIds')->with(self::LOSER_IDS)->willReturn($loserTeam);

        $result = $this->historyModel->addHistory($request);

        $this->assertInstanceOf(SuccessResponse::class, $result);
    }

    private function buildHistoryModel(): void
    {
        $this->historyModel = new HistoryModel(
            $this->em,
            $this->historyRepository,
            $this->historyFormatter,
            $this->teamFactory,
            $this->createMock(RosterFactory::class)
        );
    }

    private function createDummyAddHistoryRequest(): AddHistoryRequest
    {
        $request = new AddHistoryRequest();
        $request->winner = self::WINNER_IDS;
        $request->loser = self::LOSER_IDS;
        $request->proofUrl = 'proof.url';
        $request->winnerTeamName = 'winnerTeam';
        $request->loserTeamName = 'loserTeam';
        return $request;
    }
}
