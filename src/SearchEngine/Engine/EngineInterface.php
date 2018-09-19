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

namespace Sugarcrm\Sugarcrm\SearchEngine\Engine;

/**
 *
 * Engine interface
 *
 */
interface EngineInterface
{
    /**
     * Get MetaDataHelper
     * @return \Sugarcrm\Sugarcrm\SearchEngine\MetaDataHelper
     */
    public function getMetaDataHelper();

    /**
     * Set engine configuration parameters which are defined
     * in `$sugar_config['full_text_search']['engine']`
     *
     * @param array $config
     */
    public function setEngineConfig(array $config);

    /**
     * Get engine configuration
     *
     * @return array
     */
    public function getEngineConfig();

    /**
     * Set global search engine configuration parameters which
     * are defined in `$sugar_config['search_engine']`
     *
     * @param array $config
     */
    public function setGlobalConfig(array $config);

    /**
     * Get global search engine configuration
     *
     * @return array
     */
    public function getGlobalConfig();

    /**
     * Verify if search engine connection is available. Responses might
     * be cached to avoid frequent connectivity checks.
     *
     * @param boolean $force Force connection check
     * @return boolean
     */
    public function isAvailable($force = false);

    /**
     * Verify actual connectivity to the search engine backend. The usage
     * of `$this->isAVailable` is preferred over this method. Only use this
     * method for low level connectivity checks.
     *
     * @param boolean $updateAvailability Optional disable updating the
     *      cached availability based on the outcome of this test.
     * @return integer Connection status, 1 => success, < 0 => error
     */
    public function verifyConnectivity($updateAvailability = true);

    /**
     * Schedule indexing
     *
     * @param array $modules
     * @param string $clearData
     * @return boolean
     */
    public function scheduleIndexing(array $modules = array(), $clearData = false);

    /**
     * Create the mappings for given module without re-creating the index.
     * @param array $modules
     * @return bool
     */
    public function addMappings(array $modules = array());

    /**
     * Index given bean
     *
     * @param \SugarBean $bean The bean to index
     * @param array $options Optional options
     */
    public function indexBean(\SugarBean $bean, array $options = array());

    /**
     * Run full reindexing inline
     *
     * @param string $clearData
     */
    public function runFullReindex($clearData = false);

    /**
     * Enable/disable asynchronous indexing
     *
     * @param boolean $toggle
     */
    public function setForceAsyncIndex($toggle);

    /**
     * Enable/disable indexing
     *
     * @param boolean $toggle
     */
    public function setDisableIndexing($toggle);
}
