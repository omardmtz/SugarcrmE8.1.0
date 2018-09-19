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
require_once 'modules/UpgradeWizard/SidecarUpdate/SidecarAbstractMetaDataUpgrader.php';

/**
 * Sidecar Subpanel Layoutdefs Upgrader
 */
class SidecarLayoutdefsMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{

    // we don't care about all the fields right now, we just care about
    // override_subpanel_name => override_subpanel_list_view
    // get_subpanel_data => link
    // title_key => label
    protected static $conversionKeys = array(
            'override_subpanel_name' => 'override_subpanel_list_view',
            'get_subpanel_data' => 'link',
            'title_key' => 'label',
    );

    // don't delete old layoutdefs
    public $deleteOld = false;

    /**
     * Is this a collection panel which needs to be expaned to a number of panels?
     * @var bool
     */
    protected $collection = false;
    /**
     * Storage for labels to create
     * @var array
     */
    protected $labels = array();

    /**
     * Subpanels full data by module
     * @var array
     */
    protected static $supanelData = array();

    public function loadSubpanelData($module)
    {
        if(!isset(self::$supanelData[$module])) {
            $layout_defs = null;
            if (file_exists("modules/{$module}/metadata/subpaneldefs.php")) {
                include "modules/{$module}/metadata/subpaneldefs.php";
            }
            if (file_exists("custom/modules/{$module}/metadata/subpaneldefs.php")) {
                include "custom/modules/{$module}/metadata/subpaneldefs.php";
            }
            if (file_exists("custom/modules/{$module}/Ext/Layoutdefs/layoutdefs.ext.php")) {
                include "custom/modules/{$module}/Ext/Layoutdefs/layoutdefs.ext.php";
            }
            if($layout_defs[$module]['subpanel_setup']) {
                self::$supanelData[$module] = $layout_defs[$module]['subpanel_setup'];
            } else {
                self::$supanelData[$module] = array();
            }
        }
    }

    public function setLegacyViewdefs()
    {
        $this->loadSubpanelData($this->module);
        $layout_defs = null;
        // no layoutdefs, nothing to upgrade
        if (empty(self::$supanelData[$this->module])) {
            return;
        }

        include $this->fullpath;
        if (empty($layout_defs[$this->module]['subpanel_setup'])) {
            return true;
        }
        $this->legacyViewdefs = $layout_defs[$this->module]['subpanel_setup'];
    }

    /**
     * Extract sidecar data from subpanel data
     * @param array $legacyDefs layoudefs definition
     * @param array $fullDefs full definition for subpanel
     * @param array $newdefs current sidecar definition
     * @return array New sidecar definition
     */
    protected function extractSidecarData($legacyDefs, $fullDefs, $newdefs = array())
    {
        unset($fullDefs['top_buttons']); // we don't care about the buttons for now
        $sidecarDef = $this->metaDataConverter->fromLegacySubpanelLayout($fullDefs);
        foreach ($fullDefs as $k => $v) {
            if (!empty($legacyDefs[$k]) && $legacyDefs[$k] == $v && !empty(self::$conversionKeys[$k])) {
                // take out the key we are trying to create
                if (self::$conversionKeys[$k] == 'link') {
                    $newdefs['context']['link'] = $sidecarDef['context']['link'];
                } else {
                    $newdefs[self::$conversionKeys[$k]] = $sidecarDef[self::$conversionKeys[$k]];
                }
            }
        }
        return $newdefs;
    }

    /**
     * Check if this module already has subpanel for relationship with another module
     * @param string $module Module name
     * @return boolean
     */
    protected function havePanel($module)
    {
        foreach (self::$supanelData[$this->module] as $key => $def) {
            if(!empty($def['module']) && $def['module'] == $module) {
                return true;
            }
        }
        return false;
    }

