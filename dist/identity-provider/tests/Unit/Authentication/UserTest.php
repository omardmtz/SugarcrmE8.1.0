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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication;

use Sugarcrm\IdentityProvider\Authentication\User;

/**
 * Class UserTest.
 * The source of this test is:
 * @see Symfony\Component\Security\Core\Tests\User\UserTest
 *
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\User
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = new User('user1', 'user1password');
    }

    /**
     * The username can be empty.
     */
    public function testConstructor()
    {
        new User('', '');
    }

    /**
     * Test user roles by default.
     */
    public function testGetRoles()
    {
        $this->assertNotEmpty($this->user->getRoles());
    }

    /**
     * Test default user roles.
     */
    public function testGetDefaultRoles()
    {
        $this->assertNotEmpty(User::getDefaultRoles());
    }

    public function testGetPassword()
    {
        $this->assertEquals('user1password', $this->user->getPassword());
    }

    public function testGetUsername()
    {
        $this->assertEquals('user1', $this->user->getUsername());
    }

    public function testGetSalt()
    {
        $this->assertEquals('', $this->user->getSalt());
    }

    public function testEraseCredentials()
    {
        $this->user->eraseCredentials();
        $this->assertEmpty($this->user->getPassword());
    }

    public function testToString()
    {
        $this->assertEquals('user1', (string) $this->user);
    }

    public function testAttributes()
    {
        $user = new User('user1', 'user1password', ['attr1' => 1, 'attr2' => 2]);

        $this->assertNotEmpty($user->getAttributes());
        $this->assertNotEmpty($user->getAttribute('attr1'));
        $this->assertTrue($user->hasAttribute('attr2'));

        $user->setAttribute('attr2', 22);
        $this->assertEquals(22, $user->getAttribute('attr2'));

        $user->removeAttribute('attr2');
        $this->assertFalse($user->hasAttribute('attr2'));
    }

    /**
     * @covers ::isAccountNonExpired
     */
    public function testIsAccountNonExpired()
    {
        $this->assertTrue($this->user->isAccountNonExpired());
    }

    /**
     * @covers ::isAccountNonLocked
     */
    public function testIsAccountNonLocked()
    {
        $this->assertTrue($this->user->isAccountNonLocked());
    }

    /**
     * @covers ::isCredentialsNonExpired
     */
    public function testIsCredentialsNonExpired()
    {
        $this->assertTrue($this->user->isCredentialsNonExpired());
    }

    /**
     * @covers ::isEnabled
     */
    public function testIsEnabled()
    {
        $this->assertTrue($this->user->isEnabled());
    }

    /**
     * @covers ::getLocalUser
     */
    public function testGetLocalUser()
    {
        $user = new User('barry', '');
        $this->assertNull($user->getLocalUser());

        $user = new User('max', '');
        $user->setAttribute('id', 'max-id');
        $this->assertNotNull($user->getLocalUser());
        $this->assertEquals('max-id', $user->getLocalUser()->getAttribute('id'));

        $user = new User('jim', '');
        $localCorrespondingUser = new User('jim', '');
        $localCorrespondingUser->setAttribute('id', 'jim-id');
        $user->setLocalUser($localCorrespondingUser);
        $this->assertNotNull($user->getLocalUser());
        $this->assertEquals('jim-id', $user->getLocalUser()->getAttribute('id'));
    }

    /**
     * @covers ::getOidcAttribute
     */
    public function testGetOidcAttribute()
    {
        $user = new User('test', 'test', ['attributes' => [
            'preferred_username' => 'test',
        ]]);
        $this->assertEquals('test', $user->getOidcAttribute('preferred_username'));
        $this->assertNull($user->getOidcAttribute('not_exists'));
        $this->assertNull($this->user->getOidcAttribute('not_exists'));
    }

    /**
     * @covers ::setSrn
     * @covers ::getSrn
     */
    public function testSrn()
    {
        $srn = 'srn:cloud:idp:eu:2000000001:user:583b2229-9eb0-4129-9315-34487f22b5b0';
        $this->user->setSrn($srn);
        $this->assertEquals($srn, $this->user->getSrn());
    }
}
