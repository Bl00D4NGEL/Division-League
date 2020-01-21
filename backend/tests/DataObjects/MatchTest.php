<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 21.01.2020
 * Time: 19:19
 */

namespace App\Tests\DataObjects;

use App\DataObjects\Match;
use App\Entity\History;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Repository\RosterRepository;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    /** @var PlayerRepository|MockObject */
    private $playerRepository;

    /** @var RosterRepository|MockObject */
    private $rosterRepository;

    /** @var Match */
    private $match;

    public function setUp(): void
    {
        $this->rosterRepository = $this->createMock(RosterRepository::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);

        $this->buildMatch();
    }

    public function testSetLoserWithFoundTeamByPlayerIds(): void
    {
        $playerIds = [1];
        $team = $this->createMock(Team::class);
        $this->rosterRepository->expects($this->once())->method('getTeamForPlayers')->with($playerIds)->willReturn($team);
        $this->rosterRepository->expects($this->never())->method('createTeamForPlayers');

        $this->buildMatch();

        $this->match->setLoser($playerIds);
        $this->assertSame($team, $this->match->getLoser());
    }

    public function testSetLoserWithCreatedTeamAndEmptyTeamName(): void
    {
        $playerIds = [1];
        $team = $this->createMock(Team::class);
        $this->rosterRepository->expects($this->once())->method('getTeamForPlayers')->with($playerIds)->willReturn(null);
        $this->rosterRepository->expects($this->once())->method('createTeamForPlayers')->with($playerIds, '')->willReturn($team);

        $this->buildMatch();

        $this->match->setLoser($playerIds);
        $this->assertSame($team, $this->match->getLoser());
    }

    public function testSetLoserWithCreatedTeamAndNonEmptyTeamName(): void
    {
        $playerIds = [1];
        $teamName = 'losers';
        $team = $this->createMock(Team::class);
        $this->rosterRepository->expects($this->once())->method('getTeamForPlayers')->with($playerIds)->willReturn(null);
        $this->rosterRepository->expects($this->once())->method('createTeamForPlayers')->with($playerIds, $teamName)->willReturn($team);

        $this->buildMatch();

        $this->match->setLoser($playerIds, $teamName);
        $this->assertSame($team, $this->match->getLoser());
    }


    public function testExecute(): void
    {
        $player = $this->createMock(Player::class);
        $player->expects($this->exactly(2))->method('getElo')->willReturn(1000);

        $winnerIds = [1];
        $loserIds = [2];

        $winnerTeam = $this->createMock(Team::class);
        $winnerTeam->expects($this->once())->method('getPlayers')->willReturn([$player]);
        $winnerTeam->expects($this->once())->method('getId')->willReturn(1);

        $loserTeam = $this->createMock(Team::class);
        $loserTeam->expects($this->once())->method('getPlayers')->willReturn([$player]);
        $loserTeam->expects($this->once())->method('getId')->willReturn(2);

        $this->rosterRepository->expects($this->at(0))->method('getTeamForPlayers')->with($winnerIds)->willReturn($winnerTeam);
        $this->rosterRepository->expects($this->at(1))->method('getTeamForPlayers')->with($loserIds)->willReturn($loserTeam);

        $this->buildMatch();

        $this->match->setWinner($winnerIds);
        $this->match->setLoser($loserIds);
        $this->match->setProofUrl('proof.url');
        $this->match->execute();

        $expected = new History();
        $expected->setProofUrl('proof.url')
            ->setWinner(1)
            ->setLoser(2)
            ->setWinnerGain(10)
            ->setLoserGain(-10);

        $this->assertInstanceOf(History::class, $this->match->getHistory());
        $this->assertSame($expected->asArray(), $this->match->getHistory()->asArray());
    }

    public function testSetWinnerWithFoundTeamByPlayerIds(): void
    {
        $playerIds = [1];
        $team = $this->createMock(Team::class);
        $this->rosterRepository->expects($this->once())->method('getTeamForPlayers')->with($playerIds)->willReturn($team);
        $this->rosterRepository->expects($this->never())->method('createTeamForPlayers');

        $this->buildMatch();

        $this->match->setWinner($playerIds);
        $this->assertSame($team, $this->match->getWinner());

    }

    public function testSetWinnerWithCreatedTeamAndEmptyTeamName(): void
    {
        $playerIds = [1];
        $team = $this->createMock(Team::class);
        $this->rosterRepository->expects($this->once())->method('getTeamForPlayers')->with($playerIds)->willReturn(null);
        $this->rosterRepository->expects($this->once())->method('createTeamForPlayers')->with($playerIds, '')->willReturn($team);

        $this->buildMatch();

        $this->match->setWinner($playerIds);
        $this->assertSame($team, $this->match->getWinner());
    }

    public function testSetWinnerWithCreatedTeamAndNonEmptyTeamName(): void
    {
        $playerIds = [1];
        $teamName = 'winners';
        $team = $this->createMock(Team::class);
        $this->rosterRepository->expects($this->once())->method('getTeamForPlayers')->with($playerIds)->willReturn(null);
        $this->rosterRepository->expects($this->once())->method('createTeamForPlayers')->with($playerIds, $teamName)->willReturn($team);

        $this->buildMatch();

        $this->match->setWinner($playerIds, $teamName);
        $this->assertSame($team, $this->match->getWinner());
    }

    public function testGetHistoryShouldThrowExceptionIfExecuteHasNotBeenCalled(): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Execute function hasn\'t generated a history yet. Please call `execute` before this.');
        $this->match->getHistory();
    }

    private function buildMatch(): void
    {
        $this->match = new Match($this->rosterRepository, $this->playerRepository);
    }
}