    /**
     * Convert Activity/History panels into set of separate panels
     */
    protected function convertActivityPanel()
    {
        $this->collection = true;
        $done_modules = array();
        $convertSubpanelDefs = array();
        $label_prefix = "LBL_".strtoupper($this->module)."_";
        foreach ($this->legacyViewdefs as $name => $def) {
            $convertSubpanelDefs[$name] = array_intersect_key($def, self::$conversionKeys);
        }

        foreach(array('activities', 'history') as $part) {
            foreach($this->legacyViewdefs[$part]['collection_list'] as $key => $actpanel) {
                if(empty($actpanel['module']) || isset($done_modules[$actpanel['module']])) {
                    continue;
                }
                if($this->havePanel($actpanel['module'])) {
                    // already have another panel for this module - skip it
                    $done_modules[$actpanel['module']] = true;
                    continue;
                }
                $this->logUpgradeStatus("Processing subpanel $key from $part");

                $paneldata = array_merge($convertSubpanelDefs[$part], $actpanel);
                $fullData = array_merge(self::$supanelData[$this->module][$part], $actpanel);
                unset($paneldata['collection_list']);
                unset($fullData['collection_list']);
                unset($paneldata['type']);
                unset($fullData['type']);
                $newdefs = $this->extractSidecarData($paneldata, $fullData);

                if (!empty($newdefs)) {
                    if(empty($newdefs['override_subpanel_list_view'])) {
                        $newdefs['layout'] = 'subpanel';
                    }
                    $ukey = strtoupper($key);
                    $newdefs['label'] = $label_prefix."{$ukey}_FROM_{$ukey}_TITLE";
                    $this->sidecarViewdefs[$key] = $newdefs;
                    $this->labels[$newdefs['label']] = $actpanel['module'];
                    $this->logUpgradeStatus("Adding subpanel $key from $part");
                }
            }
        }
        return true;
    }

    /**
     * This converts custom legacy subpanel layout defs to
     * the new style layoutdefs
     *
     * @param $module the module to convert all the subpanel layoutdefs for
     */
    public function convertLegacyViewDefsToSidecar()
    {
        if(empty($this->legacyViewdefs)) {
            return true;
        }

        // detect activity/history panel
        if(isset($this->legacyViewdefs['activities']['module'])
            && $this->legacyViewdefs['activities']['module'] == 'Activities'
            && isset($this->legacyViewdefs['activities']['collection_list'])
            && isset($this->legacyViewdefs['history']['module'])
            && isset($this->legacyViewdefs['history']['collection_list'])
            && self::$supanelData[$this->module]['history']['module'] == 'History') {
            $this->logUpgradeStatus("Activity upgrade for {$this->module}");
            return $this->convertActivityPanel();
        }

        foreach ($this->legacyViewdefs as $name => $def) {
            $convertSubpanelDefs[$name] = array_intersect_key($def, self::$conversionKeys);
        }

        if (empty($convertSubpanelDefs)) {
            // no workable defs
            return true;
        }

        $bean = BeanFactory::newBean($this->module);
        $newdefs = array();
        $allNewDefs = array();

        // find the subpaneldef that contains the $convertSubpanelDefs
        foreach (self::$supanelData[$this->module] as $key => $def) {
            // if no keys for this module, don't bother
            if (empty($convertSubpanelDefs[$key])) {
                continue;
            }

            // ignore the collection list subpanels for now
            if (!empty($def['collection_list'])) {
                unset($convertSubpanelDefs[$key]);
                continue;
            }

            // Ignore defs which contain only overrides, see BR-1597
            if (count(self::$supanelData[$this->module][$key]) == 1 && !empty(self::$supanelData[$this->module][$key]['override_subpanel_name'])) {
                unset($convertSubpanelDefs[$key]);
                continue;
            }

            // Skip subpanels that have links to non-existing modules
            // BR-3248, using full list
            if (!empty($def['module']) && !isset($GLOBALS['beanList'][$def['module']])) {
                unset($convertSubpanelDefs[$key]);
                $this->logUpgradeStatus(
                    "Skipping subpanel $key in {$this->module} module. Linked module '{$def['module']}' does not exist"
                );
                continue;
            }

            // Type function in "get_subpanel_data" is no longer supported in Sugar 7.x,
            // but if necessary better to handle it here.
            if (!empty($def['get_subpanel_data'])) {
                $fieldDef = $bean->getFieldDefinition($def['get_subpanel_data']);
                if (!$fieldDef) {
                    $this->logUpgradeStatus("Skipping subpanel $key in {$this->module} module.");
                    continue;
                }
            }

            $newdefs = $this->extractSidecarData($convertSubpanelDefs[$key], $def, $newdefs);

            if (!empty($newdefs)) {
                if (empty($newdefs['override_subpanel_list_view'])) {
                    $newdefs['layout'] = 'subpanel';
                }
                $allNewDefs[$key] = $newdefs;
            }

        }

        if (!empty($allNewDefs)) {
            if (sizeof($allNewDefs) > 1) {
                $this->sidecarViewdefs = $allNewDefs;
                $this->collection = true;
            } else {
                $this->sidecarViewdefs = current($allNewDefs);
                $this->collection = false;
            }
        }

    }

