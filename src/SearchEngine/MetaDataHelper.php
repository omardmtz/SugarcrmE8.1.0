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

namespace Sugarcrm\Sugarcrm\SearchEngine;

use Sugarcrm\Sugarcrm\Logger\LoggerTransition;
use Psr\Log\LoggerInterface;

/**
 *
 * Helper class around MetaDataManager for SearchEngine
 *
 */
class MetaDataHelper
{
    /**
     * @var \MetaDataManager
     */
    protected $mdm;

    /**
     * @var string Metadata hash
     */
    protected $mdmHash;

    /**
     * @var \SugarCacheAbstract
     */
    protected $sugarCache;

    /**
     * @var LoggerTransition
     */
    protected $logger;

    /**
     * Disable caching
     * @var boolean
     */
    protected $disableCache = false;

    /**
     * Cross module aggregations definitions
     * @var array
     */
    protected $crossModuleAggDefs = array();


    /**
     * Force an in memory cache to be used when
     * both SugarCache and MetadataCache are disabled
     * @var array
     */
    protected $inMemoryCache = array();

    /**
     * @param \MetaDataManager $mdm
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->mdm = \MetaDataManager::getManager();
        $this->sugarCache = \SugarCache::instance();
        $this->updateHash();
    }

    /**
     * Enable/disable cache
     * @param boolean $toggle
     */
    public function disableCache($toggle)
    {
        $this->disableCache = (bool) $toggle;
        if ($toggle) {
            $this->logger->warning("MetaDataHelper: Performance degradation, cache disabled.");
        }
    }

    /**
     * Refresh cache. All keys from MetaDataHepler are prefixed with the hash
     * from MetaDataManager. Calling this method will get the current hash
     * and will automatically invalidate previous cache entries if anything
     * changed for MetaDataManager.
     */
    public function updateHash()
    {
        $defaultContext = new \MetaDataContextDefault();
        $this->mdmHash = $this->mdm->getCachedMetadataHash($defaultContext);

        // If the metadata manager cache is enabled but hash is not calculated, force the hash to be calculated
        if ($this->mdm->cacheEnabled() && empty($this->mdmHash)) {
            $this->mdm->getMetadata(array(), $defaultContext);
            $this->mdmHash = $this->mdm->getCachedMetadataHash($defaultContext);
        }

        // Make sure we have a hash available, if not lets temporarily disable
        // our cache backend.
        if (empty($this->mdmHash)) {
            $this->disableCache(true);
            $this->logger->warning("MetaDataHelper: No MetaDataHelper hash value available.");
        } else {
            $this->logger->debug("MetaDataHelper: Using hash " . $this->mdmHash);
        }
    }

    /**
     * Return system wide enabled FTS modules.
     * @return array
     */
    public function getAllEnabledModules()
    {
        $cacheKey = 'enabled_modules';
        if ($list = $this->getCache($cacheKey)) {
            return $list;
        }

        $list = array();
        $modules = $this->mdm->getModuleList();
        foreach ($modules as $module) {
            $vardefs = $this->getModuleVardefs($module);
            if (!empty($vardefs['full_text_search'])) {
                $list[] = $module;
            }
        }

        return $this->setCache($cacheKey, $list);
    }

    /**
     * Return FTS enabled modules configured for asynchronous indexing.
     * @return array
     */
    public function getAsyncModules()
    {
        $cacheKey = 'async_modules';
        if ($list = $this->getCache($cacheKey)) {
            return $list;
        }

        $list = array();
        foreach ($this->getAllEnabledModules() as $module) {
            $vardefs = $this->getModuleVardefs($module);
            if (!empty($vardefs['full_text_search_async'])) {
                $list[] = $module;
            }
        }
        return $this->setCache($cacheKey, $list);
    }

    /**
     * Get vardefs for given module
     * @param string $module
     * @return array
     */
    public function getModuleVardefs($module)
    {
        $cacheKey = 'vardefs_' . $module;
        if ($vardefs = $this->getCache($cacheKey)) {
            return $vardefs;
        }
        return $this->setCache($cacheKey, $this->mdm->getVarDef($module));
    }

    /**
     * Return vardefs for FTS enabled fields
     * @param string $module Module name
     * @param boolean $allowTypeOverride
     * @return array
     */
    public function getFtsFields($module, $allowTypeOverride = true)
    {
        $cacheKey = 'ftsfields_' . $module;
        if ($allowTypeOverride) {
            $cacheKey .= '_override';
        }

        if ($ftsFields = $this->getCache($cacheKey)) {
            return $ftsFields;
        }

        $ftsFields = array();
        $vardefs = $this->getModuleVardefs($module);
        foreach ($vardefs['fields'] as $field => $defs) {

            // skip field if no type has been defined
            if (empty($defs['type'])) {
                continue;
            }

            if (isset($defs['full_text_search']) && !empty($defs['full_text_search']['enabled'])) {
                // the type in 'full_text_search' overrides the type in the field
                if ($allowTypeOverride && !empty($defs['full_text_search']['type'])) {
                    $defs['type'] = $defs['full_text_search']['type'];
                }
                $ftsFields[$field] = $defs;
            }
        }
        return $this->setCache($cacheKey, $ftsFields);
    }

