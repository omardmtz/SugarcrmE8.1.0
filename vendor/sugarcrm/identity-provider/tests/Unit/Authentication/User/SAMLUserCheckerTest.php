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
use Sugarcrm\IdentityProvider\Authentication\User\SAMLUserChecker;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidIdentifier\EmptyFieldException;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidIdentifier\EmptyIdentifierException;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidIdentifier\IdentifierInvalidFormatException;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LocalUserProvider;
use Sugarcrm\IdentityProvider\Authentication\Provider\Providers;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class SAMLUserCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var SAMLUserChecker
     */
    protected $userChecker;

    /**
     * SAML configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var LocalUserProvider
     */
    protected $localUserProvider;

    /**
     * Data provider for testValidIdentifier
     * @see testValidIdentifier
     * @return array
     */
    public function validIdentifierProvider()
    {
        return [
            'emptyField' => [
                'expectedException' => EmptyFieldException::class,
                'field' => '',
                'value' => '',
            ],
            'emptyValue' => [
                'expectedException' => EmptyIdentifierException::class,
                'field' => 'someField',
                'value' => '',
            ],
            'invalidEmailFormat' => [
                'expectedException' => IdentifierInvalidFormatException::class,
                'field' => 'email',
                'value' => 'invalidEmail',
            ],
        ];
    }

    /**
     * @dataProvider validIdentifierProvider
     * @covers ::checkPostAuth
     * @param $expectedException
     * @param $field
     * @param $value
     */
    public function testValidIdentifier($expectedException, $field, $value)
    {
        $this->user->method('getAttribute')->willReturnMap([
            ['identityField', $field],
            ['identityValue', $value],
        ]);

        $this->expectException($expectedException);

        $this->userChecker->checkPostAuth($this->user);
    }

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
        $this->userChecker = new SAMLUserChecker($this->localUserProvider, $this->config);
    }


    /**
     * @covers ::checkPostAuth
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessageRegExp ~User not found~
     */
    public function testUserIsNotCreatedIfAutoCreateUsersIsFalse()
    {
        $user = new User('max@test.com', '', [
            'identityValue' => 'max@test.com',
            'identityField' => 'email',
        ]);
        $config = [
            'sp' => [
                'provisionUser' => false,
            ],
        ];
        $this->localUserProvider->method('loadUserByFieldAndProvider')
            ->willThrowException(new UsernameNotFoundException('User not found'));
        $userChecker = new SAMLUserChecker($this->localUserProvider, $config);

        $userChecker->checkPostAuth($user);
    }

    /**
     * @covers ::checkPostAuth
     */
    public function testUserIsCreatedIfAutoCreateUsersIsTrue()
    {
        $user = new User('max@test.com', '', [
            'identityValue' => 'max@test.com',
            'identityField' => 'email',
            'attributes' => ['a' => 'b'],
        ]);
        $config = [
            'sp' => [
                'provisionUser' => true,
            ],
        ];
        $this->localUserProvider->expects($this->once())
            ->method('loadUserByFieldAndProvider')
            ->with('max@test.com', Providers::SAML)
            ->willThrowException(new UsernameNotFoundException('User not found'));

        $this->localUserProvider->expects($this->once())
            ->method('createUser')
            ->with('max@test.com', Providers::SAML, ['a' => 'b'])
            ->willReturn($user);

        $userChecker = new SAMLUserChecker($this->localUserProvider, $config);
        $userChecker->checkPostAuth($user);
    }

    /**
     * @covers ::checkPostAuth
     */
    public function testUserIsCheckedForLocallyFoundOne()
    {
        $user = new User('max@test.com', '', [
            'identityValue' => 'max@test.com',
            'identityField' => 'email',
        ]);
        $localUser = new User('max@test.com', '', [
            'identityValue' => 'max@test.com',
            'identityField' => 'email',
        ]);

        $config = [
            'sp' => [
                'provisionUser' => true,
            ],
        ];

        $this->localUserProvider->expects($this->once())
            ->method('loadUserByFieldAndProvider')
            ->with('max@test.com', Providers::SAML)
            ->willReturn($localUser);

        $this->localUserProvider->expects($this->never())
            ->method('createUser');

        $userChecker = new SAMLUserChecker($this->localUserProvider, $config);
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
        $user = new User('max@test.com', '', [
            'identityValue' => 'max@test.com',
            'identityField' => 'email',
        ]);
        $localUser = new User('max@test.com', '', [
            'id' => 'some-real-uuid',
            'some-db-field' => 'some-db-value',
        ]);

        $config = [
            'sp' => [
                'provisionUser' => $createUser,
            ],
        ];

        $this->localUserProvider->method('loadUserByFieldAndProvider')->willReturn($localUser);
        $this->localUserProvider->method('createUser')->willReturn($user);

        $userChecker = new SAMLUserChecker($this->localUserProvider, $config);
        $userChecker->checkPostAuth($user);

        $localUser = $user->getLocalUser();
        $this->assertNotNull($localUser);

        $this->assertArrayHasKey('identityValue', $user->getAttributes());
        $this->assertArrayHasKey('identityField', $user->getAttributes());

        $this->assertArrayNotHasKey('identityValue', $localUser->getAttributes());
        $this->assertArrayNotHasKey('ldap-entry', $localUser->getAttributes());

        $this->assertArrayHasKey('id', $localUser->getAttributes());
        $this->assertEquals('some-real-uuid', $localUser->getAttribute('id'));
        $this->assertArrayHasKey('some-db-field', $localUser->getAttributes());
        $this->assertEquals('some-db-value', $localUser->getAttribute('some-db-field'));
    }
}
