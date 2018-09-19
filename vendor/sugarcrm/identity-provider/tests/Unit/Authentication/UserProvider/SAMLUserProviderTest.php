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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\UserProvider;

use Sugarcrm\IdentityProvider\Authentication\UserProvider\SAMLUserProvider;
use Sugarcrm\IdentityProvider\Authentication\User;

/**
 * @coversDefaultClass SAMLUserProvider
 */
class SAMLUserProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsClass()
    {
        $provider = new SAMLUserProvider();
        $this->assertTrue($provider->supportsClass(User::class));
    }

    public function testLoadUserByUsername()
    {
        $provider = new SAMLUserProvider();
        $user = $provider->loadUserByUsername('onelogin@onelogin.com');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('onelogin@onelogin.com', $user->getUsername());
    }

    public function testRefreshUser()
    {
        $provider = new SAMLUserProvider();
        $user = new User('onelogin@onelogin.com');
        $user = $provider->refreshUser($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('onelogin@onelogin.com', $user->getUsername());
    }
}
