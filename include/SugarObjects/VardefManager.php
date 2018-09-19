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

SugarAutoLoader::requireWithCustom('include/MetaDataManager/MetaDataCache.php');

/**
 * Vardefs management
 * @api
 */
class VardefManager{
    static $custom_disabled_modules = array();
    static $linkFields;
    public static $inReload = array();
    protected static $ignoreRelationshipsForModule = array();
    protected static $cache;
    protected static $sugarConfig;

    /**
     * List of templates that have already been fetched
     *
     * @var array
     */
    protected static $fetchedTemplates = array();

    /**
     * List of templates to ignore for BWC modules when adding templates. This
     * should be a key value pair like 'template_name' => true.
     *
     * @var array
     */
    public static $ignoreBWCTemplates = array(
        'taggable' => true,
    );

    /**
     * List of merge types used in addTemplate()
     *
     * @var array
     */
    public static $mergeTypes = array(
        'fields',
        'relationships',
        'indices',
        'name_format_map',
        'visibility',
        'acls',
    );

    /**
     * this method is called within a vardefs.php file which extends from a SugarObject.
     * It is meant to load the vardefs from the SugarObject.
     */
    static function createVardef($module, $object, $templates = array('default'), $object_name = false)
    {
        global $dictionary;

        if (isset($GLOBALS['dictionary'][$object]['uses'])) {
            // Load in the vardef 'uses' first
            $templates = array_merge($GLOBALS['dictionary'][$object]['uses'], $templates);
            unset($GLOBALS['dictionary'][$object]['uses']);

            // createVardef auto-adds the 'default' template, so to avoid using it twice
            // among avoiding using other templates twice let's make sure the templates
            // are unique
            $templates = array_unique($templates);
        }

        // If a vardef specifies templates to ignore then remove those from the
        // templates array here
        if (isset($GLOBALS['dictionary'][$object]['ignore_templates'])) {
            $ignore = (array) $GLOBALS['dictionary'][$object]['ignore_templates'];
            $templates = array_diff($templates, $ignore);
        }

        // Load up fields if there is a need for that. Introduced with the taggable
        // template
        if (isset($GLOBALS['dictionary'][$object]['load_fields'])) {
            $lf = $GLOBALS['dictionary'][$object]['load_fields'];

            // Make sure we actually have a fields array to work with
            if (empty($GLOBALS['dictionary'][$object]['fields'])) {
                $GLOBALS['dictionary'][$object]['fields'] = array();
            }

            if (is_string($lf) && function_exists($lf)) {
                // Merge fields from the function onto the known fields
                $GLOBALS['dictionary'][$object]['fields'] = array_merge(
                    $GLOBALS['dictionary'][$object]['fields'],
                    $lf()
                );
            } elseif (is_array($lf) && isset($lf['class']) && isset($lf['method'])) {
                $class = $lf['class'];
                $method = $lf['method'];

                // Merge fields from the method call onto the known fields
                $GLOBALS['dictionary'][$object]['fields'] = array_merge(
                    $GLOBALS['dictionary'][$object]['fields'],
                    $class::$method()
                );
            }
        }

        // Get all of the templates that would be needed, with the core templates
        // up front in reverse order followed by the implementations
        $templates = self::getLoadableTemplates($templates, $module, $object, $object_name);

        // Skip over uses in the addTemplate method because we've already gotten
        // all templates gathered.
        foreach ($templates as $template) {
            VardefManager::addTemplate($module, $object, $template, $object_name, true);
        }

        // Some of the templates might have loaded templates
        if (isset($GLOBALS['dictionary'][$object]['templates'])) {
            $tDiff = array_diff($GLOBALS['dictionary'][$object]['templates'], $templates);
            if ($tDiff) {
                $templates = $GLOBALS['dictionary'][$object]['templates'];
            }
        }

        LanguageManager::createLanguageFile($module, $templates);

        if (isset(VardefManager::$custom_disabled_modules[$module]))
        {
            $vardef_paths = array(
                SugarAutoLoader::loadExtension("vardefs", $module),
                'custom/Extension/modules/' . $module . '/Ext/Vardefs/vardefs.php'
            );

            //search a predefined set of locations for the vardef files
            foreach ($vardef_paths as $path)
            {
                // file_exists here is only for custom/Extension since loadExtension already checks
                // the file map and will return false if something's wrong
                if(!empty($path) && file_exists($path)) {
                    require($path);
                }
            }
        }

        // Handle unsetting of fields as per the defs. Do this last to make sure
        // all extension fields have loaded
        if (isset($GLOBALS['dictionary'][$object]['unset_fields'])) {
            $uf = $GLOBALS['dictionary'][$object]['unset_fields'];
            if (is_string($uf)) {
                unset($GLOBALS['dictionary'][$object]['fields'][$uf]);
            } elseif (is_array($uf)) {
                foreach ($uf as $f) {
                    unset($GLOBALS['dictionary'][$object]['fields'][$f]);
                }
            }
        }
    }

