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
class SidecarQuickcreateMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    // don't delete old files
    public $deleteOld = false;

    /**
     * Is this module enabled in DCActions?
     * @var bool
     */
    public $isDCEnabled = true;

    /**
     * @var int Index of this item in the list.
     */
    public $order = 0;

    public function upgradeCheck()
    {
        $viewdefs = array();

        if ($this->filename === 'quickcreatedefs.php') {
            // old quickcreate defs file, rejecting it.
            return false;
        }

        if (file_exists("custom/{$this->fullpath}")) {
            include "custom/{$this->fullpath}";
        } elseif (file_exists($this->fullpath)) {
            include $this->fullpath;
        }

        $hasVisibleProperty = !empty($viewdefs[$this->module]['base']['menu']['quickcreate']) &&
            !empty($viewdefs[$this->module]['base']['menu']['quickcreate']['layout']) &&
            isset($viewdefs[$this->module]['base']['menu']['quickcreate']['visible']);

        $qcVisibleMatch = $hasVisibleProperty &&
            $viewdefs[$this->module]['base']['menu']['quickcreate']['visible'] == $this->isDCEnabled;

        $qcOrderMatch = $hasVisibleProperty &&
            isset($viewdefs[$this->module]['base']['menu']['quickcreate']['order']) &&
            $viewdefs[$this->module]['base']['menu']['quickcreate']['order'] === $this->order;

        if ($qcVisibleMatch && $qcOrderMatch) {
            // no upgrade needed
            return false;
        }

        return true;
    }

    public function setLegacyViewdefs()
    {
        $viewdefs = array();
        // require the file
        if (file_exists("custom/{$this->fullpath}")) {
            include "custom/{$this->fullpath}";
        } elseif (file_exists($this->fullpath)) {
            include $this->fullpath;
        } else {

            // Labels for Quickcreate Menu are of type
            // `LBL_NEW_{MODULE_NAME_SINGULAR}`

            // Some modules like `Project` are already singular
            // For other modules, we need to remove the `s` char from the
            // module name. Note that this is obviously not ideal

            // We are first verifying that this label exists. Custom modules
            // won't have this label, they will have `LNK_NEW_RECORD` instead.
            // In case this doesn't even exist, we fall back to the module name
            // singular
            $moduleNameSingular = $this->module;
            if (substr($moduleNameSingular, -1) === 's') {
                $moduleNameSingular = substr($this->module, 0, -1);
            }
            $quickcreateLabel = 'LNK_NEW_' . strtoupper($moduleNameSingular);
            $testLabelExists = translate($quickcreateLabel, $this->module);
            if ($quickcreateLabel === $testLabelExists) {
                //if strings are the same, it means label does not exist
                $quickcreateLabel = 'LNK_NEW_RECORD';
                $testLabelExists = translate($quickcreateLabel, $this->module);
                if ($quickcreateLabel === $testLabelExists) {
                    //if strings are the same, it means label does not exist
                    $quickcreateLabel = 'LBL_MODULE_NAME_SINGULAR';
                }
            }
            $viewdefs[$this->module]['base']['menu']['quickcreate'] = array(
                'layout' => 'create',
                'label' => $quickcreateLabel,
                'icon' => 'fa-plus',
            );
        }

        $viewdefs[$this->module]['base']['menu']['quickcreate']['visible'] = $this->isDCEnabled;
        $viewdefs[$this->module]['base']['menu']['quickcreate']['order'] = $this->order;

        $this->legacyViewdefs = $viewdefs;
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
        $this->sidecarViewdefs = $this->legacyViewdefs[$this->getNormalizedModuleName()]['base']['menu']['quickcreate'];
    }

    /**
     * Write out the new subpanel layout def
     */
    public function handleSave()
    {
        return $this->handleSaveArray("viewdefs['{$this->getNormalizedModuleName()}']['base']['menu']['quickcreate']",
            "custom/{$this->fullpath}");
    }
}

