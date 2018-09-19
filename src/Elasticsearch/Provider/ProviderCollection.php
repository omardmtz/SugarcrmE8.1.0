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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider;

use Sugarcrm\Sugarcrm\Elasticsearch\Container;

/**
 *
 * Provider collection iterator
 *
 */
class ProviderCollection implements \IteratorAggregate
{
    /**
     * @var AbstractProvider[]
     */
    private $providers = array();

    /**
     * @param array $providers Provider list
     */
    public function __construct(Container $container, array $providers = array())
    {
        foreach ($providers as $provider) {
            if (!$provider instanceof ProviderInterface) {
                $provider = $container->getProvider($provider);
            }
            $this->addProvider($provider);
        }
    }

    /**
     * {@inheritdoc}
     * @return ProviderInterface[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->providers);
    }

    /**
     * Add provider
     * @param AbstractProvider $provider
     */
    public function addProvider(AbstractProvider $provider)
    {
        $this->providers[] = $provider;
    }
}
