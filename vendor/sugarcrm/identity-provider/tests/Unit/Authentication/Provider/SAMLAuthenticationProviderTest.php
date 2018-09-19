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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\Provider;

use PHPUnit_Framework_TestCase;
use Sugarcrm\IdentityProvider\Authentication\Exception\SAMLResponseException;
use Sugarcrm\IdentityProvider\Authentication\Provider\SAMLAuthenticationProvider;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ConsumeLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\AcsToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\IdpLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\InitiateLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\InitiateToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ResultToken;
use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\SAMLUserProvider;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sugarcrm\IdentityProvider\Saml2\Request\AuthnRequest;
use Sugarcrm\IdentityProvider\Authentication\UserMapping\SAMLUserMapping;
use Sugarcrm\IdentityProvider\Authentication\User\SAMLUserChecker;

/**
 * Class covers all step of SAML authentication.
 *
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\Provider\SAMLAuthenticationProvider
 */
class SAMLAuthenticationProviderTest extends PHPUnit_Framework_TestCase
{
    /** @var SAMLUserProvider | \PHPUnit_Framework_MockObject_MockObject */
    protected $samlUserProvider = null;
    /**
     * @var \OneLogin_Saml2_Response|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $response = null;

    /**
     * @var SessionInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $session = null;

    /**
     * @var SAMLUserMapping
     */
    protected $userMapping = null;

    /**
     * @var SAMLUserChecker
     */
    protected $samlUserChecker;

    /**
     * @var User
     */
    protected $user;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = $this->createMock(User::class);
        $this->samlUserProvider = $this->createMock(SAMLUserProvider::class);
        $this->samlUserProvider->method('loadUserByUsername')->willReturn($this->user);
        $this->samlUserChecker = $this->createMock(SAMLUserChecker::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->response = $this->createMock(\OneLogin_Saml2_Response::class);
        $this->userMapping = $this->createMock(SAMLUserMapping::class);
    }

