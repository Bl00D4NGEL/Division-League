<?php
namespace App\Tests\Service;

use App\Service\EloCalculator;
use PHPUnit\Framework\TestCase;

class EloCalculatorTest extends TestCase
{
    public function testBasicScenario(): void {
        $eloCalc = new EloCalculator(1000, 1000);
        $this->assertSame(10, $eloCalc->getEloChangeForWinner());
        $this->assertSame(-10, $eloCalc->getEloChangeForLoser());
    }
}
