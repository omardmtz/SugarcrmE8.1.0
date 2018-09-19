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

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;

require_once 'modules/DynamicFields/FieldCases.php';

class ModuleBuilderController extends SugarController
{

    public $action_remap = array();

    /**
     * Flag used in the metadata api cache clearing method to prevent duplication
     * of the metadata cache clear call
     *
     * @var bool
     */
    public $metadataApiCacheCleared = false;

    /**
     * Handles processing before the execute process
     */
    public function preProcess()
    {
        // Turn on the metadata manager queue but turn off the queue runner until
        // after all processing is done
        MetaDataManager::enableCacheRefreshQueue();
        MetaDataManager::setRunQueueOnCallOff();
    }

    /**
     * For ModuleBuilder postProcess handles running the metadata cache queue
     */
    public function postProcess()
    {
        // Turns the queue runner back on and calls it to let it do its thing
        MetaDataManager::setRunQueueOnCallOn();
        MetaDataManager::runCacheRefreshQueue();
    }

    /**
     * Used by the _getModuleTitleParams() method calls in ModuleBuilder views to get the correct string
     * for the section you are in
     *
     * @return string
     */
    public static function getModuleTitle()
    {
        global $mod_strings;

        if (!empty($_REQUEST['type'])) {
            if ($_REQUEST['type'] == 'studio') {
                return $mod_strings['LBL_STUDIO'];
            } elseif ($_REQUEST['type'] == 'sugarportal') {
                return $mod_strings['LBL_SUGARPORTAL'];
            } elseif ($_REQUEST['type'] == 'mb') {
                return $mod_strings['LBL_MODULEBUILDER'];
            } elseif ($_REQUEST['type'] == 'dropdowns') {
                return $mod_strings['LBL_DROPDOWNEDITOR'];
            } elseif ($_REQUEST['type'] == 'home') {
                return $mod_strings['LBL_HOME'];
            } else {
                return $mod_strings['LBL_DEVELOPER_TOOLS'];
            }
        } else {
            return $mod_strings['LBL_DEVELOPER_TOOLS'];
        }
    }

    public function fromModuleBuilder()
    {
        return (isset ($_REQUEST ['MB']) && ($_REQUEST ['MB'] == '1'));
    }

    public function process()
    {
        $GLOBALS ['log']->info(get_class($this) . ":");
        global $current_user;

        // Handle BC for studio help
        $this->normalizeModStrings();

        $access = $current_user->getDeveloperModules();
            if ($current_user->isAdmin() || ($current_user->isDeveloperForAnyModule() && !isset($_REQUEST['view_module']) && (isset($_REQUEST['action']) && $_REQUEST['action'] != 'package')) ||
                (isset($_REQUEST['view_module']) && (in_array($_REQUEST['view_module'], $access) || empty($_REQUEST['view_module']))) ||
                (isset($_REQUEST['type']) && (($_REQUEST['type'] == 'dropdowns' && $current_user->isDeveloperForAnyModule()) ||
                    ($_REQUEST['type'] == 'studio' && displayStudioForCurrentUser() == true))) ||
                (isset($_REQUEST['entryPoint']) && $_REQUEST['entryPoint'] == 'jslang' && $current_user->isDeveloperForAnyModule())
            ) {
                $this->hasAccess = true;
            } else {
                $this->hasAccess = false;
            }
        parent::process();
    }

    public function action_editLayout()
    {
        $view = strtolower($_REQUEST ['view']);
        $found = false;
        //Check the StudioModule first for mapping overrides
        if (empty($_REQUEST ['view_package']) || $_REQUEST ['view_package'] == "studio") {
            $sm = StudioModuleFactory::getStudioModule($_REQUEST ['view_module']);
            foreach ($sm->sources as $file => $def) {
                if (!empty($def['type']) && !empty($def['view']) && $def['view'] == $view) {
                    $view = $def['type'];
                }
            }
        }
        if (!$found) {
            switch ($view) {
                case MB_EDITVIEW :
                case MB_DETAILVIEW :
                case MB_QUICKCREATE :
                case MB_RECORDVIEW :
                case MB_WIRELESSEDITVIEW :
                case MB_WIRELESSDETAILVIEW :
                    $this->view = 'layoutView';
                    break;
                case MB_LISTVIEW :
                case MB_WIRELESSLISTVIEW :
                    $this->view = 'listView';
                    break;
                case MB_BASICSEARCH :
                case MB_ADVANCEDSEARCH :
                case MB_WIRELESSBASICSEARCH :
                case MB_WIRELESSADVANCEDSEARCH :
                    $this->view = 'searchView';
                    break;
                case MB_DASHLET :
                case MB_DASHLETSEARCH :
                    $this->view = 'dashlet';
                    break;
                case MB_POPUPLIST :
                case MB_POPUPSEARCH :
                case MB_SIDECARPOPUPVIEW :
                    $this->view = 'popupview';
                    break;
                default :
                    $GLOBALS ['log']->fatal('Action = editLayout with unknown view=' . $_REQUEST ['view']);
            }
        }

    }

    public function action_ViewTree()
    {
        switch ($_REQUEST ['tree']) {
            case 'ModuleBuilder' :
                $mbt = new MBPackageTree ();
                break;
            case 'Studio' :
                $mbt = new StudioTree ();
        }
        $ajax = new AjaxCompose ();
        $ajax->addSection('west', $mbt->getName(), $mbt->fetchNodes());
        echo $ajax->getJavascript();

        sugar_cleanup(true);

    }

