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

namespace Sugarcrm\IdentityProvider\App;

use Doctrine\DBAL\Connection;
use Sugarcrm\IdentityProvider\App\Authentication\Adapter\AbstractAdapter;
use Sugarcrm\IdentityProvider\App\Authentication\Adapter\AdapterFactory;
use Sugarcrm\IdentityProvider\App\Authentication\Adapter\ConfigAdapterFactory;
use Sugarcrm\IdentityProvider\Srn\Srn;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sugarcrm\IdentityProvider\Authentication\Tenant;

/**
 * Initialize configuration special for tenant.
 * Class TenantConfiguration
 * @package Sugarcrm\IdentityProvider\App
 */
class TenantConfiguration
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var ConfigAdapterFactory
     */
    protected $configAdapterFactory;

    /**
     * TenantConfiguration constructor.
     * @param Connection $db
     * @param ConfigAdapterFactory $configAdapterFactory
     */
    public function __construct(Connection $db, ConfigAdapterFactory $configAdapterFactory)
    {
        $this->db = $db;
        $this->configAdapterFactory = $configAdapterFactory;
    }

    /**
     * Merge tenant configuration with base config.
     * @param Srn $tenant
     * @param array $config
     * @return array
     * @throws \LogicException
     */
    public function merge(Srn $tenant, array $config)
    {
        $tenantConfig = $this->get($tenant);
        return array_replace_recursive($tenantConfig, $config);
    }

    /**
     * Getting tenant config.
     * @param Srn $tenant
     * @return array
     * @throws \LogicException
     */
    protected function get(Srn $tenant)
    {
        $qb = $this->db->createQueryBuilder()
            ->select('provider_code, tenant_providers.config, tenant_providers.attribute_map')
            ->from('tenants')
            ->innerJoin(
                'tenants',
                'tenant_providers',
                'tenant_providers',
                'tenant_providers.tenant_id = tenants.id'
            )
            ->andWhere('tenants.id = :tenant_id')
            ->andWhere('tenants.status = :tenant_status')
            ->setParameters([
                ':tenant_id' => $tenant->getTenantId(),
                ':tenant_status' => Tenant::STATUS_ACTIVE,
            ]);
        $list = $qb->execute()->fetchAll(\PDO::FETCH_ASSOC);

        if (0 == count($list)) {
            throw new \RuntimeException('Tenant not exists or deleted');
        }
        return $this->normalize($list);
    }

    /**
     * Normalize config from list presentation.
     *
     * @param $list
     * @return array
     */
    protected function normalize($list)
    {
        $config = ['enabledProviders' => []];
        foreach ($list as $provider) {
            $providerCode = $provider['provider_code'];
            $config['enabledProviders'][] = $providerCode;
            $adapter = $this->configAdapterFactory->getAdapter($providerCode);
            if ($adapter instanceof AbstractAdapter) {
                $config[$providerCode] = $adapter->getConfig($provider['config']);
            } else {
                $config[$providerCode] = $this->decode($provider['config']);
            }
            if (!empty($provider['attribute_map'])) {
                $config[$providerCode]['user_mapping'] = $this->decode($provider['attribute_map']);
            }
        }
        return $config;
    }

    /**
     * Decodes a JSON string.
     * @param string $encoded
     * @return mixed
     */
    protected function decode($encoded)
    {
        if (empty($encoded)) {
            return [];
        }
        try {
            return \GuzzleHttp\json_decode($encoded, true);
        } catch (\InvalidArgumentException $e) {
            return [];
        }
    }
}
