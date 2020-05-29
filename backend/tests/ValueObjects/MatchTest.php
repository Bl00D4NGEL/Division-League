<?php

namespace App\Tests\ValueObjects;

use App\ValueObjects\EloCalculator\EloCalculator;
use App\ValueObjects\Match\Match;
use App\ValueObjects\Match\Player;
use App\ValueObjects\Match\Team;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    private Match $match;

    public function setUp(): void
    {
        $this->match = new Match();
    }

    public function testPlay(): void
    {
        $winnerTeam = new Team([
            new Player(1000)
        ]);
        $loserTeam = new Team([
            new Player(1000)
        ]);
        $matchResult = $this->match->play($winnerTeam, $loserTeam, new EloCalculator());

        static::assertSame(10, $matchResult->eloChange());
    }
}
