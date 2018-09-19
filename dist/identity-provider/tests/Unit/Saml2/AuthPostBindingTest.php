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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Saml2;

use Sugarcrm\IdentityProvider\Saml2\AuthPostBinding;
use Sugarcrm\IdentityProvider\Saml2\AuthResult;
use Sugarcrm\IdentityProvider\Saml2\Builder\RequestBuilder;
use Sugarcrm\IdentityProvider\Saml2\Builder\ResponseBuilder;
use Sugarcrm\IdentityProvider\Saml2\Request\LogoutPostRequest;
use Sugarcrm\IdentityProvider\Saml2\Response\LogoutPostResponse;
use Sugarcrm\IdentityProvider\Saml2\Request\AuthnRequest;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;

/**
 * Class AuthPostBindingTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\Saml2
 * @coversDefaultClass Sugarcrm\IdentityProvider\Saml2\AuthPostBinding
 */
class AuthPostBindingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $returnTo = 'http://localhost:8000/returnToSomeValue';

    /**
     * @var string
     */
    protected $signature = 'SomeSignature';

    /**
     * @var array
     */
    protected $settingSigned = [
        'authnRequestsSigned' => true,
        'wantAssertionsSigned' => false,
        'signMetadata' => false,
        'signatureAlgorithm' => 'signatureAlgorithm',
    ];

    /**
     * @var array
     */
    protected $settingUnSigned = [
        'authnRequestsSigned' => false,
        'wantAssertionsSigned' => false,
        'signMetadata' => false,
        'signatureAlgorithm' => 'signatureAlgorithm',
    ];

    /**
     * @var bool
     */
    protected $setNameIdPolicy = true;

    /**
     * @var bool
     */
    protected $isPassive = false;

    /**
     * @var bool
     */
    protected $forceAuthn = false;

    /**
     * @var array
     */
    protected $parameters = [
        'parameter1' => 'parameter1Value',
        'parameter2' => 'parameter2Value',
    ];

    /**
     * @var string
     */
    protected $expectedUrl = 'http://some.expected?url=string';

    /**
     * @var string
     */
    protected $samlRequest = '<saml>Request.xml</saml>';

    /**
     * @var string
     */
    protected $requestId = 'someRequestId';

    /**
     * @var string
     */
    protected $selfRoutedRelayState = 'http://localhost:8000/self/routed/relay/state';

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var AuthPostBinding | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $auth = null;

    /**
     * @var LogoutPostRequest | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $logoutRequest  = null;

    /**
     * @var LogoutPostResponse | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $logoutResponse  = null;

    /**
     * @var RequestBuilder | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestBuilder  = null;

    /**
     * @var ResponseBuilder | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $responseBuilder = null;

    /**
     * @var \OneLogin_Saml2_Settings | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $settingsObject = null;

    /**
     * @var \OneLogin_Saml2_Settings|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $authSettings;

    /**
     * @var AuthPostBinding|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $authMocked;

    /**
     * @var AuthnRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $authnRequest;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->settings = IDMFixturesHelper::getOktaParameters();

        $this->settingsObject = $this->getMockBuilder(\OneLogin_Saml2_Settings::class)
                               ->setConstructorArgs([$this->settings])
                               ->setMethods(['getSecurityData'])
                               ->getMock();

        $this->auth = $this->getMockBuilder(AuthPostBinding::class)
                           ->setConstructorArgs([$this->settings])
                           ->setMethods([
                                            'getRequestBuilder',
                                            'getResponseBuilder',
                                            'getSettings',
                                            'buildResponseSignature',
                                        ])
                           ->getMock();

        $this->auth->method('getSettings')->willReturn($this->settingsObject);

        $this->logoutRequest = $this->getMockBuilder(LogoutPostRequest::class)
                                    ->setConstructorArgs([$this->auth->getSettings()])
                                    ->getMock();

        $this->logoutResponse = $this->getMockBuilder(LogoutPostResponse::class)
                                     ->setConstructorArgs([$this->auth->getSettings()])
                                     ->getMock();

        $this->requestBuilder = $this->getMockBuilder(RequestBuilder::class)
                                     ->setConstructorArgs([$this->auth])
                                     ->getMock();

        $this->responseBuilder = $this->getMockBuilder(ResponseBuilder::class)
                                      ->setConstructorArgs([$this->auth])
                                      ->getMock();

        $this->logoutRequest->method('getRequest')->willReturn('generatedRequest');
        $this->auth->method('getRequestBuilder')->willReturn($this->requestBuilder);
        $this->auth->method('getResponseBuilder')->willReturn($this->responseBuilder);
        
        $this->authSettings = $this->getMockBuilder(\OneLogin_Saml2_Settings::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authnRequest = $this->getMockBuilder(AuthnRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authnRequest
            ->method('getId')
            ->willReturn($this->requestId);
        $this->authnRequest
            ->method('getRequest')
            ->willReturn($this->samlRequest);

        $this->authMocked = $this->getMockBuilder(AuthPostBinding::class)
            ->setMethods([
                'getRequestBuilder',
                'getSettings',
                'buildRequestSignature',
                'redirectTo',
                'getSSOurl',
                'getSelfRoutedURLNoQuery'
            ])
            ->disableOriginalConstructor()
            ->getMock();
        $this->authMocked
            ->method('getRequestBuilder')
            ->willReturn($this->requestBuilder);
        $this->authMocked
            ->method('getSettings')
            ->willReturn($this->authSettings);
        $this->authMocked
            ->method('getSelfRoutedURLNoQuery')
            ->willReturn($this->selfRoutedRelayState);
        $this->authMocked
            ->method('getSSOurl')
            ->willReturn($this->settings['idp']['singleSignOnService']['url']);
    }

    /**
     * Checks parameters that generated for logout.
     *
     * @covers ::logout
     */
    public function testLogoutWithRelayState()
    {
        $returnTo = 'http://return.test';
        $nameId = 'test@return.test';
        $sessionIndex = 'sessionIndex';

        $this->requestBuilder->expects($this->once())
                             ->method('buildLogoutRequest')
                             ->with(null, ['nameId' => $nameId, 'sessionIndex' => $sessionIndex])
                             ->willReturn($this->logoutRequest);

        $result = $this->auth->logout($returnTo, [], $nameId, $sessionIndex);
        $this->assertNotEmpty($result->getUrl());
        $this->assertNotEmpty($result->getMethod());
        $this->assertNotEmpty($result->getAttributes());
        $this->assertEquals('POST', $result->getMethod());
        $attributes = $result->getAttributes();
        $this->assertEquals($this->settings['idp']['singleLogoutService']['url'], $result->getUrl());
        $this->assertEquals($returnTo, $attributes['RelayState']);
        $this->assertEquals('generatedRequest', $attributes['SAMLRequest']);
    }

    /**
     * Checks parameters that generated for logout.
     *
     * @covers ::logout
     */
    public function testLogoutWithoutRelayState()
    {
        $returnTo = null;
        $nameId = 'test@return.test';
        $sessionIndex = 'sessionIndex';

        $this->requestBuilder->expects($this->once())
                             ->method('buildLogoutRequest')
                             ->with(null, ['nameId' => $nameId, 'sessionIndex' => $sessionIndex])
                             ->willReturn($this->logoutRequest);

        $result = $this->auth->logout($returnTo, [], $nameId, $sessionIndex);
        $this->assertNotEmpty($result->getUrl());
        $this->assertNotEmpty($result->getMethod());
        $this->assertNotEmpty($result->getAttributes());
        $attributes = $result->getAttributes();
        $this->assertArrayNotHasKey('RelayState', $attributes);
        $this->assertEquals('POST', $result->getMethod());
        $this->assertEquals($this->settings['idp']['singleLogoutService']['url'], $result->getUrl());
        $this->assertEquals('generatedRequest', $attributes['SAMLRequest']);
    }

    /**
     * Checking behaviour when SAML response is not created by some reasons.
     *
     * @expectedException \OneLogin_Saml2_Error
     * @expectedExceptionMessage SAML response is not valid
     *
     * @covers ::processServiceSLO
     */
    public function testProcessServiceSLOLogoutResponseNotCreated()
    {
        $response = 'testResponse';
        $this->responseBuilder->expects($this->once())->method('buildLogoutResponse')->with($response)->willReturn(null);
        $this->auth->processServiceSLO($response);
    }

    /**
     * Checking behaviour when SAML response is not valid.
     *
     * @expectedException \OneLogin_Saml2_Error
     * @expectedExceptionMessage test error
     *
     * @covers ::processServiceSLO
     */
    public function testProcessServiceSLOLogoutResponseNotValid()
    {
        $response = 'testResponse';
        $this->responseBuilder->expects($this->once())
                              ->method('buildLogoutResponse')
                              ->with($response)
                              ->willReturn($this->logoutResponse);
        $this->logoutResponse->expects($this->once())->method('isValid')->willReturn(false);
        $this->logoutResponse->expects($this->once())->method('getError')->willReturn('test error');
        $this->auth->processServiceSLO($response);
    }

    /**
     * Checking behaviour when SAML response is not success.
     *
     * @expectedException \OneLogin_Saml2_Error
     * @expectedExceptionMessage SAML response is not success
     *
     * @covers ::processServiceSLO
     */
    public function testProcessServiceSLOLogoutResponseNotSuccess()
    {
        $response = 'testResponse';
        $this->responseBuilder->expects($this->once())
                              ->method('buildLogoutResponse')
                              ->with($response)
                              ->willReturn($this->logoutResponse);
        $this->logoutResponse->expects($this->once())->method('isValid')->willReturn(true);
        $this->logoutResponse->expects($this->once())->method('getStatus')->willReturn('STATUS_ERROR');
        $this->auth->processServiceSLO($response);
    }

    /**
     * Checking behaviour when SAML response is success.
     *
     * @covers ::processServiceSLO
     */
    public function testProcessServiceSLOLogoutResponseSuccess()
    {
        $response = 'testResponse';
        $this->responseBuilder->expects($this->once())
                              ->method('buildLogoutResponse')
                              ->with($response)
                              ->willReturn($this->logoutResponse);
        $this->logoutResponse->expects($this->once())->method('isValid')->willReturn(true);
        $this->logoutResponse->expects($this->once())
                             ->method('getStatus')
                             ->willReturn(\OneLogin_Saml2_Constants::STATUS_SUCCESS);
        $result = $this->auth->processServiceSLO($response);
        $this->assertEquals($result, $this->logoutResponse);
    }

    /**
     * Checking behaviour when SAML request is not created by some reasons.
     *
     * @expectedException \OneLogin_Saml2_Error
     * @expectedExceptionMessage SAML request is not valid
     *
     * @covers ::processIdpSLO
     */
    public function testProcessIdpSLORequestNotCreated()
    {
        $request = 'testRequest';
        $this->requestBuilder->expects($this->once())->method('buildLogoutRequest')->with($request)->willReturn(null);
        $this->auth->processIdpSLO($request);
    }

    /**
     * Checking behaviour when SAML request is not created by some reasons.
     *
     * @expectedException \OneLogin_Saml2_Error
     * @expectedExceptionMessage SAML request is not valid
     *
     * @covers ::processIdpSLO
     */
    public function testProcessIdpSLORequestNotValid()
    {
        $request = 'testRequest';
        $this->requestBuilder->expects($this->once())
                             ->method('buildLogoutRequest')
                             ->with($request)
                             ->willReturn($this->logoutRequest);
        $this->logoutRequest->expects($this->once())->method('isValid')->willReturn(false);
        $this->auth->processIdpSLO($request);
    }

    /**
     * Provides data for testProcessIdpSLO
     * @return array
     */
    public function processIdpSLOProvider()
    {
        $settings = IDMFixturesHelper::getOktaParameters();
        return [
            'responseNotSignedRelayStateIsNull' => [
                'response' => 'testResponse',
                'relayState' => null,
                'security' => [
                    'logoutResponseSigned' => false,
                    'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                ],
                'expectedResult' => [
                    $settings['idp']['singleLogoutService']['url'],
                    'POST',
                    [
                        'SAMLResponse' => 'testResponse',
                    ],
                ],
            ],
            'responseNotSignedRelayStateIsNotNull' => [
                'response' => 'testResponse',
                'relayState' => 'http://relay.state',
                'security' => [
                    'logoutResponseSigned' => false,
                    'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                ],
                'expectedResult' => [
                    $settings['idp']['singleLogoutService']['url'],
                    'POST',
                    [
                        'SAMLResponse' => 'testResponse',
                        'RelayState' => 'http://relay.state',
                    ],
                ],
            ],
            'responseSignedRelayStateIsNotNull' => [
                'response' => 'testResponse',
                'relayState' => 'http://relay.state',
                'security' => [
                    'logoutResponseSigned' => true,
                    'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                ],
                'expectedResult' => [
                    $settings['idp']['singleLogoutService']['url'],
                    'POST',
                    [
                        'SAMLResponse' => 'testResponse',
                        'RelayState' => 'http://relay.state',
                        'Signature' => 'signature',
                        'SigAlg' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $response
     * @param string $relayState
     * @param array $security
     * @param array $expectedResult
     *
     * @dataProvider processIdpSLOProvider
     *
     * @covers ::processIdpSLO
     */
    public function testProcessIdpSLO($response, $relayState, array $security, array $expectedResult)
    {
        $request = 'testRequest';
        $this->logoutRequest->id = 'logoutRequestId';
        $this->logoutRequest->expects($this->once())->method('isValid')->willReturn(true);
        $this->requestBuilder->expects($this->once())
                             ->method('buildLogoutRequest')
                             ->with($request)
                             ->willReturn($this->logoutRequest);

        $this->responseBuilder->expects($this->once())
                              ->method('buildLogoutResponse')
                              ->willReturn($this->logoutResponse);

        $this->settingsObject->method('getSecurityData')->willReturn($security);
        $this->logoutResponse->expects($this->once())->method('build')->with('logoutRequestId');
        $this->auth->method('buildResponseSignature')
                   ->with($this->logoutResponse, $relayState, $security['signatureAlgorithm'])
                   ->willReturn('signature');
        $this->logoutResponse->expects($this->once())->method('getResponse')->willReturn($response);
        $result = $this->auth->processIdpSLO($request, $relayState);
        $this->assertInstanceOf(AuthResult::class, $result);
        $this->assertEquals($expectedResult[0], $result->getUrl());
        $this->assertEquals($expectedResult[1], $result->getMethod());
        $this->assertEquals($expectedResult[2], $result->getAttributes());
    }

    /**
     * Testing login signed
     *
     * @covers ::login
     */
    public function testLoginSigned()
    {
        $expectedParameters = $this->parameters + [
                'SAMLRequest' => $this->samlRequest,
                'RelayState' => $this->returnTo,
                'SigAlg' => $this->settingSigned['signatureAlgorithm'],
                'Signature' => $this->signature,
            ];

        $this->requestBuilder
            ->method('buildLoginRequest')
            ->with(
                $this->equalTo($this->forceAuthn), 
                $this->equalTo($this->isPassive),
                $this->equalTo($this->setNameIdPolicy)
            )->willReturn($this->authnRequest);
        $this->authSettings
            ->method('getSecurityData')
            ->willReturn($this->settingSigned);
        $this->authMocked
            ->expects($this->once())
            ->method('buildRequestSignature')
            ->with(
                $this->equalTo($this->samlRequest),
                $this->equalTo($this->returnTo),
                $this->equalTo($this->settingSigned['signatureAlgorithm'])
            )
            ->willReturn($this->signature);

        $result = $this->authMocked->login(
            $this->returnTo,
            $this->parameters,
            $this->forceAuthn,
            $this->isPassive,
            true,
            $this->setNameIdPolicy
        );

        $this->assertEquals($this->requestId, $this->authMocked->getLastRequestID());
        $this->assertInstanceOf(AuthResult::class, $result);
        $this->assertEquals($this->settings['idp']['singleSignOnService']['url'], $result->getUrl());
        $this->assertEquals('POST', $result->getMethod());
        $this->assertEquals($expectedParameters, $result->getAttributes());
    }

    /**
     * Testing login unsigned
     *
     * @covers ::login
     */
    public function testLoginUnSignedSelfRouted()
    {
        $expectedParameters = $this->parameters + [
                'SAMLRequest' => $this->samlRequest,
                'RelayState' => $this->selfRoutedRelayState,
            ];

        $this->requestBuilder
            ->method('buildLoginRequest')
            ->with(
                $this->equalTo($this->forceAuthn),
                $this->equalTo($this->isPassive),
                $this->equalTo($this->setNameIdPolicy)
            )
            ->willReturn($this->authnRequest);
        $this->authnRequest
            ->method('getId')
            ->willReturn($this->requestId);
        $this->authnRequest
            ->method('getRequest')
            ->willReturn($this->samlRequest);
        $this->authSettings
            ->method('getSecurityData')
            ->willReturn($this->settingUnSigned);
        $this->authMocked
            ->expects($this->never())
            ->method('buildRequestSignature');
        $this->authMocked
            ->expects($this->once())
            ->method('getSSOurl')
            ->willReturn($this->settings['idp']['singleSignOnService']['url']);

        $result = $this->authMocked->login(
            null,
            $this->parameters,
            $this->forceAuthn,
            $this->isPassive,
            true,
            $this->setNameIdPolicy
        );

        $this->assertEquals($this->requestId, $this->authMocked->getLastRequestID());
        $this->assertInstanceOf(AuthResult::class, $result);
        $this->assertEquals($this->settings['idp']['singleSignOnService']['url'], $result->getUrl());
        $this->assertEquals('POST', $result->getMethod());
        $this->assertEquals($expectedParameters, $result->getAttributes());
    }

    /**
     * @return array
     */
    public function isUserProvisionNeededDataProvider()
    {
        return [
            'provisionUser not specified' => [
                [],
                false,
            ],
            'provisionUser true' => [
                [
                    'provisionUser' => true,
                ],
                true,
            ],
            'provisionUser false' => [
                [
                    'provisionUser' => false,
                ],
                false,
            ]
        ];
    }

    /**
     * @covers ::isUserProvisionNeeded
     * @dataProvider isUserProvisionNeededDataProvider
     *
     * @param array $config
     * @param bool $result
     */
    public function testIsUserProvisionNeeded($config, $result)
    {
        $settingsObject = $this->getMockBuilder(\OneLogin_Saml2_Settings::class)
            ->disableOriginalConstructor()
            ->getMock();
        $settingsObject->method('getSPData')->willReturn($config);

        $auth = $this->getMockBuilder(AuthPostBinding::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSettings'])
            ->getMock();
        $auth->method('getSettings')->willReturn($settingsObject);


        $this->assertEquals($result, $auth->isUserProvisionNeeded());
    }


    /**
     * @see testIsRequestIdValidationNeeded
     * @return array
     */
    public function neededValidationRequestIdProvider()
    {
        $settingsNotExists = IDMFixturesHelper::getOktaParameters();
        $settingsNotExists['security'] = [];
        return [
            'true' => [
                'settings' => array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => true]]
                ),
                'expected' => true,
            ],
            'false' => [
                'settings' => array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => false]]
                ),
                'expected' => false,
            ],
            'null' => [
                'settings' => array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => null]]
                ),
                'expected' => false,
            ],
            'notExists' => [
                'settings' => $settingsNotExists,
                'expected' => false,
            ],
        ];
    }

    /**
     * Testing isRequestIdValidationNeeded
     *
     * @dataProvider neededValidationRequestIdProvider
     * @covers ::isRequestIdValidationNeeded
     * @param array $settings
     * @param boolean $expected
     */
    public function testIsRequestIdValidationNeeded($settings, $expected)
    {
        $auth = new AuthPostBinding($settings);

        $this->assertEquals($expected, $auth->isRequestIdValidationNeeded());
    }
}
