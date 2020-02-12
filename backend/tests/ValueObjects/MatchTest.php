<?php

namespace App\Tests\ValueObjects;

use App\ValueObjects\Match;
use App\Entity\History;
use App\Entity\Player;
use App\Entity\Team;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    /** @var Match */
    private $match;

    public function setUp(): void
    {
        $this->match = new Match();
    }

    public function testExecute(): void
    {
        $player = $this->createMock(Player::class);
        $player->expects($this->exactly(2))->method('getElo')->willReturn(1000);
        $player2 = $this->createMock(Player::class);
        $player2->expects($this->once())->method('getElo')->willReturn(900);

        /** @var Team|MockObject $winnerTeam */
        $winnerTeam = $this->createMock(Team::class);
        $winnerTeam->expects($this->once())->method('getPlayers')->willReturn([$player, $player2]);
        $winnerTeam->expects($this->once())->method('getId')->willReturn(1);

        /** @var Team|MockObject $loserTeam */
        $loserTeam = $this->createMock(Team::class);
        $loserTeam->expects($this->once())->method('getPlayers')->willReturn([$player]);
        $loserTeam->expects($this->once())->method('getId')->willReturn(2);

        $this->match->setWinner($winnerTeam);
        $this->match->setLoser($loserTeam);
        $this->match->setProofUrl('proof.url');
        $history = $this->match->execute();

        $expected = new History();
        $expected->setProofUrl('proof.url')
            ->setWinner(1)
            ->setLoser(2)
            ->setWinnerGain(14)
            ->setLoserGain(-9);
        $expectedValues = $expected->asArray();
        $actualValues = $history->asArray();
        // Manually set this to null because comparing dates is hard
        $actualValues['createTime'] = null;

        $this->assertInstanceOf(History::class, $history);
        $this->assertSame($expectedValues, $actualValues);
    }
}
