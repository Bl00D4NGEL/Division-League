<?php
namespace App\Tests\Repository;

use App\Entity\History;
use App\Entity\Team;
use App\Repository\HistoryRepository;
use App\Repository\RosterRepository;
use App\Resource\AddHistoryRequest;
use App\Tests\DatabaseTestCase;
use App\ValueObjects\Match;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Database
 */
class HistoryRepositoryTest extends DatabaseTestCase
{
    /** @var HistoryRepository */
    private $historyRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var RosterRepository|MockObject */
    private $rosterRepository;

    /** @var ManagerRegistry|MockObject */
    private $managerRegistry;

    protected function setUp(): void
    {
        $this->entityManager = $this->getEntityManager();
        $this->rosterRepository = $this->createMock(RosterRepository::class);
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->managerRegistry->method('getManagerForClass')->with(History::class)->willReturn($this->entityManager);
        $this->historyRepository = $this->buildHistoryRepository();
    }

    protected function tearDown(): void
    {
        $this->truncateTables([$this->getTableNameForEntity(History::class)]);
        parent::tearDown();
    }

    public function testFindLastEntriesReturnsNothing()
    {
        $this->assertCount(0, $this->historyRepository->findLastEntries());
        $this->assertCount(0, $this->historyRepository->findLastEntries(100));
    }

    public function testFindLastEntries()
    {
        $expectedLastTwo = [];
        for ($i = 0; $i < 5; $i++) {
            $history = new History();
            $history->setWinner($i+1)
                ->setLoser($i+2)
                ->setWinnerGain(10)
                ->setLoserGain(-10)
                ->setProofUrl('test.url');

            $this->entityManager->persist($history);
            if ($i > 2) {
                array_unshift($expectedLastTwo, $history);
            }
        }
        $this->entityManager->flush();
        $this->assertCount(2, $this->historyRepository->findLastEntries(2));
        $this->assertCount(5, $this->historyRepository->findLastEntries());

        $lastTwo = $this->historyRepository->findLastEntries(2);
        $this->assertEquals($expectedLastTwo, $lastTwo);
    }

    public function testCreateMatchFrom()
    {
        $request = new AddHistoryRequest();
        $request->proofUrl = 'proof.url';
        $request->loser = [1];
        $request->loserTeamName = 'loser';
        $request->winner = [2];
        $request->winnerTeamName = 'winner';

        /** @var Team|MockObject $winner */
        $winner = $this->createMock(Team::class);
        /** @var Team|MockObject $loser */
        $loser = $this->createMock(Team::class);

        $this->rosterRepository->expects($this->at(0))->method('getOrCreateTeam')->with($request->winner, $request->winnerTeamName)->willReturn($winner);
        $this->rosterRepository->expects($this->at(1))->method('getOrCreateTeam')->with($request->loser, $request->loserTeamName)->willReturn($loser);

        $expectedMatch = new Match();
        $expectedMatch->setWinner($winner);
        $expectedMatch->setLoser($loser);
        $expectedMatch->setProofUrl($request->proofUrl);
        $this->assertEquals($expectedMatch, $this->historyRepository->createMatchFrom($request));
    }

    /**
     * @return HistoryRepository
     */
    protected function buildHistoryRepository(): HistoryRepository
    {
        return new HistoryRepository($this->managerRegistry, $this->rosterRepository);
    }
}
