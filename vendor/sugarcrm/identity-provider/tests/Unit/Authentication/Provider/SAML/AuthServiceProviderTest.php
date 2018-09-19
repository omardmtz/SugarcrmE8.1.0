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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\Provider\SAML;

use Sugarcrm\IdentityProvider\Authentication\Provider\SAML\AuthServiceProvider;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ActionTokenInterface;
use Sugarcrm\IdentityProvider\Saml2\AuthPostBinding;
use Sugarcrm\IdentityProvider\Saml2\AuthRedirectBinding;
use Sugarcrm\IdentityProvider\Tests\IDMFixturesHelper;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\Provider\SAML\AuthServiceProvider
 *
 */
class AuthServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @covers ::__construct()
     */
    public function testWrongConfig()
    {
        $authServiceProvider = new AuthServiceProvider([]);
    }

    public function getAuthServiceDataProvider()
    {
        $baseConfig = IDMFixturesHelper::getOktaParameters();
        return [
            'validConfigWithLoginAction' => [
                'config' => $baseConfig,
                'tokenAction' => ActionTokenInterface::LOGIN_ACTION,
                'expectedInstance' => AuthRedirectBinding::class,
            ],
            'validConfigWithLogoutAction' => [
                'config' => $baseConfig,
                'tokenAction' => ActionTokenInterface::LOGOUT_ACTION,
                'expectedInstance' => AuthPostBinding::class,
            ],
        ];
    }

    /**
     * Checks getAuthService logic.
     *
     * @param $config
     * @param $tokenAction
     * @param $expectedInstance
     *
     * @covers ::getAuthService
     * @dataProvider getAuthServiceDataProvider
     */
    public function testGetAuthService($config, $tokenAction, $expectedInstance)
    {
        $authServiceProvider = new AuthServiceProvider($config);

        $token = $this->createMock(ActionTokenInterface::class);
        $token->expects($this->any())
            ->method('getAction')
            ->willReturn($tokenAction);

        $authService = $authServiceProvider->getAuthService($token);
        $this->assertInstanceOf($expectedInstance, $authService);
    }

    /**
     * Checks getAuthService logic when it executed several times.
     *
     * @covers ::getAuthService
     */
    public function testGetAuthServiceSeveralTimes()
    {
        $authServiceProvider = $this->getMockBuilder(AuthServiceProvider::class)
                                    ->setConstructorArgs([IDMFixturesHelper::getOktaParameters()])
                                    ->setMethods(['buildAuthService'])->getMock();
        $token = $this->createMock(ActionTokenInterface::class);
        $token->expects($this->exactly(3))
              ->method('getAction')
              ->willReturnOnConsecutiveCalls(
                  ActionTokenInterface::LOGIN_ACTION,
                  ActionTokenInterface::LOGOUT_ACTION,
                  ActionTokenInterface::LOGIN_ACTION
              );

        $authServicePostMock = $this->getMockBuilder(AuthPostBinding::class)->disableOriginalConstructor()->getMock();
        $authServiceRedirectMock = $this->getMockBuilder(AuthRedirectBinding::class)
                                        ->disableOriginalConstructor()
                                        ->getMock();

        $authServiceProvider->expects($this->exactly(2))
                            ->method('buildAuthService')
                            ->withConsecutive(
                                [ActionTokenInterface::LOGIN_ACTION],
                                [ActionTokenInterface::LOGOUT_ACTION]
                            )->willReturnOnConsecutiveCalls($authServiceRedirectMock, $authServicePostMock);

        $authServiceOne = $authServiceProvider->getAuthService($token);
        $this->assertInstanceOf(AuthRedirectBinding::class, $authServiceOne);
        $authServiceTwo = $authServiceProvider->getAuthService($token);
        $this->assertInstanceOf(AuthPostBinding::class, $authServiceTwo);
        $authServiceOne = $authServiceProvider->getAuthService($token);
        $this->assertInstanceOf(AuthRedirectBinding::class, $authServiceOne);
    }

    public function getAuthServiceInvalidConfigDataProvider()
    {
        $invalidLoginConfig = $invalidLogoutConfig =$baseConfig = IDMFixturesHelper::getOktaParameters();
        unset($invalidLoginConfig['idp']['singleSignOnService']);
        unset($invalidLogoutConfig['idp']['singleLogoutService']);
        return [
            'invalidConfig' => [
                'config' => [],
                'tokenAction' => ActionTokenInterface::LOGIN_ACTION,
            ],
            'invalidConfigWithLoginAction' => [
                'config' => $invalidLoginConfig,
                'tokenAction' => ActionTokenInterface::LOGIN_ACTION,
            ],
            'invalidConfigWithLogoutAction' => [
                'config' => $invalidLogoutConfig,
                'tokenAction' => ActionTokenInterface::LOGOUT_ACTION,
            ],
        ];
    }

    /**
     * Checks getAuthService logic when parameters are invalid.
     *
     * @param $config
     * @param $tokenAction
     *
     * @covers ::getAuthService
     * @dataProvider getAuthServiceInvalidConfigDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testGetAuthServiceWithInvalidConfig($config, $tokenAction)
    {
        $authServiceProvider = new AuthServiceProvider($config);

        $token = $this->createMock(ActionTokenInterface::class);
        $token->expects($this->any())
              ->method('getAction')
              ->willReturn($tokenAction);

        $authServiceProvider->getAuthService($token);
    }
}