    /**
     * Gets all templates for an object in a format consistent with consumption
     * by createVardef().
     *
     * @param array $templates The current stack of templates for a module
     * @param string $module The name of the module
     * @param string $object The name of the object
     * @param boolean $objectName The name of the object if different than $object
     * @return array
     */
    public static function getLoadableTemplates($templates, $module, $object, $objectName = false)
    {
        // Grab a cleaned up version of all templates, grouped by core and
        // implementations
        list($core, $impl) = self::getAllTemplates($templates, $module, $object, $objectName);

        // Now send back the list with the core templates reversed and up front,
        // followed by the added templates
        return array_merge(array_reverse($core), $impl);
    }

    /**
     * Gets all templates for an object from a list of templates
     *
     * @param array $templates The current stack of templates for a module
     * @param string $module The name of the module
     * @param string $object The name of the object
     * @param boolean $objectName The name of the object if different than $object
     * @return array
     */
    public static function getAllTemplates($templates, $module, $object, $objectName = false)
    {
        // Clear the placeholders for loaded templates in getTemplates
        self::clearFetchedTemplates();

        // Get all of the loadable templates
        $all = array();
        foreach ((array) $templates as $template) {
            $get = self::getTemplates($module, $object, $template, $objectName);
            $all = array_merge($all, $get);
        }

        // Clear the placeholders AGAIN now that we're done
        self::clearFetchedTemplates();

        // Handle getting core and added templates, starting with OOTB templates
        $coreTemplates = self::getCoreTemplates();

        // This extracts just the core templates from all templates
        $core = array_intersect($all, $coreTemplates);

        // This extracts the implementations from all templates
        $impl = array_diff($all, $core);

        // Send back the return as an array
        return array($core, $impl);
    }

    /**
     * Clears the stack of fetched templates used in collecting templates
     *
     * @param string $object The object name that the templates are keyed on
     * @return void
     */
    public static function clearFetchedTemplates($object = '')
    {
        // If there is an object name passed, unset just that object name
        if (!empty($object)) {
            unset(self::$fetchedTemplates[$object]);
        } else {
            // Otherwise, clear them all
            self::$fetchedTemplates = array();
        }
    }

    /**
     * Gets all loadable templates for an object
     *
     * @param string $module The name of the module
     * @param string $object The name of the object
     * @param array $template The template to get
     * @param boolean $object_name The name of the object if different than $object
     * @return array
     */
    public static function getTemplates($module, $object, $template, $object_name = false)
    {
        // Normalize the template name
        $template = self::getTemplateName($template);

        // Don't fetch again if fetched already
        if (isset(self::$fetchedTemplates[$object][$template])) {
            return array();
        }

        // Add to the fetched stack so we don't get stuff more than once
        self::$fetchedTemplates[$object][$template] = $template;

        // Stack it
        $templates = array($template);

        // Needed for vardef templates
        $_object_name = self::getObjectName($object, $object_name);
        $table_name = self::getTableName($module, $object);

        // Get the loadable paths for this template
        $paths = self::getTemplatePaths($template);

        // Loop the paths and get what we need if there is anything
        foreach($paths as $path) {
            $vardefs = array();
            require $path;

            // Handle recursing into the uses stack
            if (!empty($vardefs['uses'])) {
                foreach ($vardefs['uses'] as $uses) {
                    $templates = array_merge($templates, self::getTemplates($module, $object, $uses, $object_name));
                }
            }
        }

        return $templates;
    }

    /**
     * Normalizes the template name
     *
     * @param string $template The name of the template to normalize
     * @return string
     */
    public static function getTemplateName($template)
    {
        // Normalize the template name
        return $template == 'default' ? 'basic' : $template;
    }

    /**
     * Gets the object name property needed for vardef templates
     *
     * @param string $object The object name
     * @param string $objectName The object name if different than $object
     * @param bool $nameOnly Flag to decide if we want just the object name or the
     *                       the lowercase value of it
     * @return string The lowercased object name needed by vardef templates
     */
    public static function getObjectName($object, $objectName, $nameOnly = false)
    {
        if (empty($objectName)) {
            $objectName = $object;
        }

        return $nameOnly ? $objectName : strtolower($objectName);
    }

    /**
     * Gets the name of a table for the vardef templates
     *
     * @param string $module The name of the module
     * @param string $object The name of the object
     * @return string The table name for this object
     */
    public static function getTableName($module, $object)
    {
        if (!empty($GLOBALS['dictionary'][$object]['table'])) {
            $table = $GLOBALS['dictionary'][$object]['table'];
        } else {
            $table = strtolower($module);
        }

        return $table;
    }

