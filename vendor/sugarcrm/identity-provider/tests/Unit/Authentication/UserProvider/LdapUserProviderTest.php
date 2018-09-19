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

use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LdapUserProvider;
use Symfony\Component\Ldap\Adapter\CollectionInterface;
use Symfony\Component\Ldap\Adapter\QueryInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @requires extension ldap
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\UserProvider\LdapUserProvider
 */
class LdapUserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LdapInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ldap;

    /**
     * @var TokenInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $token;

    /**
     * @var LdapUserProvider
     */
    protected $userProvider;

    /**
     * @var CollectionInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $entries;

    /**
     * @var QueryInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $query;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->ldap = $this->getMockBuilder(LdapInterface::class)->getMock();
        $this->ldap->method('escape')->willReturn('username');
        $this->token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $this->token->method('getUsername')->willReturn('username');
        $this->token->method('getCredentials')->willReturn('password');
        $this->entries = $this->getMockBuilder(CollectionInterface::class)->getMock();
        $this->query = $this->getMockBuilder(QueryInterface::class)->getMock();
        $this->userProvider = new LdapUserProvider(
            $this->ldap,
            'dn',
            null,
            null,
            [],
            'userPrincipalName'
        );
    }
    /**
     * LdapUserProvider should return Sugarcrm\IdentityProvider\Authentication\User object.
     * LdapUserProvider should fill User's attribute "entry" with Symfony\Component\Ldap\Entry object.
     */
    public function testLoadUser()
    {
        $entry = new Entry('dn', ['userPrincipalName' => 'uuid']);

        $this->query->method('execute')->willReturn([$entry]);
        $this->ldap->method('escape')->willReturnArgument(0);
        $this->ldap->method('query')->willReturn($this->query);

        $user = $this->userProvider->loadUserByUsername('user1');
        $this->assertTrue($user instanceof User);
        /** @var User $user */
        $this->assertSame($entry, $user->getAttribute('entry'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @covers ::loadUserByToken
     */
    public function testLoadUserByTokenLdapConnectionFails()
    {
        $this->ldap->expects($this->once())
                   ->method('bind')->with('username', 'password')
                   ->willThrowException(new ConnectionException());
        $this->userProvider->loadUserByToken($this->token);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessage User "username" not found.
     *
     * @covers ::loadUserByToken
     */
    public function testLoadUserByTokenNoEntriesFound()
    {
        $this->ldap->expects($this->once())->method('bind')->with('username', 'password');
        $this->query->expects($this->once())->method('execute')->willReturn($this->entries);
        $this->entries->expects($this->once())->method('count')->willReturn(0);
        $this->ldap->expects($this->once())->method('query')->willReturn($this->query);
        $this->userProvider->loadUserByToken($this->token);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessage More than one user found
     *
     * @covers ::loadUserByToken
     */
    public function testLoadUserByTokenMoreThanEntriesFound()
    {
        $this->ldap->expects($this->once())->method('bind')->with('username', 'password');
        $this->query->expects($this->once())->method('execute')->willReturn($this->entries);
        $this->entries->expects($this->once())->method('count')->willReturn(2);
        $this->ldap->expects($this->once())->method('query')->willReturn($this->query);
        $this->userProvider->loadUserByToken($this->token);
    }

    /**
     * @covers ::loadUserByToken
     */
    public function testLoadUserByTokenShouldNotFailIfEntryHasNoUidKeyAttribute()
    {
        $this->ldap->expects($this->once())->method('bind')->with('username', 'password');
        $this->ldap->expects($this->once())->method('query')->willReturn($this->query);
        $this->query->expects($this->once())->method('execute')->willReturn($this->entries);
        $this->entries->expects($this->once())->method('count')->willReturn(1);
        $this->entries->expects($this->once())
                      ->method('offsetGet')
                      ->with(0)
                      ->willReturn(new Entry('username', array()));
        $result = $this->userProvider->loadUserByToken($this->token);
        $this->assertInstanceOf('Sugarcrm\IdentityProvider\Authentication\User', $result);
    }
}
