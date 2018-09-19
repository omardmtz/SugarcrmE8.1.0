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

namespace Sugarcrm\IdentityProvider\Tests\Unit\Srn;

use Sugarcrm\IdentityProvider\Srn\Converter;
use Sugarcrm\IdentityProvider\Srn\Srn;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Srn\Converter
 */
class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides data for testFromStringNotValidSrn
     * @return array
     */
    public function fromStringNotValidSrnProvider()
    {
        return [
            'invalidScheme' => [
                'srn' => 'srn1:cloud:idp:eu:1234567890:tenant',
                'message' => 'Invalid scheme, must start with "srn"',
            ],
            'fiveChunks' => [
                'srn' => 'srn:cloud:idp:eu:1234567890',
                'message' => 'Invalid number of components in SRN',
            ],
            'emptyPartition' => [
                'srn' => 'srn::idp:eu:1234567890:tenant',
                'message' => 'Partition cannot be empty',
            ],
            'emptyService' => [
                'srn' => 'srn:cloud::eu:1234567890:tenant',
                'message' => 'Service cannot be empty',
            ],
            'invalidCharacters' => [
                'srn' => 'srn:cloud:idp:eu:1234567890:tenant:^&',
                'message' => 'Invalid component characters, only allow "/^[a-zA-Z0-9_\-.;\/]*$/"',
            ],
            'tooLong' => [
                'srn' => implode('', array_fill(0, 256, 1)),
                'message' => 'SRN is too long',
            ],
            'invalidTenant' => [
                'srn' => 'srn:cloud:idp:eu:invalid-tid:tenant',
                'message' => 'Invalid tenant id',
            ],
        ];
    }

    /**
     * Checks invalid Srn logic.
     *
     * @param string $srn
     * @param string $message
     *
     * @covers ::fromString
     * @dataProvider fromStringNotValidSrnProvider
     * @expectedException \InvalidArgumentException
     */
    public function testFromStringNotValidSrn($srn, $message)
    {
        $this->expectExceptionMessage($message);
        Converter::fromString($srn);
    }

    public function testFromStringValidSrnDataProvider()
    {
        return [
            'full path tenant' => [
                'srn:cloud:idp:eu:1234567890:tenant:12345678901',
                [
                    'partition' => 'cloud',
                    'service' => 'idp',
                    'region' => 'eu',
                    'tenant' => '1234567890',
                    'resource' => ['tenant', '12345678901'],
                ]
            ],
            'short path tenant' => [
                'srn:cloud:idp:eu:1234567890:tenant',
                [
                    'partition' => 'cloud',
                    'service' => 'idp',
                    'region' => 'eu',
                    'tenant' => '1234567890',
                    'resource' => ['tenant'],
                ]
            ],
            'tenant with leading zeroes' => [
                'srn:cloud:idp:eu:1:tenant',
                [
                    'partition' => 'cloud',
                    'service' => 'idp',
                    'region' => 'eu',
                    'tenant' => '0000000001',
                    'resource' => ['tenant'],
                ]
            ],
        ];
    }

    /**
     * Checks fromString logic
     *
     * @covers ::fromString
     * @dataProvider testFromStringValidSrnDataProvider
     */
    public function testFromStringValidSrn($srn, $expected)
    {
        $srn = Converter::fromString($srn);

        $this->assertInstanceOf(Srn::class, $srn);
        $this->assertEquals($expected['partition'], $srn->getPartition());
        $this->assertEquals($expected['service'], $srn->getService());
        $this->assertEquals($expected['region'], $srn->getRegion());
        $this->assertEquals($expected['tenant'], $srn->getTenantId());
        $this->assertEquals($expected['resource'], $srn->getResource());
    }

    /**
     * Provides data for testToStringNotValidSrn
     * @return array
     */
    public function toStringNotValidSrnProvider()
    {
        $fixture = [
            'setPartition' => 'cloud',
            'setService' => 'idp',
            'setRegion' => 'eu',
            'setTenantId' => '1234567890',
            'setResource' => ['user', 'userId'],
        ];
        return [
            'noPartition' => [
                'definition' => $fixture,
                'methodToSkip' => 'setPartition',
                'expectExceptionMessage' => 'Partition is invalid',
            ],
            'noService' => [
                'definition' => $fixture,
                'methodToSkip' => 'setService',
                'expectExceptionMessage' => 'Service is invalid',
            ],
            'noResource' => [
                'definition' => $fixture,
                'methodToSkip' => 'setResource',
                'expectExceptionMessage' => 'Resource type is invalid',
            ],
        ];
    }


    /**
     * Checks invalid Srn logic.
     *
     * @param array $definition
     * @param string $methodToSkip
     * @param string $expectExceptionMessage
     *
     * @covers ::toString
     * @expectedException \InvalidArgumentException
     * @dataProvider toStringNotValidSrnProvider
     */
    public function testToStringNotValidSrn(array $definition, $methodToSkip, $expectExceptionMessage)
    {
        $srn = new Srn();
        foreach ($definition as $method => $value) {
            if ($method != $methodToSkip) {
                $srn->$method($value);
            }
        }
        $this->expectExceptionMessage($expectExceptionMessage);

        Converter::toString($srn);
    }

    /**
     * Checks a valid logic.
     *
     * @covers ::toString
     */
    public function testToString()
    {
        $srn = new Srn();
        $srn->setPartition('cloud')
            ->setService('idp')
            ->setRegion('')
            ->setTenantId('1234567890')
            ->setResource(['user', 'e9b578dc-b5ae-41b6-a680-195cfc018f30']);

        $this->assertEquals(
            'srn:cloud:idp::1234567890:user:e9b578dc-b5ae-41b6-a680-195cfc018f30',
            Converter::toString($srn)
        );
    }

    /**
     * Checks full conversion process.
     *
     * @covers ::fromString
     * @covers ::toString
     */
    public function testViceVersaConversion()
    {
        $source = 'srn:cloud:idp::1234567890:user:user1:userId';
        $srn = Converter::fromString($source);
        $this->assertEquals($source, Converter::toString($srn));
    }
}
