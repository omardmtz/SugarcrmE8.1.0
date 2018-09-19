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
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LocalUserProvider;
use Sugarcrm\IdentityProvider\Authentication\Provider\Providers;

use Doctrine\DBAL\Connection;

/**
 * Class LocalUserProviderTest.
 */
class LocalUserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $testUserName = 'user1';

    /**
     * @var string
     */
    protected $testPassword = 'passwordnohash';

    public function testLoadUserByUsernameUserExists()
    {
        $userProvider = $this->getUserProvider();
        $user = $userProvider->loadUserByUsername($this->testUserName);
        $this->assertEquals($this->testUserName, $user->getUsername());
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameUserDoesntExist()
    {
        $userProvider = $this->getUserProvider(null);
        $userProvider->loadUserByUsername('unknown_user_name');
    }

    public function testLoadUserByFieldAndProviderExists()
    {
        $userProvider = $this->getUserProvider();
        $user = $userProvider->loadUserByFieldAndProvider($this->testUserName, Providers::LDAP);
        $this->assertEquals($this->testUserName, $user->getUsername());
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByFieldAndProviderUserDoesNotExist()
    {
        $userProvider = $this->getUserProvider(null);
        $userProvider->loadUserByFieldAndProvider('unknown_user_name', Providers::SAML);
    }

    public function testRefreshUser()
    {
        $userProvider = $this->getUserProvider();
        $user = new User($this->testUserName, $this->testPassword . 'suffix');
        $user = $userProvider->refreshUser($user);
        $this->assertEquals($this->testPassword, $user->getPassword());
    }

    public function testCreateUser()
    {
        $tenantId = '123-tenant';
        $db = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userProvider = $this->getMockBuilder(LocalUserProvider::class)
            ->setConstructorArgs([$db, $tenantId])
            ->setMethods(['getProviderId'])
            ->getMock();

        $db->method('transactional')
            ->will($this->returnCallback(function (callable $callback) use ($db) {
                $callback($db);
            }));

        $userProvider->method('getProviderId')->willReturn(2);

        $db->expects($this->exactly(2))
            ->method('insert')
            ->withConsecutive(
                ['users',
                    $this->logicalAnd(
                        $this->isType('array'),
                        $this->arrayHasKey('attributes'),
                        $this->contains('{}'),
                        $this->arrayHasKey('custom_attributes'),
                        $this->contains('{"a":"b"}'),
                        $this->arrayHasKey('status'),
                        $this->contains('active'),
                        $this->arrayHasKey('tenant_id'),
                        $this->contains($tenantId),
                        $this->arrayHasKey('id'),
                        $this->arrayHasKey('create_time'),
                        $this->arrayHasKey('modify_time')
                    )],
                ['user_providers',
                    $this->logicalAnd(
                        $this->isType('array'),
                        $this->arrayHasKey('tenant_id'),
                        $this->contains($tenantId),
                        $this->arrayHasKey('identity_value'),
                        $this->contains('max'),
                        $this->arrayHasKey('provider_code'),
                        $this->contains('ldap'),
                        $this->arrayHasKey('user_id')
                    )]
            );

        $userProvider->createUser('max', Providers::LDAP, ['a' => 'b']);
    }

    /**
     * @param array|null $data Creates UserProvider object which returns predefined data
     *                         that can be overwritten by $data param.
     *                         UserProvider will return null if $data is not array.
     * @return LocalUserProvider
     */
    protected function getUserProvider($data = [])
    {
        $userProvider = $this->getMockBuilder(LocalUserProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUserData'])
            ->getMock()
        ;

        if (is_array($data)) {
            $rowData = array_merge([
                'id' => '12345678-9012-3456-7890-123456789012',
                'identity_value' => $this->testUserName,
                'password_hash' => $this->testPassword,
                'status' => User::STATUS_ACTIVE,
                'create_time' => '',
                'modify_time' => '',
            ], $data);

        } else {
            $rowData = null;
        }

        $userProvider->method('getUserData')
            ->willReturn($rowData);

        return $userProvider;
    }
}
