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

use PHPUnit_Framework_TestCase;
use Sugarcrm\IdentityProvider\Authentication\UserMapping\SAMLUserMapping;
use Sugarcrm\IdentityProvider\Authentication\User;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Authentication\UserMapping\SAMLUserMapping
 */
class SAMLUserMappingTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::map
     * @dataProvider getMappingDataProvider
     *
     * @param array $mappingConfig App mappings from configuration.
     * @param array $attributes Attributes from SAML IdP response.
     * @param string $nameId NameId value from SAML IdP.
     * @param array $expected Expected map result.
     */
    public function testMap($mappingConfig, $attributes, $nameId, $expected)
    {
        $samlResponse = $this->getMockBuilder('OneLogin_Saml2_Response')->disableOriginalConstructor()->getMock();
        $samlResponse->method('getAttributes')->willReturn($attributes);
        $samlResponse->method('getNameId')->willReturn($nameId);

        $mapping = new SAMLUserMapping($mappingConfig);
        $result = $mapping->map($samlResponse);
        ksort($expected);
        ksort($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public static function getMappingDataProvider()
    {
        return [
            'emptyMappingAndAttributes' => [
                [],
                [],
                '',
                [],
            ],
            'emptyMappingSomeAttributes' => [
                [],
                ['attr1' => ['foo'], 'attr2' => ['bar'],],
                '',
                [],
            ],
            'someMappingSomeAttributes' => [
                ['attr1' => 'email', 'attr2' => 'title',],
                ['attr1' => ['foo'], 'attr2' => ['bar'],],
                '',
                ['email' => 'foo', 'title' => 'bar',],
            ],
            'nonMappedAttributesAreIgnored' => [
                ['attr1' => 'email',],
                ['attr1' => ['foo'], 'attr2' => ['bar'],],
                '',
                ['email' => 'foo'],
            ],
            'missingAttributesCanNotBePresentInResult' => [
                ['attr1' => 'email', 'attr2' => 'title',],
                ['attr1' => ['foo'], 'attr2' => ['bar'], 'attr3' => ['baz'],],
                '',
                ['email' => 'foo', 'title' => 'bar',],
            ],
            'nameIdValueIsMappedToEmail' => [
                ['name_id' => 'email'],
                [],
                'test@test.com',
                ['email' => 'test@test.com',],
            ],
            'nameIdValueIsMappedToEmailAlongWithOtherAttributes' => [
                ['name_id' => 'email', 'attr1' => 'title'],
                ['attr1' => ['foo']],
                'test@test.com',
                ['email' => 'test@test.com', 'title' => 'foo'],
            ],
        ];
    }

    /**
     * @return array
     */
    public static function mapIdentityDataProvider()
    {
        return [
            'Empty mapping config' => [
                [],
                'email',
                'foo@test.com',
            ],
            'No name_id specified' => [
                ['attr1' => 'first_name', 'attr2' => 'last_name'],
                'email',
                'foo@test.com',
            ],
            'name_id specified' => [
                ['name_id' => 'user_name', 'attr1' => 'first_name', 'attr2' => 'last_name'],
                'user_name',
                'foo@test.com',
            ],
        ];
    }

    /**
     * @covers ::mapIdentity
     * @dataProvider mapIdentityDataProvider
     *
     * @param array $mappingConfig
     * @param string $idField
     * @param string $idValue
     */
    public function testMapIdentity($mappingConfig, $idField, $idValue)
    {
        $samlResponse = $this->createMock(\OneLogin_Saml2_Response::class);
        $samlResponse->method('getNameId')->willReturn($idValue);

        $mapping = new SAMLUserMapping($mappingConfig);
        $this->assertEquals($idField, $mapping->mapIdentity($samlResponse)['field']);
        $this->assertEquals($idValue, $mapping->mapIdentity($samlResponse)['value']);
    }

    public function getIdentityValueDataProvider()
    {
        return [
            ['user@test.com', true, 'user@test.com'],
            [null, false, null],
        ];
    }

    /**
     * @covers ::getIdentityValue
     * @dataProvider getIdentityValueDataProvider
     */
    public function testGetIdentityValue($expected, $userHasAttribute, $userAttribute)
    {
        $mapping = $this->getMockBuilder(SAMLUserMapping::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIdentityField'])
            ->getMock();
        $mapping->expects($this->once())
            ->method('getIdentityField')
            ->willReturn('identityField');
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasAttribute')
            ->with('identityField')
            ->willReturn($userHasAttribute);
        $user->method('getAttribute')
            ->willReturn($userAttribute);
        $this->assertEquals($expected, $mapping->getIdentityValue($user));
    }
}