    /**
     * Return list of modules which are available for a given user.
     * @param \User $user
     * @return array
     */
    public function getAvailableModulesForUser(\User $user)
    {
        $cacheKey = 'modules_user_' . $user->id;
        if ($list = $this->getCache($cacheKey)) {
            return $list;
        }

        $list = array();

        foreach ($this->getAllEnabledModules() as $module) {
            $seed = \BeanFactory::newBean($module);
            if ($seed->ACLAccess('ListView', array('user' => $user))) {
                $list[] = $module;
            }
        }

        //Add the module "Tags" when it's not enabled, since the Tag module is globally used
        if (!in_array("Tags", $list)) {
            $list[] = "Tags";
        }
        return $this->setCache($cacheKey, $list);
    }

    /**
     * Verify if given module is FTS enabled
     * @param unknown $module
     * @return boolean
     */
    public function isModuleEnabled($module)
    {
        return in_array($module, $this->getAllEnabledModules());
    }

    /**
     * Verify if a module is available for given user
     * @param string $module
     * @param \User $user
     * @return boolean
     */
    public function isModuleAvailableForUser($module, \User $user)
    {
        return in_array($module, $this->getAvailableModulesForUser($user));
    }

    /**
     * Get auto increment fields for module.
     * @param string $module
     * @return array
     */
    public function getFtsAutoIncrementFields($module)
    {
        $cacheKey = 'autoincr_' . $module;
        if ($incFields = $this->getCache($cacheKey)) {
            return $incFields;
        }

        $incFields = array();
        foreach ($this->getFtsFields($module) as $field => $defs) {
            if (!empty($defs['auto_increment'])) {
                $incFields[] = $defs['name'];
            }
        }
        return $this->setCache($cacheKey, $incFields);
    }

    /**
     * Get HTML fields for module.
     * @param string $module
     * @return array
     */
    public function getFtsHtmlFields($module)
    {
        $cacheKey = 'html_' . $module;
        if ($htmlFields = $this->getCache($cacheKey)) {
            return $htmlFields;
        }

        $htmlFields = array();
        foreach ($this->getFtsFields($module) as $field => $defs) {
            if (!empty($defs['type']) && $defs['type'] === 'htmleditable_tinymce') {
                $htmlFields[] = $defs['name'];
            }
        }
        return $this->setCache($cacheKey, $htmlFields);
    }


    /**
     * Get cached content
     * @param string $key Cache key
     * @return null|mixed
     */
    protected function getCache($key)
    {
        if ($this->disableCache) {
            //use the in-memory cache when the sugar cache is disabled
            if ($this->isRealCacheDisabled() && isset($this->inMemoryCache[$key])) {
                return $this->inMemoryCache[$key];
            }
            return null;
        }
        $key = $this->getRealCacheKey($key);
        if (!$cached = $this->getRealCache($key)) {
            $this->logger->debug("MetaDataHelper: cache miss for '{$key}'");
        }
        return $cached;
    }

    /**
     * Set value in cache
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function setCache($key, $value)
    {
        if (!$this->disableCache) {
            $key = $this->getRealCacheKey($key);
            $this->setRealCache($key, $value);
        } else {
            //set to the in-memory cache when the sugar cache is disabled
            if ($this->isRealCacheDisabled()) {
                $this->inMemoryCache[$key] = $value;
            }
        }
        return $value;
    }

    /**
     * Check if the cache of MetaDataManager is disabled.
     * @return bool
     */
    protected function isRealCacheDisabled()
    {
        return !\MetaDataManager::cacheEnabled();
    }

    /**
     * Get the value from the real cache.
     * @param $key
     * @return mixed
     */
    protected function getRealCache($key)
    {
        return $this->sugarCache->$key;
    }

    /**
     * Set the value to the real cache.
     * @param string $key
     * @param mixed $value
     */
    protected function setRealCache($key, $value)
    {
        $this->sugarCache->set($key, $value);
    }

    /**
     * Get cache key. This method is not supposed to be called directly.
     * Use `$this->getCache` or `$this->setCache` as both implicitly use
     * this method.
     * @param string $key
     * @return string
     */
    protected function getRealCacheKey($key)
    {
        return "mdmhelper_" . $this->mdmHash . "_" . $key;
    }

