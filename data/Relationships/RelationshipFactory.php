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

// For clearing the metadata API cache

/**
 * Create relationship objects
 * @api
 */
class SugarRelationshipFactory {
    static $rfInstance;

    protected $relationships;

    protected function __construct(){
        //Load the relationship definitions from the cache.
        $this->loadRelationships();
    }

    /**
     * @static
     * @return SugarRelationshipFactory
     */
    public static function getInstance()
    {
        if (is_null(self::$rfInstance)) {
            self::$rfInstance = new SugarRelationshipFactory();
        }
        return self::$rfInstance;
    }

    public static function rebuildCache($modules = array())
    {
        $rf = self::getInstance();
        $rf->buildRelationshipCache($modules);
    }

    public static function deleteCache()
    {
        $file = self::getInstance()->getCacheFile();
        if(sugar_is_file($file)) {
            unlink($file);
        }
    }

    /**
     * Gets a relationship by name
     * 
     * @param  $relationshipName String name of relationship to load
     * @return SugarRelationship
     */
    public function getRelationship($relationshipName)
    {
        if (!$this->relationshipExists($relationshipName)) {
            $GLOBALS['log']->error("Unable to find relationship $relationshipName");
            return false;
        }

        $def = $this->relationships[$relationshipName];

        $class = $this->getCustomRelationshipClass($def, $relationshipName);
        if ($class) {
            return new $class($def);
        }

        $type = isset($def['true_relationship_type']) ? $def['true_relationship_type'] : $def['relationship_type'];
        switch($type) {
            case "many-to-many":
                if (isset($def['rhs_module']) && $def['rhs_module'] == 'EmailAddresses') {
                    return new EmailAddressRelationship($def);
                }
                
                return new M2MRelationship($def);
            break;
            case "user-based":
                return new UserBasedRelationship($def);
            break;
            case "one-to-many":
                //If a relationship has no table or join keys, it must be bean based
                if (empty($def['true_relationship_type']) || (empty($def['table']) && empty($def['join_table'])) || empty($def['join_key_rhs'])){
                    return new One2MBeanRelationship($def);
                } else {
                    return new One2MRelationship($def);
                }
                break;
            case "one-to-one":
                if (empty($def['true_relationship_type'])) {
                    return new One2OneBeanRelationship($def);
                } else {
                    return new One2OneRelationship($def);
                }
                break;
        }

        $GLOBALS['log']->fatal ("$relationshipName had an unknown type $type ");

        return false;
    }

    public function relationshipExists($relationshipName) {
        return !empty($this->relationships[$relationshipName]);
    }

    /**
     * Returns custom relationship class based on relationship definition, or NULL if it's not defined or incorrect
     *
     * @param array $def Relationship definition
     * @param string $name Relationship name
     *
     * @return string|null
     */
    protected function getCustomRelationshipClass(array $def, $name)
    {
        global $log;

        if (!isset($def['relationship_file'], $def['relationship_class'])) {
            return null;
        }

        if (!isset($def['relationship_file'])) {
            $log->fatal("Relationship file for $name is not specified");
            return null;
        }

        if (!isset($def['relationship_class'])) {
            $log->fatal("Relationship class for $name is not specified");
            return null;
        }

        if (!file_exists($def['relationship_file'])) {
            $log->fatal("Relationship file {$def['relationship_file']} does not exist");
            return null;
        }

        require_once $def['relationship_file'];
        if (!class_exists($def['relationship_class'])) {
            $log->fatal("Relationship class {$def['relationship_class']} does not exist");
            return null;
        }

        return $def['relationship_class'];
    }

    public function getRelationshipDef($relationshipName)
    {
        if (empty($this->relationships[$relationshipName])) {
            $GLOBALS['log']->error("Unable to find relationship $relationshipName");
            return false;
        }

        return $this->relationships[$relationshipName];
    }

    /**
     * This function returns an array of every relationship in the system from 
     * cache unless told to fetch fresh defs
     * 
     * @param boolean $fresh If true, will get data from the source instead of cache
     * @return array An array of relationships, indexed by the relationship name
     */
    public function getRelationshipDefs($fresh = false)
    {
        // The relationships are loaded in the constructor, so if there is a
        // request for fresh relationships, handle it
        if ($fresh) {
            $this->relationships = $this->getRelationshipData();
        }

        return $this->relationships;
    }

    protected function loadRelationships()
    {
        if(sugar_is_file($this->getCacheFile())) {
            include $this->getCacheFile();
            $this->relationships = $relationships;
        } else {
            $this->buildRelationshipCache();
        }
        //For now set the global relationships. These are deprecated but we need to keep them around for now.
        $GLOBALS['relationships'] = $this->relationships;
    }

