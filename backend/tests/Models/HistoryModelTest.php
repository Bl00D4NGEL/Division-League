<?php

namespace App\Tests\Models;

use App\Entity\History;
use App\Entity\Player;
use App\Models\HistoryModel;
use App\Repository\HistoryRepository;
use App\Repository\ParticipantRepository;
use App\Repository\PlayerRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\InvalidRequestException;
use App\ValueObjects\StreakDeterminer;
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

    /** @var HistoryModel */
    private $historyModel;


    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->historyRepository = $this->createMock(HistoryRepository::class);
        $this->buildHistoryModel();
    }

    private function buildHistoryModel(): void
    {
        /** @var MockObject|PlayerRepository $playerRepository */
        $playerRepository = $this->createMock(PlayerRepository::class);
        $player = new Player();
        $player->setElo(1000);
        $playerRepository->method('find')->willReturn($player);

        /** @var MockObject|ParticipantRepository $participantRepository */
        $participantRepository = $this->createMock(ParticipantRepository::class);
        $participantRepository->method('getHistoryTimesForPlayer')->willReturn([]);
        $streakDeterminer = new StreakDeterminer($participantRepository);

        $this->historyModel = new HistoryModel(
            $this->em,
            new EloDifferenceValidator(),
            $playerRepository,
            $streakDeterminer
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
