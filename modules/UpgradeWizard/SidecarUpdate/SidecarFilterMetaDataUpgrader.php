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
// This will need to be pathed properly when packaged
require_once 'SidecarAbstractMetaDataUpgrader.php';
require_once 'modules/ModuleBuilder/Module/StudioModuleFactory.php';
require_once 'modules/ModuleBuilder/parsers/views/DeployedSearchMetaDataImplementation.php';
require_once 'modules/ModuleBuilder/parsers/ParserFactory.php';

class SidecarFilterMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    /**
     * Should we delete pre-upgrade files?
     * Not deleting searchviews since we may need them for popups in subpanels driven by BWC module.
     * See BR-1044
     * @var bool
     */
    public $deleteOld = false;

    /**
     * Check if we actually want to upgrade this file
     * @return boolean
     */
    public function upgradeCheck()
    {
        $module = $this->getNormalizedModuleName();
        if (!isset($GLOBALS['beanList'][$module])) {
            // don't upgrade non-deployed search defs
            return false;
        }
        $target = $this->getNewFileName($this->viewtype);
        if(file_exists($target)) {
            // if we already have the target, skip the upgrade
            return false;
        }
        return true;
    }

    /**
     * Move the functionalities to DeployedSearchMetaDataImplementation::convertLegacyViewDefsToSidecar().
     * Use $this->handleSave() to convert and save the files.
     *
     * @override SidecarAbstractMetaDataUpgrader::convertLegacyViewDefsToSidecar()
     */
    public function convertLegacyViewDefsToSidecar()
    {
    }
    /**
     * Handling the file conversion.
     * @override SidecarAbstractMetaDataUpgrader::handleSave()
     */
    public function handleSave()
    {
        // Get what we need to make our new files
        $viewName = $this->views[$this->client . $this->viewtype];
        $module = $this->getNormalizedModuleName();
        //Translate the viewName, only handling the base filter case
        if ($viewName == MB_SEARCHVIEW) {
            $viewName = MB_BASICSEARCH;
        } elseif ($viewName != MB_BASICSEARCH) {
            return array();
        }
        $impl = new DeployedSearchMetaDataImplementation($viewName, $module);
        return $impl->createSidecarFilterDefsFromLegacy(array(), $this->loadFilterDef());
    }

    public function getNewFileName($viewname)
    {
        $client = $this->client == 'wireless' ? 'mobile' : $this->client;
        // Cut off metadata/searchdefs.php
        $dirname = dirname(dirname($this->fullpath));
        return $dirname . "/clients/$client/filters/default/default.php";
    }

    /**
     * Load the field definitions from clients/$client/filters/default/default.php.
     * @return array
     */
    protected function loadFilterDef()
    {
        $module = $this->getNormalizedModuleName();
        $parser = ParserFactory::getParser(MB_BASICSEARCH, $module);
        $defs = $parser->getOriginalViewDefs();

        return isset($defs['fields']) ? $defs['fields'] : array();
    }
}