    protected function buildRelationshipCache($modules = array())
    {
        global $beanList, $buildingRelCache;
        if ($buildingRelCache) {
            return;
        }

        $buildingRelCache = true;
        $relationships = $this->getRelationshipData($modules);

        // if there is a list of modules, it needs to merge it with the already loaded list
        // so it doesn't have a partial list of relationships
        if (!empty($modules) && is_array($this->relationships) && is_array($relationships)) {
            $relationships = array_merge($this->relationships, $relationships);
        }

        //Save it out
        sugar_mkdir(dirname($this->getCacheFile()), null, true);
        $out = "<?php \n \$relationships = " . var_export($relationships, true) . ";";
        sugar_file_put_contents_atomic($this->getCacheFile(), $out);

        // There are only certain times when the relationship cache needs to be 
        // refreshed...
        // 
        // If we have a cache, but no internal relationships yet, do NOT rebuild
        // the api cache. This is a first load of this class.
        //
        // If there is no cache, then do NOT rebuild the api cache since there is
        // nothing to rebuild.
        //
        // DO rebuild the cache if there is a cache, there is an internal relationships
        // property and it is different than $relationships
        $rebuildApiCache = false;
        $metaCacheKey = MetaDataManager::getManager()->getCachedMetadataHash(new MetaDataContextDefault());
        if (empty($metaCacheKey)) {
            $rebuildApiCache = !empty($this->relationships) && array_diff_key($this->relationships, $relationships) !== array();
        }

        $this->relationships = $relationships;

        if ($rebuildApiCache) {
            MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_RELATIONSHIPS));
        }

        // set the variable back to false, as we are now going to rebuild the vardefs since we have all the
        // relationships are loaded
        $buildingRelCache = false;

        //Now load all vardefs a second time populating the rel_calc_fields
        if (empty($modules)) {
            //Reload ALL the module vardefs....
            $modules = array_keys($beanList);
        }

        foreach ($modules as $moduleName) {
            // need to refresh the vardef so that the related calc fields are loaded
            VardefManager::loadVardef($moduleName, BeanFactory::getObjectName($moduleName), true);
        }
    }


    /**
     * Gets the relationship metadata data that is ultimately cached
     * @param array $modules list of modules to rebuild vardefs for before loading relationship data.
     *
     * @return array
     */
    protected function getRelationshipData($modules = array())
    {
        global $beanList, $dictionary;
        include("modules/TableDictionary.php");

        if (empty($beanList)) {
            include("include/modules.php");
        }

        if (empty($modules)) {
            //Reload ALL the module vardefs....
            $modules = array_keys($beanList);
        }

        foreach($modules as $moduleName)
        {
            VardefManager::loadVardef($moduleName, BeanFactory::getObjectName($moduleName), false, array(
                //If relationships are not yet loaded, we can't figure out the rel_calc_fields.
                "ignore_rel_calc_fields" => true,
            ));
        }

        $relationships = array();

        //Grab all the relationships from the dictionary.
        foreach ($dictionary as $key => $def) {
            if (!empty($def['relationships'])) {
                foreach($def['relationships'] as $relKey => $relDef) {
                    if ($key == $relKey) {
                        //Relationship only entry, we need to capture everything
                        $relationships[$key] = array_merge(array('name' => $key), $def, $relDef);
                    } else {
                        $relationships[$relKey] = array_merge(array('name' => $relKey), $relDef);
                        if (empty($relationships[$relKey]['fields'])) {
                            if (isset($relationships[$relKey]['table'])) {
                                $table = $relationships[$relKey]['table'];
                            } elseif (isset($relationships[$relKey]['join_table'])) {
                                $table = $relationships[$relKey]['join_table'];
                            } else {
                                $table = null;
                            }

                            if ($table) {
                                if ($table == 'team_memberships') {
                                    $tableKey = 'TeamMembership';
                                } else {
                                    $tableKey = $table;
                                }
                                if (isset($dictionary[$tableKey]['fields'])) {
                                    $relationships[$relKey]['fields'] = $dictionary[$tableKey]['fields'];
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $relationships;
    }

    /**
     * Gets the cache file name
     * 
     * @return string
     */
    protected function getCacheFile() {
        return sugar_cached("Relationships/relationships.cache.php");
    }

    public function getRelationshipsBetweenModules($mod1, $mod2, $type = "")
    {
        $result = array();
        foreach ($this->relationships as $name => $def) {
            if (isset($def['lhs_module'], $def['rhs_module']) && (
                    $def['lhs_module'] == $mod1 && $def['rhs_module'] == $mod2 ||
                    $def['lhs_module'] == $mod2 && $def['rhs_module'] == $mod1
                )
            ) {
                if (empty($type) || (isset($def['relationship_type']) && $def['relationship_type'] == $type)) {
                    $result[] = $name;
                }
            }
        }

        return $result;
    }

}