    /**
     * Provides valid settings for tests.
     * @see testInitiateLogin
     * @return array
     */
    public function samlSettingsProvider()
    {
        return [
            'neededValidationRequestId' => [
                'settings' => array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => true]]
                ),
                'setIdInvocation' => $this->once(),
            ],
            'notNeededValidationRequestId' => [
                'settings' => array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => false]]
                ),
                'setIdInvocation' => $this->never(),
            ],
        ];
    }

    /**
     * @dataProvider samlSettingsProvider
     * @covers ::initiateLogin
     * @param array $settings
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $setIdInvocation
     */
    public function testInitiateLogin(array $settings, $setIdInvocation)
    {
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );

        $token = new InitiateToken();
        $this->session->expects($setIdInvocation)->method('set')->with(
            $this->equalTo(SAMLAuthenticationProvider::REQUEST_ID_KEY),
            $this->callback(function ($id) {
                $this->assertStringStartsWith(AuthnRequest::REQUEST_ID_PREFIX, $id);
                $this->assertEquals(AuthnRequest::REQUEST_ID_LENGTH, strlen($id));
                return true;
            })
        );

        $returnTo = 'http://local.host';
        $token->setAttribute('returnTo', $returnTo);
        $returnedToken = $samlProvider->authenticate($token);

        $this->assertFalse($returnedToken->isAuthenticated());

        $ssoUrl = $settings['idp']['singleSignOnService']['url'];
        $this->assertContains($ssoUrl . '?SAMLRequest', $returnedToken->getAttribute('url'));
        $this->assertContains('RelayState=' . urlencode($returnTo), $returnedToken->getAttribute('url'));
    }

    /**
     * Provides set of data for check POST binding initiate logout logic.
     *
     * @return array
     */
    public function initiatePostLogoutProvider()
    {
        $oktaSettings = IDMFixturesHelper::getOktaParameters();
        return [
            'oktaWithRelayState' => [
                'settings' => $oktaSettings,
                'returnTo' => 'http://test.com',
                'expectedUrl' => $oktaSettings['idp']['singleLogoutService']['url'],
                'expectedMethod' => 'POST',
            ],
            'oktaWithoutRelayState' => [
                'settings' => $oktaSettings,
                'returnTo' => null,
                'expectedUrl' => $oktaSettings['idp']['singleLogoutService']['url'],
                'expectedMethod' => 'POST',
            ],
        ];
    }

    /**
     * @param array $settings
     * @param string $returnTo
     * @param string $expectedUrl
     * @param string $expectedMethod
     *
     * @dataProvider initiatePostLogoutProvider
     * @covers ::initiateLogout
     */
    public function testInitiateLogoutPostBinding(array $settings, $returnTo, $expectedUrl, $expectedMethod)
    {
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $token = new InitiateLogoutToken();
        $token->setAttribute('returnTo', $returnTo);

        $returnedToken = $samlProvider->authenticate($token);

        $this->assertContains($expectedUrl, $returnedToken->getAttribute('url'));
        $this->assertEquals($expectedMethod, $returnedToken->getAttribute('method'));

        $parameters = $returnedToken->getAttribute('parameters');
        $this->assertArrayHasKey('SAMLRequest', $parameters);
        if ($returnTo) {
            $this->assertArrayHasKey('RelayState', $parameters);
            $this->assertEquals($returnTo, $parameters['RelayState']);
        } else {
            $this->assertArrayNotHasKey('RelayState', $parameters);
        }
    }

    /**
     * Provides set of data for check REDIRECT binding initiate logout logic.
     *
     * @return array
     */
    public function initiateRedirectLogoutProvider()
    {
        $oneLoginSettings = IDMFixturesHelper::getOneLoginParameters();
        return [
            'OneLoginWithRelayState' => [
                'settings' => $oneLoginSettings,
                'returnTo' => 'http://test.com',
                'expectedUrl' => $oneLoginSettings['idp']['singleLogoutService']['url'].'?SAMLRequest=',
                'expectedMethod' => 'GET',
            ],
        ];
    }

    /**
     * @param array $settings
     * @param string $returnTo
     * @param string $expectedUrl
     * @param string $expectedMethod
     *
     * @dataProvider initiateRedirectLogoutProvider
     * @covers ::initiateLogout
     */
    public function testInitiateLogoutRedirectBinding(array $settings, $returnTo, $expectedUrl, $expectedMethod)
    {
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $token = new InitiateLogoutToken();
        $token->setAttribute('returnTo', $returnTo);

        $returnedToken = $samlProvider->authenticate($token);

        $this->assertContains($expectedUrl, $returnedToken->getAttribute('url'));
        $this->assertEquals($expectedMethod, $returnedToken->getAttribute('method'));
        $this->assertContains('RelayState='.$returnTo, urldecode($returnedToken->getAttribute('url')));
    }

    /**
     * @return array
     */
    public function logoutConsumeProvider()
    {
        $oktaLoginResponse = IDMFixturesHelper::getSAMLFixture('Okta/SignedAssertion/Response.xml');
        $oktaLogoutResponse = IDMFixturesHelper::getSAMLFixture('Okta/Logout/LogoutResponse.xml');
        $adfsLoginResponse = IDMFixturesHelper::getSAMLFixture('ADFS/SignedResponse/Response.xml');

        return [
            'validOktaLoginResponse' => [
                'settings' => IDMFixturesHelper::getOktaParameters(),
                'response' => base64_encode($oktaLoginResponse),
                'expectedException' => AuthenticationException::class,
            ],
            'validOktaLogoutResponse' => [
                'settings' => IDMFixturesHelper::getOktaParameters(),
                'response' => base64_encode($oktaLogoutResponse),
                'expectedException' => null,
            ],
            'invalidResponse' => [
                'settings' => IDMFixturesHelper::getOktaParameters(),
                'response' => base64_encode($adfsLoginResponse),
                'expectedException' => AuthenticationException::class,
            ],
        ];
    }

    /**
     * @param array $settings
     * @param $response
     * @param $expectedException
     *
     * @dataProvider logoutConsumeProvider
     */
    public function testLogoutConsume(array $settings, $response, $expectedException)
    {
        if ($expectedException) {
            $this->expectException($expectedException);
        }
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $logoutToken = new ConsumeLogoutToken($response);

        $returnedToken = $samlProvider->authenticate($logoutToken);

        $this->assertFalse($returnedToken->isAuthenticated());
    }

    /**
     * Provides valid settings for test.
     * @see testConsume
     * @return array
     */
    public function consumeProvider()
    {
        return [
            'neededValidationRequestId' => [
                'settings' => array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => true]]
                ),
                'requestId' => 'ONELOGIN_124d7f4dc1ee343111c5b134c4e9e93d3a2a2a07',
                'removeIdInvocation' => $this->once(),
            ],
            'notNeededValidationRequestId' => [
                'settings' => array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => false]]
                ),
                'requestId' => null,
                'removeIdInvocation' => $this->never(),
            ],
        ];
    }
    
    /**
     * Checks consume logic.
     * @dataProvider consumeProvider
     * @covers ::consume
     * @param array $settings
     * @param string|null $requestId
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $removeIdInvocation
     */
    public function testConsume(array $settings, $requestId, $removeIdInvocation)
    {
        $response = base64_encode(IDMFixturesHelper::getSAMLFixture('Okta/SignedAssertion/Response.xml'));
        $idpSessionIndex =  'ONELOGIN_124d7f4dc1ee343111c5b134c4e9e93d3a2a2a07';

        /** @var SAMLAuthenticationProvider|\PHPUnit_Framework_MockObject_MockObject $samlProvider */
        $samlProvider = $this->getMockBuilder(SAMLAuthenticationProvider::class)
            ->setMethods(['buildLoginResponse'])
            ->setConstructorArgs([
                $settings,
                $this->samlUserProvider,
                $this->samlUserChecker,
                $this->session,
                $this->userMapping
            ])
            ->getMock();
        $samlProvider->method('buildLoginResponse')->willReturn($this->response);

        $this->response
            ->method('isValid')
            ->with($requestId)
            ->willReturn(true);
        $this->response
            ->method('getSessionIndex')
            ->willReturn($idpSessionIndex);

        $this->session->expects($removeIdInvocation)
            ->method('remove')
            ->with(SAMLAuthenticationProvider::REQUEST_ID_KEY)
            ->willReturn($requestId);

        $token = $this->getMockBuilder(AcsToken::class)
            ->setConstructorArgs([$response])
            ->setMethods(['setUser', 'setAuthenticated', 'setAttribute'])
            ->setMockClassName('SAMLAcsToken')
            ->getMock();
        $user = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();

        $this->samlUserProvider->expects($this->once())->method('loadUserByUsername')->willReturn($user);

        $result = $samlProvider->authenticate($token);

        $this->assertEquals($idpSessionIndex, $result->getAttribute('IdPSessionIndex'));
        $this->assertInstanceOf(User::class, $result->getUser());
        $this->assertTrue($result->isAuthenticated());
    }

    /**
     * @covers ::consume
     * @expectedException Sugarcrm\IdentityProvider\Authentication\Exception\SAMLResponseException
     */
    public function testConsumeInvalidResponse()
    {
        $response = base64_encode(IDMFixturesHelper::getSAMLFixture('Okta/SignedAssertion/Response.xml'));

        /** @var SAMLAuthenticationProvider|\PHPUnit_Framework_MockObject_MockObject $samlProvider */
        $samlProvider = $this->getMockBuilder(SAMLAuthenticationProvider::class)
            ->setMethods(['buildLoginResponse'])
            ->setConstructorArgs([
                array_replace_recursive(
                    IDMFixturesHelper::getOktaParameters(),
                    ['security' => ['validateRequestId' => true]]
                ),
                $this->samlUserProvider,
                $this->samlUserChecker,
                $this->session,
                $this->userMapping
            ])
            ->getMock();
        $samlProvider->method('buildLoginResponse')->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->response->expects($this->once())
            ->method('getError')
            ->willReturn('error');

        $token = $this->getMockBuilder(AcsToken::class)
            ->setConstructorArgs([$response])
            ->setMockClassName('SAMLAcsToken')
            ->getMock();

        $samlProvider->authenticate($token);
    }

    /**
     * Checks consume logic when response is invalid.
     *
     * @expectedException Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testConsumeWithInvalidResponse()
    {
        $settings = IDMFixturesHelper::getOktaParameters();
        $response = base64_encode(IDMFixturesHelper::getSAMLFixture('ADFS/SignedResponse/Response.xml'));
        $this->session->expects($this->once())
            ->method('remove')
            ->with(SAMLAuthenticationProvider::REQUEST_ID_KEY)
            ->willReturn('someRequestId');
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $token = $this->getMockBuilder(AcsToken::class)
                      ->setConstructorArgs([$response])
                      ->setMethods(['setUser', 'setAuthenticated', 'setAttribute'])
                      ->setMockClassName('SAMLAcsToken')
                      ->getMock();
        $samlProvider->authenticate($token);
    }

    /**
     * @dataProvider samlSettingsAndResponseWithUserAttributesProvider
     *
     * @param $settings
     * @param $mapping
     * @param $response
     * @param $expectedAttributes
     */
    public function testConsumeWithUserAttributes($settings, $mapping, $response, $expectedAttributes)
    {
        $userProvider = $this->createMock(SAMLUserProvider::class);
        $userProvider->method('loadUserByUsername')->willReturn(new User('foo'));
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $userProvider,
            $this->samlUserChecker,
            $this->session,
            new SAMLUserMapping($mapping)
        );
        $token = new AcsToken($response);
        $userAttributes = $samlProvider->authenticate($token)->getUser()->getAttribute('attributes');
        ksort($userAttributes);
        ksort($expectedAttributes);
        $this->assertEquals($expectedAttributes, $userAttributes);
    }

    /**
     * @return array
     */
    public function samlSettingsAndResponseWithUserAttributesProvider()
    {
        $oktaResponse = IDMFixturesHelper::getSAMLFixture('Okta/SignedAssertionWithUserAttributes/Response.xml');
        $oneLoginResponse = IDMFixturesHelper::getSAMLFixture('OneLogin/SignedResponseWithUserAttributes/Response.xml');
        $adfsLoginResponse = IDMFixturesHelper::getSAMLFixture('ADFS/SignedResponseWithUserAttributes/Response.xml');
        return [
            'Okta Identity Provider' => [
                IDMFixturesHelper::getOktaParameters(),
                ['name_id' => 'email', 'attribute1' => 'title', 'attribute2' => 'department'],
                base64_encode($oktaResponse),
                ['email' => 'sugarcrm.idm.developer@gmail.com', 'title' => 'Foo', 'department' => 'Bar'],
            ],
            'Onelogin Identity Provider' => [
                IDMFixturesHelper::getOneLoginParameters(),
                ['name_id' => 'email', 'attribute1' => 'department'],
                base64_encode($oneLoginResponse),
                ['email' => 'sugarcrm.idm.developer@gmail.com', 'department' => 'Development'],
            ],
            'ADFS Identity Provider' => [
                IDMFixturesHelper::getADFSParameters(),
                ['name_id' => 'email', 'surname' => 'last_name', 'givenname' => 'first_name'],
                base64_encode($adfsLoginResponse),
                ['email' => 'sugardeveloper@test.com', 'last_name' => 'Developer', 'first_name' => 'Sugar'],
            ],
        ];
    }

    /**
     * @covers ::consume
     */
    public function testConsumeUsesPostAuth()
    {
        $settings = IDMFixturesHelper::getOktaParameters();
        $response = base64_encode(IDMFixturesHelper::getSAMLFixture('Okta/SignedAssertion/Response.xml'));
        $token = $this->getMockBuilder(AcsToken::class)
            ->setConstructorArgs([$response])
            ->setMethods(['setUser', 'setAuthenticated', 'setAttribute'])
            ->setMockClassName('SAMLAcsToken')
            ->getMock();
        $this->user->expects($this->exactly(5))
            ->method('setAttribute')
            ->withConsecutive(
                ['provision', $this->anything()],
                ['identityField', $this->anything()],
                ['identityValue', $this->anything()],
                ['attributes', $this->anything()],
                ['XMLResponse', $this->anything()]
            );
        $this->samlUserChecker->expects($this->once())
            ->method('checkPostAuth')
            ->with($this->user);
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $samlProvider->authenticate($token);
    }
    /**
     * @covers ::consume
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage User was not matched to db-user
     */
    public function testConsumeReactsOnPostAuthException()
    {
        $settings = IDMFixturesHelper::getOktaParameters();
        $response = base64_encode(IDMFixturesHelper::getSAMLFixture('Okta/SignedAssertion/Response.xml'));
        $token = $this->getMockBuilder(AcsToken::class)
            ->setConstructorArgs([$response])
            ->setMockClassName('SAMLAcsToken')
            ->getMock();
        $this->samlUserChecker
            ->method('checkPostAuth')
            ->will($this->throwException(new AuthenticationException('User was not matched to db-user')));
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $samlProvider->authenticate($token);
    }

    /**
     * Checks idpLogout logic when request is not valid.
     *
     * @expectedException Sugarcrm\IdentityProvider\Authentication\Exception\SAMLRequestException
     * @covers ::idpLogout
     */
    public function testIdpLogoutWithInvalidRequest()
    {
        $request = base64_encode(IDMFixturesHelper::getSAMLFixture('OneLogin/Logout/idpLogoutRequest.xml'));
        $settings = IDMFixturesHelper::getOktaParameters();
        $settings['strict'] = true;

        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $token = $this->getMockBuilder(IdpLogoutToken::class)
                      ->setConstructorArgs([$request])
                      ->setMethods(['setAuthenticated', 'setAttribute'])
                      ->getMock();
        $samlProvider->authenticate($token);
    }

    /**
     * Checks idpLogout logic for redirect binding.
     *
     * @covers ::idpLogout
     */
    public function testRedirectBindingIdpLogout()
    {
        $request = base64_encode(IDMFixturesHelper::getSAMLFixture('OneLogin/Logout/idpLogoutRequest.xml'));
        $settings = IDMFixturesHelper::getOneLoginParameters();
        $expectedResult = [
            'url' =>
                'https://sugarcrm-idmeloper-dev.onelogin.com/trust/saml2/http-redirect/slo/622315?SAMLResponse=',
            'method' => 'GET',
            'parameters' => [],
        ];
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );

        $token = $this->getMockBuilder(IdpLogoutToken::class)
                      ->setConstructorArgs([$request])
                      ->setMethods(['setAuthenticated', 'setAttribute'])
                      ->getMock();

        $result = $samlProvider->authenticate($token);

        $this->assertInstanceOf(ResultToken::class, $result);
        $this->assertContains($expectedResult['url'], $result->getAttribute('url'));
        $this->assertEquals($expectedResult['method'], $result->getAttribute('method'));
        $this->assertEquals($expectedResult['parameters'], $result->getAttribute('parameters'));
        $this->assertFalse($result->isAuthenticated());
    }

    /**
     * Checks idpLogout logic for post binding.
     *
     * @covers ::idpLogout
     */
    public function testPostBindingIdpLogout()
    {
        $request = base64_encode(IDMFixturesHelper::getSAMLFixture('Okta/Logout/idpLogoutRequest.xml'));
        $settings = IDMFixturesHelper::getOktaParameters();
        $expectedResult = [
            'url' =>
                'https://dev-432366.oktapreview.com/app/sugarcrmdev432366_sugarcrmidmdev_1/exk9f6zk3cchXSMkP0h7/slo/saml',
            'method' => 'POST',
            'parameters' => [
                'SAMLResponse' => '',
            ],
        ];
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );

        $token = $this->getMockBuilder(IdpLogoutToken::class)
                      ->setConstructorArgs([$request])
                      ->setMethods(['setAuthenticated', 'setAttribute'])
                      ->getMock();

        $result = $samlProvider->authenticate($token);

        $this->assertInstanceOf(ResultToken::class, $result);
        $this->assertContains($expectedResult['url'], $result->getAttribute('url'));
        $this->assertEquals($expectedResult['method'], $result->getAttribute('method'));
        $this->assertArrayHasKey('SAMLResponse', $result->getAttribute('parameters'));
        $this->assertFalse($result->isAuthenticated());
    }

    /**
     * Test to check that SAML provider supports only valid tokens.
     *
     * @dataProvider samlSettingsProvider
     * @param array $settings
     */
    public function testSupports(array $settings)
    {
        $samlProvider = new SAMLAuthenticationProvider(
            $settings,
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $this->assertTrue($samlProvider->supports(new InitiateToken()));
        $this->assertTrue($samlProvider->supports(new AcsToken('')));
        $this->assertTrue($samlProvider->supports(new InitiateLogoutToken()));
        $this->assertTrue($samlProvider->supports(new ConsumeLogoutToken('')));
        $this->assertFalse($samlProvider->supports(new UsernamePasswordToken('username', 'password', 'saml')));
    }

    /**
     * Exception must be thrown when no authentication service for some IdP configured.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage  Invalid authentication services configuration
     */
    public function testExceptionWhenNoAuthenticationServiceConfigured()
    {
        $samlProvider = new SAMLAuthenticationProvider(
            [],
            $this->samlUserProvider,
            $this->samlUserChecker,
            $this->session,
            $this->userMapping
        );
        $token = new InitiateToken();
        $token->setAttribute('returnTo', 'http://local.host');
        $samlProvider->authenticate($token);
    }
}
