<?php

namespace App\Tests\DataObjects;

use App\DataObjects\HistoryFormatter;
use App\Entity\History;
use App\Entity\Player;
use App\Repository\RosterRepository;
use App\Repository\TeamRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HistoryFormatterTest extends TestCase
{
    /** @var RosterRepository|MockObject */
    private $rosterRepository;

    /** @var TeamRepository|MockObject */
    private $teamRepository;

    /** @var HistoryFormatter */
    private $formatter;

    public function setUp(): void
    {
        $this->rosterRepository = $this->createMock(RosterRepository::class);
        $this->teamRepository = $this->createMock(TeamRepository::class);
        $this->buildFormatter();
    }


    public function testFormatOnEmptyArrayShouldReturnEmptyArray(): void
    {
        $this->assertCount(0, $this->formatter->format([]));
    }

    public function testFormatReturnsProperDataStructure(): void
    {
        $history = $this->createMock(History::class);

        $history->expects($this->exactly(2))->method('getWinner')->willReturn(1);
        $history->expects($this->exactly(2))->method('getLoser')->willReturn(2);

        $history->expects($this->once())->method('getProofUrl')->willReturn('proof.url');
        $history->expects($this->once())->method('getWinnerGain')->willReturn(1);
        $history->expects($this->once())->method('getLoserGain')->willReturn(2);
        $history->expects($this->once())->method('getId')->willReturn(1);

        $winner = $this->createMock(Player::class);
        $winner->expects($this->once())->method('asArray')->willReturn(['player' => 1]);
        $this->rosterRepository->expects($this->at(0))->method('getPlayersForTeam')->with(1)->willReturn([$winner]);

        $loser = $this->createMock(Player::class);
        $loser->expects($this->once())->method('asArray')->willReturn(['player' => 2]);
        $this->rosterRepository->expects($this->at(1))->method('getPlayersForTeam')->with(2)->willReturn([$loser]);

        $this->teamRepository->expects($this->at(0))->method('getTeamName')->with(1)->willReturn('winner');
        $this->teamRepository->expects($this->at(1))->method('getTeamName')->with(2)->willReturn('loser');

        $this->buildFormatter();

        $result = $this->formatter->format([$history]);
        $expected = [
            [
                "winner" => [
                    [
                        'player' => 1
                    ]
                ],
                "loser" => [
                    [
                        'player' => 2
                    ]
                ],
                "proofUrl" => "proof.url",
                "winnerTeamName" => "winner",
                "loserTeamName" => "loser",
                "winnerEloWin" => 1,
                "loserEloLose" => 2,
                "id" => 1
            ]
        ];

        $this->assertSame($expected, $result);
    }

    private function buildFormatter(): void
    {
        $this->formatter = new HistoryFormatter($this->rosterRepository, $this->teamRepository);
    }
}
