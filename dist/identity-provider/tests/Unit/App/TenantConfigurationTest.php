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

namespace Sugarcrm\IdentityProvider\Tests\Unit\App;

use Sugarcrm\IdentityProvider\App\Authentication\Adapter\ConfigAdapterFactory;
use Sugarcrm\IdentityProvider\App\TenantConfiguration;
use Sugarcrm\IdentityProvider\Srn\Srn;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class TenantConfigurationTest
 * @package Sugarcrm\IdentityProvider\Tests\Unit\App
 * @coversDefaultClass \Sugarcrm\IdentityProvider\App\TenantConfiguration
 */
class TenantConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Srn|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $srn;

    /**
     * @var Statement|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $statement;

    /**
     * @var QueryBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $queryBuilder;

    /**
     * @var Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $db;

    /**
     * @var TenantConfiguration
     */
    protected $tenantConfiguration;

    /**
     * @var UrlGeneratorInterface
     */
    protected $configAdapterFactory;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->configAdapterFactory = new ConfigAdapterFactory($urlGenerator);

        $this->srn = $this->createMock(Srn::class);
        $this->statement = $this->createMock(Statement::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->queryBuilder->method('execute')->willReturn($this->statement);

        $this->queryBuilder->method('select')->willReturnSelf();
        $this->queryBuilder->method('from')->willReturnSelf();
        $this->queryBuilder->method('innerJoin')->willReturnSelf();
        $this->queryBuilder->method('andWhere')->willReturnSelf();

        $this->db = $this->createMock(Connection::class);
        $this->db->method('createQueryBuilder')->willReturn($this->queryBuilder);


        $this->tenantConfiguration = new TenantConfiguration($this->db, $this->configAdapterFactory);
    }

    /**
     * Data Provider for testMerge
     * @see testMerge
     * @return array
     */
    public function expectedConfigs()
    {
        $baseConfig = ['some' => 'base', 'config' => 'value'];
        $provider = 'someProviderName';
        $providerConfig = [
            'some' => 'provider',
            'config' => 'value',
            'host' => 'hostValue',
        ];
        $providerAttributeMap = [
            'some' => 'provider',
            'map' => 'value',
            'name' => 'email',
        ];
        return [
            'emptyProviderConfigAndMap' => [
                'baseConfig' => $baseConfig,
                'listConfig' => [
                    [
                        'provider_code' => $provider,
                        'config' => null,
                        'attribute_map' => null,
                    ],
                ],
                'tenantId' => '0000000002',
                'expectedConfig' => $baseConfig +
                    [
                        'enabledProviders' => [$provider],
                        $provider => [],
                    ],
            ],
            'expectedConfigAndMap' => [
                'baseConfig' => $baseConfig,
                'listConfig' => [
                    [
                        'provider_code' => $provider,
                        'config' => json_encode($providerConfig),
                        'attribute_map' => json_encode($providerAttributeMap),
                    ],
                ],
                'tenantId' => '0000000003',
                'expectedConfig' => $baseConfig +
                    [
                        'enabledProviders' => [$provider],
                        $provider => $providerConfig + ['user_mapping' => $providerAttributeMap],
                    ],
            ],
        ];
    }

    /**
     * Testing initialize tenant config.
     * @covers ::merge
     * @dataProvider expectedConfigs
     * @param $baseConfig
     * @param $listConfig
     * @param $tenantId
     * @param $expectedConfig
     */
    public function testMerge($baseConfig, $listConfig, $tenantId, $expectedConfig)
    {
        $this->srn->method('getTenantId')->willReturn($tenantId);

        $this->queryBuilder
            ->expects($this->once())
            ->method('setParameters')
            ->with([
                ':tenant_id' => $tenantId,
                ':tenant_status' => 0,
            ])
            ->willReturnSelf();
        $this->statement
            ->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn($listConfig);

        $config = $this->tenantConfiguration->merge($this->srn, $baseConfig);

        $this->assertEquals($expectedConfig, $config);
    }

    /**
     * Testing initialize tenant config if tenant not exists.
     * @covers ::merge
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Tenant not exists or deleted
     */
    public function testMergeEmptyTenant()
    {
        $tenantId = '0000000000';

        $this->srn->method('getTenantId')->willReturn($tenantId);

        $this->queryBuilder
            ->expects($this->once())
            ->method('setParameters')
            ->with([
                ':tenant_id' => $tenantId,
                ':tenant_status' => 0,
            ])
            ->willReturnSelf();
        $this->statement
            ->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn([]);

        $this->tenantConfiguration->merge($this->srn, ['some' => 'base', 'config' => 'value']);
    }
}
