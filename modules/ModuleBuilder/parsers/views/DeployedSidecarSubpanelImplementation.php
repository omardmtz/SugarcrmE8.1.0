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

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

require_once 'modules/ModuleBuilder/parsers/constants.php';

class DeployedSidecarSubpanelImplementation extends AbstractMetaDataImplementation implements MetaDataImplementationInterface
{
    const HISTORYFILENAME = 'restored.php';
    const HISTORYVARIABLENAME = 'viewdefs';

    /**
     * Subpanel link name
     *
     * @var string
     */
    protected $linkName;

    /**
     * @var string
     */
    protected $originalDefsFileName;

    /**
     * The constructor
     * @param string $subpanelName
     * @param string $loadedModule - Accounts
     * @param string $client - base
     */
    public function __construct($subpanelName, $loadedModule, $client = 'base')
    {
        $GLOBALS['log']->debug(get_class($this) . "->__construct($subpanelName , $loadedModule)");

        $this->mdc = new MetaDataConverter();
        $this->loadedModule = $loadedModule;
        $this->setViewClient($client);
        $linkName = $this->linkName = $this->detectLinkName($subpanelName, $loadedModule);

        // get the link and the related module name as the module we need the subpanel from
        $bean = BeanFactory::newBean($loadedModule);
        // Get the linkdef, but make sure to tell VardefManager to use name instead by passing true
        $linkDef = VardefManager::getLinkFieldForRelationship($bean->module_dir, $bean->object_name, $subpanelName, true);
        if ($bean->load_relationship($linkName)) {
            $link = $bean->$linkName;
        } else {
            $link = new Link2($linkName, $bean);
        }
        $this->_moduleName = $link->getRelatedModuleName();
        $this->bean = BeanFactory::newBean($this->_moduleName);

        $subpanelFixed = true;
        if(empty($this->bean)) {
            $subpanelFixed = $this->fixUpSubpanel();
        }

        if(empty($linkDef['name']) && (!$subpanelFixed && isModuleBWC($this->loadedModule))) {
            $GLOBALS['log']->error("Cannot find a link for {$subpanelName} on {$loadedModule}");
            return true;
        }

        // Handle validation up front that will throw exceptions
        if (empty($this->bean) && !$subpanelFixed) {
            throw new Exception("No valid parent bean found for {$this->linkName} on {$this->loadedModule}");
        }

        $this->setUpSubpanelViewDefFileInfo();

        include FileLoader::validateFilePath($this->loadedSubpanelFileName);

        if ($this->originalDefsFileName) {
            $this->loadOriginalViewDefs();
        }

        // Prepare to load the history file. This will be available in cases when
        // a layout is restored.
        $this->historyPathname = 'custom/history/modules/' . $this->_moduleName . '/clients/' . $this->getViewClient(
            ) . '/views/' . $this->sidecarSubpanelName. '/' . self::HISTORYFILENAME;

        if (file_exists($this->historyPathname)) {
            // load in the subpanelDefOverride from the history file
            $GLOBALS['log']->debug(get_class($this) . ": loading from history");
            require FileLoader::validateFilePath($this->historyPathname);
        }
        $this->_history = new History($this->historyPathname);

        $this->_history = new History($this->historyPathname);
        $this->_viewdefs = !empty($viewdefs) ? $this->getNewViewDefs($viewdefs) : array();
        $this->_fielddefs = $this->bean->field_defs;
        $this->_mergeFielddefs($this->_fielddefs, $this->_viewdefs);
        $this->_language = '';
        // don't attempt to access the template_instance property if our subpanel represents a collection, as it won't be there - the sub-sub-panels get this value instead
        if (isset($this->_viewdefs['type']) && $this->_viewdefs['type'] != 'collection') {
            $this->_language = $this->bean->module_dir;
        }
        // Make sure the paneldefs are proper if there are any
        $this->_paneldefs = isset($this->_viewdefs['panels']) ? $this->_viewdefs['panels'] : array();
    }

    /**
     * Returns an array of modules affected by this object. In almost all cases
     * this will be a single array. For subpanels, it will be more than one.
     * 
     * @return array List of modules changed within this object
     */
    public function getAffectedModules()
    {
        return array($this->_moduleName, $this->loadedModule);
    }

    /**
     * Returns subpanel primary module name
     *
     * @return string
     */
    public function getPrimaryModuleName()
    {
        return $this->loadedModule;
    }