    /**
     * Write out the new subpanel layout def
     */
    public function handleSave()
    {
        if (isset($this->sidecarViewdefs['override_subpanel_list_view']['view'])
            && isset($this->sidecarViewdefs['override_subpanel_list_view']['link'])
            && isset(self::$supanelData[$this->module][$this->sidecarViewdefs['override_subpanel_list_view']['link']])
        ) {
            $subpanelView = $this->sidecarViewdefs['override_subpanel_list_view']['view'];
            $subpanelLink = self::$supanelData[$this->module][$this->sidecarViewdefs['override_subpanel_list_view']['link']];
            
            $fileName = "modules/{$subpanelLink['module']}/clients/{$this->client}/views/{$subpanelView}/{$subpanelView}.php";
            $subpanelFile = "modules/{$subpanelLink['module']}/metadata/subpanels/{$subpanelLink['override_subpanel_name']}.php";

            //If no file can be found for either the bwc or sidecar version of override subpanel name, do not save the override
            if (!SugarAutoLoader::existingCustomOne($fileName, $subpanelFile)) {
                unset($this->sidecarViewdefs);
                return true;
            }
        }
        
        if($this->collection) {
            $allviewdefs = $this->sidecarViewdefs;

            foreach($allviewdefs as $key => $subpanel) {
                $this->sidecarViewdefs = $subpanel;
                if(!$this->handleSaveArray("viewdefs['{$this->module}']['{$this->client}']['layout']['subpanels']['components'][]",
                    "custom/Extension/modules/{$this->module}/Ext/clients/{$this->client}/layouts/subpanels/" . "{$key}_".basename($this->fullpath))) {
                    return false;
                }
            }
            // save labels for subpanels
            if(!empty($this->labels)) {
                $languages = get_languages();
                $enstrings = return_app_list_strings_language("en_us");
                foreach($languages as $langKey => $langName) {
                    $strings = return_app_list_strings_language($langKey);
                    $reslabels = array();
                    foreach($this->labels as $label => $module) {
                        if(!empty($strings['moduleList'][$module])) {
                            $reslabels[$label] = $strings['moduleList'][$module];
                        } elseif ($enstrings['moduleList'][$module]) {
                            $reslabels[$label] = $enstrings['moduleList'][$module];
                        } else {
                            $reslabels[$label] = $module;
                        }
                    }
                    $labeldata = "<?php\n";
                    foreach($reslabels as $label => $str) {
                        $labeldata .= "\$mod_strings['$label'] = ".var_export($str, true).";\n";
                    }
                    file_put_contents("custom/Extension/modules/{$this->module}/Ext/Language/{$langKey}.{$this->client}_".basename($this->fullpath), $labeldata);
                }
            }

            return true;
        } else {
            return $this->handleSaveArray("viewdefs['{$this->module}']['{$this->client}']['layout']['subpanels']['components'][]",
                "custom/Extension/modules/{$this->module}/Ext/clients/{$this->client}/layouts/subpanels/" . basename($this->fullpath));
        }
    }
}

