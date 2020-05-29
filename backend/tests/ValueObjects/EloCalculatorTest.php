<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\ValueObjects\EloCalculator\DefaultEloMultiplier;
use App\ValueObjects\EloCalculator\EloCalculator;
use App\ValueObjects\EloCalculator\SweepEloMultiplier;
use PHPUnit\Framework\TestCase;

class EloCalculatorTest extends TestCase
{
    /**
     * @dataProvider eloProvider
     */
    public function testBasicScenario(int $winnerElo, int $loserElo, int $expectedWinnerGain, int $expectedLoserGain): void
    {
        $defaultEloCalculation = new DefaultEloMultiplier();
        $eloCalc = new EloCalculator($defaultEloCalculation);
        $result = $eloCalc->calculate($winnerElo, $loserElo);
        $this->assertSame($expectedWinnerGain, $result->eloGain());
        $this->assertSame($expectedLoserGain, $result->eloLoss());
    }

    /**
     * @dataProvider sweepEloProvider
     */
    public function testSweepScenario(int $winnerElo, int $loserElo, int $expectedWinnerGain, int $expectedLoserGain): void
    {
        $defaultEloCalculation = new SweepEloMultiplier();
        $eloCalc = new EloCalculator($defaultEloCalculation);
        $result = $eloCalc->calculate($winnerElo, $loserElo);
        $this->assertSame($expectedWinnerGain, $result->eloGain());
        $this->assertSame($expectedLoserGain, $result->eloLoss());
    }

    public function eloProvider(): array
    {
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

    public function sweepEloProvider(): array
    {
        return [
            [1000, 1000, 15, -9],
            [0, 0, 12, -7],
            [-1000, -1000, 12, -7],
            [500, 1000, 23, -14],
            [1000, 500, 2, -1],
            [10000, 0, 0, 0],
            [10000, 5000, 1, -1],
            [5000, 10000, 225, -132],
            [10000, 10000, 150, -88],
        ];
    }
}
