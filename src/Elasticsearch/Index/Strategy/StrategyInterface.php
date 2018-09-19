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
 * Interface for Index Strategies
 *
 */
interface StrategyInterface
{
    /**
     * Get list of all managed indices. This methods is only used during index
     * (re)initialization and should never be used for query building or
     * document indexing. The list of managed indices for a given modules is
     * not necessarily equal to the read indices list as there is never context
     * involved.
     *
     * @param string $module
     * return array
     */
    public function getManagedIndices($module);

    /**
     * Get list of read indices for given module. An index strategy can make
     * use of more than one index for one module in a distributed model or
     * for strategies implementing a rolling model.
     *
     * Supported context:
     *      'user'
     *      'range' (not implemented yet, used for rolling indices)
     *
     * @param array $module
     * @param array $context
     * return array
     */
    public function getReadIndices($module, array $context = array());

    /**
     * Return the current active write index for given module. Only one
     * index can be used at any given time to write new documents for every
     * module.
     *
     * Supported context:
     *      'bean'
     *
     * @param string $module
     * @param array $context
     * @return string
     */
    public function getWriteIndex($module, array $context = array());

    /**
     * Set configuration parameters
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     * Set identifier
     * @param string $identifier
     */
    public function setIdentifier($identifier);

    /**
     * Get identifier
     * @return string
     */
    public function getIdentifier();
}
