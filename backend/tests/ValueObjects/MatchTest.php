<?php

namespace App\Tests\ValueObjects;

use App\Entity\Proof;
use App\ValueObjects\Match;
use App\Entity\History;
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
        /** @var Team|MockObject $winnerTeam */
        $winnerTeam = $this->createMock(Team::class);
        $winnerTeam->expects($this->once())->method('getAverageElo')->willReturn(1000);
        $winnerTeam->expects($this->once())->method('getId')->willReturn(1);

        /** @var Team|MockObject $loserTeam */
        $loserTeam = $this->createMock(Team::class);
        $loserTeam->expects($this->once())->method('getAverageElo')->willReturn(900);
        $loserTeam->expects($this->once())->method('getId')->willReturn(2);

        $this->match->setWinner($winnerTeam);
        $this->match->setLoser($loserTeam);
        $this->match->setProofUrl(['proof.url']);
        $history = $this->match->execute();

        $proof = new Proof();
        $proof->setUrl('proof.url');
        $expected = new History();
        $expected
            ->setWinner(1)
            ->setLoser(2)
            ->setWinnerGain(9)
            ->setLoserGain(-6)
            ->addProof($proof);
        $expectedValues = $expected->asArray();
        $actualValues = $history->asArray();
        // Manually set this to null because comparing dates is hard
        $actualValues['createTime'] = null;

        /** @var Proof $actualProof */
        $actualProof = $actualValues['proof'][0];
        $h = $actualProof->getHistory();
        $h->setCreateTime(null);
        $actualProof->setHistory($h);

        $this->assertInstanceOf(History::class, $history);
        $this->assertEquals($expectedValues, $actualValues);
    }
}