    /**
     * Returns subpanel link name
     *
     * @return string
     */
    public function getLinkName()
    {
        return $this->linkName;
    }

    /**
     * If a subpanel cannot be found in sidecar, try to find it in legacy
     * and convert it
     * @return bool
     */
    protected function fixUpSubpanel()
    {
        // getting here usually means that the link passed in is from an oldschool layoutdef
        // get the name, get the key, get the link, then we work the magic
        $spd = new SubPanelDefinitions(BeanFactory::newBean($this->loadedModule));
        if (! empty ( $spd->layout_defs )) {
            if (array_key_exists(strtolower($this->linkName), $spd->layout_defs ['subpanel_setup'])) {
                $aSubPanelObject = $spd->load_subpanel($this->linkName);
                $this->_moduleName = $aSubPanelObject->get_module_name();
                $this->bean = BeanFactory::newBean($this->_moduleName);
                // convert the old viewdef on the fly
                $this->convertLegacyViewDef($aSubPanelObject->get_list_fields());
                return true;
            }
        }

        return false;
    }

    /**
     * Convert the legacy viewdefs to sidecar viewdefs
     * @param array $listFields list of fields on teh subpanel
     * @return bool
     */
    protected function convertLegacyViewDef($listFields)
    {
        $this->sidecarSubpanelName = $this->getSidecarSubpanelViewName($this->loadedModule, $this->linkName);
        $this->sidecarFile = "custom/modules/{$this->_moduleName}/clients/" . $this->getViewClient(
            ) . "/views/{$this->sidecarSubpanelName}/{$this->sidecarSubpanelName}.php";
        if (!file_exists($this->sidecarFile)) {
            $viewDefs = $this->mdc->fromLegacySubpanelsViewDefs(array('list_fields' => $listFields), $this->_moduleName);
            $this->deploy($viewDefs);
        }
        return true;
    }

    /**
     * @param $parentModule
     * @param $linkName
     *
     * @return string the custom subpanel name for this given parent module and link name
     */
    protected function getSidecarSubpanelViewName($parentModule, $linkName) {
        return strtolower("subpanel-for-$parentModule-$linkName");
    }

    /**
     * Detects the link name for a subpanel using witchcraft and wizardry
     *
     * @param string $subpanelName - this is the name of the subpanel
     * @param string $loadedModule - this is the name of the module that is loaded
     * @return string the linkname for the subpanel
     */
    protected function detectLinkName($subpanelName, $loadedModule)
    {
        if (isModuleBWC($loadedModule) && !file_exists("modules/{$loadedModule}/clients/" . $this->getViewClient() . "/layouts/subpanels/subpanels.php")) {
            @include "modules/{$loadedModule}/metadata/subpaneldefs.php";
            if(empty($layout_defs[$loadedModule]['subpanel_setup'])) {
                $GLOBALS['log']->error("Cannot find subpanel layout defs for {$loadedModule}");
                return $subpanelName;
            }
            foreach($layout_defs[$loadedModule]['subpanel_setup'] as $linkName => $def) {
                if ($def['module'] == $subpanelName) {
                    return $linkName;
                }
            }            
        }
       
        $viewdefs = MetaDataFiles::getClientFileContents(MetaDataFiles::getClientFiles(array($this->getViewClient()), 'layout', $loadedModule), 'layout', $loadedModule);

        if (empty($viewdefs['subpanels'])) {
            return $subpanelName;
        }

        $legacyDefs = $this->mdc->toLegacySubpanelLayoutDefs($viewdefs['subpanels']['meta']['components'], BeanFactory::newBean($loadedModule));

        if(empty($legacyDefs['subpanel_setup'])) {
            if (isModuleBWC($loadedModule)) {
                $GLOBALS['log']->error("Could not convert subpanels for subpanel: {$subpanelName} - {$loadedModule}");
            }

            return $subpanelName;
        }

        foreach($legacyDefs['subpanel_setup'] as $linkName => $def) {
            if ($def['module'] == $subpanelName) {
                return $linkName;
            }
        }

        return $subpanelName;
    }


