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

use Sugarcrm\IdentityProvider\Authentication\Exception\RuntimeException;
use Sugarcrm\IdentityProvider\Authentication\Provider\LdapAuthenticationProvider;
use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LdapUserProvider;
use Sugarcrm\IdentityProvider\Authentication\UserMapping\LDAPUserMapping;
use Symfony\Component\Ldap\Adapter\QueryInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Ldap\Adapter\CollectionInterface;
use Symfony\Component\Ldap\Exception\LdapException;

/**
 * @requires extension ldap
 */
class LdapAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    protected $ldapConfig;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $userProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ldap;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ldapQuery;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $ldapCollection;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $userChecker;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mapper;

    public function setUp()
    {
        $this->ldapConfig = [
            'baseDn' => 'dc=openldap,dc=com',
            'entryAttribute' => null,
            'groupMembership' => true,
            'groupDn' => 'cn=Administrators,ou=groups,dc=openldap,dc=com',
            'userUniqueAttribute' => null,
            'groupAttribute' => 'member',
            'includeUserDN' => false,
        ];
        $this->userProvider = $this->getMockBuilder(LdapUserProvider::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $this->ldap = $this->getMockBuilder(LdapInterface::class)->getMock();
        $this->ldapQuery = $this->getMockBuilder(QueryInterface::class)->getMock();
        $this->ldapCollection = $this->getMockBuilder(CollectionInterface::class)->getMock();
        $this->userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();
        $this->mapper = $this->createMock(LDAPUserMapping::class);

        parent::setUp();
    }

    public function testLdapEntryNotFound()
    {
        $this->expectException(RuntimeException::class);

        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();
        $ldap = $this->getMockBuilder(LdapInterface::class)->getMock();
        $userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();

        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([$userProvider, $userChecker, 'key', $ldap, $this->mapper, '', true])
            ->setMethods(['retrieveUser'])
            ->getMock()
        ;
        $provider->method('retrieveUser')->willReturn(new User('user1', 'pass', []));

        $token = new UsernamePasswordToken('user1', 'pass', 'key');
        $provider->authenticate($token);
    }

    public function testLdapEntryAttributeNotFound()
    {
        $this->expectException(RuntimeException::class);

        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();
        $ldap = $this->getMockBuilder(LdapInterface::class)->getMock();
        $userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();

        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $userProvider,
                $userChecker,
                'key',
                $ldap,
                $this->mapper,
                '',
                true,
                ['entryAttribute' => 'attr1']
            ])
            ->setMethods(['retrieveUser'])
            ->getMock()
        ;
        $provider->method('retrieveUser')->willReturn(
            new User('user1', 'pass', ['entry' => new Entry('dn', [])])
        );

        $token = new UsernamePasswordToken('user1', 'pass', 'key');
        $provider->authenticate($token);
    }

    public function testEmptyPasswordShouldThrowAnException()
    {
        $this->expectException(BadCredentialsException::class);

        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();
        $ldap = $this->getMockBuilder(LdapInterface::class)->getMock();
        $userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();

        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([$userProvider, $userChecker, 'key', $ldap, $this->mapper, '', true])
            ->setMethods(['retrieveUser'])
            ->getMock()
        ;
        $provider->method('retrieveUser')->willReturn(
            new User('user1', '', ['entry' => new Entry('dn', [])])
        );

        $token = new UsernamePasswordToken('user1', '', 'key');
        $provider->authenticate($token);
    }

    public function testBindFailureShouldThrowAnException()
    {
        $this->expectException(BadCredentialsException::class);

        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();
        $ldap = $this->getMockBuilder(LdapInterface::class)->getMock();
        $ldap
            ->expects($this->once())
            ->method('bind')
            ->will($this->throwException(new ConnectionException()))
        ;
        $userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();

        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([$userProvider, $userChecker, 'key', $ldap, $this->mapper, '', true])
            ->setMethods(['retrieveUser'])
            ->getMock()
        ;
        $provider->method('retrieveUser')->willReturn(
            new User('user1', '', ['entry' => new Entry('dn', [])])
        );

        $token = new UsernamePasswordToken('user1', 'pass', 'key');
        $provider->authenticate($token);
    }

    /**
     * @dataProvider testDnForLdapBindDataProvider
     */
    public function testDnForLdapBind($username, $dnString, $entryDn, $entryAttribute, $entryAttributeValue, $expected)
    {
        $password = 'pass';

        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();
        $ldap = $this->getMockBuilder(LdapInterface::class)->getMock();
        $ldap->method('escape')->willReturnArgument(0);
        $ldap
            ->expects($this->once())
            ->method('bind')
            ->with($expected, $password)
        ;
        $userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();

        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                    $userProvider,
                    $userChecker,
                    'key',
                    $ldap,
                    $this->mapper,
                    $dnString,
                    true,
                    ['entryAttribute' => $entryAttribute],
            ])
            ->setMethods(['retrieveUser'])
            ->getMock()
        ;
        $provider->method('retrieveUser')->willReturn(
            new User(
                $username,
                '',
                ['entry' => new Entry($entryDn, [strtolower($entryAttribute) => $entryAttributeValue])]
            )
        );

        $token = new UsernamePasswordToken($username, $password, 'key');
        $provider->authenticate($token);
    }

    public function testDnForLdapBindDataProvider()
    {
        return [
            // have dnString, no entryAttribute - standard behaviour
            ['user1', '{username}', 'dn', '', '', 'dn'],
            // no dnString, no entryAttribute - get DN from Ldap Entry
            ['user1', '', 'dn', '', '', 'dn'],
            // have dnString, have entryAttribute - get entryAttribute value from Ldap Entry, use it as username
            ['user1', '{username}@test.com', 'dn', 'attr1', 'attr1value', 'attr1value@test.com'],
            // no dnString, have entryAttribute - get entryAttribute value from Ldap Entry, use it as username
            ['user1', '', 'dn', 'attr1', 'attr1value', 'attr1value'],
            // have entryAttribute in uppercase  - get entryAttribute value from Ldap Entry, use it as username
            ['user1', '', 'dn', 'ATTR1', 'attr1value', 'attr1value'],
        ];
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage LDAP config option groupDn must not be empty
     */
    public function testGroupCheckNoDN()
    {
        $this->ldap->expects($this->once())
            ->method('bind')
            ->with('dn', $password = 'pass');
        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $this->userProvider,
                $this->userChecker,
                'key',
                $this->ldap,
                $this->mapper,
                '{username}',
                true,
                ['groupMembership' => true],
            ])
            ->setMethods(['retrieveUser'])
            ->getMock();
        $provider->expects($this->any())->method('retrieveUser')->willReturn(
            new User($username = 'user1', '', ['entry' => new Entry('dn', ['' => ''])])
        );
        $token = new UsernamePasswordToken($username, $password, 'key');
        $provider->authenticate($token);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage LDAP groupAttribute must not be empty
     */
    public function testGroupCheckNoGroupAttribute()
    {
        $this->ldap->expects($this->once())
            ->method('bind')
            ->with('dn', $password = 'pass');
        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $this->userProvider,
                $this->userChecker,
                'key',
                $this->ldap,
                $this->mapper,
                '{username}',
                true,
                ['groupMembership' => true, 'groupDn' => 'cn=Administrators,ou=groups,dc=openldap,dc=com'],
            ])
            ->setMethods(['retrieveUser'])
            ->getMock();
        $provider->expects($this->any())->method('retrieveUser')->willReturn(
            new User($username = 'user1', '', ['entry' => new Entry('dn', ['' => ''])])
        );
        $token = new UsernamePasswordToken($username, $password, 'key');
        $provider->authenticate($token);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage LDAP baseDn must not be empty
     */
    public function testGroupCheckNoBaseDn()
    {
        $this->ldap->expects($this->once())
            ->method('bind')
            ->with('dn', $password = 'pass');
        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $this->userProvider,
                $this->userChecker,
                'key',
                $this->ldap,
                $this->mapper,
                '{username}',
                true,
                [
                    'groupMembership' => true,
                    'groupDn' => 'cn=Administrators,ou=groups,dc=openldap,dc=com',
                    'groupAttribute' => 'member',
                ],
            ])
            ->setMethods(['retrieveUser'])
            ->getMock();
        $provider->expects($this->any())->method('retrieveUser')->willReturn(
            new User($username = 'user1', '', ['entry' => new Entry('dn', ['' => ''])])
        );
        $token = new UsernamePasswordToken($username, $password, 'key');
        $provider->authenticate($token);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage LDAP user does not belong to group specified
     */
    public function testGroupCheckNoUserUniqueAttributeNoEntity()
    {
        $this->ldap->expects($this->once())
            ->method('bind')
            ->with('dn', $password = 'pass');
        $this->ldap->expects($this->once())
            ->method('query')
            ->with(
                $this->equalTo($this->ldapConfig['groupDn']),
                $this->equalTo('(' . $this->ldapConfig['groupAttribute'] . '=' . ($userDn = 'dn') . ')')
            )
            ->willReturn($this->ldapQuery);
        $this->ldap->expects($this->once())
            ->method('escape')
            ->willReturnArgument(0);
        $this->ldapQuery->expects($this->once())->method('execute')->willReturn($this->ldapCollection);
        $this->ldapCollection->expects($this->once())->method('count')->willReturn(0);

        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $this->userProvider,
                $this->userChecker,
                'key',
                $this->ldap,
                $this->mapper,
                '{username}',
                true,
                $this->ldapConfig,
            ])
            ->setMethods(['retrieveUser'])
            ->getMock();
        $provider->expects($this->any())->method('retrieveUser')->willReturn(
            new User($username = 'user1', '', ['entry' => new Entry($userDn, ['' => ''])])
        );
        $token = new UsernamePasswordToken($username, $password, 'key');
        $provider->authenticate($token);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessageRegExp ~^Could.*group.*$~
     */
    public function testGroupCheckUserUniqueAttributeNotFindGroup()
    {
        $this->ldap->expects($this->once())
            ->method('bind')
            ->with('dn', $password = 'pass');
        $this->ldap->expects($this->once())
            ->method('query')
            ->with(
                $this->equalTo($this->ldapConfig['groupDn']),
                $this->equalTo('(member=unique=test,dc=openldap,dc=com)')
            )
            ->willReturn($this->ldapQuery);
        $this->ldap->expects($this->once())
            ->method('escape')
            ->willReturnArgument(0);
        $this->ldapQuery
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new LdapException());

        $this->ldapCollection->expects($this->never())->method('count')->willReturn(0);

        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $this->userProvider,
                $this->userChecker,
                'key',
                $this->ldap,
                $this->mapper,
                '{username}',
                true,
                array_merge($this->ldapConfig, [
                    'userUniqueAttribute' => ($userUnique = 'unique'),
                    'includeUserDN' => true,
                ]),
            ])
            ->setMethods(['retrieveUser'])
            ->getMock();
        $provider->expects($this->any())->method('retrieveUser')->willReturn(
            new User($username = 'user1', '', ['entry' => new Entry('dn', [
                '' => '',
                $userUnique => 'test',
            ])])
        );
        $token = new UsernamePasswordToken($username, $password, 'key');
        $provider->authenticate($token);
    }

    /**
     * Test authentication when search option is empty
     * Trying to bind by user's username and password
     *
     * @covers ::authenticate
     */
    public function testUserRetrievingWithEmptySearchDN()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
                         ->setConstructorArgs([
                                                  $this->userProvider,
                                                  $this->userChecker,
                                                  'key',
                                                  $this->ldap,
                                                  $this->mapper,
                                                  '{username}',
                                                  true,
                                                  $this->ldapConfig,
                                              ])
                         ->setMethods(['groupCheck'])
                         ->getMock();
        $token = new UsernamePasswordToken($username, $password, 'key');
        $user = new User($username, '', ['entry' => new Entry('dn', [])]);

        $this->userProvider->expects($this->once())
                               ->method('loadUserByToken')->with($token)->willReturn($user);
        $provider->authenticate($token);
    }

    /**
     * Test authentication when search option is empty
     * Trying to bind by user's username and password
     * @expectedException \Symfony\Component\Ldap\Exception\LdapException
     * @covers ::authenticate
     */
    public function testUserRetrievingWithEmptySearchDNAndInvalidAdminCredentials()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $this->userProvider,
                $this->userChecker,
                'key',
                $this->ldap,
                $this->mapper,
                '{username}',
                true,
                array_merge($this->ldapConfig, ['searchDn' => 'admin', 'searchPassword' => 'admin']),
            ])
            ->setMethods(['groupCheck'])
            ->getMock();
        $token = new UsernamePasswordToken($username, $password, 'key');
        $ldapException = new LdapException('ldap exception', 0, new ConnectionException('Invalid credentials'));
        $this->userProvider->expects($this->once())
            ->method('loadUserByUsername')
            ->with($username)
            ->willThrowException($ldapException);
        $this->userProvider->expects($this->never())->method('loadUserByToken');
        $provider->authenticate($token);
    }

    /**
     * @covers ::authenticate
     */
    public function testRetrieveUserReturnsUserWithIdentityPairAndMappedAttributes()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $ldapEntry = new Entry('dn', []);
        $identityMap = [
            'field' => 'username',
            'value' => $username,
        ];
        $mappedAttributes = ['attributes_given_name' => 'Baz'];
        $provider = $this->getMockBuilder(LdapAuthenticationProvider::class)
            ->setConstructorArgs([
                $this->userProvider,
                $this->userChecker,
                'key',
                $this->ldap,
                $this->mapper,
                '{username}',
                true,
                array_merge($this->ldapConfig, ['searchDn' => 'admin', 'searchPassword' => 'admin']),
            ])
            ->setMethods(['groupCheck'])
            ->getMock();
        $token = new UsernamePasswordToken($username, $password, 'key');

        $user = $this->createMock(User::class);
        $user->method('getAttribute')->with('entry')->willReturn($ldapEntry);
        $user->method('getRoles')->willReturn([]);

        $this->userProvider->expects($this->once())
            ->method('loadUserByUsername')
            ->with($username)
            ->willReturn($user);

        $this->mapper->method('mapIdentity')->willReturn($identityMap);
        $this->mapper->method('map')->willReturn($mappedAttributes);

        $user->expects($this->exactly(3))
            ->method('setAttribute')->withConsecutive(
                ['identityField', 'username'],
                ['identityValue', $username],
                ['attributes', $mappedAttributes]
            );

        $provider->authenticate($token);
    }
}