    /**
     * Gets a list of paths that might contain vardefs for a template.
     *
     * @param string $template The template name
     * @return array Paths that might contain vardefs for this template
     */
    public static function getTemplatePaths($template)
    {
        return SugarAutoLoader::existingCustom(
            'include/SugarObjects/templates/' . $template . '/vardefs.php',
            'include/SugarObjects/implements/' . $template . '/vardefs.php'
        );
    }

    /**
     * Gets the list of core templates use in building modules off of
     *
     * @return array
     */
    public static function getCoreTemplates()
    {
        // We will search in base and custom SugarObjects templates
        $paths = array(
            'include/SugarObjects/templates/',
            'custom/include/SugarObjects/templates/',
        );

        // Grab all top level directories from there, and add in default
        $dirs = array('default');
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $dirs = array_merge($dirs, scandir($path));
            }
        }

        // Get rid of all dot and double dot dirs, and reorder indexes
        $dirs = array_values(array_diff($dirs, array('.', '..')));

        // Send back the uniqued array of these dirs
        return array_unique($dirs);
    }

    /**
     * Enables/Disables the loading of custom vardefs for a module.
     * @param String $module Module to be enabled/disabled
     * @param Boolean $enable true to enable, false to disable
     * @return  null
     */
    public static function setCustomAllowedForModule($module, $enable) {
        if ($enable && isset($custom_disabled_modules[$module])) {
              unset($custom_disabled_modules[$module]);
        } else if (!$enable) {
              $custom_disabled_modules[$module] = true;
        }
    }

    /**
     * Checks to see if the template for a module should be skipped. Used by
     * addTemplate to see if certain templates should be added by BWC modules.
     *
     * @param string $module The name of the module to check
     * @param string $template The template to check for the module
     * @return boolean
     */
    public static function ignoreBWCTemplate($module, $template)
    {
        // Add logic as needed here... starting with BWC modules not being taggable
        return isModuleBWC($module) && !empty(self::$ignoreBWCTemplates[$template]);
    }

    /**
     * Checks the ignore_templates vardef directive to see if a module should not
     * implement a template that is implemented by a parent module.
     *
     * @param string $object The name of the object
     * @param string $template The name of the template
     * @return boolean
     */
    public static function ignoreTemplate($object, $template)
    {
        if (isset($GLOBALS['dictionary'][$object]['ignore_templates'])) {
            if (is_array($GLOBALS['dictionary'][$object]['ignore_templates'])) {
                return in_array($template, $GLOBALS['dictionary'][$object]['ignore_templates']);
            } else {
                return $template === $GLOBALS['dictionary'][$object]['ignore_templates'];
            }
        }

        return false;
    }

    /**
     * Adds a template for a module and object. Will recurse into the 'uses'
     * property of the vardef unless $skipUses is false.
     *
     * @param string $module The bean module name
     * @param string $object The bean object name
     * @param string $template The name of the template to add
     * @param boolean $object_name Name of the object as used in Module Builder
     * @param boolean $skipUses If true, will not recurse into templates in 'uses'
     */
    public static function addTemplate($module, $object, $template, $object_name = false, $skipUses = false)
    {
        // Normalize the template name
        $template = self::getTemplateName($template);

        // The ActivityStream has subdirectories but this code doesn't expect it
        // let's fix it up here
        if (strpos($module,'/') !== false) {
            $tmp = explode('/',$module);
            $module = array_pop($tmp);
        }

        // Verify that we should use this template for BWC modules
        if (self::ignoreBWCTemplate($module, $template)) {
            return;
        }

        // Verify that we should use this template in general
        if (self::ignoreTemplate($object, $template)) {
            return;
        }

        $templates = array();
        $fields = array();
        $object_name = self::getObjectName($object, $object_name, true);
        $_object_name = self::getObjectName($object, $object_name);
        $table_name = self::getTableName($module, $object);

        if (empty($templates[$template])) {
            $paths = self::getTemplatePaths($template);
            foreach ($paths as $path) {
                require $path;
                $templates[$template] = $vardefs;
                // Implementations have to be loaded after core templates. This
                // makes sure that happens properly.
                $templates[$template]['_implementation'] = strpos($path, 'implements/') !== false;
            }
        }

        if (!empty($templates[$template])) {
            foreach (self::$mergeTypes as $merge_type) {
                if (empty($GLOBALS['dictionary'][$object][$merge_type])) {
                    $GLOBALS['dictionary'][$object][$merge_type] = array();
                }

                // Only handle merging if the merge type of the template is not
                // empty and it is an array. Also? I think PHP really needs an
                // empty_type() function for things like this.
                $handleMerge = !empty($templates[$template][$merge_type])
                               && is_array($templates[$template][$merge_type]);
                if ($handleMerge) {
                    // If this template is an implementation, merge it into existing
                    if ($templates[$template]['_implementation']) {
                        $merged = array_merge(
                            $GLOBALS['dictionary'][$object][$merge_type],
                            $templates[$template][$merge_type]
                        );
                    } else {
                        // Otherwise merge what we have onto the template
                        $merged = array_merge(
                            $templates[$template][$merge_type],
                            $GLOBALS['dictionary'][$object][$merge_type]
                        );
                    }
                    $GLOBALS['dictionary'][$object][$merge_type] = $merged;
                }
            }

            /* The duplicate_check property is inherited in full unless already defined - merge has no meaning here */
            if(empty($GLOBALS['dictionary'][$object]['duplicate_check']) && !empty($templates[$template]['duplicate_check'])) {
               $GLOBALS['dictionary'][$object]['duplicate_check'] = $templates[$template]['duplicate_check'];
            }

            if(isset($templates[$template]['favorites']) && !isset($GLOBALS['dictionary'][$object]['favorites']))
            {
            	$GLOBALS['dictionary'][$object]['favorites'] = $templates[$template]['favorites'];
            }
            // maintain a record of this objects inheritance from the SugarObject templates...
            $GLOBALS['dictionary'][$object]['templates'][ $template ] = $template ;

            // Only load consumed templates if we were not told not to
            if (!empty($templates[$template]['uses']) && !$skipUses) {
                foreach ($templates[$template]['uses'] as $extraTemplate) {
                    VardefManager::addTemplate($module, $object, $extraTemplate, $object_name);
                }
            }
        }
    }

    /**
     * Remove invalid field definitions
     * @static
     * @param Array $fieldDefs
     * @return  Array
     */
    static function cleanVardefs($fieldDefs)
    {
        foreach($fieldDefs as $field => $defs) {
            if (empty($defs['name']) || empty($defs['type'])) {
                unset($fieldDefs[$field]);
            }
        }

        return $fieldDefs;
    }

    /**
     * Save the dictionary object to the cache
     * @param string $module the name of the module
     * @param string $object the name of the object
     */
    public static function saveCache($module, $object)
    {
        $object = self::updateObjectDictionary($module, $object);
        
        $sc = self::$sugarConfig ?: self::$sugarConfig = SugarConfig::getInstance();
        if ($sc->get('noFilesystemMetadataCache', false)) {
            $cache = self::$cache ?: self::$cache = new MetaDataCache(DBManagerFactory::getInstance());
            $cache->set(static::getCacheKey($module, $object), $GLOBALS['dictionary'][$object]);
        } else {
            $file = create_cache_directory(self::getCacheFileName($module, $object));

            $out="<?php \n \$GLOBALS[\"dictionary\"][\"". $object . "\"]=" . var_export($GLOBALS['dictionary'][$object], true) .";";
            sugar_file_put_contents_atomic($file, $out);
        }
    }

    /**
     * Update the dictionary object.
     * @param string $module the name of the module
     * @param string $object the name of the object
     * @return string
     */
    public static function updateObjectDictionary($module, $object)
    {
        if (empty($GLOBALS['dictionary'][$object]))
            $object = BeanFactory::getObjectName($module);

        $GLOBALS['dictionary'][$object]['fields'] = self::cleanVardefs($GLOBALS['dictionary'][$object]['fields']);
        return $object;
    }


    /**
     * clear out the vardef cache. If we receive a module name then just clear the vardef cache for that module
     * otherwise clear out the cache for every module
     * @param string module_dir the module_dir to clear, if not specified then clear
     *                      clear vardef cache for all modules.
     * @param string object_name the name of the object we are clearing this is for sugar_cache
     */
    static function clearVardef($module_dir = '', $object_name = '')
    {
        //if we have a module name specified then just remove that vardef file
        //otherwise go through each module and remove the vardefs.php
        if(!empty($module_dir) && !empty($object_name)){
        }else{
            $sc = self::$sugarConfig ?: self::$sugarConfig = SugarConfig::getInstance();
            if ($sc->get('noFilesystemMetadataCache', false)) {
                $cache = self::$cache ?: self::$cache = new MetaDataCache(DBManagerFactory::getInstance());
                $cache->clearKeysLike('vardefs::');
            } else {
                global $beanList;
                foreach($beanList as $module_dir => $object_name){
                    VardefManager::_clearCache($module_dir, $object_name);
                }
            }

        }
        VardefManager::_clearCache($module_dir, $object_name);
    }

    /**
     * PRIVATE function used within clearVardefCache so we do not repeat logic
     * @param string module_dir the module_dir to clear
     * @param string object_name the name of the object we are clearing this is for sugar_cache
     */
    protected static function _clearCache($module_dir = '', $object_name = '')
    {
        if(!empty($module_dir) && !empty($object_name)){

            //Some modules like cases have a bean name that doesn't match the object name
            if (empty($GLOBALS['dictionary'][$object_name])) {
                $newName = BeanFactory::getObjectName($module_dir);
                $object_name = $newName != false ? $newName : $object_name;
            }

            $sc = self::$sugarConfig ?: self::$sugarConfig = SugarConfig::getInstance();
            if ($sc->get('noFilesystemMetadataCache', false)) {
                $cache = self::$cache ?: self::$cache = new MetaDataCache(DBManagerFactory::getInstance());
                $cache->set(static::getCacheKey($module_dir, $object_name), null);
            } else {
                $file = sugar_cached(self::getCacheFileName($module_dir, $object_name));
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Given a module, search all of the specified locations, and any others as specified
     * in order to refresh the cache file
     *
     * @param string $module the given module we want to load the vardefs for
     * @param string $object the given object we wish to load the vardefs for
     * @param array $additional_search_paths an array which allows a consumer to pass in additional vardef locations to search
     * @param boolean $cacheCustom a flag to include rebuilding custom fields into cache
     * @param array $params a set of parameters
     * @param boolean $includeExtension a flag to include rebuilding the extension files or not
     */
    public static function refreshVardefs(
        $module,
        $object,
        $additional_search_paths = null,
        $cacheCustom = true,
        $params = array(),
        $includeExtension = true
    ) {
        // Some of the vardefs do not correctly define dictionary as global.  Declare it first.
        global $dictionary, $beanList;
        // some tests do new SugarBean(), we can't do much with it here.
        if(empty($module)) return;
        $guard_name = "$module:$object";
        if (isset(self::$inReload[$guard_name])) {
            return;
        }
        self::$inReload[$guard_name] = true;
        $moduleDir = BeanFactory::getModuleDir($module);
        //Do not force reloading from vardefs if we are only updating calc_fields
        if (empty($dictionary[$object]) || empty($params['related_calc_fields_only'])) {
            if ($includeExtension === true) {
                $vardef_paths = array(
                    'modules/' . $moduleDir . '/vardefs.php',
                    SugarAutoLoader::loadExtension("vardefs", $moduleDir),
                    'custom/Extension/modules/' . $moduleDir . '/Ext/Vardefs/vardefs.php',
                );
            } else {
                $vardef_paths = array('modules/' . $moduleDir . '/vardefs.php');
            }

            // Add in additional search paths if they were provided.
            if(!empty($additional_search_paths) && is_array($additional_search_paths))
            {
                $vardef_paths = array_merge($vardef_paths, $additional_search_paths);
            }
            $found = false;
            //search a predefined set of locations for the vardef files
            foreach(SugarAutoLoader::existing($vardef_paths) as $path){
                require($path);
                $found = true;
            }

            if ($found){
                // Put ACLStatic into vardefs for beans supporting ACLs
                self::addSugarACLStatic($object, $module);
            }

            if (!$found) {
                $GLOBALS['log']->warn("Failed to locate vardef files of $module:$object object");
                unset(self::$inReload[$guard_name]);
                return;
            }

            //Some modules like cases have a bean name that doesn't match the object name
            if(empty($dictionary[$object])) {
                 $newName = BeanFactory::getObjectName($module);
                 if(!empty($newName)) {
                    $object = $newName;
                }
            }
        }

        //load custom fields into the vardef cache
        if($cacheCustom && !empty($GLOBALS['dictionary'][$object]['fields'])){
            $df = new DynamicField ($module) ;
            $df->buildCache($module, false);
        }

        if (!isset($GLOBALS['dictionary'][$object]['has_pii_fields'])
            && !empty($GLOBALS['dictionary'][$object]['fields'])) {
            $hasPiiFields = false;

            foreach ($GLOBALS['dictionary'][$object]['fields'] as $definition) {
                if (!empty($definition['pii'])) {
                    $hasPiiFields = true;
                    break;
                }
            }

            $GLOBALS['dictionary'][$object]['has_pii_fields'] = $hasPiiFields;
        }

        // if we are currently rebuilding the relationships, we don't want `updateRelCFModules` to be called
        // as it will fail when trying to look up relationships as they my have not been loaded into the
        // cache yet
        $rebuildingRelationships = (isset($GLOBALS['buildingRelCache']) && $GLOBALS['buildingRelCache'] === true);
        if (empty($params['ignore_rel_calc_fields']) && $rebuildingRelationships === false) {
            self::updateRelCFModules($module, $object);
        }

        //great! now that we have loaded all of our vardefs.
        //let's go save them to the cache file
        //note that we don't write to cache when $includeExtension = false,
        //where we only need vardef values temporarily (see 1_UpdateFTSSettings.php::getNewFieldDefs()
        if (!empty($dictionary[$object])) {
            if ($includeExtension) {
                VardefManager::saveCache($module, $object);
            } else {
                VardefManager::updateObjectDictionary($module, $object);
            }
            SugarBean::clearLoadedDef($object);
        }
        unset(self::$inReload[$guard_name]);
    }

    /**
     * Add default SugarACLStatic
     *
     * @param string $object Object name
     * @param string $module Module name
     */
    protected static function addSugarACLStatic($object, $module)
    {
        global $dictionary;
        // Put ACLStatic into vardefs for beans supporting ACLs
        if(!empty($dictionary[$object]) && !isset($dictionary[$object]['acls']['SugarACLStatic'])){
            // $beanList is a mess. most of its keys are module names (Cases, etc.),
            // but some are object names (ForecastOpportunities), so we check both
            $class = BeanFactory::getBeanClass($module) ?: BeanFactory::getBeanClass($object);
            if ($class) {
                $ref = new ReflectionClass($class);
                $instance = $ref->newInstanceWithoutConstructor();
                if (($instance instanceof SugarBean) && $instance->bean_implements('ACL')) {
                    $dictionary[$object]['acls']['SugarACLStatic'] = true;
                    SugarACL::resetACLs($module);
                }
            }
        }
    }

    /**
     * @static
     * @param  $module
     * @param  $object
     * @return array|bool  returns a list of all fields in the module of type 'link'.
     */
    public static function getLinkFieldsForModule($module, $object)
    {
        global $dictionary;
        //Some modules like cases have a bean name that doesn't match the object name
        if (empty($dictionary[$object])) {
            $newName = BeanFactory::getObjectName($module);
            $object = $newName != false ? $newName : $object;
        }
        if (empty($dictionary[$object])) {
            self::loadVardef($module, $object, false, array('ignore_rel_calc_fields' => true));
        }
        if (empty($dictionary[$object]))
        {
            $GLOBALS['log']->debug("Failed to load vardefs for $module:$object in linkFieldsForModule<br/>");
            return false;
        }

        //Cache link fields for this call in a static variable
        if (!isset(self::$linkFields)) {
            self::$linkFields = array();
        }

        // But make sure that the cache is not empty before moving on
        if (!empty(self::$linkFields[$object])) {
            return self::$linkFields[$object];
        }

        $vardef = $dictionary[$object];
        $links = array();
        foreach($vardef['fields'] as $name => $def)
        {
            //Look through all link fields for related modules that have calculated fields that use that relationship
            if(!empty($def['type']) && $def['type'] == 'link'
                && (!empty($def['relationship']) || (!empty($def['link_class'])))
            )
            {
                $links[$name] = $def;
            }
        }

        self::$linkFields[$object] = $links;

        return $links;
    }

    /**
     * Gets a link field for a relationship
     *
     * @param string $module The module to find a link field from
     * @param string $object The object name for the module
     * @param string $relName The relationship name or link name to use
     * @param boolean $byLinkName If set, will treat $relName as a link name not
     *                            a relationship name
     * @return array, or false if no link field is found
     */
    public static function getLinkFieldForRelationship($module, $object, $relName, $byLinkName = false)
    {
        $cacheKey = "LFR{$module}{$object}{$relName}";
        $cacheValue = sugar_cache_retrieve($cacheKey);
        if(!empty($cacheValue)) {
            return $cacheValue;
        }

        // If we are searching by link name instead of relationship name, set that here
        $defIndex = $byLinkName ? 'name' : 'relationship';
        $relLinkFields = self::getLinkFieldsForModule($module, $object);
        $matches = array();
        if (!empty($relLinkFields)) {
            // If there is a field with this name as a field def, use it
            if ($byLinkName && isset($relLinkFields[$relName])) {
                $matches[] = $relLinkFields[$relName];
            } else {
                // Otherwise loop and set
                foreach($relLinkFields as $rfName => $rfDef) {
                    if ($rfDef[$defIndex] == $relName) {
                        $matches[] = $rfDef;
                    }
                }
            }
        }

        if (empty($matches)) {
            return false;
        }

        if (sizeof($matches) == 1) {
            $results = $matches[0];
        } else {
            //For relationships where both sides are the same module, more than one link will be returned
            $results = $matches;
        }

        sugar_cache_put($cacheKey, $results);
        return $results;
    }

    /**
     * Returns a list of link names for the collection field.
     *
     * @param string $module
     * @param string $objectName
     * @param string $collectionName The name of the collection field.
     * @return array
     */
    public static function getLinkFieldsForCollection($module, $objectName, $collectionName)
    {
        $cacheKey = "LFC{$module}{$objectName}{$collectionName}";
        $cacheValue = sugar_cache_retrieve($cacheKey);

        if (!empty($cacheValue)) {
            return $cacheValue;
        }

        $links = array();

        if (empty($GLOBALS['dictionary'][$objectName])) {
            static::loadVardef($module, $objectName, false, array('ignore_rel_calc_fields' => true));
        }

        if (!empty($GLOBALS['dictionary'][$objectName]['fields'][$collectionName]) &&
            $GLOBALS['dictionary'][$objectName]['fields'][$collectionName]['type'] === 'collection' &&
            !empty($GLOBALS['dictionary'][$objectName]['fields'][$collectionName]['links'])
        ) {
            foreach ($GLOBALS['dictionary'][$objectName]['fields'][$collectionName]['links'] as $def) {
                $links[] = is_array($def) ? $def['name'] : $def;
            }
        }

        return $links;
    }

    /**
     * @static
     * @param  $module String name of module.
     * @param  $object String name of module Bean.
     * Updates a list of link fields which have relationships to modules with calculated fields
     * that use this module. Needed to cause an update to those modules when this module is updated.
     * @return bool
     */
    protected static function updateRelCFModules($module, $object){
        global $dictionary, $beanList;
        if (empty($dictionary[$object]) || empty($dictionary[$object]['fields']))
            return false;

        $linkFields = self::getLinkFieldsForModule($module, $object);
        if (empty($linkFields))
        {
            $dictionary[$object]['related_calc_fields'] = array();
            return false;
        }

        $linksWithCFs = array();

        foreach($linkFields as $name => $def)
        {
            $relName = $def['relationship'];

            //Start by getting the relation
            $relDef = false;
            if (!empty($def['module']))
                $relMod = $def['module'];
            else {
                if (!empty($dictionary[$relName]['relationships'][$relName]))
                    $relDef = $dictionary[$relName]['relationships'][$relName];//[$relName];
                else if (!empty($dictionary[$object][$relName]))
                    $relDef = $dictionary[$object][$relName];
                else {
                    $relDef = SugarRelationshipFactory::getInstance()->getRelationshipDef($relName);
                    if (!$relDef)
                        continue;
                }

                if(empty($relDef['lhs_module']))
                {
                    continue;
                }
                $relMod = $relDef['lhs_module'] == $module ? $relDef['rhs_module'] : $relDef['lhs_module'];
            }

            if (empty($beanList[$relMod]))
                continue;

            $relObject = BeanFactory::getObjectName($relMod);
            $relLinkFields = self::getLinkFieldsForModule($relMod, $relObject);
            if (!empty($relLinkFields))
            {
                foreach($relLinkFields as $rfName => $rfDef)
                {
                    if ($rfDef['relationship'] == $relName && self::modHasCalcFieldsWithLink($relMod, $relObject, $rfName))
                    {
                        $linksWithCFs[$name] = true;
                    }
                }
            }
        }

        $dictionary[$object]['related_calc_fields'] = array_keys($linksWithCFs);
    }



    /**
     * @static
     * @param  $module
     * @param  $object
     * @param  $linkName
     * @return bool return true if the module contains calculated fields with the specified link in the formula.
     */
    public static function modHasCalcFieldsWithLink($module, $object, $linkName)
    {
        global $dictionary;
        //Some modules like cases have a bean name that doesn't match the object name
        $newName = BeanFactory::getObjectName($module);
        $object = empty($newName) ? $object : $newName;

        if (empty($dictionary[$object]))
            self::loadVardef($module, $object);
        if (empty($dictionary[$object])){
            return false;
        }

        $vardef = $dictionary[$object];
        $hasFieldsWithLink = false;
        if (!empty($vardef['fields']))
        {
            foreach($vardef['fields'] as $name => $def)
            {
                //Look through all calculated fields for uses of this link field
                if(!empty($def['formula']) && preg_match('/\W\$' . $linkName . '\W/', $def['formula']))
                {
                    $hasFieldsWithLink = true;
                    break;
                }
            }
        }
        return $hasFieldsWithLink;
    }

    /**
     * @static
     * @param SugarBean $focus
     * @param String $formula
     * @return array of related modules used in the formula
     */
    public static function getLinkedModulesFromFormula($focus, $formula)
    {
        global $dictionary, $beanList;
        $links = self::getLinkFieldsForModule($focus->module_name, $focus->object_name);
        $relMods = array();
        if (!empty($links))
        {
            foreach($links as $name => $def)
            {
                //Look through all calculated fields for uses of this link field
                if(preg_match('/\W\$' . $name . '\W/', $formula))
                {
                    $focus->load_relationship($name);
                    $relMod = $focus->$name->getRelatedModuleName();
                    if (!empty($beanList[$relMod]))
                        $relMods[$relMod] = $beanList[$relMod];
                }
            }
        }
        return $relMods;
    }


    /**
     * applyGlobalAccountRequirements
     *
     * This method ensures that the account_name relationships are set to always be required if the configuration file specifies
     * so.  For more information on this require_accounts parameter, please see the administrators guide or go to the
     * developers.sugarcrm.com website to find articles relating to the use of this field.
     *
     * @param Array $vardef The vardefs of the module to apply the account_name field requirement to
     * @return Array $vardef The vardefs of the module with the updated required setting based on the system configuration
     */
    static function applyGlobalAccountRequirements($vardef)
    {
        if (isset($GLOBALS['sugar_config']['require_accounts']))
        {
            if (isset($vardef['fields'])
                && isset($vardef['fields']['account_name'])
                && isset($vardef['fields']['account_name']['type'])
                && $vardef['fields']['account_name']['type'] == 'relate'
                && isset($vardef['fields']['account_name']['required']))
            {
                $vardef['fields']['account_name']['required'] = $GLOBALS['sugar_config']['require_accounts'];
            }

        }
        return $vardef;
    }


    /**
     * load the vardefs for a given module and object
     * @param string $module the given module we want to load the vardefs for
     * @param string $object the given object we wish to load the vardefs for
     * @param bool   $refresh whether or not we wish to refresh the cache file.
     */
    static function loadVardef($module, $object, $refresh=false, $params = array()){
        if ( empty($module) || empty($object) ) {
            return;
        }

        $GLOBALS['log']->debug("VardefManager::loadVardef called for module: $module");

        if (!$refresh && empty($params['ignore_rel_calc_fields']) &&
            !empty($GLOBALS['dictionary'][$object]) &&
            !isset($GLOBALS['dictionary'][$object]['related_calc_fields'])) {
            $refresh = true;
            $params['related_calc_fields_only'] = true;
        }

        // Some of the vardefs do not correctly define dictionary as global.  Declare it first.
        global $dictionary;

        include_once('modules/TableDictionary.php');

        if (empty($GLOBALS['dictionary'][$object]) || $refresh || !isset($GLOBALS['dictionary'][$object]['fields'])) {
            //if the consumer has demanded a refresh or the cache/modules... file
            //does not exist, then we should do out and try to reload things
            if (!$refresh) {
                self::loadFromCache($module, $object);
            }
            if (empty($GLOBALS['dictionary'][$object]) || $refresh || !isset($GLOBALS['dictionary'][$object]['fields'])) {
                VardefManager::refreshVardefs($module, $object, null, true, $params);
                if (!empty($GLOBALS['dictionary'][$object])) {
                    $GLOBALS['dictionary'][$object] = self::applyGlobalAccountRequirements($GLOBALS['dictionary'][$object]);
                }
            }
        }

    }


    protected static function loadFromCache($module, $object) {
        $sc = self::$sugarConfig ?: self::$sugarConfig = SugarConfig::getInstance();
        if ($sc->get('noFilesystemMetadataCache', false)) {
            $cache = self::$cache ?: self::$cache = new MetaDataCache(DBManagerFactory::getInstance());
            $GLOBALS['dictionary'][$object] = $cache->get(static::getCacheKey($module, $object));
        } else {
            $cachedfile = sugar_cached(self::getCacheFileName($module, $object));
            if (file_exists($cachedfile)) {
                include $cachedfile;
            }
        }
    }

    /**
     * Used to retrieve the field defs for a given module
     * (and optionally a specific object if a module has multiple)
     * @param string $module
     * @param bool $object
     *
     * @return null
     */
    public static function getFieldDefs($module, $object = false) {
        if (!$object) {
            $object = BeanFactory::getObjectName($module);
        }
        if (empty($object)) {
            return null;
        }
        if (!isset($GLOBALS['dictionary'][$object])) {
            static::loadVardef($module, $object);
        }
        if (isset($GLOBALS['dictionary'][$object]['fields'])) {
            return $GLOBALS['dictionary'][$object]['fields'];
        }
    }

    /**
     * Gets the cache file name
     * @param string $module the given module we want to load the vardefs for
     * @param string $object the given object we wish to load the vardefs for
     * @return string The filename for the vardef cache
     */
    static function getCacheFileName($module, $object){
        global $beanList, $beanFiles;
        if (isset($beanList[$module]) && isset($beanFiles[$beanList[$module]])) {
            $dirname = dirname($beanFiles[$beanList[$module]]);
            return $dirname . "/" . $object . 'vardefs.php';
        } else {
            return 'modules/' . $module . '/' . $object . 'vardefs.php';
        }
    }


    protected static function getCacheKey($module, $object) {
        return "vardefs::{$module}_{$object}";
    }

    /**
     * Does the specified module use the specified templates?
     *
     * @param string $module
     * @param string|array $templates
     * @return bool The module must use all of the specified templates to return true.
     */
    public static function usesTemplate($module, $templates)
    {
        if (!is_array($templates)) {
            $templates = array($templates);
        }

        $objectName = BeanFactory::getObjectName($module);

        if (empty($GLOBALS['dictionary'][$objectName])) {
            static::loadVardef($module, $objectName);
        }

        foreach ($templates as $template) {
            if (empty($GLOBALS['dictionary'][$objectName]['templates'][$template])) {
                return false;
            }
        }

        return true;
    }
}
