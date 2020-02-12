<?php

namespace App\Tests\Models;

use App\Entity\History;
use App\Entity\Player;
use App\Entity\Roster;
use App\Entity\Team;
use App\Factory\HistoryFactory;
use App\Factory\TeamFactory;
use App\Models\StatisticModel;
use App\Resource\JsonResponse\SuccessResponse;
use App\Tests\DatabaseTestCase;
use App\Tests\TestObjectFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/** @group Database */
class StatisticModelTest extends DatabaseTestCase
{
    /** @var StatisticModel */
    private $statisticsModel;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function setUp(): void
    {
        $this->entityManager = $this->getEntityManager();
        $this->buildStatisticModel();
    }

    public function tearDown(): void
    {
        $toTruncate = [];
        foreach ([Roster::class, Team::class, Player::class, History::class] as $class) {
            $toTruncate[] = $this->getTableNameForEntity($class);
        }
        $this->truncateTables($toTruncate);
    }

    protected function truncateTables($tableNames = array(), $cascade = false): void
    {
        parent::truncateTables($tableNames);
    }

    public function testGetGamesPerWeek(): void
    {
        $this->assertInstanceOf(SuccessResponse::class, $this->statisticsModel->getGamesPerWeek());
    }

    public function testGetGamesPerWeekShouldReturnStatisticsForOneWeek(): void
    {
        $winnerPlayer = TestObjectFactory::createPlayer('Winner Player');
        $winnerTeam = TestObjectFactory::createTeam('Winner Team');
        $this->entityManager->persist($winnerPlayer);
        $this->entityManager->persist($winnerTeam);

        $loserPlayer = TestObjectFactory::createPlayer('Loser Player');
        $loserTeam = TestObjectFactory::createTeam('Loser Team');
        $this->entityManager->persist($loserPlayer);
        $this->entityManager->persist($loserTeam);

        $this->entityManager->flush();

        $winnerRoster = TestObjectFactory::createRoster($winnerTeam->getId(), $winnerPlayer->getId());
        $loserRoster = TestObjectFactory::createRoster($loserTeam->getId(), $loserPlayer->getId());
        $this->entityManager->persist($winnerRoster);
        $this->entityManager->persist($loserRoster);

        $tuesday = new DateTime('11-02-2020');
        $recentHistory = TestObjectFactory::createHistory($winnerTeam->getId(), $loserTeam->getId(), $tuesday);
        $tuesdayOneMonthAgo = new DateTime('14-01-2020');
        $historyInPast = TestObjectFactory::createHistory($winnerTeam->getId(), $loserTeam->getId(), $tuesdayOneMonthAgo);
        $this->entityManager->persist($recentHistory);
        $this->entityManager->persist($historyInPast);

        $this->entityManager->flush();

        $expectedResponse = new SuccessResponse([
            $winnerPlayer->getName() => [
                '2020-02-10:2020-02-16' => 1,
                '2020-01-13:2020-01-19' => 1,
            ],
            $loserPlayer->getName() => [
                '2020-02-10:2020-02-16' => 1,
                '2020-01-13:2020-01-19' => 1,
            ],
        ]);
        $this->assertSame($expectedResponse->getContent(), $this->statisticsModel->getGamesPerWeek()->getContent());
    }

    protected function buildStatisticModel(): void
    {
        $teamFactory = new TeamFactory(
            $this->entityManager->getRepository(Roster::class),
            $this->entityManager->getRepository(Team::class),
            $this->entityManager->getRepository(Player::class)
        );
        $historyFactory = new HistoryFactory(
            $this->entityManager->getRepository(History::class),
            $teamFactory
        );
        $this->statisticsModel = new StatisticModel(
            $historyFactory,
            $this->entityManager->getRepository(History::class)
        );
    }
}
