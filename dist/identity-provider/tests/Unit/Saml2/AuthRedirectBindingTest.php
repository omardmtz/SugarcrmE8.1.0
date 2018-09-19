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

use Sugarcrm\IdentityProvider\Saml2\AuthRedirectBinding;
use Sugarcrm\IdentityProvider\Saml2\AuthResult;
use Sugarcrm\IdentityProvider\Saml2\Builder\RequestBuilder;
use Sugarcrm\IdentityProvider\Saml2\Builder\ResponseBuilder;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;
use Sugarcrm\IdentityProvider\Saml2\Request\AuthnRequest;

/**
 * Class AuthRedirectBindingTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\Saml2
 * @coversDefaultClass Sugarcrm\IdentityProvider\Saml2\AuthRedirectBinding
 */
class AuthRedirectBindingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AuthRedirectBinding | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $auth = null;

    /**
     * @var AuthRedirectBinding | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $authMocked = null;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var \OneLogin_Saml2_LogoutRequest | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $logoutRequest  = null;

    /**
     * @var \OneLogin_Saml2_LogoutResponse | \PHPUnit_Framework_MockObject_MockObject
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
     * @var array
     */
    protected $parameters = [
        'parameter1' => 'parameter1Value',
        'parameter2' => 'parameter2Value',
    ];

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
     * @var string
     */
    protected $returnTo = 'http://localhost:8000/returnToSomeValue';

    /**
     * @var string
     */
    protected $signature = 'SomeSignature';

    /**
     * @var string
     */
    protected $samlRequest = '<saml>Request.xml</saml>';

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
     * @var string
     */
    protected $expectedUrl = 'http://some.expected?url=string';

    /**
     * @var string
     */
    protected $selfRoutedRelayState = 'http://localhost:8000/self/routed/relay/state';

    /**
     * @var string
     */
    protected $requestId = 'someRequestId';

    /**
     * @var AuthnRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $authnRequest;

    /**
     * @var \OneLogin_Saml2_Settings|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $authSettings;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $settings = [
            'sp' => [
                'entityId' => 'spEntityId',
                'assertionConsumerService' => [
                    'url' => 'http://sp.com/acs',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_POST,
                ],
                'singleLogoutService' => [
                    'url' => 'http://sp.com/logout',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'NameIDFormat' => \OneLogin_Saml2_Constants::NAMEID_EMAIL_ADDRESS,
            ],
            'idp' => [
                'entityId' => 'idpEntityId',
                'singleSignOnService' => [
                    'url' => 'http://idp.com/saml/sso',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'singleLogoutService' => [
                    'url' => 'http://idp.com/saml/slo',
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'x509cert' => 'dummyCert',
            ],
            'security' => [
                'validateRequestId' => true,
            ],
        ];

        $this->settingsObject = $this->getMockBuilder(\OneLogin_Saml2_Settings::class)
                                     ->setConstructorArgs([$settings])
                                     ->setMethods(['getSecurityData'])
                                     ->getMock();

        $this->auth = $this->getMockBuilder(AuthRedirectBinding::class)
                           ->setConstructorArgs([$settings])
                           ->setMethods([
                                            'getRequestBuilder',
                                            'getResponseBuilder',
                                            'getSettings',
                                            'buildResponseSignature',
                                        ])->getMock();

        $this->auth->method('getSettings')->willReturn($this->settingsObject);

        $this->logoutRequest = $this->getMockBuilder(\OneLogin_Saml2_LogoutRequest::class)
                                    ->setConstructorArgs([$this->auth->getSettings()])
                                    ->getMock();

        $this->logoutResponse = $this->getMockBuilder(\OneLogin_Saml2_LogoutResponse::class)
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

        $this->settings = IDMFixturesHelper::getOktaParameters();
        $this->authMocked = $this->getMockBuilder(AuthRedirectBinding::class)
            ->setMethods([
                'getRequestBuilder',
                'getSettings',
                'buildRequestSignature',
                'redirectTo',
                'getSSOurl',
                'getSelfRoutedURLNoQuery'
            ])
            ->setConstructorArgs([$this->settings])
            ->getMock();
        $this->authSettings = $this->getMockBuilder(\OneLogin_Saml2_Settings::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authMocked->method('getRequestBuilder')->willReturn($this->requestBuilder);
        $this->authnRequest = $this->getMockBuilder(AuthnRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->authMocked
            ->method('getSSOurl')
            ->willReturn($this->settings['idp']['singleSignOnService']['url']);
        $this->authMocked
            ->method('getSettings')
            ->willReturn($this->authSettings);
        $this->authnRequest
            ->method('getRequest')
            ->willReturn($this->samlRequest);
        $this->authnRequest
            ->method('getId')
            ->willReturn($this->requestId);
        $this->authMocked
            ->method('getSelfRoutedURLNoQuery')
            ->willReturn($this->selfRoutedRelayState);
    }

    /**
     * Checks parameters that generated for logout.
     *
     * @covers ::logout
     */
    public function testLogout()
    {
        $returnTo = 'http://return.test';
        $result = $this->auth->logout($returnTo, []);
        $this->assertNotEmpty($result->getUrl());
        $this->assertNotEmpty($result->getMethod());
        $this->assertEquals([], $result->getAttributes());
        $this->assertEquals('GET', $result->getMethod());
        $this->assertContains('http://idp.com/saml/slo?SAMLRequest=', $result->getUrl());
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
        $this->requestBuilder->expects($this->once())->method('buildLogoutRequest')->willReturn(null);
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
        return [
            'responseNotSignedRelayStateIsNull' => [
                'response' => 'testResponse',
                'relayState' => null,
                'security' => [
                    'logoutResponseSigned' => false,
                    'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                ],
                'expectedResult' => [
                    'http://idp.com/saml/slo?SAMLResponse=testResponse',
                    'GET',
                    [],
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
                    'http://idp.com/saml/slo?SAMLResponse=testResponse&RelayState=http%3A%2F%2Frelay.state',
                    'GET',
                    [],
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
                    'http://idp.com/saml/slo?SAMLResponse=testResponse&' .
                        'RelayState=http%3A%2F%2Frelay.state&' .
                        'SigAlg=http%3A%2F%2Fwww.w3.org%2F2001%2F04%2Fxmldsig-more%23rsa-sha256&Signature=signature',
                    'GET',
                    [],
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
                $this->equalTo($this->setNameIdPolicy))
            ->willReturn($this->authnRequest);
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
        $this->authMocked
            ->expects($this->once())
            ->method('redirectTo')
            ->with(
                $this->equalTo($this->settings['idp']['singleSignOnService']['url']),
                $this->equalTo($expectedParameters),
                $this->isTrue()
            )
            ->willReturn($this->expectedUrl);
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
        $this->assertEquals($this->expectedUrl, $result->getUrl());
        $this->assertEquals('GET', $result->getMethod());
        $this->assertEquals([], $result->getAttributes());
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
            )->willReturn($this->authnRequest);
        $this->authSettings
            ->method('getSecurityData')
            ->willReturn($this->settingUnSigned);
        $this->authMocked
            ->expects($this->never())
            ->method('buildRequestSignature');
        $this->authMocked
            ->expects($this->once())
            ->method('redirectTo')
            ->with(
                $this->equalTo($this->settings['idp']['singleSignOnService']['url']),
                $this->equalTo($expectedParameters),
                $this->isTrue()
            )
            ->willReturn($this->expectedUrl);
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
        $this->assertEquals($this->expectedUrl, $result->getUrl());
        $this->assertEquals('GET', $result->getMethod());
        $this->assertEquals([], $result->getAttributes());
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

        $auth = $this->getMockBuilder(AuthRedirectBinding::class)
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
        $auth = new AuthRedirectBinding($settings);

        $this->assertEquals($expected, $auth->isRequestIdValidationNeeded());
    }
}
