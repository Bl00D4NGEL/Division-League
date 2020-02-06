<?php

namespace App\Tests\ValueObjects;

use App\Entity\Team;
use App\Factory\HistoryFactory;
use App\ValueObjects\HistoryFormatter;
use App\Entity\History;
use App\Entity\Player;
use App\ValueObjects\RichHistory;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HistoryFormatterTest extends TestCase
{
    /** @var HistoryFormatter */
    private $formatter;

    /** @var HistoryFactory|MockObject */
    private $historyFactory;

    public function setUp(): void
    {
        $this->historyFactory = $this->createMock(HistoryFactory::class);
        $this->buildFormatter();
    }


    public function testFormatOnEmptyArrayShouldReturnEmptyArray(): void
    {
        $this->assertCount(0, $this->formatter->format([]));
    }

    public function testFormatReturnsProperDataStructure(): void
    {
        $history = $this->createMock(History::class);

        $history->expects($this->once())->method('getProofUrl')->willReturn('proof.url');
        $history->expects($this->once())->method('getWinnerGain')->willReturn(1);
        $history->expects($this->once())->method('getLoserGain')->willReturn(2);
        $history->expects($this->exactly(2))->method('getId')->willReturn(1);
        $dateTime = $this->createMock(DateTime::class);
        $dateTime->expects($this->once())->method('getTimestamp')->willReturn(123456789);
        $history->expects($this->once())->method('getCreateTime')->willReturn($dateTime);

        $winner = $this->createMock(Player::class);
        $winner->expects($this->once())->method('asArray')->willReturn(['player' => 1]);
        $winnerTeam = $this->createMock(Team::class);
        $winnerTeam->expects($this->once())->method('getPlayers')->willReturn([$winner]);
        $winnerTeam->expects($this->once())->method('getName')->willReturn('winner');

        $loser = $this->createMock(Player::class);
        $loser->expects($this->once())->method('asArray')->willReturn(['player' => 2]);
        $loserTeam = $this->createMock(Team::class);
        $loserTeam->expects($this->once())->method('getPlayers')->willReturn([$loser]);
        $loserTeam->expects($this->once())->method('getName')->willReturn('loser');

        $richHistory = $this->createMock(RichHistory::class);
        $richHistory->expects($this->once())->method('getLoserObject')->willReturn($loserTeam);
        $richHistory->expects($this->once())->method('getWinnerObject')->willReturn($winnerTeam);

        $this->historyFactory->expects($this->once())->method('createFromId')->willReturn($richHistory);

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
                "creationTime" => 123456789,
                "id" => 1
            ]
        ];

        $this->assertSame($expected, $result);
    }

    private function buildFormatter(): void
    {
        $this->formatter = new HistoryFormatter($this->historyFactory);
    }
}
