<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\ValueObjects\EloCalculator\EloCalculator;
use PHPUnit\Framework\TestCase;

class EloCalculatorTest extends TestCase
{

    /**
     * @dataProvider defaultEloProvider
     * @param int $winnerElo
     * @param int $loserElo
     * @param int $expectedChange
     */
    public function testCalculation(int $winnerElo, int $loserElo, int $expectedChange): void {
        $eloCalculator = new EloCalculator();
        static::assertSame($expectedChange, $eloCalculator->calculateEloChange($winnerElo, $loserElo));
    }

    public function defaultEloProvider(): array
    {
        return [
            [-1000, -1000, 8],
            [1000, 1000, 10],
            [10000, 10000, 100],
            [10000, 5000, 1],
            [5000, 10000, 150],
            [1000, 500, 1],
            [500, 1000, 16],
            [10000, 0, 0],
            [0, 0, 8],
        ];
    }
}
