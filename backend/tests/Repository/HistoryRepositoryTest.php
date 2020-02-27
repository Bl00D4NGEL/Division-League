<?php

namespace App\Tests\Repository;

use App\Entity\History;
use App\Repository\HistoryRepository;
use App\Tests\DatabaseTestCase;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @group Database
 */
class HistoryRepositoryTest extends DatabaseTestCase
{
    /** @var HistoryRepository */
    private $historyRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->getEntityManager();
        $this->historyRepository = $this->entityManager->getRepository(History::class);
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
            $history->setWinner($i + 1)
                ->setLoser($i + 2)
                ->setWinnerGain(10)
                ->setLoserGain(-10)
                ->setCreateTime(new DateTime())
                ->setIsSweep(false);

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
}
