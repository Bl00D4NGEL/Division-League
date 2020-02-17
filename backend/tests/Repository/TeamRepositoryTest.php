<?php

namespace App\Tests\Repository;

use App\Entity\Player;
use App\Entity\Roster;
use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Tests\DatabaseTestCase;
use App\Tests\TestObjectFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;

/** @group Database */
class TeamRepositoryTest extends DatabaseTestCase
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ManagerRegistry|MockObject */
    private $managerRegistry;

    /** @var TeamRepository */
    private $teamRepository;

    public function setUp(): void
    {
        $this->entityManager = $this->getEntityManager();
        $this->managerRegistry = $this->getMockedManagerRegistryForClass();
        $this->teamRepository = $this->entityManager->getRepository(Team::class);
    }

    protected function tearDown(): void
    {
        $toTruncate = [];
        foreach ([Roster::class, Team::class, Player::class] as $class) {
            $toTruncate[] = $this->getTableNameForEntity($class);
        }
        $this->truncateTables($toTruncate);
        parent::tearDown();
    }

    public function testGetSoloTeamForPlayerShouldReturnNullIfNoSoloTeamIsFound()
    {
        $team = new Team();
        $team->setName('Team 1');
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        $playerOne = $this->createPlayerOne();
        $this->entityManager->persist($playerOne);

        $playerTwo = $this->createPlayerTwo();
        $this->entityManager->persist($playerTwo);
        $this->entityManager->flush();

        $roster = new Roster();
        $roster->setPlayer($playerOne->getId());
        $roster->setTeam($team->getId());
        $this->entityManager->persist($roster);
        $roster = new Roster();
        $roster->setPlayer($playerTwo->getId());
        $roster->setTeam($team->getId());
        $this->entityManager->persist($roster);

        $this->entityManager->flush();

        /** @var Player|MockObject $player */
        $player = $this->createMock(Player::class);
        $player->expects($this->once())->method('getId')->willReturn(1);
        $this->assertNull($this->teamRepository->getSoloTeamForPlayer($player));
    }

    public function testGetSoloTeamForPlayer()
    {
        $playerOne = $this->createPlayerOne();
        $this->entityManager->persist($playerOne);

        $team = new Team();
        $team->setName('Team 1');
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        $roster = new Roster();
        $roster->setPlayer($playerOne->getId());
        $roster->setTeam($team->getId());
        $this->entityManager->persist($roster);
        $this->entityManager->flush();

        $soloTeam = $this->teamRepository->getSoloTeamForPlayer($playerOne);
        $this->assertInstanceOf(Team::class, $soloTeam);
        $this->assertSame('Team 1', $soloTeam->getName());
        $this->assertSame(1, $soloTeam->getId());
        $this->assertCount(1, $soloTeam->getPlayers());
    }

    private function createPlayerOne(): Player
    {
        return TestObjectFactory::createPlayer('Player 1', 123);
    }

    private function createPlayerTwo(): Player
    {
        return TestObjectFactory::createPlayer('Player 2', 234);
    }
}
