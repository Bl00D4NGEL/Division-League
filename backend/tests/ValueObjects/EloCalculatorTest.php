<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\ValueObjects\EloCalculator\DefaultEloMultiplier;
use App\ValueObjects\EloCalculator\EloCalculator;
use App\ValueObjects\EloCalculator\StreakEloMultiplier;
use App\ValueObjects\EloCalculator\SweepEloMultiplier;
use PHPUnit\Framework\TestCase;

class EloCalculatorTest extends TestCase
{
    /**
     * @dataProvider defaultEloProvider
     * @param int $winnerElo
     * @param int $loserElo
     * @param int $expectedWinnerGain
     * @param int $expectedLoserGain
     */
    public function testDefaultScenario(int $winnerElo, int $loserElo, int $expectedWinnerGain, int $expectedLoserGain): void
    {
        $eloCalculator = new EloCalculator(new DefaultEloMultiplier());
        $result = $eloCalculator->calculate($winnerElo, $loserElo);
        $this->assertSame($expectedWinnerGain, $result->eloGain());
        $this->assertSame($expectedLoserGain, $result->eloLoss());
    }

    /**
     * @dataProvider sweepEloProvider
     * @param int $winnerElo
     * @param int $loserElo
     * @param int $expectedWinnerGain
     * @param int $expectedLoserGain
     */
    public function testSweepScenario(int $winnerElo, int $loserElo, int $expectedWinnerGain, int $expectedLoserGain): void
    {
        $eloCalculator = new EloCalculator(new SweepEloMultiplier());
        $result = $eloCalculator->calculate($winnerElo, $loserElo);
        $this->assertSame($expectedWinnerGain, $result->eloGain());
        $this->assertSame($expectedLoserGain, $result->eloLoss());
    }

    /**
     * @dataProvider streakDefaultEloProvider
     * @param int $winnerElo
     * @param int $loserElo
     * @param int $expectedWinnerGain
     * @param int $expectedLoserGain
     * @param int $streakLength
     */
    public function testDefaultStreakScenario(int $winnerElo, int $loserElo, int $expectedWinnerGain, int $expectedLoserGain, int $streakLength): void {
        $eloCalculator = new EloCalculator(new StreakEloMultiplier(new DefaultEloMultiplier(), $streakLength));
        $result = $eloCalculator->calculate($winnerElo, $loserElo);
        $this->assertSame($expectedWinnerGain, $result->eloGain());
        $this->assertSame($expectedLoserGain, $result->eloLoss());
    }

    /**
     * @dataProvider streakSweepEloProvider
     * @param int $winnerElo
     * @param int $loserElo
     * @param int $expectedWinnerGain
     * @param int $expectedLoserGain
     * @param int $streakLength
     */
    public function testSweepStreakScenario(int $winnerElo, int $loserElo, int $expectedWinnerGain, int $expectedLoserGain, int $streakLength): void {
        $eloCalculator = new EloCalculator(new StreakEloMultiplier(new SweepEloMultiplier(), $streakLength));
        $result = $eloCalculator->calculate($winnerElo, $loserElo);
        $this->assertSame($expectedWinnerGain, $result->eloGain());
        $this->assertSame($expectedLoserGain, $result->eloLoss());
    }

    public function defaultEloProvider(): array
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

    public function streakDefaultEloProvider(): array {

        return [
            [1000, 1000, 13, -8, 0],
            [1000, 1000, 14, -8, 1],
            [1000, 1000, 15, -9, 2],
            [1000, 1000, 17, -9, 3],
        ];
    }

    public function streakSweepEloProvider(): array {

        return [
            [1000, 1000, 15, -9, 0],
            [1000, 1000, 17, -10, 1],
            [1000, 1000, 18, -10, 2],
            [1000, 1000, 20, -11, 3],
        ];
    }
}