    public function action_SavePackage()
    {
        $originalName = $this->request->getValidInputRequest('original_name', 'Assert\ComponentName');
        $name = $this->request->getValidInputRequest('name', 'Assert\ComponentName');

        $mb = new ModuleBuilder ();
        $load = $originalName ? $originalName : $name;
        if (!empty ($load)) {
            $mb->getPackage($load);

            if (!empty ($_REQUEST ['duplicate'])) {
                $result = $mb->packages [$load]->copy($name);
                $load = $mb->packages [$load]->name;
                $mb->getPackage($load);
            }
            $mb->packages [$load]->populateFromPost();
            $mb->packages [$load]->loadModules();
            $mb->save();
            if ($originalName && $originalName != $name) {
                if (!$mb->packages [$load]->rename($name)) {
                    $mb->packages [$load]->name = $originalName;
                    $name = $originalName;
                }
            }
            $_REQUEST ['package'] = $name;
            $this->view = 'package';
        }
    }

    public function action_BuildPackage()
    {
        $mb = new ModuleBuilder ();
        $load = $this->request->getValidInputRequest('name', 'Assert\ComponentName');
        if (!empty ($load)) {
            $mb->getPackage($load);
            $mb->packages [$load]->build();
        }
    }

    public function action_DeployPackage()
    {
        global $current_user;

        if (defined('TEMPLATE_URL')) {
            sugar_cache_reset();
            SugarTemplateUtilities::disableCache();
        }

        //increment etag for menu so the new module shows up when the AJAX UI reloads
        $current_user->incrementETag("mainMenuETag");

        $mb = new ModuleBuilder ();
        $load = $this->request->getValidInputRequest('package', 'Assert\ComponentName');
        $message = $GLOBALS ['mod_strings'] ['LBL_MODULE_DEPLOYED'];
        if (!empty ($load)) {
            // there may be temp files left over for unknown reason
            $this->removeTempFiles($load);
            $zip = $mb->getPackage($load);
            $pm = new PackageManager ();
            $info = $mb->packages [$load]->build(false);
            $uploadDir = $pm->upload_dir . '/upgrades/module/';
            mkdir_recursive($uploadDir);
            rename($info ['zip'], $uploadDir . $info ['name'] . '.zip');
            copy($info ['manifest'], $uploadDir . $info ['name'] . '-manifest.php');
            $installFile = $uploadDir . $info ['name'] . '.zip';

            // FIXME: This *may* be needed somewhere else, leaving it for now
            $_REQUEST ['install_file'] = $installFile;

            $GLOBALS ['mi_remove_tables'] = false;
            $pm->performUninstall($load);
            //#23177 , js cache clear
            clearAllJsAndJsLangFilesWithoutOutput();
            //#30747, clear the cache in memory
            $cache_key = 'app_list_strings.' . $GLOBALS['current_language'];
            sugar_cache_clear($cache_key);
            sugar_cache_reset();
            //clear end
            $pm->performInstall($installFile, true);

            //clear the unified_search_module.php file
            UnifiedSearchAdvanced::unlinkUnifiedSearchModulesFile();

            //bug 44269 - start

            //clear workflow admin modules cache
            if (isset($_SESSION['get_workflow_admin_modules_for_user'])) unset($_SESSION['get_workflow_admin_modules_for_user']);

            //clear "is_admin_for_module" cache
            $sessionVar = 'MLA_' . $current_user->user_name;
            foreach ($mb->packages as $package) {
                foreach ($package->modules as $module) {
                    $_SESSION[$sessionVar][$package->name . '_' . $module->name] = true;
                }
            }

            //recreate acl cache
            $actions = ACLAction::getUserActions($current_user->id, true);
            //bug 44269 - end

            //add the mapping to Elastic for the modules in this package
            //Note that this works for newly created custom modules only.
            //The existing modules will need deletion of mappings and data first,
            //before rebuilding of the mappings.
            if (isset($zip)) {
                $modules = array();
                foreach ($zip->modules as $module) {
                    $modules[] = $module->key_name;
                }

                $engine = SearchEngine::getInstance()->getEngine();
                if (isset($engine)) {
                    $engine->addMappings($modules);
                }
            }
        }

        echo 'complete';
    }

    public function action_ExportPackage()
    {
        $mb = new ModuleBuilder ();
        $load = $this->request->getValidInputRequest('name', 'Assert\ComponentName');
        $author = $_REQUEST ['author'];
        $description = $_REQUEST ['description'];
        $readme = $_REQUEST ['readme'];
        if (!empty ($load)) {
            $mb->getPackage($load);
            $mb->packages [$load]->author = $author;
            $mb->packages [$load]->description = $description;
            $mb->packages [$load]->exportProject();
            $mb->packages [$load]->readme = $readme;
        }
    }

    public function action_DeletePackage()
    {
        $package = $this->request->getValidInputRequest('package', 'Assert\ComponentName');

        $mb = new ModuleBuilder ();
        $mb->getPackage($package)->delete();
        $this->view = 'deletepackage';
    }

    public function action_SaveModule()
    {
        $originalName = $this->request->getValidInputRequest('original_name', 'Assert\ComponentName');
        $name = $this->request->getValidInputRequest('name', 'Assert\ComponentName');
        $package = $this->request->getValidInputRequest('package', 'Assert\ComponentName');

        $mb = new ModuleBuilder ();
        $load = $originalName ? $originalName : $name;
        if (!empty ($load)) {
            $mb->getPackage($package);
            $mb->packages [$package]->getModule($load);
            $module = & $mb->packages [$package]->modules [$load];
            $module->populateFromPost($this->request);
            $mb->save();
            if (!empty ($_REQUEST ['duplicate'])) {
                $module->copy($name);
            } elseif (!empty ($originalName) && $originalName != $name) {
                if (!$module->rename($name)) {
                    $module->name = $originalName;
                    $_REQUEST ['name'] = $originalName;
                }
            }
            $_REQUEST ['view_package'] = $package;
            $_REQUEST ['view_module'] = $module->name;
            $this->view = 'module';
        }
    }

