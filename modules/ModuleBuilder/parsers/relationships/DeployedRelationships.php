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



class DeployedRelationships extends AbstractRelationships implements RelationshipsInterface
{


    protected static $subpanelDefs = array();

    function __construct ($moduleName, $relationshipName = "")
    {
        $this->moduleName = $moduleName ;
        $this->load ($relationshipName) ;
    }

    public static function findRelatableModules($includeActivitiesSubmodules = true)
    {
        return parent::findRelatableModules ( true ) ;
    }

    /*
     * Load the set of relationships for this module - the set is the combination of that held in the working file plus all of the relevant deployed relationships for the module
     * Note that deployed relationships are readonly and cannot be modified - getDeployedRelationships() takes care of marking them as such
     * This means that load() cannot be called for Activities, only Tasks, Notes, etc
     *
     * Note that we may need to adjust the cardinality for any custom relationships that we do not have entries for in the working directory
     * These relationships might have been loaded from an installation package by ModuleInstaller, or the custom/working directory might have been cleared at some point
     * The cardinality in the installed relationship is not necessarily correct for custom relationships, which currently are all built as many-to-many relationships
     * Instead we must obtain the true cardinality from a property we added to the relationship metadata when we created the relationship
     * This relationship metadata is accessed through the Table Dictionary
     */
    function load($relationshipName = "")
    {
        $relationships = $this->getDeployedRelationships();

        if (!empty ($relationships)) {
            // load the relationship definitions for all installed custom relationships into $dictionary
            $dictionary = array();
            if (file_exists('custom/application/Ext/TableDictionary/tabledictionary.ext.php')) {
                include('custom/application/Ext/TableDictionary/tabledictionary.ext.php');
            }

            $invalidModules = array();
            $invalidModules[] = 'Teams';
            $validModules = array_keys(self::findRelatableModules());

            //If a single relationship was passed, we can save time by loading only that relationship.
            if (!empty($relationshipName) && isset($relationships[$relationshipName])) {
                $relationships = array($relationshipName => $relationships[$relationshipName]);
            }
            // now convert the relationships array into an array of AbstractRelationship objects
            foreach ($relationships as $name => $definition) {
                if (($definition['lhs_module'] == $this->moduleName) || ($definition['rhs_module'] == $this->moduleName)) {
                    if (in_array($definition['lhs_module'], $validModules) && in_array($definition['rhs_module'], $validModules)
                        && !in_array($definition['lhs_module'], $invalidModules) && !in_array($definition['rhs_module'], $invalidModules)
                    ) {
                        // identify the subpanels for this relationship - TODO: optimize this - currently does m x n scans through the subpanel list...
                        $definition['rhs_subpanel'] = $this->identifySubpanel($definition['lhs_module'], $definition['rhs_module']);
                        $definition['lhs_subpanel'] = $this->identifySubpanel($definition['rhs_module'], $definition['lhs_module']);

                        // now adjust the cardinality with the true cardinality found in the relationships metadata (see method comment above)


                        if (!empty ($dictionary) && !empty ($dictionary[$name])) {
                            if (!empty ($dictionary[$name]['true_relationship_type'])) {
                                $definition['relationship_type'] = $dictionary[$name]['true_relationship_type'];
                            }
                            if (!empty ($dictionary[$name]['from_studio'])) {
                                $definition['from_studio'] = $dictionary[$name]['from_studio'];
                            }
                            $definition['is_custom'] = true;
                        }


                        $this->relationships[$name] = RelationshipFactory::newRelationship($definition);
                    }
                }
            }

        }
    }

