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

use Sugarcrm\IdentityProvider\Srn\Manager;

/**
 * @coversDefaultClass Sugarcrm\IdentityProvider\Srn\Manager
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::createUserSrnFromPrototype
     */
    public function testCreateUserSrnFromPrototype()
    {
        $config = [
            'partition' => 'cluster',
            'region' => 'by',
        ];

        $srnManager = new Manager($config);
        $userSrn = $srnManager->createUserSrn('0000000001', 'userId');
        $this->assertEquals('cluster', $userSrn->getPartition());
        $this->assertEquals('iam', $userSrn->getService());
        $this->assertEquals('by', $userSrn->getRegion());
        $this->assertEquals('0000000001', $userSrn->getTenantId());
        $this->assertEquals(['user', 'userId'], $userSrn->getResource());
    }

    /**
     * Provides data for testCreateManagerWithInvalidConfig
     * @return array
     */
    public function createManagerWithInvalidConfigProvider()
    {
        return [
            [
                'emptyConfig' => [],
            ],
            [
                'noPartition' => [
                    'region' => 'by',
                ],
            ],
            [
                'noRegion' => [
                    'partition' => 'cluster',
                ],
            ],
        ];
    }

    /**
     * @param array $config
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider createManagerWithInvalidConfigProvider
     */
    public function testCreateManagerWithInvalidConfig(array $config)
    {
        new Manager($config);
    }
}