    public function action_DeleteModule()
    {
        $package = $this->request->getValidInputRequest('package', 'Assert\ComponentName');
        $viewModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        $mb = new ModuleBuilder ();
        $module = & $mb->getPackageModule($package, $viewModule);
        $module->delete();
        $this->view = 'package';
    }

    public function action_saveLabels()
    {
        $viewPackage = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $viewModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        $parser = new ParserLabel($viewModule, $viewPackage);
        $parser->handleSave($_REQUEST, $_REQUEST ['selected_lang']);

        // Clear the language cache to make sure the view picks up the latest
        $cache_key = LanguageManager::getLanguageCacheKey($viewModule, $_REQUEST['selected_lang']);
        sugar_cache_clear($cache_key);
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_LABELS);
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_ORDEREDLABELS);

        if ($viewPackage) { //MODULE BUILDER
            $this->view = 'modulelabels';
        } else { //STUDIO
            $this->view = isset ($_REQUEST ['view']) ? 'edit' : 'labels'; // detect if we are being called by the LayoutEditor rather than the LabelEditor (set in view.layoutlabel.php)
        }
    }

    public function action_SaveLabel()
    {
        $viewModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        if ($viewModule && !empty($_REQUEST ['labelValue'])) {
            $_REQUEST ["label_" . $_REQUEST ['label']] = $_REQUEST ['labelValue'];

            // Since the following loop will change aspects of the $_REQUEST 
            // array read it into a copy to preserve state on $_REQUEST
            $req = $_REQUEST;
            $packageName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
            foreach (ModuleBuilder::getModuleAliases($viewModule) as $key)
            {
                $req['view_module'] = $key;
                $parser = new ParserLabel($req['view_module'], $packageName);
                $parser->handleSave($req, $GLOBALS['current_language']);
                
                // Clear the language cache to make sure the view picks up the latest
                $cache_key = LanguageManager::getLanguageCacheKey($req['view_module'], $GLOBALS['current_language']);
                sugar_cache_clear($cache_key);

            }
            MetaDataManager::refreshSectionCache(MetaDataManager::MM_LABELS);
            MetaDataManager::refreshSectionCache(MetaDataManager::MM_ORDEREDLABELS);
        }
        $this->view = 'modulefields';
    }

    public function action_ExportCustom()
    {
        $modules = $this->request->getValidInputRequest('modules', array('Assert\All' => array(
            'constraints' => 'Assert\ComponentName',
        )));

        $name = $this->request->getValidInputRequest('name',  array('Assert\Regex' =>
            array('pattern' => '/^[a-z][a-z0-9_-]*$/i')
        ));

        $author = $_REQUEST ['author'];
        $description = $_REQUEST ['description'];
        ob_clean();
        if (!empty ($modules) && !empty ($name)) {

            $mb = new MBPackage ($name);
            $mb->author = $author;
            $mb->description = $description;
            $mb->exportCustom($modules, true, true);
        }
    }

    public function action_SaveField()
    {
        $type = $this->request->getValidInputRequest('type');

        $field = get_widget($type);
        $field->populateFromPost($this->request);

        $packageName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $viewModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        if (!$packageName) {
            if ($viewModule) {
                $module = $viewModule;
                if ($module == 'Employees') {
                    $module = 'Users';
                }

                $bean = BeanFactory::newBean($module);
                if (!empty($bean)) {
                    $field_defs = $bean->field_defs;
                    if (isset($field_defs[$field->name . '_c'])) {
                        $GLOBALS['log']->error($GLOBALS['mod_strings']['ERROR_ALREADY_EXISTS'] . '[' . $field->name . ']');
                        sugar_die($GLOBALS['mod_strings']['ERROR_ALREADY_EXISTS']);
                    }
                }

                $df = new DynamicField ($module);
                $mod = BeanFactory::newBean($module);
                $obj = BeanFactory::getObjectName($module);
                $df->setup($mod);

                $field->save($df);
                $this->action_SaveLabel();
                include_once 'modules/Administration/QuickRepairAndRebuild.php';
                global $mod_strings;
                $mod_strings['LBL_ALL_MODULES'] = 'all_modules';
                $repair = new RepairAndClear();

                // Set up an array for repairing modules
                $repairModules = array($module);
                if ($module === 'Users') {
                    $repairModules[] = 'Employees';
                }
                $repair->repairAndClearAll(array('rebuildExtensions', 'clearVardefs', 'clearTpls', 'clearSearchCache'), $repairModules, true, false);
                //Ensure the vardefs are up to date for this module before we rebuild the cache now.
                VardefManager::loadVardef($module, $obj, true);

                //Make sure to clear the vardef for related modules as well.
                $relatedMods = array();
                if (!empty($field->dependency)) {
                    $relatedMods = array_merge($relatedMods, VardefManager::getLinkedModulesFromFormula($bean, $field->dependency));
                }
                if (!empty($field->formula)) {
                    $relatedMods = array_merge($relatedMods, VardefManager::getLinkedModulesFromFormula($bean, $field->formula));
                }

                // But only if there are related modules to work on, otherwise 
                // we end up handling these processes for ALL THE MODULES
                if ($relatedMods) {
                    $repair->repairAndClearAll(array('clearVardefs', 'clearTpls', 'rebuildExtensions'), array_values($relatedMods), true, false);

                    foreach ($relatedMods as $mName => $oName) {
                        VardefManager::loadVardef($mName, $oName, true);
                    }
                }
                //#28707 ,clear all the js files in cache
                $repair->module_list = array();
                $repair->clearJsFiles();

                // Clear the metadata cache for labels and the requested module
                $repair->module_list = array($module);
                $repair->repairMetadataAPICache(MetaDataManager::MM_LABELS);
                $repair->repairMetadataAPICache(MetaDataManager::MM_ORDEREDLABELS);
            }
        } else {
            $mb = new ModuleBuilder ();
            $module = & $mb->getPackageModule($packageName, $viewModule);
            $field->save($module);
            $module->mbvardefs->save();
            // get the module again to refresh the labels we might have saved with the $field->save (e.g., for address fields)
            $module = & $mb->getPackageModule($packageName, $viewModule);
            if (isset ($_REQUEST ['label']) && isset ($_REQUEST ['labelValue']))
                $module->setLabel($GLOBALS ['current_language'], $_REQUEST ['label'], $_REQUEST ['labelValue']);
            $module->save();
        }
        $this->view = 'modulefields';
        LanguageManager::invalidateJsLanguageCache();
    }

    public function action_saveSugarField()
    {
        global $mod_strings;

        $type = $this->request->getValidInputRequest('type');
        $field = get_widget($type);
        $_REQUEST ['name'] = trim($_POST ['name']);

        $field->populateFromPost();

        $module = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        $df = new StandardField ($module);
        $mod = BeanFactory::newBean($module);
        $obj = BeanFactory::getObjectName($module);
        $df->setup($mod);

        $field->module = $mod;
        $field->save($df);
        $this->action_SaveLabel();

        $MBmodStrings = $mod_strings;
        $GLOBALS ['mod_strings'] = return_module_language('', 'Administration');

        include_once 'modules/Administration/QuickRepairAndRebuild.php';
        $GLOBALS ['mod_strings']['LBL_ALL_MODULES'] = 'all_modules';
        $_REQUEST['execute_sql'] = true;

        SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        $mi = new $moduleInstallerClass();
        $mi->silent = true;
        $mi->rebuild_extensions();

        $repair = new RepairAndClear();
        $repair->repairAndClearAll(array('clearVardefs', 'clearTpls', 'clearSearchCache'), array($module), true, false);
        //#28707 ,clear all the js files in cache
        $repair->module_list = array();
        $repair->clearJsFiles();

        //Ensure the vardefs are up to date for this module before we rebuild the cache now.
        VardefManager::loadVardef($module, $obj, true);
        //Make sure to clear the vardef for related modules as well
        $relatedMods = array();
        if (!empty($field->dependency))
            $relatedMods = array_merge($relatedMods, VardefManager::getLinkedModulesFromFormula($mod, $field->dependency));
        if (!empty($field->formula))
            $relatedMods = array_merge($relatedMods, VardefManager::getLinkedModulesFromFormula($mod, $field->formula));
        foreach ($relatedMods as $mName => $oName) {
            $repair->repairAndClearAll(array('clearVardefs', 'clearTpls'), array($mName), true, false);
            VardefManager::clearVardef($mName, $oName);
        }

        // now clear the cache so that the results are immediately visible
        TemplateHandler::clearCache($module);
        if ($module == 'Users') {
            TemplateHandler::clearCache('Employees');
        }

        // Bug 59210
        // Clear the metadata cache so this change can be reflected
        // immediately.
        $repair->module_list = array($module);

        // Sending false will only rebuild the $module section of the cache
        $repair->repairMetadataAPICache(false);

        $GLOBALS ['mod_strings'] = $MBmodStrings;
    }

    public function action_RefreshField()
    {
        $field = get_widget($_POST ['type']);
        $field->populateFromPost();
        $this->view = 'modulefield';
    }

    public function action_refreshDropDown()
    {
        require_once 'modules/DynamicFields/templates/Fields/Forms/enum2.php';
        $dropdowns = enum_get_lists();
        $dropdowns = array_keys($dropdowns);
        echo json_encode($dropdowns);
    }

    public function action_saveVisibility()
    {
        $packageName = (isset ($_REQUEST ['view_package']) && (strtolower($_REQUEST['view_package']) != 'studio')) ? $_REQUEST ['view_package'] : null;
        $parser = ParserFactory::getParser(MB_VISIBILITY, $_REQUEST ['view_module'], $packageName);

        $json = getJSONobj();
        $visibility_grid = $json->decode(html_entity_decode(rawurldecode($_REQUEST ['visibility_grid']), ENT_QUOTES));
        $parser->saveVisibility($_REQUEST ['fieldname'], $_REQUEST ['trigger'], $visibility_grid);

        echo $json->encode(array("visibility_editor_{$_REQUEST['fieldname']}" => array("action" => "deactivate")));
    }

    public function action_SaveRelationshipLabel()
    {
        global $locale;

        if (!empty($_REQUEST['relationship_lang'])) {
            $selected_lang = $_REQUEST['relationship_lang'];
        } else {
            $selected_lang = $locale->getAuthenticatedUserLanguage();
        }

        if (empty($_REQUEST ['view_package'])) {
            $relationships = new DeployedRelationships ($_REQUEST ['view_module']);
            if (!empty ($_REQUEST ['relationship_name'])) {
                if ($relationship = $relationships->get($_REQUEST ['relationship_name'])) {
                    $metadata = $relationship->buildLabels(true);
                    $parser = new ParserLabel ($_REQUEST['view_module']);
                    $parser->handleSaveRelationshipLabels($metadata, $selected_lang);
                }
            }
        } else {
            //TODO FOR MB
        }
        $this->view = 'relationships';
    }

    public function action_SaveRelationship()
    {
        if (!empty($GLOBALS['current_user']) && empty($GLOBALS['modListHeader'])) {
            $GLOBALS['modListHeader'] = query_module_access_list($GLOBALS['current_user']);
        }

        if (empty($_REQUEST ['view_package'])) {
            $relationships = new DeployedRelationships ($_REQUEST ['view_module']);
        } else {
            $mb = new ModuleBuilder ();
            $module = & $mb->getPackageModule($_REQUEST ['view_package'], $_REQUEST ['view_module']);
            $relationships = new UndeployedRelationships ($module->getModuleDir());
        }

        $relationships->addFromPost();
        // Since the build() call below will call save() again, save the rebuild
        // of relationship metadata for now
        $relationships->save(false);
        $GLOBALS['log']->debug("\n\nSTART BUILD");
        if (empty($_REQUEST ['view_package'])) {
            $relationships->build();
            LanguageManager::clearLanguageCache($_REQUEST ['view_module']);
            LanguageManager::invalidateJsLanguageCache();
        }
        $GLOBALS['log']->debug("\n\nEND BUILD");
        $this->view = 'relationships';
    }

    public function action_DeleteRelationship()
    {
        $relationshipName = $this->request->getValidInputRequest('relationship_name', 'Assert\ComponentName');
        $packageName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        if ($relationshipName) {
            $videModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
            if (!$packageName) {
                if (!empty($_REQUEST['remove_tables']))
                    $GLOBALS['mi_remove_tables'] = $_REQUEST['remove_tables'];
                $relationships = new DeployedRelationships($videModule);
            } else {
                $mb = new ModuleBuilder ();
                $module = & $mb->getPackageModule($packageName, $videModule);
                $relationships = new UndeployedRelationships ($module->getModuleDir());
            }
            $relationships->delete($relationshipName);

            $relationships->save();
            SugarRelationshipFactory::deleteCache();
        }
        $this->view = 'relationships';
    }

    public function action_SaveDropDown()
    {
        $parser = ParserFactory::getParser('dropdown');
        $parser->saveDropDown($_REQUEST);
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_LABELS);
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_ORDEREDLABELS);
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_EDITDDFILTERS);
        LanguageManager::invalidateJsLanguageCache();
        $this->view = 'dropdowns';
    }

    public function action_SaveRoleDropDownFilter()
    {
        $dropDownName = $this->request->getValidInputRequest('dropdown_name', 'Assert\ComponentName');
        $dropDownRole = $this->request->getValidInputRequest('dropdown_role', 'Assert\Guid');
        if (empty($dropDownName) || empty($dropDownRole) || empty($_REQUEST['dropdown_keys'])) {
            return;
        }

        $parser = new ParserRoleDropDownFilter();
        $parser->handleSave($dropDownName, $dropDownRole, $_REQUEST['dropdown_keys']);
        $this->view = 'dropdowns';
    }

    public function action_DeleteField()
    {
        $type = $this->request->getValidInputRequest('type');
        $name = $this->request->getValidInputRequest('name', 'Assert\ComponentName');

        $field = get_widget($type);
        $field->name = $name;

        $viewPackage = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $moduleName = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        $module = null;
        if (!$viewPackage) {
            if ($name && $moduleName) {

                // bug 51325 make sure we make this switch or delete will not work
                if ($moduleName == 'Employees')
                    $moduleName = 'Users';

                $seed = BeanFactory::newBean($moduleName);
                $df = new DynamicField ($moduleName);
                $df->setup($seed);
                //Need to load the entire field_meta_data for some field types
                $field = $df->getFieldWidget($moduleName, $field->name);
                $field->delete($df);

                $GLOBALS ['mod_strings']['LBL_ALL_MODULES'] = 'all_modules';
                $_REQUEST['execute_sql'] = true;
                include_once 'modules/Administration/QuickRepairAndRebuild.php';
                $repair = new RepairAndClear();
                $repair->repairAndClearAll(array('rebuildExtensions', 'clearVardefs', 'clearTpls'), array($moduleName), true, false);

                $module = StudioModuleFactory::getStudioModule($moduleName);
            }
        } else {
            $mb = new ModuleBuilder ();
            $module = & $mb->getPackageModule($viewPackage, $moduleName);
            $field = $module->getField($field->name);
            $field->delete($module);
            $mb->save();
        }

        if (!$module) {
            $GLOBALS['log']->fatal('Module not found');
            return;
        }

        $module->removeFieldFromLayouts($field->name);
        $this->view = 'modulefields';

        $label = $this->request->getValidInputRequest('label');
        $labelValue = $this->request->getValidInputRequest('label');

        if (isset($GLOBALS['current_language'])
            && $label !== null
            && $labelValue !== null
            && $moduleName !== null
        ) {
            $this->DeleteLabel($GLOBALS['current_language'], $label, $labelValue, $moduleName);
            $this->metadataApiCacheCleared = true;
        }

        // Clear the metadata cache if it hasn't been done already
        if (!$this->metadataApiCacheCleared && !empty($moduleName)) {
            // This removes the labels associated with the field and rebuilds
            // the api cache for the module
            $repair->module_list = array($moduleName);
            $repair->repairMetadataAPICache('labels');
        }
    }

    public function DeleteLabel($language, $label, $labelvalue, $modulename, $basepath = null, $forRelationshipLabel = false)
    {
        // remove the label
        ParserLabel::removeLabel($language, $label, $labelvalue, $modulename, $basepath, $forRelationshipLabel);
    }

    public function action_CloneField()
    {
        $this->view_object_map ['field_name'] = $_REQUEST ['name'];
        $this->view_object_map ['is_clone'] = true;
        $this->view = 'modulefield';
    }

    public function action_SaveAssistantPref()
    {
        global $current_user;
        if (isset ($_REQUEST ['pref_value'])) {
            if ($_REQUEST ['pref_value'] == 'ignore') {
                $current_user->setPreference('mb_assist', 'DISABLED', 0, 'Assistant');
            } else {
                $current_user->setPreference('mb_assist', 'ENABLED', 0, 'Assistant');
            }
            $current_pref = $current_user->getPreference('mb_assist', 'Assistant');
            echo "Assistant.processUserPref(" . json_encode($current_pref) . ")";
            sugar_cleanup(true); //push preferences to DB.
        }
    }

    // Studio2 Actions

    public function action_EditProperty()
    {
        $this->view = 'property';
    }

    public function action_saveProperty()
    {
        $subpanel = $this->request->getValidInputRequest('subpanel', 'Assert\ComponentName');
        $module = $subpanel ? $subpanel : $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        $viewPackage = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $parser = new ParserLabel ($module, $viewPackage);
        // if no language provided, then use the user's current language which is most likely what they intended
        $language = (isset($_REQUEST ['selected_lang'])) ? $_REQUEST ['selected_lang'] : $GLOBALS['current_language'];
        $parser->handleSave($_REQUEST, $language);
        $json = getJSONobj();
        echo $json->encode(array("east" => array("action" => "deactivate")));
    }

    public function action_editModule()
    {
        $this->view = 'module';
    }

    public function action_wizard()
    {
        $this->view = 'wizard';
    }


    /**
     * Receive a layout through $_REQUEST and save it out to the working files directory
     * Expects a series of $_REQUEST parameters all in the format $_REQUEST['slot-panel#-slot#-property']=value
     */

    public function action_saveLayout()
    {
        $parserview = $_REQUEST['view'];
        if (isset($_REQUEST['PORTAL'])) {
            $client = 'portal';
            $this->view = 'portallayoutview' ;
            $parserview = $client . strtolower($parserview);
        } else {
            $this->view = 'layoutview';
            $client = null;
        }

        $params = array();
        if (!empty($_REQUEST['role'])) {
            $params['role'] = $_REQUEST['role'];
        }
        $parser = ParserFactory::getParser(
            $parserview,
            $_REQUEST['view_module'],
            isset($_REQUEST ['view_package']) ? $_REQUEST ['view_package'] : null,
            null,
            $client,
            $params
        );
        $parser->writeWorkingFile () ;


        if (!empty($_REQUEST ['sync_detail_and_edit']) && $_REQUEST['sync_detail_and_edit'] != false && $_REQUEST['sync_detail_and_edit'] != "false") {
            if (strtolower($parser->_view) == MB_EDITVIEW) {
                $parser2 = ParserFactory::getParser(MB_DETAILVIEW, $_REQUEST ['view_module'], isset ($_REQUEST ['view_package']) ? $_REQUEST ['view_package'] : null);
                $parser2->setUseTabs($parser->getUseTabs());
                $parser2->writeWorkingFile();
            }
        }
    }

    public function action_saveAndPublishLayout()
    {
        $parserview = $_REQUEST['view'];
        if (isset($_REQUEST['PORTAL'])) {
            $client = 'portal';
            $this->view = 'portallayoutview' ;
            $parserview = $client . strtolower($parserview);
        } else {
            $client = null;
            $this->view = 'layoutview';
        }

        $params = array();
        if (!empty($_REQUEST['role'])) {
            $params['role'] = $_REQUEST['role'];
        }
        $parser = ParserFactory::getParser(
            $parserview,
            $_REQUEST['view_module'],
            isset ($_REQUEST ['view_package']) ? $_REQUEST ['view_package'] : null,
            null,
            $client,
            $params
        );

        if (!empty($_REQUEST['is_reset'])) {
            $parser->resetToDefault();
        } else {
            $parser->handleSave();
        }

        if (!empty($_REQUEST [ 'sync_detail_and_edit' ]) && $_REQUEST['sync_detail_and_edit'] != false && $_REQUEST['sync_detail_and_edit'] != "false") {
            if (strtolower ($parser->_view) == MB_EDITVIEW) {
                $parser2 = ParserFactory::getParser ( MB_DETAILVIEW, $_REQUEST [ 'view_module' ], isset ( $_REQUEST [ 'view_package' ] ) ? $_REQUEST [ 'view_package' ] : null ) ;
                $parser2->setUseTabs($parser->getUseTabs());
                $parser2->handleSave () ;
            }
        }
    }

    public function action_manageBackups()
    {

    }

    public function action_listViewSave()
    {
        $GLOBALS['log']->info("action_listViewSave");
        if (isset($_REQUEST['PORTAL'])) {
            $this->view = 'portallistview';
            $parser = ParserFactory::getParser($this->view, $_REQUEST['view_module'], null, null, MB_PORTAL);
            //$parser->init ( $_REQUEST [ 'view_module' ] ) ; // removed init because MetaDataParsers don't use it
        } else {

            $packageName = (isset ($_REQUEST ['view_package']) && (strtolower($_REQUEST['view_package']) != 'studio')) ? $_REQUEST ['view_package'] : null;
            $subpanelName = (!empty ($_REQUEST ['subpanel'])) ? $_REQUEST ['subpanel'] : null;
            $parser = ParserFactory::getParser($_REQUEST ['view'], $_REQUEST ['view_module'], $packageName, $subpanelName);
            $this->view = 'listView';
            
            // To make sure that dashlets can render customized list views on BWC
            // modules, we need to save list customizations for BWC modules in 
            // the new style as well.
            if (isModuleBWC($_REQUEST['view_module']) && empty($packageName) && empty($subpanelName)) {
                $sidecarListParser = new SidecarListLayoutMetaDataParser(MB_SIDECARLISTVIEW, $_REQUEST['view_module'], null, 'base');
                $sidecarListParser->handleSave();
            }
        }
        // for backwards compatibility we need to parse the subpanel the old way as well
        // TODO: Remove this when all BWC Modules are converted
        if($parser instanceof SidecarSubpanelLayoutMetaDataParser) {
            $oldSubpanelParser = new SubpanelMetaDataParser($subpanelName, $_REQUEST ['view_module'], $packageName);
            $oldSubpanelParser->handleSave();
            unset($oldSubpanelParser);
        }
        $parser->handleSave();
        if (empty($packageName) && !empty($subpanelName)) {
            $rr = new RepairAndClear();
            $rr->show_output = false;
            $rr->rebuildExtensions(array($_REQUEST['view_module']));
        }
        // clear the cache for the linked module and requested module
        MetaDataManager::refreshModulesCache($parser->getAffectedModules());
    }

    public function action_dashletSave()
    {
        $this->view = 'dashlet';
        $packageName = (isset ($_REQUEST ['view_package']) && (strtolower($_REQUEST['view_package']) != 'studio')) ? $_REQUEST ['view_package'] : null;
        $parser = ParserFactory::getParser($_REQUEST ['view'], $_REQUEST ['view_module'], $packageName);
        $parser->handleSave();
    }

    public function action_popupSave()
    {
        $this->view = 'popupview';

        $view = $this->request->getValidInputRequest('view', 'Assert\ComponentName');
        $viewModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        $packageName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');

        if ($packageName === 'studio') {
            $packageName = null;
        }

        $parser = ParserFactory::getParser($view, $viewModule, $packageName);
        $parser->handleSave();

        if ($view != MB_POPUPSEARCH) {
            $parser = ParserFactory::getParser(MB_POPUPLIST, $viewModule, $packageName);
            $parser->handleSave();
        }

        if (empty($packageName)) {
            include_once 'modules/Administration/QuickRepairAndRebuild.php';
            global $mod_strings;
            $mod_strings['LBL_ALL_MODULES'] = 'all_modules';
            $repair = new RepairAndClear();
            $repair->show_output = false;
            $class_name = $GLOBALS ['beanList'] [$viewModule];
            $repair->module_list = array($class_name);
            $repair->clearTpls();
            // Clear the module metadata but nothing else
            $repair->repairMetadataAPICache(false);
        }
    }

    public function action_searchViewSave()
    {
        $packageName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');

        // Bug 56789 - Set the client from the view to ensure the proper viewdef file
        $client = MetaDataFiles::getClientByView($_REQUEST['view']);
        if (isModuleBWC($_REQUEST['view_module'])) {
            $parser = new SearchViewMetaDataParser($_REQUEST ['view'], $_REQUEST ['view_module'], $packageName, $client);
        } else {
            $client = empty($client) ? 'base' : $client;
            $parser = new SidecarFilterLayoutMetaDataParser($_REQUEST ['view_module'], $packageName, $client);
        }
        $parser->handleSave();

        //Repair or create a custom SearchFields.php file as needed
        $module_name = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        global $beanList;
        if (isset($beanList[$module_name]) && $beanList[$module_name] != "") {
            $objectName = BeanFactory::getObjectName($module_name);

            //Load the vardefs for the module to pass to TemplateRange
            VardefManager::loadVardef($module_name, $objectName, true);
            global $dictionary;
            $vardefs = $dictionary[$objectName]['fields'];
            TemplateRange::repairCustomSearchFields($vardefs, $module_name, $packageName);
        }
        $this->view = 'searchView';
    }

    public function action_editLabels()
    {
        if (isset ($_REQUEST ['view_package'])) { //MODULE BUILDER
            $this->view = 'modulelabels';
        } else { //STUDIO
            $this->view = 'labels';
        }
    }

    public function action_get_app_list_string()
    {
        $json = new JSON ();
        if (isset ($_REQUEST ['key']) && !empty ($_REQUEST ['key'])) {
            $key = $_REQUEST ['key'];
            $value = array();
            if (!empty ($GLOBALS ['app_list_strings'] [$key])) {
                $value = $GLOBALS ['app_list_strings'] [$key];
            } else {
                $package_strings = array();
                if (!empty ($_REQUEST ['view_package']) && $_REQUEST ['view_package'] != 'studio' && !empty ($_REQUEST ['view_module'])) {
                    $mb = new ModuleBuilder ();
                    $module = & $mb->getPackageModule($_REQUEST ['view_package'], $_REQUEST ['view_module']);
                    $lang = $GLOBALS ['current_language'];
                    $module->mblanguage->generateAppStrings(false);
                    $package_strings = $module->mblanguage->appListStrings [$lang . '.lang.php'];
                    if (isset ($package_strings [$key]) && is_array($package_strings [$key])) {
                        $value = $package_strings [$key];
                    }
                }
            }
            echo $json->encode($value);
        }
    }

    public function action_history()
    {
        $this->view = 'history';
    }

    public function resetmodule()
    {
        $this->view = 'resetmodule';
    }

    public function action_editPortal()
    {
        // when the file names changed, the request variable (which was tied to them in SugarPortalModule.php)
        // changed. hence, chop off the 'view' suffix in case it appears.
        $view = strtolower($_REQUEST['view']);
        if (substr_compare($view, 'view', -4) === 0) {
            $view = substr($view, 0, -4);
        }

        switch ($view) {
            case 'record' :
                $this->view = 'portallayoutView';
                break;
            case 'list' :
                $this->view = 'portallistView';
                break;
            case 'search' :
                $this->view = 'portalsearchView';
                break;
            default :
                $GLOBALS ['log']->fatal('Action = editPortal with unknown view=' . $_REQUEST ['view']);
        }
    }

    public function action_portalstylesave()
    {
        if (strcmp(strtolower($_FILES ['filename'] ['type']), 'text/css') == 0) {
            mkdir_recursive('custom/portal/custom');
            move_uploaded_file($_FILES ['filename'] ['tmp_name'], 'custom/portal/custom/style.css');
            $this->viewParams['label'] = 'LBL_SP_UPLOADED';
            $GLOBALS ['log']->debug('action_portalstylesave(): saving file ' . $_FILES ['filename'] ['name']);
        } else {
            $this->viewParams['label'] = 'ERROR_SP_UPLOADED';
            $GLOBALS ['log']->error('action_portalstylesave(): error uploading ' . $_FILES ['filename'] ['tmp_name']);
        }

        @unlink($_FILES ['filename'] ['tmp_name']);
        $this->view = 'portalstyleupload';
    }

    public function action_portalsyncsync()
    {
        $option = str_replace(array('soap.php', 'index.php', 'portal_sync.php'), '', $_REQUEST ['portalURL']);
        $GLOBALS ['system_config']->saveSetting('system', 'portal_url', $option);
        $GLOBALS ['system_config']->settings ['system_portal_url'] = $option;
        $this->view = 'portalsync';
    }

    public function action_portalconfigsave()
    {
        $parser = new ParserModifyPortalConfig();
        $parser->handleSave();
        $this->view = 'portalconfig';
    }


    /**
     * savetablesort
     * This method handles saving the current user's tabling sorting preferences.  It is called when
     * the user clicks on a column to sort from the fields layout table.
     *
     */
    public function action_savetablesort()
    {
        $this->view = 'ajax';
        global $current_user;

        if (!empty($current_user) && isset($_REQUEST['column']) && isset($_REQUEST['direction'])) {
            $direction = ($_REQUEST['direction'] == 'yui-dt-asc') ? 'ASC' : 'DESC';
            $valid_columns = array('name', 'label', 'type', 'required', 'unified_search', 'custom');
            $key = in_array($_REQUEST['column'], $valid_columns) ? $_REQUEST['column'] : 'name';
            $val = array('key' => $key, 'direction' => $direction);
            $current_user->setPreference('fieldsTableColumn', getJSONobj()->encode($val), 0, 'ModuleBuilder');
        }
    }

    public function action_copyLayout()
    {
        $module = $_REQUEST['view_module'];
        $view = $_REQUEST['view'];
        $role = $_REQUEST['role'];
        $source = $_REQUEST['source'];

        $sourceParser = ParserFactory::getParser($view, $module, null, null, null, array('role' => $source));
        $sourceImplementation = $sourceParser->getImplementation();
        $fileName = $sourceImplementation->getFileNameNoDefault($view, $module);
        if (!file_exists($fileName)) {
            return;
        }

        $parser = ParserFactory::getParser($view, $module, null, null, null, array('role' => $role));
        $history = $parser->getHistory();
        $history->savePreview($fileName);

        $this->view = 'layoutview';
    }

    /**
     * Nomalizes module strings.
     *
     * In most cases this method won't do much in the way of transformation. But
     * in the case of the backward compatibility indicator in studio, it will
     * parse the indicator and add it to the help text that appears on the right
     * hand pane.
     */
    protected function normalizeModStrings()
    {
        global $mod_strings;

        // Handle BC for studio help
        if (isset($mod_strings['help']['studioWizard']['studioHelp']) && isset($mod_strings['help']['studioWizard']['studioBCHelp'])) {
            // Keep it clean... make this assignment on more lines so it is more readable
            $add  = '<br><br><b>' . StudioModule::$bwcIndicator . '</b>';
            $add .= $mod_strings['help']['studioWizard']['studioBCHelp'];
            $mod_strings['help']['studioWizard']['studioHelp'] .= $add;
        }
    }

    /**
     * Remove temp files created by modulebuilder.
     * @param string $name package name
     */
    protected function removeTempFiles($name)
    {
        $path = MB_PACKAGE_PATH . '/' . $name;
        // tempnam() allows ony three characeters on windows for file name prefix
        $prefix = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'tem' : 'temp';
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($files as $filePath => $info) {
            if (preg_match('/^' . $prefix . '[a-zA-Z0-9]+$/', $info->getFilename())) {
                @unlink($filePath);
                $GLOBALS['log']->warning('ModuleBuilder deleted a temp file: ' . $filePath);
            }
        }
    }
}