    /**
     * Get the aggregation definitions of a given module.
     * @param string $module : the name of module
     * @return array
     */
    public function getModuleAggregations($module)
    {
        $aggDefs = $this->getAllAggDefs();
        if (isset($aggDefs['modules'][$module])) {
            return $aggDefs['modules'][$module];
        }
        return array();
    }

    /**
     * Get the aggregations definitions shared by multiple modules.
     * @return array
     */
    public function getCrossModuleAggregations()
    {
        $aggDefs = $this->getAllAggDefs();
        return $aggDefs['cross'];
    }

    /**
     * Get all aggregation definitions
     * @return array
     */
    public function getAllAggDefs()
    {
        $cacheKey = 'aggdefs';
        if ($list = $this->getCache($cacheKey)) {
            return $list;
        }

        $allAggDefs = array(
            'cross' => array(),
            'modules' => array(),
        );
        foreach ($this->getAllEnabledModules() as $module) {
            $aggDefs = $this->getAllAggDefsModule($module);
            $allAggDefs['cross'] = array_merge($allAggDefs['cross'], $aggDefs['cross']);
            $allAggDefs['modules'][$module] = $aggDefs['module'];
        }
        return $this->setCache($cacheKey, $allAggDefs);
    }

    /**
     * Get all aggregation definitions for given module split between cross
     * module aggregations and module specific aggregations. A field may have
     * more than one aggregation defined. Every aggregation requires an
     * aggregation id which is the key of the array definition. When this key
     * equals to the field name then we consider that aggregation as a cross
     * module one.
     *
     * Example definition for the field "date_entered":
     *
     *  'name' => 'date_entered',
     *  'type' => 'dateTime',
     *  'full_text_search' => array(
     *      'enabled' => true,
     *      'searchable' => true,
     *      'aggregations' => array(
     *          'date_entered' => array(
     *              'type' => 'date_range',
     *              'options' => array(..),
     *          ),
     *          'agg1' => array(
     *              'type' => 'my_date_range',
     *              'options' => array(..),
     *          ),
     *      ),
     *  ),
     *
     * The above field has two aggregations defined:
     *  1. The first one is a cross module aggregation because the id being
     *  used "date_entered" matches the field name.
     *  2. The second aggregation is module specific. The id can be choosen
     *  at will as long as it's different from the field name.
     *
     * Note that it's encouraged to only use cross module aggregation defs
     * in the SugarObject templates although not required. When cross module
     * aggregations are defined/overriden on a per module base ensure that
     * all those definitions match exactly. Having two different cross module
     * aggregations on the field "date_entered" with different settings will
     * have unpredictable results.
     *
     * @param string $module Module name
     * @return array
     */
    protected function getAllAggDefsModule($module)
    {
        $allAggDefs = array(
            'cross' => array(),
            'module' => array(),
        );

        $fieldDefs = $this->getFtsFields($module);
        foreach ($fieldDefs as $fieldName => $fieldDef) {

            // skip the field without aggregations defs
            if (empty($fieldDef['full_text_search']['aggregations'])) {
                continue;
            }

            $aggDefs = $fieldDef['full_text_search']['aggregations'];
            if (!is_array($aggDefs)) {
                continue;
            }

            foreach ($aggDefs as $aggId => $aggDef) {
                // type is required
                if (empty($aggDef['type'])) {
                    continue;
                }

                // set empty options array if nothing specified
                if (empty($aggDef['options']) || !is_array($aggDef['options'])) {
                    $aggDef['options'] = array();
                }

                // split module vs cross module aggregations
                if ($aggId === $fieldName) {
                    $allAggDefs['cross'][$fieldName] = $aggDef;
                } else {
                    $aggId = $fieldName . '.' . $aggId;
                    $allAggDefs['module'][$aggId] = $aggDef;
                }
            }
        }
        return $allAggDefs;
    }

    /**
     * Check if a field is searchable or not.
     * @param array $defs Field vardefs
     * @return boolean
     */
    public function isFieldSearchable(array $defs)
    {
        $isSearchable = false;

        // Determine if a field is considered as searchable:
        // 1. searchable is is set to true
        // 2. searchable is empty and boost is set (*)
        //
        // (*) This will be deprecated after 7.7 as this was the old behavior.

        if (isset($defs['full_text_search']['searchable'])) {
            if ($defs['full_text_search']['searchable'] === true) {
                $isSearchable = true;
            }
        } else {
            if (!empty($defs['full_text_search']['boost'])) {
                $isSearchable = true;
            }
        }
        return $isSearchable;
    }
}