    /**
     * Sets up the class vars for the file information
     * @return bool
     * @throws Exception
     */
    protected function setupSubpanelViewDefFileInfo()
    {
        $client = $this->getViewClient();
        $this->sidecarSubpanelName = $this->getSidecarSubpanelViewName($this->loadedModule, $this->linkName);

        if ($client !== 'base') {
            $layoutFiles[] = "modules/{$this->loadedModule}/clients/base/layouts/subpanels/subpanels.php";
        }

        // check if there is an override
        $layoutFiles = array(
            "modules/{$this->loadedModule}/clients/{$client}/layouts/subpanels/subpanels.php",
        );

        foreach ($layoutFiles as $file) {
            @include $file;
        }
        $extension = "custom/modules/{$this->loadedModule}/Ext/clients/$client/layouts/subpanels/subpanels.ext.php";
        if (file_exists($extension)) {
            @include $extension;
        }

        $overrideSubpanelName = null;
        $overrideSubpanelFileName = null;

        if(!empty($viewdefs[$this->loadedModule][$client]['layout']['subpanels']['components'])) {
            $components = $viewdefs[$this->loadedModule][$client]['layout']['subpanels']['components'];
            foreach ($components as $key => $component) {
                if (empty($component['override_subpanel_list_view'])) {
                    continue;
                }
                if (is_array($component['override_subpanel_list_view'])
                    && $component['override_subpanel_list_view']['link'] == $this->linkName
                ) {
                    $this->loadedSubpanelName = $component['override_subpanel_list_view']['view'];
                    $path = "modules/{$this->_moduleName}/clients/{$client}"
                          . "/views/{$this->loadedSubpanelName}/{$this->loadedSubpanelName}.php";

                    $this->loadedSubpanelFileName = file_exists("custom/$path") ? "custom/$path" : $path;
                    $this->sidecarFile = "custom/modules/{$this->_moduleName}/clients/{$client}"
                                       . "/views/{$this->sidecarSubpanelName}/{$this->sidecarSubpanelName}.php";
                    $this->overrideArrayKey = $key;
                    return true;
                }
                // handle revenuelineitems' subpanel-for-opportunities 
                if ((!empty($component['context']['link']) && $component['context']['link'] == $this->linkName)) {
                    $overrideSubpanelName = $component['override_subpanel_list_view'];
                    $overrideSubpanelFileName = "modules/{$this->_moduleName}/clients/{$client}"
                        . "/views/{$overrideSubpanelName}/{$overrideSubpanelName}.php";
                    break;
                }
            }
        }

        $subpanelFile = "modules/{$this->_moduleName}/clients/{$client}"
                      . "/views/{$this->sidecarSubpanelName}/{$this->sidecarSubpanelName}.php";

        $defaultSubpanelFile = "modules/{$this->_moduleName}/clients/base/views/subpanel-list/subpanel-list.php";
        $this->loadedSubpanelName = $this->sidecarSubpanelName;
        $this->originalDefsFileName = null;

        $studioModule = new StudioModule($this->_moduleName);
        $defaultTemplate = $studioModule->getType();
        $defaultTemplateSubpanelFile = "include/SugarObjects/templates/{$defaultTemplate}/clients/base/views/subpanel-list/subpanel-list.php";

        $baseTemplateSubpanelFile = "include/SugarObjects/templates/basic/clients/base/views/subpanel-list/subpanel-list.php";

        $files = array();
        $files[] = array('custom/' . $subpanelFile, null);
        $files[] = array($subpanelFile, null);

        if ($overrideSubpanelName) {
            $files[] = array($overrideSubpanelFileName, $overrideSubpanelName);
        }

        $files[] = array($defaultSubpanelFile, 'subpanel-list');
        $files[] = array($defaultTemplateSubpanelFile, 'subpanel-list');
        $files[] = array($baseTemplateSubpanelFile, 'subpanel-list');

        // locate effective subpanel definition file
        foreach ($files as $spec) {
            list($path, $subPanelName) = $spec;

            if (file_exists($path)) {
                $this->loadedSubpanelFileName = $path;
                if ($subPanelName) {
                    $this->loadedSubpanelName = $subPanelName;
                }
                break;
            }
        }

        if (!$this->loadedSubpanelFileName) {
            throw new Exception('No metadata file found for subpanel: ' . $this->loadedSubpanelName);
        }

        // locate original subpanel definition file
        foreach ($files as $spec) {
            list($path) = $spec;

            if (strpos($path, 'custom/') !== 0 && file_exists($path)) {
                $this->originalDefsFileName = $path;
                break;
            }
        }

        $this->sidecarFile = "custom/" . $subpanelFile;
    }

