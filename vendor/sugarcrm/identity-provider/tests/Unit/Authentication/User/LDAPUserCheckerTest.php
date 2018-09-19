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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\User;

use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Authentication\User\LDAPUserChecker;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LocalUserProvider;
use Sugarcrm\IdentityProvider\Authentication\Provider\Providers;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class LDAPUserCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var LDAPUserChecker
     */
    protected $userChecker;

    /**
     * LDAP configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var LocalUserProvider
     */
    protected $localUserProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = $this->createMock(User::class);
        $this->localUserProvider = $this->getMockBuilder(LocalUserProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userChecker = new LDAPUserChecker($this->localUserProvider, $this->config);
    }

    /**
     * @covers ::checkPostAuth
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessageRegExp ~User not found~
     */
    public function testUserIsNotCreatedIfAutoCreateUsersIsFalse()
    {
        $config = ['auto_create_users' => false];
        $this->localUserProvider->method('loadUserByFieldAndProvider')
            ->willThrowException(new UsernameNotFoundException('User not found'));
        $userChecker = new LDAPUserChecker($this->localUserProvider, $config);

        $userChecker->checkPostAuth($this->user);
    }

    /**
     * @covers ::checkPostAuth
     */
    public function testUserIsCreatedIfAutoCreateUsersIsTrue()
    {
        $user = new User('max', '', [
            'identityValue' => 'max',
            'attributes' => ['a' => 'b'],
        ]);
        $config = ['auto_create_users' => true];

        $this->localUserProvider->expects($this->once())
            ->method('loadUserByFieldAndProvider')
            ->with('max', Providers::LDAP)
            ->willThrowException(new UsernameNotFoundException('User not found'));

        $this->localUserProvider->expects($this->once())
            ->method('createUser')
            ->with('max', Providers::LDAP, ['a' => 'b'])
            ->willReturn($user);

        $userChecker = new LDAPUserChecker($this->localUserProvider, $config);
        $userChecker->checkPostAuth($user);
    }

    /**
     * @covers ::checkPostAuth
     */
    public function testUserIsCheckedForLocallyFoundOne()
    {
        $user = new User('max', '', [
            'identityValue' => 'max',
        ]);
        $localUser = new User('max', '', [
            'identityValue' => 'max',
        ]);

        $config = ['auto_create_users' => true];

        $this->localUserProvider->expects($this->once())
            ->method('loadUserByFieldAndProvider')
            ->with('max', Providers::LDAP)
            ->willReturn($localUser);

        $this->localUserProvider->expects($this->never())
            ->method('createUser');

        $userChecker = new LDAPUserChecker($this->localUserProvider, $config);
        $userChecker->checkPostAuth($user);
    }

    /**
     * @return array
     */
    public static function createUsersConfigProvider()
    {
        return [
            'create' => [true],
            'do-not-create' => [false],
        ];
    }

    /**
     * @dataProvider createUsersConfigProvider
     * @covers ::checkPostAuth
     *
     * @param bool $createUser
     */
    public function testLocalUserAttributesAreSetToOriginalUser($createUser)
    {
        $user = new User('max', '', [
            'identityValue' => 'max',
            'ldap-entry' => [],
        ]);
        $localUser = new User('max', '', [
            'id' => 'some-real-uuid',
            'some-db-field' => 'some-db-value',
        ]);

        $this->localUserProvider->method('loadUserByFieldAndProvider')->willReturn($localUser);
        $this->localUserProvider->method('createUser')->willReturn($user);

        $userChecker = new LDAPUserChecker($this->localUserProvider, ['auto_create_users' => $createUser]);
        $userChecker->checkPostAuth($user);

        $localUser = $user->getLocalUser();
        $this->assertNotNull($localUser);

        $this->assertArrayHasKey('identityValue', $user->getAttributes());
        $this->assertArrayHasKey('ldap-entry', $user->getAttributes());

        $this->assertArrayNotHasKey('identityValue', $localUser->getAttributes());
        $this->assertArrayNotHasKey('ldap-entry', $localUser->getAttributes());

        $this->assertArrayHasKey('id', $localUser->getAttributes());
        $this->assertEquals('some-real-uuid', $localUser->getAttribute('id'));
        $this->assertArrayHasKey('some-db-field', $localUser->getAttributes());
        $this->assertEquals('some-db-value', $localUser->getAttribute('some-db-field'));
    }
}
