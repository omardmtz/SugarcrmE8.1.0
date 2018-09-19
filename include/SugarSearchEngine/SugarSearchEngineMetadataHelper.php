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


/**
 * Class dealing with searchable modules
 *
 *                      !!! DEPRECATION WARNING !!!
 *
 * All code in include/SugarSearchEngine is going to be deprecated in a future
 * release. Do not use any of its APIs for code customizations as there will be
 * no guarantee of support and/or functionality for it. Use the new framework
 * located in the directories src/SearchEngine and src/Elasticsearch.
 *
 * @deprecated
 */
class SugarSearchEngineMetadataHelper
{
    /**
     * Cache key for enabled modules
     */
    const ENABLE_MODULE_CACHE_KEY = 'ftsEnabledModules';

    /**
     *
     * Cache key prefix for FTS enabled fields per module
     * @var string
     */
    const FTS_FIELDS_CACHE_KEY_PREFIX = 'fts_fields_';

    /**
     * Retrieve all FTS fields for all FTS enabled modules.
     *
     * @return array
     */
    public static function retrieveFtsEnabledFieldsForAllModules()
    {
        $cachedResults = sugar_cache_retrieve(self::ENABLE_MODULE_CACHE_KEY);
        if($cachedResults != null && !empty($cachedResults) )
        {
            $GLOBALS['log']->debug("Retrieving enabled fts modules from cache");
            return $cachedResults;
        }

        $results = array();

        $usa = new UnifiedSearchAdvanced();
        $modules = $usa->retrieveEnabledAndDisabledModules();

        foreach($modules['enabled'] as $module)
        {
            $fields = self::retrieveFtsEnabledFieldsPerModule($module['module']);
            $results[$module['module']] = $fields;
        }

        sugar_cache_put(self::ENABLE_MODULE_CACHE_KEY, $results, 0);
        return $results;

    }

    /**
     * Return all of the modules disabled for FTS by the administrator
     *
     * @return mixed|The
     */
    public static function getSystemEnabledFTSModules()
    {
        $usa = new UnifiedSearchAdvanced();
        $modules = $usa->retrieveEnabledAndDisabledModules();
        $enabledModules = array();
        foreach($modules['enabled'] as $module)
        {
            $enabledModules[ $module['module'] ] = $module['module'];
        }

        return $enabledModules;
    }

    /**
     * For a given module, return all of the full text search enabled fields.
     *
     * @param $module
     *
     */
    public static function retrieveFtsEnabledFieldsPerModule($module)
    {
        $results = array();
        if( is_string($module))
        {
            $obj = BeanFactory::getBean($module, null);
            if($obj == null)
               return FALSE;
        }
        else if( is_a($module, 'SugarBean') )
        {
            $obj = $module;
        }
        else
        {
            return $results;
        }

        if (empty($obj->module_name)) {
            return $results;
        }

        $cacheKey = self::FTS_FIELDS_CACHE_KEY_PREFIX . $obj->module_name;
        $cacheResults = sugar_cache_retrieve($cacheKey);
        if(!empty($cacheResults))
            return $cacheResults;

        foreach($obj->field_defs as $field => $def)
        {
            if (isset($def['full_text_search']) && is_array($def['full_text_search']) && !empty($def['full_text_search']['enabled'])) {
                $results[$field] = $def;
            }
        }

        sugar_cache_put($cacheKey, $results);
        return $results;

    }

    /**
     * Return all of the FTS enabled modules for a specific user
     *
     * @static
     * @param null|User $user
     * @return array
     */
    public static function getUserEnabledFTSModules(User $user = null)
    {
        if($user == null)
            $user = $GLOBALS['current_user'];

        $userDisabled = $user->getPreference('fts_disabled_modules');
        $userDisabled = explode(",", $userDisabled);

        $enabledModules = self::retrieveFtsEnabledFieldsForAllModules();
        $enabledModules = array_keys($enabledModules);

        $filteredEnabled = array();
        foreach($enabledModules as $m)
        {
            if( ! in_array($m, $userDisabled) )
            {
                $filteredEnabled[] = $m;
            }
        }

        return $filteredEnabled;
    }

    /**
     * Determine if a module is FTS enabled.
     *
     * @param $module
     * @return bool
     */
    public static function isModuleFtsEnabled($module)
    {
        $GLOBALS['log']->debug("Checking if module is fts enabled");
        $enabledModules = self::getSystemEnabledFTSModules();

        return in_array($module, $enabledModules);
    }

    /**
     *
     * Clear FTS metadata cache
     */
    public static function clearCache()
    {
        // clear possible cache entries per module
        $usa = new UnifiedSearchAdvanced();
        $list = $usa->retrieveEnabledAndDisabledModules();
        foreach ($list as $modules) {
            foreach ($modules as $module) {
                $cacheKey = self::FTS_FIELDS_CACHE_KEY_PREFIX . $module['module'];
                sugar_cache_clear($cacheKey);
            }
        }

        // clear master list of enabled modules
        sugar_cache_clear(self::ENABLE_MODULE_CACHE_KEY);
    }
}
