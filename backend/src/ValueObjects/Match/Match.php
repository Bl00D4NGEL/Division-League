<?php

namespace App\ValueObjects\Match;

use App\ValueObjects\EloCalculator\EloCalculator;

class Match
{
    public function play(Team $winnerTeam, Team $loserTeam, EloCalculator $eloCalculator): MatchResult {
        return new MatchResult(
            $eloCalculator->calculateEloChange(
                $winnerTeam->getAverageElo(),
                $loserTeam->getAverageElo()
            )
        );
    }
}