    /**
     * Get the correct viewdefs from the array in the file
     * @param array $viewDefs
     * @return array
     */
    public function getNewViewDefs(array $viewDefs)
    {
        if (isset($viewDefs[$this->_moduleName][$this->_viewClient]['view'][$this->loadedSubpanelName])) {
            return $viewDefs[$this->_moduleName][$this->_viewClient]['view'][$this->loadedSubpanelName];
        }

        return array();
    }

    /**
     * Getter for the fielddefs
     * @return array
     */
    public function getFieldDefs()
    {
        return $this->_fielddefs;
    }

    /**
     * Gets the appropriate module name for use in translation of labels in
     * studio
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->_moduleName;
    }

    /*
     * Save a definition that will be used to display a subpanel for $this->_moduleName
     * @param array defs Layout definition in the same format as received by the constructor
     */

    public function deploy($defs, $clearCache = true)
    {
        // Make a viewdefs variable for saving
        $varname = "viewdefs['{$this->_moduleName}']['{$this->_viewClient}']['view']['{$this->sidecarSubpanelName}']";
        if (!empty($this->historyPathname)) {
            // first sort out the historical record...
            write_array_to_file($varname, $this->_viewdefs, $this->historyPathname);
            $this->_history->append($this->historyPathname);
        }
        $this->_viewdefs = $defs;

        // Now move on to the viewdefs proper
        if (!is_dir(dirname($this->sidecarFile))) {
            if (!mkdir(dirname($this->sidecarFile), 0755, true)) {
                throw new Exception(sprintf("Cannot create directory %s", $this->sidecarFile));
            }
        }

        // always set the type to subpanel-list for the client
        if (strpos($this->sidecarSubpanelName, 'subpanel-for-') !== false) {
            $this->_viewdefs['type'] = 'subpanel-list';
        }

        write_array_to_file(
            $varname,
            $this->_viewdefs,
            $this->sidecarFile
        );

        $this->saveSidecarSubpanelDefOverride();
        if ($clearCache) {
            // clear the cache for this modules only
            MetaDataManager::refreshModulesCache(array($this->loadedModule, $this->_moduleName));
        }
    }

    /**
     * Saves a sidecar layout extension to use the new view override for this subpanel
     */
    protected function saveSidecarSubpanelDefOverride()
    {

        $client = $this->getViewClient();
        $layoutPath = "custom/Extension/modules/{$this->loadedModule}/Ext/clients/$client/layouts/subpanels";
        $layoutDefsName = "viewdefs['{$this->loadedModule}']['$client']['layout']['subpanels']['components'][]";
        $viewName = $filename = $this->sidecarSubpanelName;
        $overrideName = 'override_subpanel_list_view';

        $this->removeOldOverideExtension($layoutPath, $client);

        $overrideValue = array(
            "link" => $this->linkName,
            "view" => $viewName,
        );

        $newValue = override_value_to_string($layoutDefsName, $overrideName, $overrideValue);
        mkdir_recursive($layoutPath, true);

        $extname = '_override' . $filename;

        sugar_file_put_contents(
            "{$layoutPath}/{$extname}.php",
            "<?php\n//auto-generated file DO NOT EDIT\n$newValue\n"
        );
    }

    /**
     * Searches for and removes any older layout override extensions that reference this subpanel
     * @param $layoutPath path to the layout extension folder for the current module
     */
    protected function removeOldOverideExtension($layoutPath, $client = 'base')
    {
        $files = glob("$layoutPath/_override*.php");
        foreach ($files as $override) {
            $viewdefs = array();
            @include $override;
            if (!empty($viewdefs[$this->loadedModule][$client]['layout']['subpanels']['components'][0]['override_subpanel_list_view']['link'])
                && $viewdefs[$this->loadedModule][$client]['layout']['subpanels']['components'][0]['override_subpanel_list_view']['link'] == $this->linkName
            ) {
                unlink($override);
            }
        }
    }

    protected function loadOriginalViewDefs()
    {
        $viewdefs = array();
        include $this->originalDefsFileName;

        if (isset($viewdefs[$this->loadedModule])) {
            $this->_originalViewdefs = $viewdefs[$this->loadedModule];
        }
    }
}
