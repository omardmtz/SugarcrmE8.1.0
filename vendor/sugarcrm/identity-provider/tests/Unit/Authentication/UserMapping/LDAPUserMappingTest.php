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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Authentication\UserMapping;

use Sugarcrm\IdentityProvider\Authentication\UserMapping\LDAPUserMapping;
use Sugarcrm\IdentityProvider\Authentication\User;

use Symfony\Component\Ldap\Entry;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\UserMapping\LDAPUserMapping
 */
class LDAPUserMappingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::mapIdentity
     */
    public function testMapIdentity()
    {
        $token = new UsernamePasswordToken(new User('max'), '123', 'ldap', []);
        $mapper = new LDAPUserMapping([]);
        $result = $mapper->mapIdentity($token);
        $this->assertArrayHasKey('field', $result);
        $this->assertArrayHasKey('value', $result);
        $this->assertEquals('username', $result['field']);
        $this->assertEquals('max', $result['value']);
    }

    public function testGetIdentityValue()
    {
        $user = new User('Jim', '', ['middle-name' => 'Boyer']);
        $mapper = new LDAPUserMapping([]);
        $this->assertEquals($user->getUsername(), $mapper->getIdentityValue($user));
    }

    /**
     * @return array
     */
    public static function mapDataProvider()
    {
        return [
            'Empty mapping' => [
                [],
                new Entry('foo', []),
                [],
            ],
            'Empty mapping, nonempty Entry' => [
                [],
                new Entry('foo', ['cn' => 'Foo']),
                [],
            ],
            'Mapping has no entry value' => [
                [
                    'sn' => 'last_name'
                ],
                new Entry('foo', ['cn' => 'Foo']),
                [],
            ],
            'Mapping has entry value' => [
                [
                    'sn' => 'last_name',
                    'cn' => 'first_name',
                ],
                new Entry('foo', ['sn' => 'Bobby']),
                [
                    'last_name' => 'Bobby',
                ],
            ],
            'Mapping and Entry all match' => [
                [
                    'sn' => 'last_name',
                    'cn' => 'first_name',
                ],
                new Entry('foo', ['cn' => 'Foo', 'sn' => 'Bobby']),
                [
                    'last_name' => 'Bobby',
                    'first_name' => 'Foo',
                ],
            ],
        ];
    }

    /**
     * @covers ::map
     * @dataProvider mapDataProvider
     *
     * @param array $mapping
     * @param Entry $entry
     * @param array $expected
     */
    public function testMap($mapping, $entry, $expected)
    {
        $mapper = new LDAPUserMapping($mapping);
        $this->assertEquals($expected, $mapper->map($entry));
    }
}
