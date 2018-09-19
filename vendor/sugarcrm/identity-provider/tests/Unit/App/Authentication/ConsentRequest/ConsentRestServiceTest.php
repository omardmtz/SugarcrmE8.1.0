<?php

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Authentication\ConsentRequest;

use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentRestService;
use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentToken;
use Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service;

/**
 * @coversDefaultClass \Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentRestService
 */
class ConsentRestServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var OAuth2Service | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $oAuth2Service;

    /**
     * @var ConsentRestService
     */
    protected $service;

    protected function setUp()
    {
        $this->oAuth2Service = $this->createMock(OAuth2Service::class);
        $this->service = new ConsentRestService($this->oAuth2Service);
    }

    /**
     * @covers ::getToken
     */
    public function testGetToken()
    {
        $requestId = 'test_consent_id';
        $this->oAuth2Service->expects($this->once())
            ->method('getConsentRequestData')
            ->willReturn([
                'id' => $requestId,
                'requestedScopes' => ['offline', 'openid', 'hydra.*'],
                'clientId' => 'testLocal1',
                'redirectUrl' => 'http://test/?login_hint=srn:cloud:idp:eu:2000000001:tenant',
            ]);

        /** @var ConsentToken $token */
        $token = $this->service->getToken($requestId);
        $this->assertEquals('srn:cloud:idp:eu:2000000001:tenant', $token->getTenantSRN());
    }
}
