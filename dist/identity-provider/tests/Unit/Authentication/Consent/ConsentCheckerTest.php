<?php

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\Consent;

use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentToken;
use Sugarcrm\IdentityProvider\Authentication\Consent\ConsentChecker;
use Sugarcrm\IdentityProvider\Authentication\Consent;

class ConsentCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Consent
     */
    protected $consent;

    /**
     * @var ConsentToken | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $token;

    protected function setUp()
    {
        $this->consent = new Consent();
        $this->token = $this->createMock(ConsentToken::class);
    }

    public function providerTestCheck()
    {
        return [
            ['match', ['match'], true],
            ['not match', ['notMatch'], false],
        ];
    }

    /**
     * @param $consentScope
     * @param $tokenScope
     * @param $result
     * @dataProvider providerTestCheck
     */
    public function testCheck($consentScope, $tokenScope, $result)
    {
        $this->consent->setScopes($consentScope);
        $this->token->expects($this->any())
            ->method('getScope')
            ->willReturn($tokenScope);

        $checker = new ConsentChecker($this->consent, $this->token);
        $this->assertEquals($result, $checker->check());
    }
}
