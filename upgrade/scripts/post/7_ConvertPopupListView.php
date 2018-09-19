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

require_once 'modules/ModuleBuilder/parsers/constants.php';

/**
 * Converts custom "popupdefs.php" files to sidecar "selection-list.php".
 */
class SugarUpgradeConvertPopupListView extends UpgradeScript
{
    /**
     * @inheritdoc
     */
    public $order = 7100;

    /**
     * @inheritdoc
     */
    public $type = self::UPGRADE_CUSTOM;

    /**
     * @inheritdoc
     */
    public $version = '7.2';

    /**
     * Converts only listViewDefs.
     * Old format contains only default fields, current - default and enabled.
     */
    public function run()
    {
        if (!version_compare($this->from_version, '7.2', '<')) {
            return;
        }

        $sb = new StudioBrowser();
        $sb->loadModules();

        foreach ($sb->modules as $module => $defs) {
            $this->convertModuleListViewDefs($module);
        }
    }

    /**
     * Converts listViewDefs for single module.
     * @param string $module
     */
    protected function convertModuleListViewDefs($module)
    {
        if (!file_exists("custom/modules/$module/metadata/popupdefs.php")) {
            return;
        }
        require "custom/modules/$module/metadata/popupdefs.php";

        if (!isset($popupMeta['listviewdefs'])) {
            return;
        }

        $popupDefaultFieldNames = array();
        foreach ($popupMeta['listviewdefs'] as $key => $popupFieldDefs) {
            $popupDefaultFieldNames[] = isset($popupFieldDefs['name']) ?
                $popupFieldDefs['name'] :
                strtolower($key);
        }

        $sidecarParser = new SidecarListLayoutMetaDataParser(MB_SIDECARPOPUPVIEW, $module, null, 'base');
        $panel = $sidecarParser->getOriginalPanelDefs();
        $allFields = array_merge($sidecarParser->getAvailableFields(), $sidecarParser->getAdditionalFields());

        // Sidecar originally enabled and default fields.
        $newPanelDef = $panel[0]['fields'];
        // Reset all defaul fields to save available.
        array_walk($newPanelDef, function (&$val) {
            $val['default'] = false;
        });

        foreach ($popupDefaultFieldNames as $defaultFieldName) {
            // Populate with new default set.
            foreach ($newPanelDef as &$panelDef) {
                if ($panelDef['name'] == $defaultFieldName) {
                    $panelDef['default'] = true;
                    continue 2;
                }
            }
            // The field is hidden, populate the result defs with it.
            if (isset($allFields[$defaultFieldName])) {
                $newPanelDef[] = array_merge(
                    $allFields[$defaultFieldName],
                    // Some valid fields have no name.
                    array('default' => true, 'enabled' => true, 'name' => $defaultFieldName)
                );
            }
        }

        $sidecarParser->setPanelFields($newPanelDef);
        $sidecarParser->handleSave(false);
    }
}
