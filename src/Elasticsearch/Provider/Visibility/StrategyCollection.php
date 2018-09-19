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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility;

/**
 *
 * Visibility strategy collection
 *
 */
class StrategyCollection extends \SplObjectStorage
{
    /**
     * {@inheritdoc}
     * @param \SugarVisibility $strategy
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getHash($strategy)
    {
        if (!$strategy instanceof \SugarVisibility) {
            throw new \InvalidArgumentException('\SugarVisibility class expected');
        }
        return get_class($strategy);
    }

    /**
     * Add strategies for given modules
     * @param array $modules
     */
    public function addModuleStrategies(array $modules)
    {
        foreach ($modules as $module) {
            $this->addBeanStrategies(\BeanFactory::newBean($module));
        }
    }

    /**
     * Add strategies from given bean
     * @param \SugarBean $bean
     */
    public function addBeanStrategies(\SugarBean $bean)
    {
        foreach ($bean->loadVisibility()->getStrategies() as $strategy) {
            $this->addStrategy($strategy);
        }
    }

    /**
     * Attach visibility strategy object
     * @param \SugarVisibility $strategy
     */
    public function addStrategy(\SugarVisibility $strategy)
    {
        if ($strategy instanceof StrategyInterface) {
            $this->attach($strategy);
        }
    }
}
