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
 * Keep only Ext/LogicHooks/fts.php. Old logic hooks will be deleted
 */
require_once 'include/utils/file_utils.php';

class SugarUpgradeFTSHook extends UpgradeScript
{
    public $order = 5000;
    public $type = self::UPGRADE_CUSTOM;

    protected $mainHookFile = 'Ext/LogicHooks/fts.php';

    protected $oldHookDefs = array(
        'application/Ext/LogicHooks/logichooks.ext.php',
        'Extension/application/Ext/LogicHooks/SugarFTSHooks.php',
    );

    public function run()
    {
        if ($this->fileExists($this->mainHookFile)) {
            $this->removeDuplicates();
        } else {
            $this->log('Error: Main FTS hook file ' . $this->mainHookFile . ' missing.');
        }
    }

    /**
     * Removing dublicates fts logic hook
     */
    protected function removeDuplicates()
    {
        foreach ($this->oldHookDefs as $defPath) {
            $this->upgrader->fileToDelete($defPath, $this);
        }
    }

    /**
     * @param $defPath
     * @return bool
     */
    protected function fileExists($defPath)
    {
        return file_exists($defPath);
    }
}
