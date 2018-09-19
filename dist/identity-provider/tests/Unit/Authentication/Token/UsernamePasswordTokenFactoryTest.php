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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\Token;

use Sugarcrm\IdentityProvider\App\Authentication\AuthProviderManagerBuilder;
use Sugarcrm\IdentityProvider\Authentication\Token\MixedUsernamePasswordToken;
use Sugarcrm\IdentityProvider\Authentication\Token\UsernamePasswordTokenFactory;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @coversDefaultClass Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Token\UsernamePasswordTokenFactory
 */
class UsernamePasswordTokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::createAuthenticationToken
     */
    public function testCreateMixedAuthenticationToken()
    {
        $tokenFactory = new UsernamePasswordTokenFactory(
            ['enabledProviders' => ['local', 'ldap']],
            'username',
            'password'
        );

        $token = $tokenFactory->createAuthenticationToken();

        $this->assertInstanceOf(MixedUsernamePasswordToken::class, $token);
        $this->assertEquals('username', $token->getUsername());
        $this->assertEquals('password', $token->getCredentials());
        $this->assertEquals(AuthProviderManagerBuilder::PROVIDER_KEY_MIXED, $token->getProviderKey());

        $authTokens = $token->getTokens();

        $this->assertInstanceOf(UsernamePasswordToken::class, $authTokens[0]);
        $this->assertEquals('username', $authTokens[0]->getUsername());
        $this->assertEquals('password', $authTokens[0]->getCredentials());
        $this->assertEquals(AuthProviderManagerBuilder::PROVIDER_KEY_LDAP, $authTokens[0]->getProviderKey());

        $this->assertEquals('username', $authTokens[1]->getUsername());
        $this->assertEquals('password', $authTokens[1]->getCredentials());
        $this->assertEquals(AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL, $authTokens[1]->getProviderKey());
    }

    /**
     * @covers ::createAuthenticationToken
     */
    public function testCreateAuthenticationTokenWithOneLocalProvider()
    {
        $tokenFactory = new UsernamePasswordTokenFactory(
            ['enabledProviders' => ['local']],
            'username',
            'password'
        );

        $token = $tokenFactory->createAuthenticationToken();

        $this->assertInstanceOf(UsernamePasswordToken::class, $token);
        $this->assertEquals('username', $token->getUsername());
        $this->assertEquals('password', $token->getCredentials());
        $this->assertEquals(AuthProviderManagerBuilder::PROVIDER_KEY_LOCAL, $token->getProviderKey());
    }

    /**
     * @covers ::createAuthenticationToken
     */
    public function testCreateAuthenticationTokenWithOneLdapProvider()
    {
        $tokenFactory = new UsernamePasswordTokenFactory(
            ['enabledProviders' => ['ldap']],
            'username',
            'password'
        );

        $token = $tokenFactory->createAuthenticationToken();

        $this->assertInstanceOf(UsernamePasswordToken::class, $token);
        $this->assertEquals('username', $token->getUsername());
        $this->assertEquals('password', $token->getCredentials());
        $this->assertEquals(AuthProviderManagerBuilder::PROVIDER_KEY_LDAP, $token->getProviderKey());
    }
}
