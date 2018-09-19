<?php

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Authentication\ConsentRequest;

use Jose\Object\JWSInterface;
use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentToken;

/**
 * @coversDefaultClass \Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentToken
 */
class ConsentTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testFillByConsentRequestData()
    {
        $token = (new ConsentToken())->fillByConsentRequestData([
            'id' => 'test_request_id',
            'requestedScopes' => ['offline', 'openid', 'hydra.*'],
            'clientId' => 'testLocal1',
            'redirectUrl' => 'http://test/?login_hint=srn:cloud:idp:eu:2000000001:tenant',
        ]);
        $this->assertEquals('srn:cloud:idp:eu:2000000001:tenant', $token->getTenantSRN());
        $this->assertEquals('http://test/?login_hint=srn:cloud:idp:eu:2000000001:tenant', $token->getRedirectUrl());
        $this->assertEquals('testLocal1', $token->getClientId());
        $this->assertEquals('test_request_id', $token->getRequestId());
        $this->assertEquals(['offline', 'openid', 'hydra.*'], $token->getScope());
    }

    public function testFillByConsentRequestNoTenant()
    {
        $token = (new ConsentToken())->fillByConsentRequestData([
            'id' => 'test_request_id',
            'requestedScopes' => ['offline', 'openid', 'hydra.*'],
            'clientId' => 'testLocal1',
            'redirectUrl' => 'http://test/',
        ]);
        $this->assertNull($token->getTenantSRN());
    }
}
