<?php
namespace App\Tests\Service;

use App\ValueObjects\EloCalculator;
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
            [1000, 1000, 13, -8],
            [0, 0, 10, -6],
            [-1000, -1000, 10, -6],
            [500, 1000, 19, -12],
            [1000, 500, 2, -1],
            [10000, 0, 0, 0],
            [10000, 5000, 1, -1],
            [5000, 10000, 188, -113],
            [10000, 10000, 125, -75],
        ];
    }
}
