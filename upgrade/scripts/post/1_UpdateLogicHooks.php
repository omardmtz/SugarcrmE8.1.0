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

SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');

/**
 * Upgrade script to update the compiled logic hooks extension file
 */
class SugarUpgradeUpdateLogicHooks extends UpgradeScript
{
    /**
     * Order is important here. This needs to run after the var def clear and FTS
     * settings updater have been run.
     * @var integer
     */
    public $order = 1100;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * @inheritDoc
     */
    public function run()
    {
        // We only need to run this if coming from 7.8rc2 or below
        if (version_compare($this->from_version, '7.8.0.0RC3', '<')) {
            $this->handleLogicHookUpdate();
        }
    }

    /**
     * Handles updating the logic hook extension files, since some logic hooks
     * have changes since the last version
     */
    protected function handleLogicHookUpdate()
    {
        $this->log("Update Logic Hooks: setting LogicHook to refresh its internal cache");

        // First things first, clear out the logic hook internal cache
        LogicHook::refreshHooks();

        // Now rebuild the logic hooks extension
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        $mi = new $moduleInstallerClass();

        // So that there is no output when logging happens
        $mi->silent = true;

        $this->log("Update Logic Hooks: preparing to rebuild the logic hooks extension...");
        $mi->rebuild_extensions(array(), array('logichooks'));
        $this->log("Update Logic Hooks: logic hooks extension rebuilt.");

        // And now a simple test...
        $this->testSuccess(true, true);

        // And one more time without the cache refresh
        $this->testSuccess(true);
    }

    /**
     * Runs a simple test to see if the work in run() took. This simply logs a failure.
     * @param boolean $log Flag that determines if this should log or not
     * @param boolean $refresh If true, forces a refresh on the LogicHooks get hook call
     */
    protected function testSuccess($log = true, $refresh = false)
    {
        static $callCount = 1;

        // Flags for determing if this needs failure logging
        $fail = false;

        // Now lets see if the LogicHook class is giving us the right data...
        $lh = new LogicHook();

        // Send true here to force a refresh. Better safe than sorry in an upgrader.
        $hooks = $lh->getHooks('', $refresh);
        if (isset($hooks['before_save'])) {
            foreach ($hooks['before_save'] as $hook) {
                if ($hook[1] === 'pmse') {
                    // We found a before save PMSE hook here, so mark it and bail
                    $fail = true;
                    break;
                }
            }
        }

        if ($fail && $log) {
            // Needed to make sure lines don't get too long for the PSR2 checker
            $pre = "Update Logic Hooks: Try #{$callCount}:";
            $msg = "$pre after upgrade, before_save PMSE hook still found in LogicHook::getHooks.";
            $this->log($msg);
        }

        $callCount++;
    }
}
