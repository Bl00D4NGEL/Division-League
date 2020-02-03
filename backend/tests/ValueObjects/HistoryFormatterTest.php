<?php

namespace App\Tests\ValueObjects;

use App\Entity\Team;
use App\Factory\TeamFactory;
use App\ValueObjects\HistoryFormatter;
use App\Entity\History;
use App\Entity\Player;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HistoryFormatterTest extends TestCase
{
    /** @var HistoryFormatter */
    private $formatter;

    /** @var TeamFactory|MockObject */
    private $teamFactory;

    public function setUp(): void
    {
        $this->teamFactory = $this->createMock(TeamFactory::class);
        $this->buildFormatter();
    }


    public function testFormatOnEmptyArrayShouldReturnEmptyArray(): void
    {
        $this->assertCount(0, $this->formatter->format([]));
    }

    public function testFormatReturnsProperDataStructure(): void
    {
        $history = $this->createMock(History::class);

        $history->expects($this->once())->method('getWinner')->willReturn(1);
        $history->expects($this->once())->method('getLoser')->willReturn(2);

        $history->expects($this->once())->method('getProofUrl')->willReturn('proof.url');
        $history->expects($this->once())->method('getWinnerGain')->willReturn(1);
        $history->expects($this->once())->method('getLoserGain')->willReturn(2);
        $history->expects($this->once())->method('getId')->willReturn(1);

        $winner = $this->createMock(Player::class);
        $winner->expects($this->once())->method('asArray')->willReturn(['player' => 1]);
        $winnerTeam = $this->createMock(Team::class);
        $winnerTeam->expects($this->once())->method('getPlayers')->willReturn([$winner]);
        $winnerTeam->expects($this->once())->method('getName')->willReturn('winner');
        $this->teamFactory->expects($this->at(0))->method('createFromId')->with(1)->willReturn($winnerTeam);

        $loser = $this->createMock(Player::class);
        $loser->expects($this->once())->method('asArray')->willReturn(['player' => 2]);
        $loserTeam = $this->createMock(Team::class);
        $loserTeam->expects($this->once())->method('getPlayers')->willReturn([$loser]);
        $loserTeam->expects($this->once())->method('getName')->willReturn('loser');
        $this->teamFactory->expects($this->at(1))->method('createFromId')->with(2)->willReturn($loserTeam);

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
        $this->formatter = new HistoryFormatter($this->teamFactory);
    }
}
