<?php
namespace App\Tests\Service;

use App\Service\EloCalculator;
use PHPUnit\Framework\TestCase;

class EloCalculatorTest extends TestCase
{
    /**
     * @dataProvider eloProvider
     * @param int $winnerElo
     * @param int $loserElo
     * @param int $expectedWinnerGain
     * @param int $expectedLoserGain
     */
    public function testBasicScenario(int $winnerElo, int $loserElo, int $expectedWinnerGain, int $expectedLoserGain): void {
        $eloCalc = new EloCalculator($winnerElo, $loserElo);
        $this->assertSame($expectedWinnerGain, $eloCalc->getEloChangeForWinner());
        $this->assertSame($expectedLoserGain, $eloCalc->getEloChangeForLoser());
    }

    public function eloProvider(): array {
        return [
            [1000, 1000, 10, -10],
            [0, 0, 8, -8],
            [500, 1000, 16, -16],
            [1000, 500, 1, -1],
        ];
    }
}