    /*
     * Save this modules relationship definitions out to a working file
     */
    function save ($rebuildMetadata = true)
    {
        parent::_save ( $this->relationships, "custom/working/modules/{$this->moduleName}" ) ;

        // Rebuild the relationship portion of the metadata cache if requested
        if ($rebuildMetadata) {
            MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_RELATIONSHIPS));
        }
    }

    /*
     * Update pre-5.1 relationships to the 5.1 relationship definition
     * There is nothing to do for Deployed relationships as these were only introduced in 5.1
     * @param array definition  The 5.0 relationship definition
     * @return array            The definition updated to 5.1 format
     */
    protected function _updateRelationshipDefinition ($definition)
    {
        return $definition ;
    }

    /*
     * Use the module Loader to delete the relationship from the instance.
     */
    function delete ($rel_name)
    {
        $this->newRelationshipName = $rel_name;

    	//Remove any fields from layouts
        $rel = $this->get($rel_name);
        if (!empty($rel))
        {
            $this->removeFieldsFromDeployedLayout($rel);
        }
        SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        $mi = new $moduleInstallerClass();
    	$mi->silent = true;
    	$mi->uninstall_relationship("custom/metadata/{$rel_name}MetaData.php");

    	// now clear all caches so that our changes are visible
        $mi->rebuild_tabledictionary();
        SugarRelationshipFactory::rebuildCache();


        $MBmodStrings = $GLOBALS [ 'mod_strings' ];
        $GLOBALS['reload_vardefs'] = true;
        $GLOBALS [ 'mod_strings' ] = return_module_language ( '', 'Administration' ) ;
        $rac = new RepairAndClear ( ) ;
        $rac->repairAndClearAll ( array ( 'clearAll', 'rebuildExtensions',  ), array ( $GLOBALS [ 'mod_strings' ] [ 'LBL_ALL_MODULES' ] ), true, false, '' ) ;
        $GLOBALS [ 'mod_strings' ] = $MBmodStrings;

        //Bug 41070, supercedes the previous 40941 fix in this section
        if (isset($this->relationships[$rel_name]))
        {
            unset($this->relationships[$rel_name]);
        }
    }

    /*
     * Return the set of all known relevant relationships for a deployed module
     * The set is made up of the relationships held in this class, plus all those already deployed in the application
     * @return array Set of all relevant relationships
     */
    protected function getAllRelationships ()
    {
        return array_merge ( $this->relationships, parent::getDeployedRelationships () ) ;
    }

    /*
     * Return the name of the first (currently only) subpanel displayed in the DetailView of $thisModuleName provided by $sourceModuleName
     * We can assume that both sides of the relationship are deployed modules as this is only called within the context of DeployedRelationships
     * @param string $thisModuleName    Name of the related module
     * @param string $sourceModuleName  Name of the primary module
     * @return string Name of the subpanel if found; null otherwise
     */
    protected function identifySubpanel($thisModuleName, $sourceModuleName)
    {
        $bean = BeanFactory::newBean($thisModuleName);
        if (empty($bean)) {
            return null;
        }
        foreach (static::loadSubpanelDefs($bean) as $name => $def) {
            if (isset($def['module'], $def['subpanel_name']) && $def['module'] == $sourceModuleName) {
                return $def['subpanel_name'];
            }
        }

        return null;

    }

    static protected function loadSubpanelDefs($bean) {
        $module = $bean->module_dir;
        if (!isset(static::$subpanelDefs[$module])) {
            static::$subpanelDefs[$module] = array();
            $spd = new SubPanelDefinitions ($bean);
            static::$subpanelDefs[$module] = isset($spd->layout_defs['subpanel_setup'])
                ? $spd->layout_defs['subpanel_setup']
                : array();
        }
        return static::$subpanelDefs[$module];
    }

    /*
     * Return the name of the first (currently only) relate field of $thisModuleName sourced from by $sourceModuleName
     * We can assume that both sides of the relationship are deployed modules as this is only called within the context of DeployedRelationships
     * @param string $thisModuleName    Name of the related module
     * @param string $sourceModuleName  Name of the primary module
     * @return string Name of the relate field, if found; null otherwise
     */

    static private function identifyRelateField ($thisModuleName , $sourceModuleName)
    {
        $module = BeanFactory::newBean($thisModuleName);
        if(empty($module)) {
            return null;
        }
        foreach ( $module->field_defs as $field )
        {
            if ($field [ 'type' ] == 'relate' && isset ( $field [ 'module' ] ) && $field [ 'module' ] == $sourceModuleName)
                return $field [ 'name' ] ;
        }
        return null ;
    }

    /*
     * As of SugarCRM 5.1 the subpanel code and the widgets have difficulty handling multiple subpanels or relate fields from the same module
     * Until this is fixed, we new relationships which will trigger this problem must be flagged as "relationship_only" and built without a UI component
     * This function is called from the view when constructing a new relationship
     * We can assume that both sides of the relationship are deployed modules as this is only called within the context of DeployedRelationships
     * @param AbstractRelationship $relationship The relationship to be enforced
     */
    public function enforceRelationshipOnly ($relationship)
    {
        $lhs = $relationship->lhs_module ;
        $rhs = $relationship->rhs_module ;
        // if the lhs_module already has a subpanel or relate field sourced from the rhs_module,
    // or the rhs_module already has a subpanel or relate field sourced from the lhs_module,
    // then set "relationship_only" in the relationship


    //        if (($relationship->getType() != MB_ONETOONE && ! is_null ( self::identifySubpanel ( $lhs, $rhs ) )) || ($relationship->getType() == MB_MANYTOMANY && ! is_null ( self::identifySubpanel ( $rhs, $lhs ) )) || ($relationship->getType() == MB_ONETOONE && ! is_null ( self::identifyRelateField ( $rhs, $lhs ) )) || ($relationship->getType() != MB_MANYTOMANY && ! is_null ( self::identifyRelateField ( $lhs, $rhs ) )))
    //            $relationship->setRelationship_only () ;
    }

    /*
     * BUILD FUNCTIONS
     */

    /*
     * Implement all of the Relationships in this set of relationships
     * This is more general than it needs to be given that deployed relationships are built immediately - there should only be one relationship to build here...
     * We use the Extension mechanism to do this for DeployedRelationships
     * All metadata is placed in the modules Ext directory, and then Rebuild is called to activate them
     */
    public function build($basepath = null, $installDefPrefix = null, $relationships = null)
    {
        $modulesToBuild = array();
        if (!isset($this->relationships[$this->newRelationshipName])) {
            $GLOBALS['log']->fatal("Could not find a relationship by the name of {$this->newRelationshipName}, you will have to quick repair and rebuild to get the new relationship added.");
        } else {
            $newRel = $this->relationships[$this->newRelationshipName];
            $newRelDef = $newRel->getDefinition();
            $modulesToBuild[$newRelDef['rhs_module']] = true;
            $modulesToBuild[$newRelDef['lhs_module']] = true;
        }

        $basepath = "custom/Extension/modules" ;

        $this->activitiesToAdd = false ;

        // and mark all as built so that the next time we come through we'll know and won't build again
        foreach ( $this->relationships as $name => $relationship )
        {
            if ($relationship->readonly() ) {
                continue;
            }

            $definition = $relationship->getDefinition () ;
            // activities will always appear on the rhs only - lhs will be always be this module in MB
            if (strtolower ( $definition [ 'rhs_module' ] ) == 'activities')
            {
                $this->activitiesToAdd = true ;
                $relationshipName = $definition [ 'relationship_name' ] ;
                foreach ( self::$activities as $activitiesSubModuleLower => $activitiesSubModuleName )
                {
                    $definition [ 'rhs_module' ] = $activitiesSubModuleName ;
                    $definition [ 'for_activities' ] = true ;
                    $definition [ 'relationship_name' ] = $relationshipName . '_' . $activitiesSubModuleLower ;
                    $this->relationships [ $definition [ 'relationship_name' ] ] = RelationshipFactory::newRelationship ( $definition ) ;
                }
                unset ( $this->relationships [ $name ] ) ;
            }
        }

        $GLOBALS [ 'log' ]->info ( get_class ( $this ) . "->build(): installing relationships" ) ;

        $MBModStrings = $GLOBALS [ 'mod_strings' ] ;
        $adminModStrings = return_module_language ( '', 'Administration' ) ; // required by ModuleInstaller

        foreach ( $this->relationships as $name => $relationship )
        {
            if ($relationship->readonly() ) {
                continue;
            }

            $relationship->setFromStudio();
        	$GLOBALS [ 'mod_strings' ] = $MBModStrings ;
            $installDefs = parent::build ( $basepath, "<basepath>",  array ($name => $relationship ) ) ;

            // and mark as built so that the next time we come through we'll know and won't build again
            $relationship->setReadonly () ;
            $this->relationships [ $name ] = $relationship ;

            // now install the relationship - ModuleInstaller normally only does this as part of a package load where it installs the relationships defined in the manifest. However, we don't have a manifest or a package, so...

            // If we were to chose to just use the Extension mechanism, without using the ModuleInstaller install_...() methods, we must :
            // 1)   place the information for each side of the relationship in the appropriate Ext directory for the module, which means specific $this->save...() methods for DeployedRelationships, and
            // 2)   we must also manually add the relationship into the custom/application/Ext/TableDictionary/tabledictionary.ext.php file as install_relationship doesn't handle that (install_relationships which requires the manifest however does)
            //      Relationships must be in tabledictionary.ext.php for the Admin command Rebuild Relationships to reliably work:
            //      Rebuild Relationships looks for relationships in the modules vardefs.php, in custom/modules/<modulename>/Ext/vardefs/vardefs.ext.php, and in modules/TableDictionary.php and custom/application/Ext/TableDictionary/tabledictionary.ext.php
            //      if the relationship is not defined in one of those four places it could be deleted during a rebuilt, or during a module installation (when RebuildRelationships.php deletes all entries in the Relationships table)
            // So instead of doing this, we use common save...() methods between DeployedRelationships and UndeployedRelationships that will produce installDefs,
            // and rather than building a full manifest file to carry them, we manually add these installDefs to the ModuleInstaller, and then
            // individually call the appropriate ModuleInstaller->install_...() methods to take our relationship out of our staging area and expand it out to the individual module Ext areas

            $GLOBALS [ 'mod_strings' ] = $adminModStrings ;
            SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
            $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
            $mi = new $moduleInstallerClass();

            $mi->id_name = 'custom' . $name ; // provide the moduleinstaller with a unique name for this relationship - normally this value is set to the package key...
            $mi->installdefs = $installDefs ;
            $mi->base_dir = $basepath ;
            $mi->silent = true ;


            VardefManager::clearVardef () ;

            $mi->install_relationships () ;
            $mi->install_languages () ;
            $mi->install_vardefs () ;
            $mi->install_layoutdefs () ;
            $mi->install_extensions();
            $mi->install_client_files();

        }

        $GLOBALS [ 'mod_strings' ] = $MBModStrings ; // finally, restore the ModuleBuilder mod_strings

        // Anything that runs in-process needs to reload their vardefs
        $GLOBALS['reload_vardefs'] = true;

        // save out the updated definitions so that we keep track of the change in built status
        // sending false so we don't rebuild relationshsips for a third time.
        $this->save(false);

        // now clear all caches so that our changes are visible
        $rac = new RepairAndClear ( ) ;
        $rac->module_list = $modulesToBuild;
        $rac->clearJsFiles();
        $rac->clearVardefs();
        $rac->clearJsLangFiles();
        $rac->clearLanguageCache();
        $rac->rebuildExtensions(array_keys($modulesToBuild));

        foreach (array_keys($modulesToBuild) as $module) {
            unset($GLOBALS['dictionary'][BeanFactory::getObjectName($module)]);
        }
        MetaDataManager::refreshLanguagesCache(array($GLOBALS['current_language']));
        MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_RELATIONSHIPS));
        MetaDataManager::refreshModulesCache(array_keys($modulesToBuild));

        $GLOBALS [ 'log' ]->info ( get_class ( $this ) . "->build(): finished relationship installation" ) ;

    }

    /*
     * Add any fields to the DetailView and EditView of the appropriate modules
     * @param string $basepath              Basepath location for this module (not used)
     * @param string $relationshipName      Name of this relationship (for uniqueness)
     * @param array $layoutAdditions  An array of module => fieldname
     * return null
     */
    protected function saveFieldsToLayouts ($basepath , $dummy , $relationshipName , $layoutAdditions)
    {
        // these modules either lack editviews/detailviews or use custom mechanisms for the editview/detailview. In either case, we don't want to attempt to add a relate field to them
        // would be better if GridLayoutMetaDataParser could handle this gracefully, so we don't have to maintain this list here
        $invalidModules = array('emails') ;

        foreach ( $layoutAdditions as $deployedModuleName => $fieldName )
        {
            if (! in_array ( strtolower ( $deployedModuleName ), $invalidModules )) {
                // Handle decision making on views for BWC/non-BWC modules
                if (isModuleBWC($deployedModuleName)) {
                    $views = array(MB_EDITVIEW, MB_DETAILVIEW);
                } else {
                    $views = array(MB_RECORDVIEW);
                }
                
                foreach ($views as $view) {
                    $GLOBALS [ 'log' ]->info ( get_class ( $this ) . ": adding $fieldName to $view layout for module $deployedModuleName" ) ;
                    $parser = ParserFactory::getParser($view, $deployedModuleName);
                    $parser->addField ( array ( 'name' => $fieldName ) ) ;
                    $parser->handleSave ( false ) ;
                }
            }
        }
    }

    /**
     * Added for bug #40941
     * Deletes the field from DetailView and editView of the appropriate module
     * after the relatioship is deleted in delete() function above.
     * @param $relationship    The relationship that is getting deleted
     * return null
     */
	private function removeFieldsFromDeployedLayout ($relationship)
    {

        // many-to-many relationships don't have fields so if we have a many-to-many we can just skip this...
        if ($relationship->getType () == MB_MANYTOMANY)
            return false ;

        $successful = true ;
        $layoutAdditions = $relationship->buildFieldsToLayouts () ;

        foreach ( $layoutAdditions as $deployedModuleName => $fieldName )
        {
            // Handle decision making on views for BWC/non-BWC modules
            if (isModuleBWC($deployedModuleName)) {
                $views = array(MB_EDITVIEW, MB_DETAILVIEW);
            } else {
                $views = array(MB_RECORDVIEW);
            }
            
            foreach($views as $view) {
                $parser = ParserFactory::getParser($view, $deployedModuleName);
                $parser->removeField ( $fieldName );
                $parser->handleSave ( false ) ;

            }
        }

        return $successful ;
    }

}

?>
