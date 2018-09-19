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

use Sugarcrm\IdentityProvider\App\Authentication\AuthProviderManagerBuilder;
use Sugarcrm\IdentityProvider\Authentication\Provider\MixedAuthenticationProvider;
use Sugarcrm\IdentityProvider\Authentication\Provider\LdapAuthenticationProvider;
use Sugarcrm\IdentityProvider\Authentication\Token\MixedUsernamePasswordToken;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\Provider\MixedAuthenticationProvider
 */
class MixedAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LdapAuthenticationProvider | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ldapProvider;

    /**
     * @var DaoAuthenticationProvider | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $localProvider;

    /**
     * @var MixedAuthenticationProvider
     */
    protected $provider;

    /**
     * @var MixedUsernamePasswordToken
     */
    protected $mixedToken;

    /**
     * @var UsernamePasswordToken | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $localToken;

    /**
     * @var UsernamePasswordToken | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ldapToken;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->ldapProvider = $this->createMock(LdapAuthenticationProvider::class);
        $this->localProvider = $this->createMock(DaoAuthenticationProvider::class);
        $this->provider = new MixedAuthenticationProvider(
            [$this->ldapProvider, $this->localProvider],
            AuthProviderManagerBuilder::PROVIDER_KEY_MIXED
        );

        $this->mixedToken = new MixedUsernamePasswordToken(
            'username',
            'password',
            AuthProviderManagerBuilder::PROVIDER_KEY_MIXED
        );

        $this->localToken = $this->createMock(UsernamePasswordToken::class);
        $this->ldapToken = $this->createMock(UsernamePasswordToken::class);
    }

    /**
     * Provides data for testSupports.
     *
     * @return array
     */
    public function supportsProvider()
    {
        return [
            'supportedToken' => [
                'class' => MixedUsernamePasswordToken::class,
                'key' => AuthProviderManagerBuilder::PROVIDER_KEY_MIXED,
                'result' => true,
            ],
            'supportedClassUnsupportedKey' => [
                'class' => MixedUsernamePasswordToken::class,
                'key' => 'UnsupportedKey',
                'result' => false,
            ],
            'unsupportedClassSupportedKey' => [
                'class' => UsernamePasswordToken::class,
                'key' => AuthProviderManagerBuilder::PROVIDER_KEY_MIXED,
                'result' => false,
            ],
        ];
    }

    /**
     * @param string $class
     * @param string $key
     * @param bool $result
     *
     * @dataProvider supportsProvider
     * @covers ::supports
     */
    public function testSupports($class, $key, $result)
    {
        $token = new $class('username', 'password', $key);
        $this->assertEquals($result, $this->provider->supports($token));
    }

    /**
     * @covers ::authenticate
     * @expectedException \Symfony\Component\Security\Core\Exception\ProviderNotFoundException
     */
    public function testAuthenticateWithoutAnyAdditionalToken()
    {
        $this->provider->authenticate($this->mixedToken);
    }

    /**
     * @covers ::authenticate
     * @expectedException \Symfony\Component\Security\Core\Exception\ProviderNotFoundException
     */
    public function testAuthenticateWithoutAnySuitableToken()
    {
        $this->localToken->method('getProviderKey')->willReturn('providerKey');
        $this->mixedToken->setAttribute('mixedAuthTokens', [$this->localToken]);
        $this->provider->authenticate($this->mixedToken);
    }

    /**
     * @covers ::authenticate
     */
    public function testSuccessAuthenticateThroughProvider()
    {
        $resultToken = $this->createMock(UsernamePasswordToken::class);
        $this->localToken->method('getProviderKey')
                         ->willReturn(AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL);
        $this->localProvider->method('supports')->with($this->localToken)->willReturn(true);
        $this->localProvider->expects($this->once())
                            ->method('authenticate')
                            ->with($this->localToken)
                            ->willReturn($resultToken);

        $this->mixedToken->addToken($this->localToken);
        $this->assertEquals($resultToken, $this->provider->authenticate($this->mixedToken));
    }

    /**
     * @covers ::authenticate
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testFailedAuthenticateThroughProvider()
    {
        $this->localToken->method('getProviderKey')
                         ->willReturn(AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL);
        $this->localProvider->method('supports')->with($this->localToken)->willReturn(true);
        $this->localProvider->expects($this->once())
                            ->method('authenticate')
                            ->with($this->localToken)
                            ->willThrowException(new AuthenticationException());

        $this->mixedToken->addToken($this->localToken);
        $this->provider->authenticate($this->mixedToken);
    }

    /**
     * @covers ::authenticate
     */
    public function testSuccessAuthenticateThroughSeveralProviders()
    {
        $resultToken = $this->createMock(UsernamePasswordToken::class);

        $this->localToken->method('getProviderKey')
            ->willReturn(AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL);
        $this->ldapToken->method('getProviderKey')
            ->willReturn(AuthProviderManagerBuilder::PROVIDER_KEY_LDAP);

        $this->localProvider->method('supports')->willReturnMap(
            [
                [$this->ldapToken, false],
                [$this->localToken, true],
            ]
        );
        $this->ldapProvider->method('supports')->willReturnMap(
            [
                [$this->ldapToken, true],
                [$this->localToken, false],
            ]
        );

        $this->ldapProvider->expects($this->once())
            ->method('authenticate')
            ->with($this->ldapToken)
            ->willThrowException(new AuthenticationException());

        $this->localProvider->expects($this->once())
            ->method('authenticate')
            ->with($this->localToken)
            ->willReturn($resultToken);

        $this->mixedToken->addToken($this->ldapToken);
        $this->mixedToken->addToken($this->localToken);
        $this->assertEquals($resultToken, $this->provider->authenticate($this->mixedToken));
    }

    /**
     * @covers ::authenticate
     */
    public function testSuccessAuthenticateWithFirstTokenWin()
    {
        $this->localToken->method('getProviderKey')
            ->willReturn(AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL);
        $this->ldapToken->method('getProviderKey')
            ->willReturn(AuthProviderManagerBuilder::PROVIDER_KEY_LDAP);

        $this->localProvider->method('supports')->willReturnMap(
            [
                [$this->ldapToken, false],
                [$this->localToken, true],
            ]
        );
        $this->ldapProvider->method('supports')->willReturnMap(
            [
                [$this->ldapToken, true],
                [$this->localToken, false],
            ]
        );

        $this->ldapProvider->method('authenticate')
            ->with($this->ldapToken)
            ->willReturn($this->ldapToken);

        $this->localProvider->method('authenticate')
            ->with($this->localToken)
            ->willReturn($this->localToken);

        $this->mixedToken->addToken($this->ldapToken);
        $this->mixedToken->addToken($this->localToken);
        $resultToken = $this->provider->authenticate($this->mixedToken);
        $this->assertSame($this->ldapToken, $resultToken);
    }
}
