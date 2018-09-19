<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Controller;

use GuzzleHttp\Exception\RequestException;

use Jose\Object\JWSInterface;

use Psr\Http\Message\RequestInterface;

use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentJwtParser;
use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentRestService;
use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentToken;
use Sugarcrm\IdentityProvider\App\Repository\ConsentRepository;
use Sugarcrm\IdentityProvider\Authentication\Consent;
use Sugarcrm\IdentityProvider\STS\EndpointInterface;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;
use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\App\Authentication\JoseService;
use Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service;
use Sugarcrm\IdentityProvider\App\Controller\ConsentController;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\App\Controller\ConsentController
 */
class ConsentControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $application;

    /**
     * @var ConsentRepository | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $consentRepository;

    /**
     * @var Consent
     */
    protected $consent;

    /**
     * @var OAuth2Service | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $oAuth2Service;

    /**
     * @var JoseService | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $joseService;

    /**
     * @var ParserInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $consentRequestParser;

    /**
     * @var ParserFactory | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $consentRequestParserFactory;

    /**
     * @var Session | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $sessionService;

    /**
     * @var Request | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    /**
     * @var RequestInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $oAuth2Request;

    /**
     * @var ConsentController | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $consentController;

    /**
     * @var string
     */
    protected $challenge;

    /**
     * @var string
     */
    protected $invalidChallenge;

    /**
     * @var TokenInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $userToken;

    /**
     * @var ConsentRestService | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $consentRestService;

    /**
     * @var ConsentToken | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $consentToken;

    /**
     * @var ParameterBag | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestQueryBug;

    /**
     * @var array
     */
    protected $publicKey = [
        'use' => 'sig',
        'kty' => 'RSA',
        'kid' => 'public',
        'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
        'e' => 'AQAB',
    ];

    /**
     * @var array
     */
    protected $privateKey = [
        'use' => 'sig',
        'kty' => 'RSA',
        'kid' => 'private',
        'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
        'e' => 'AQAB',
    ];

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->application = $this->createMock(Application::class);

        $this->consentRepository = $this->createMock(ConsentRepository::class);
        $this->consentRestService = $this->createMock(ConsentRestService::class);
        $this->consentToken = $this->createMock(ConsentToken::class);

        $this->consent = new Consent();
        $this->application->expects($this->any())->method('getConsentRepository')->willReturn($this->consentRepository);

        $this->oAuth2Service = $this->createMock(OAuth2Service::class);
        $this->joseService = $this->createMock(JoseService::class);
        $this->sessionService = $this->createMock(Session::class);

        $this->request = $this->createMock(Request::class);
        $this->requestQueryBug = $this->createMock(ParameterBag::class);
        $this->request->query = $this->requestQueryBug;

        $this->oAuth2Request = $this->createMock(RequestInterface::class);
        $this->userToken = $this->createMock(AbstractToken::class);
        $this->application->method('offsetGet')
            ->willReturnMap(
                [
                    ['JoseService', $this->joseService],
                    ['oAuth2Service', $this->oAuth2Service],
                    ['session', $this->sessionService],
                ]
            );

        $this->application->method('getUrlGeneratorService')
            ->willReturn($this->createMock(UrlGeneratorInterface::class));

        $this->application->method('getConsentRestService')
            ->willReturn($this->consentRestService);

        $this->consentController = new ConsentController($this->application);
        $this->challenge = IDMFixturesHelper::getValidJWT();
        $this->invalidChallenge = IDMFixturesHelper::getExpiredJWT();
    }

    public function testConsentInitAction()
    {
        $this->requestQueryBug->expects($this->once())
            ->method('get')
            ->with($this->equalTo('consent'))
            ->willReturn($consent = 'test_consent');

        $this->consentRestService->expects($this->once())
            ->method('getToken')
            ->with($this->equalTo($consent))
            ->willReturn($this->consentToken);

        $this->consentToken->expects($this->exactly(2))
            ->method('getTenantSRN')
            ->willReturn($tenantSrn = 'srn:cloud:idp:eu:2000000001:tenant');

        $this->sessionService->expects($this->exactly(2))
            ->method('set')
            ->withConsecutive(
                [$this->equalTo('tenant'), $this->equalTo($tenantSrn)],
                [$this->equalTo('consent'), $this->isInstanceOf(ConsentToken::class)]
            );
        $this->application->expects($this->once())->method('redirect');
        $this->consentController->consentInitAction($this->application, $this->request);
    }

    /**
     * Checks logic when JWT was not saved after init.
     *
     * @covers ::consentFinishAction
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException
     */
    public function testConsentFinishActionWithoutSavedConsentToken()
    {
        $this->sessionService->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap(
                [
                    ['consent', null, null],
                    ['authenticatedUser', null, $this->userToken],
                ]
            );

        $this->application->expects($this->never())->method('redirect');

        $this->consentController->consentFinishAction($this->application, $this->request);
    }

    /**
     * Checks logic when user was not authenticated.
     *
     * @covers ::consentFinishAction
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException
     */
    public function testConsentFinishActionWithoutAuthenticatedUser()
    {
        $this->sessionService->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap(
                [
                    ['consent', null, $this->consentToken],
                    ['authenticatedUser', null, null],
                ]
            );

        $this->application->expects($this->never())->method('redirect');

        $this->consentController->consentFinishAction($this->application, $this->request);
    }

    /**
     * Checks consent finish flow.
     *
     * @covers ::consentFinishAction
     */
    public function testConsentFinishActionRestFlowAcceptRequest()
    {
        $this->consentToken->expects($this->once())
            ->method('getRedirectUrl')
            ->willReturn('http://oauth.server/?state=123&consent=encodedToken');

        $this->sessionService->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap(
                [
                    ['consent', null, $this->consentToken],
                    ['authenticatedUser', null, $this->userToken],
                ]
            );

        $this->oAuth2Service->expects($this->once())
            ->method('acceptConsentRequest')
            ->with($this->consentToken, $this->userToken);

        $this->application->expects($this->once())
            ->method('redirect')
            ->with('http://oauth.server/?state=123&consent=encodedToken');

        $this->consentController->consentFinishAction($this->application, $this->request);
    }

    /**
     * Checks consent finish flow.
     *
     * @covers ::consentFinishAction
     */
    public function testConsentFinishActionRestFlowRejectRequest()
    {
        $this->consentToken->expects($this->once())
            ->method('getRequestId')
            ->willReturn($requestId = 'test_consent_request_id');

        $this->consentToken->expects($this->once())
            ->method('getRedirectUrl')
            ->willReturn('http://oauth.server/?state=123&consent=encodedToken');

        $this->sessionService->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap(
                [
                    ['consent', null, $this->consentToken],
                    ['authenticatedUser', null, $this->userToken],
                ]
            );

        $this->oAuth2Service->expects($this->once())
            ->method('rejectConsentRequest')
            ->with($requestId, 'No consent');

        $this->application->expects($this->once())
            ->method('redirect')
            ->with('http://oauth.server/?state=123&consent=encodedToken');

        $this->consentController->consentCancelAction($this->application, $this->request);
    }
}
