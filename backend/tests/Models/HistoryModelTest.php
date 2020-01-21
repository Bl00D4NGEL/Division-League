<?php

namespace App\Tests\Models;

use App\DataObjects\HistoryFormatter;
use App\DataObjects\Match;
use App\Entity\History;
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

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->historyRepository = $this->createMock(HistoryRepository::class);
        $this->historyFormatter = $this->createMock(HistoryFormatter::class);

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

        $winnerTeam = $this->createMock(Team::class);
        $loserTeam = $this->createMock(Team::class);
        $history = $this->createMock(History::class);

        $match = $this->createMock(Match::class);
        $match->expects($this->once())->method('execute');
        $match->expects($this->once())->method('getWinner')->willReturn($winnerTeam);
        $match->expects($this->once())->method('getLoser')->willReturn($loserTeam);
        $match->expects($this->exactly(2))->method('getHistory')->willReturn($history);

        $this->historyRepository->expects($this->once())->method('createMatchFrom')->with($request)->willReturn($match);

        $this->em->expects($this->at(0))->method('persist')->with($history);
        $this->em->expects($this->at(1))->method('persist')->with($winnerTeam);
        $this->em->expects($this->at(2))->method('persist')->with($loserTeam);
        $this->em->expects($this->once())->method('flush');

        $this->historyFormatter->expects($this->once())->method('format')->with([$history]);

        $result = $this->historyModel->addHistory($request);

        $this->assertInstanceOf(SuccessResponse::class, $result);
    }

    private function buildHistoryModel(): void
    {
        $this->historyModel = new HistoryModel(
            $this->em,
            $this->historyRepository,
            $this->historyFormatter
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

/*
 *
 *
 * For Match test:
        $player = $this->createMock(Player::class);
        $player->expects($this->exactly(2))->method('getElo')->willReturn(1000);
$winnerTeam = $this->createMock(Team::class);
$winnerTeam->expects($this->once())->method('getId')->willReturn(1);
$winnerTeam->expects($this->once())->method('win')->with(10);
$winnerTeam->expects($this->once())->method('getPlayers')->willReturn([$player]);
$this->rosterRepository
    ->expects($this->at(0))
    ->method('getTeamForPlayers')
    ->with(self::WINNER_IDS)
    ->willReturn($winnerTeam);

$loserTeam = $this->createMock(Team::class);
$loserTeam->expects($this->once())->method('getId')->willReturn(2);
$loserTeam->expects($this->once())->method('lose')->with(-10);
$loserTeam->expects($this->once())->method('getPlayers')->willReturn([$player]);
$this->rosterRepository
    ->expects($this->at(1))
    ->method('getTeamForPlayers')
    ->with(self::LOSER_IDS)
    ->willReturn($loserTeam);

$this->buildHistoryModel();

*/
