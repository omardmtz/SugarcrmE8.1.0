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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App\Authentication;

use Psr\Http\Message\RequestInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentTokenInterface;
use Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service;
use Sugarcrm\IdentityProvider\STS\EndpointService;
use Sugarcrm\IdentityProvider\League\OAuth2\Client\Provider\HttpBasicAuth\GenericProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Sugarcrm\IdentityProvider\Authentication\User;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service
 */
class OAuth2ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GenericProvider | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $oAuth2Provider;

    /**
     * @var OAuth2Service | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $oAuth2Service;

    /**
     * @var EndpointService | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $endpointService;

    /**
     * @var string
     */
    protected $accessToken = 'accessToken';

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $response;

    /**
     * @var ConsentTokenInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $consentToken;

    /**
     * @var AbstractToken | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $userToken;

    /**
     * @var User | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $user;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->oAuth2Provider = $this->createMock(GenericProvider::class);
        $this->endpointService = $this->createMock(EndpointService::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->consentToken = $this->createMock(ConsentTokenInterface::class);
        $this->userToken = $this->createMock(AbstractToken::class);
        $this->user = $this->createMock(User::class);
        $this->user->expects($this->any())
            ->method('getLocalUser')
            ->willReturnSelf();

        $this->oAuth2Provider->method('getAccessToken')
                             ->with('client_credentials', $this->arrayHasKey('scope'))
                             ->willReturn($this->accessToken);

        $this->oAuth2Service = new OAuth2Service($this->endpointService, $this->oAuth2Provider);
    }

    /**
     * Provides data for testGetKey.
     *
     * @return array
     */
    public function getKeyProvider()
    {
        return [
            'hydra.consent.challenge:public' => [
                'keyEndpoint' => EndpointService::CONSENT_CHALLENGE_KEYS,
                'keyType' => EndpointService::PUBLIC_KEY,
                'endpointURL' => 'http://oauth.host/keys/hydra.consent.challenge/public',
                'parsedResponse' => [
                    'keys' => [
                        [
                            'use' => 'sig',
                            'kty' => 'RSA',
                            'kid' => 'public',
                            'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
                            'e' => 'AQAB',
                        ],
                    ],
                ],
                'expectedResult' => [
                    'use' => 'sig',
                    'kty' => 'RSA',
                    'kid' => 'public',
                    'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
                    'e' => 'AQAB',
                ],
            ],
            'hydra.consent.response:all' => [
                'keyEndpoint' => EndpointService::CONSENT_RESPONSE_KEYS,
                'keyType' => null,
                'endpointURL' => 'http://oauth.host/keys/hydra.consent.response',
                'parsedResponse' => [
                    'keys' => [
                        [
                            'use' => 'sig',
                            'kty' => 'RSA',
                            'kid' => 'public',
                            'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
                            'e' => 'AQAB',
                        ],
                        [
                            'use' => 'sig',
                            'kty' => 'RSA',
                            'kid' => 'private',
                            'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
                            'e' => 'AQAB',
                        ],
                    ],
                ],
                'expectedResult' => [
                    [
                        'use' => 'sig',
                        'kty' => 'RSA',
                        'kid' => 'public',
                        'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
                        'e' => 'AQAB',
                    ],
                    [
                        'use' => 'sig',
                        'kty' => 'RSA',
                        'kid' => 'private',
                        'n' => 'pdtMaSmWnAYx8rXUssH0Aa',
                        'e' => 'AQAB',
                    ],
                ],
            ],
        ];
    }

    /**
     * Checks getKey logic with valid data.
     *
     * @param string $keyEndpoint
     * @param string $keyType
     * @param string $endpointURL
     * @param array $parsedResponse
     * @param array $expectedResult
     *
     * @covers ::getKey
     * @dataProvider getKeyProvider
     */
    public function testGetKey($keyEndpoint, $keyType, $endpointURL, array $parsedResponse, array $expectedResult)
    {
        $this->endpointService->expects($this->once())
                              ->method('getKeysEndpoint')
                              ->with($keyEndpoint, $keyType)
                              ->willReturn($endpointURL);

        $this->oAuth2Provider->expects($this->once())
                             ->method('getAuthenticatedRequest')
                             ->with(GenericProvider::METHOD_GET, $endpointURL, $this->accessToken)
                             ->willReturn($this->request);

        $this->oAuth2Provider->expects($this->once())
                             ->method('getParsedResponse')
                             ->with($this->request)
                             ->willReturn($parsedResponse);

        $result = $this->oAuth2Service->getKey($keyEndpoint, $keyType);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Checks logic if keys not found.
     *
     * @covers ::getKey
     * @expectedException \UnexpectedValueException
     */
    public function testGetKeyKeysNotFound()
    {
        $endpointURL = 'http://oauth.host/keys/hydra.consent.response';
        $this->endpointService->expects($this->once())
                              ->method('getKeysEndpoint')
                              ->with(EndpointService::CONSENT_RESPONSE_KEYS, null)
                              ->willReturn($endpointURL);

        $this->oAuth2Provider->expects($this->once())
                             ->method('getAuthenticatedRequest')
                             ->with(GenericProvider::METHOD_GET, $endpointURL, $this->accessToken)
                             ->willReturn($this->request);

        $this->oAuth2Provider->expects($this->once())
                             ->method('getParsedResponse')
                             ->with($this->request)
                             ->willReturn([]);

        $this->oAuth2Service->getKey(EndpointService::CONSENT_RESPONSE_KEYS, null);
    }

    /**
     * @see testInvalidIntrospectToken
     * @return array
     */
    public function invalidIntrospectTokenDataProvider()
    {
        return [
            'empty result' => [
                'result' => [],
            ],
            'in active token' => [
                'result' => ['active' => false],
            ],
        ];
    }

    /**
     * @covers ::introspectToken
     * @expectedExceptionMessage OIDC Token is not valid
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @dataProvider invalidIntrospectTokenDataProvider
     * @param $result
     */
    public function testInvalidIntrospectToken($result)
    {
        $token = '--some--token--';
        $this->oAuth2Provider->expects($this->once())
            ->method('introspectToken')
            ->with(new AccessToken(['access_token' => $token]))
            ->willReturn($result);

        $this->oAuth2Service->introspectToken($token);
    }

    /**
     * @covers ::introspectToken
     */
    public function testIntrospectToken()
    {
        $token = '--some--token--';
        $expectedResult = ['active' => true, 'scope' => 'someScopeValue'];
        $this->oAuth2Provider->expects($this->once())
            ->method('introspectToken')
            ->with(new AccessToken(['access_token' => $token]))
            ->willReturn($expectedResult);

        $result = $this->oAuth2Service->introspectToken($token);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @covers ::getConsentRequestData
     */
    public function testGetConsentRequestData()
    {
        $this->endpointService->expects($this->once())
            ->method('getConsentDataRequestEndpoint')
            ->with($this->equalTo($requestId = 'test_consent_request_id'))
            ->willReturn($url = 'http://test/oauth2/consent/' . $requestId);

        $this->oAuth2Provider->expects($this->once())
            ->method('getAuthenticatedRequest')
            ->with(
                $this->equalTo(GenericProvider::METHOD_GET),
                $this->equalTo($url),
                $this->equalTo($this->accessToken)
            )
            ->willReturn($this->request);
        $this->oAuth2Provider->expects($this->once())
            ->method('getParsedResponse')
            ->with($this->equalTo($this->request))
            ->willReturn([
                'id' => 'test_request_id',
                'requestedScopes' => ['offline', 'openid', 'hydra.*'],
                'clientId' => 'testLocal1',
                'redirectUrl' => 'http://test/?tid=srn:cloud:idp:eu:2000000001:tenant',
            ]);
        $this->oAuth2Service->getConsentRequestData($requestId);
    }

    /**
     * @covers ::acceptConsentRequest
     */
    public function testAcceptConsentRequest()
    {
        $this->userToken->expects($this->once())
            ->method('getAttribute')
            ->with('srn')
            ->willReturn('srn:cloud:idp:eu:2000000001:tenant:user-id');

        $this->userToken->expects($this->once())
            ->method('getUser')
            ->willReturn($this->user);

        $this->consentToken->expects($this->once())
            ->method('getScope')
            ->willReturn($scope = ['offline', 'openid', 'hydra.*']);

        $this->consentToken->expects($this->once())
            ->method('getRequestId')
            ->willReturn($requestId = 'test_consent_request_id');

        $this->endpointService->expects($this->once())
            ->method('getConsentAcceptRequestEndpoint')
            ->with($this->equalTo($requestId))
            ->willReturn($url = 'http://test/oauth2/consent/' . $requestId . '/accept');

        $this->oAuth2Provider->expects($this->once())
            ->method('getAuthenticatedRequest')
            ->with(
                $this->equalTo(Request::METHOD_PATCH),
                $this->equalTo($url),
                $this->equalTo($this->accessToken),
                $this->arrayHasKey('body')
            )
            ->willReturn($this->request);

        $this->oAuth2Provider->expects($this->once())
            ->method('getResponse')
            ->with($this->request)
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_NO_CONTENT);

        $this->oAuth2Service->acceptConsentRequest($this->consentToken, $this->userToken);
    }

    /**
     * @expectedException \RuntimeException
     * @covers ::acceptConsentRequest
     */
    public function testAcceptConsentRequestException()
    {
        $this->userToken->expects($this->once())
            ->method('getAttribute')
            ->with('srn')
            ->willReturn('srn:cloud:idp:eu:2000000001:tenant:user-id');

        $this->userToken->expects($this->once())
            ->method('getUser')
            ->willReturn($this->user);

        $this->consentToken->expects($this->once())
            ->method('getScope')
            ->willReturn($scope = ['offline', 'openid', 'hydra.*']);

        $this->consentToken->expects($this->once())
            ->method('getRequestId')
            ->willReturn($requestId = 'test_consent_request_id');

        $this->endpointService->expects($this->once())
            ->method('getConsentAcceptRequestEndpoint')
            ->with($this->equalTo($requestId))
            ->willReturn($url = 'http://test/oauth2/consent/' . $requestId . '/accept');

        $this->oAuth2Provider->expects($this->once())
            ->method('getAuthenticatedRequest')
            ->with(
                $this->equalTo(Request::METHOD_PATCH),
                $this->equalTo($url),
                $this->equalTo($this->accessToken),
                $this->arrayHasKey('body')
            )
            ->willReturn($this->request);

        $this->oAuth2Provider->expects($this->once())
            ->method('getResponse')
            ->with($this->request)
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_NOT_FOUND);

        $this->oAuth2Service->acceptConsentRequest($this->consentToken, $this->userToken);
    }
    /**
     * @covers ::rejectConsentRequest
     */
    public function testRejectConsentRequest()
    {
        $requestId = 'test-consent-request-id';
        $this->endpointService->expects($this->once())
            ->method('getConsentRejectRequestEndpoint')
            ->with($this->equalTo($requestId))
            ->willReturn($url = 'http://test/oauth2/consent/' . $requestId . '/reject');

        $this->oAuth2Provider->expects($this->once())
            ->method('getAuthenticatedRequest')
            ->with(
                $this->equalTo(Request::METHOD_PATCH),
                $this->equalTo($url),
                $this->equalTo($this->accessToken),
                $this->arrayHasKey('body')
            )
            ->willReturn($this->request);

        $this->oAuth2Provider->expects($this->once())
            ->method('getResponse')
            ->with($this->request)
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_NO_CONTENT);

        $this->oAuth2Service->rejectConsentRequest($requestId, 'invalid scope');
    }

    /**
     * @expectedException \RuntimeException
     * @covers ::rejectConsentRequest
     */
    public function testRejectConsentRequestException()
    {
        $requestId = 'test-consent-request-id';
        $this->endpointService->expects($this->once())
            ->method('getConsentRejectRequestEndpoint')
            ->with($this->equalTo($requestId))
            ->willReturn($url = 'http://test/oauth2/consent/' . $requestId . '/reject');

        $this->oAuth2Provider->expects($this->once())
            ->method('getAuthenticatedRequest')
            ->with(
                $this->equalTo(Request::METHOD_PATCH),
                $this->equalTo($url),
                $this->equalTo($this->accessToken),
                $this->arrayHasKey('body')
            )
            ->willReturn($this->request);

        $this->oAuth2Provider->expects($this->once())
            ->method('getResponse')
            ->with($this->request)
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_NOT_FOUND);

        $this->oAuth2Service->rejectConsentRequest($requestId, 'invalid scope');
    }
}
