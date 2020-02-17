<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 17.02.2020
 * Time: 08:27
 */

namespace App\Tests\ValueObjects;

use App\ValueObjects\SessionAuthorization;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionAuthorizationTest extends TestCase
{
    /** @var Session|MockObject */
    private $session;

    /** @var SessionAuthorization */
    private $sessionAuthorization;

    public function setUp(): void
    {

        $this->session = $this->createMock(Session::class);
        $this->buildSessionAuthorization();
    }

    /**
     * @dataProvider isAuthorizedProvider
     */
    public function testIsAuthorized($value, $expected): void
    {
        $this->session->expects($this->once())->method('get')->with(SessionAuthorization::AUTHORIZATION_KEY)->willReturn($value);
        $this->buildSessionAuthorization();
        $this->assertSame($expected, $this->sessionAuthorization->isAuthorized());
    }

    public function isAuthorizedProvider(): array
    {
        return [
            [true, true],
            [false, false],
            [null, false],
            ['true', false],
            [1, false]
        ];
    }

    public function testAuthorize(): void
    {
        $this->session->expects($this->at(0))->method('set')->with(SessionAuthorization::AUTHORIZATION_KEY, true);
        $this->session->expects($this->at(1))->method('set')->with(SessionAuthorization::USER_ID, 0);
        $this->buildSessionAuthorization();
        $this->sessionAuthorization->authorize(0);
    }

    public function testUnauthorize(): void
    {
        $this->session->expects($this->at(0))->method('set')->with(SessionAuthorization::AUTHORIZATION_KEY, false);
        $this->session->expects($this->at(1))->method('set')->with(SessionAuthorization::USER_ID, null);
        $this->buildSessionAuthorization();
        $this->sessionAuthorization->unauthorize();
    }

    private function buildSessionAuthorization(): void
    {
        $this->sessionAuthorization = new SessionAuthorization($this->session);
    }
}
