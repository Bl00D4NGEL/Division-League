<?php

namespace App\Tests\Models;

use App\Entity\History;
use App\Entity\Team;
use App\Factory\RosterFactory;
use App\Factory\TeamFactory;
use App\Models\HistoryModel;
use App\Repository\HistoryRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\InvalidRequestException;
use App\Tests\TestObjectFactory;
use App\ValueObjects\HistoryFormatter;
use App\ValueObjects\Validator\EloValidator\EloDifferenceValidator;
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

    private function buildHistoryModel(): void
    {
        $this->historyModel = new HistoryModel(
            $this->em,
            $this->teamFactory,
            $this->createMock(RosterFactory::class),
            new EloDifferenceValidator()
        );
    }

    public function testAddHistoryReturnsErrorResponseOnInvalidRequest(): void
    {
        $this->expectException(InvalidRequestException::class);
        /** @var AddHistoryRequest|MockObject $request */
        $request = $this->createMock(AddHistoryRequest::class);
        $request->expects($this->once())->method('isValid')->willReturn(false);

        $this->historyModel->addHistory($request);
    }

    public function testAddHistoryReturnsSuccessResponse(): void
    {
        $request = $this->createDummyAddHistoryRequest();

        /** @var Team|MockObject $winnerTeam */
        $winnerTeam = $this->createMock(Team::class);
        $winnerTeam->method('getId')->willReturn(1);
        $winnerTeam->method('getPlayers')->willReturn([TestObjectFactory::createPlayer('Player 1')]);
        $winnerTeam->method('isPlayerEloDifferenceValid')->willReturn(true);

        /** @var Team|MockObject $loserTeam */
        $loserTeam = $this->createMock(Team::class);
        $loserTeam->method('getId')->willReturn(2);
        $loserTeam->method('getPlayers')->willReturn([TestObjectFactory::createPlayer('Player21')]);
        $loserTeam->method('isPlayerEloDifferenceValid')->willReturn(true);

        $this->teamFactory->expects($this->at(0))->method('createTeamFromPlayerIds')->with(self::WINNER_IDS)->willReturn($winnerTeam);
        $this->teamFactory->expects($this->at(1))->method('createTeamFromPlayerIds')->with(self::LOSER_IDS)->willReturn($loserTeam);

        $result = $this->historyModel->addHistory($request);

        $this->assertInstanceOf(History::class, $result);
    }

    private function createDummyAddHistoryRequest(): AddHistoryRequest
    {
        $request = new AddHistoryRequest();
        $request->winner = self::WINNER_IDS;
        $request->loser = self::LOSER_IDS;
        $request->proofUrl = ['proof.url'];
        $request->winnerTeamName = 'winnerTeam';
        $request->loserTeamName = 'loserTeam';
        $request->isSweep = false;
        return $request;
    }
}
