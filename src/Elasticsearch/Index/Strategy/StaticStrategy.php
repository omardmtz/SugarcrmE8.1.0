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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Index\Strategy;

/**
 *
 * Use a static index for given module. Index names can be overriden
 * through `$sugar_config`. If none given, we default to the name "shared".
 *
 * Example configuration:
 *
 * $sugar_config['full_text_engine']['Elastic']['index_strategy']['Accounts'] = array(
 *      'strategy' => 'static',
 *      'index' => 'index_name_goes_here',
 * );
 *
 */
class StaticStrategy extends AbstractStrategy
{
    const DEFAULT_INDEX = 'shared';

    /**
     * {@inheritdoc}
     */
    public function getManagedIndices($module)
    {
        return array($this->getStaticIndex($module));
    }

    /**
     * {@inheritdoc}
     */
    public function getReadIndices($module, array $context = array())
    {
        return array($this->getStaticIndex($module));
    }
    /**
     * {@inheritdoc}
     */
    public function getWriteIndex($module, array $context = array())
    {
        return $this->getStaticIndex($module);
    }

    /**
     * Return static index configuration for given module
     * @param string $module
     * @return array
     */
    protected function getStaticIndex($module)
    {
        return $this->getModuleConfig($module, 'index', self::DEFAULT_INDEX);
    }
}
