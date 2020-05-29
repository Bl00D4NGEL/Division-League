<?php

namespace App\ValueObjects\Match;

use App\ValueObjects\EloCalculator\EloCalculator;

class Match
{
    public function play(Team $winnerTeam, Team $loserTeam, EloCalculator $eloCalculator): MatchResult {
        $eloCalculationResult = $eloCalculator->calculate($winnerTeam->getAverageElo(), $loserTeam->getAverageElo());

        return new MatchResult(
            $eloCalculationResult->eloGain(),
            $eloCalculationResult->eloLoss()
        );
    }
}
