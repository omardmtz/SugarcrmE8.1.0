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
if(!file_exists('modules/UpgradeWizard/SidecarUpdate/SidecarMetaDataUpgrader.php')) return;

/**
 * Upgrade sidecar portal metadata
 */
class SugarUpgrade6xToSidecar extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if(version_compare($this->from_version, '7.0', '>=')) {
            // right now there's no need to run this on 7
            return;
        }

        if(!file_exists('modules/UpgradeWizard/SidecarUpdate/SidecarMetaDataUpgrader.php')) return;
        // TODO: fix uw_utils references in SidecarMetaDataUpgrader
        $smdUpgrader = new SidecarMetaDataUpgrader2($this);
        $smdUpgrader->upgrade();

        // Log failures if any
        $failures = $smdUpgrader->getFailures();
        if (!empty($failures)) {
            $this->log('Sidecar Upgrade: ' . count($failures) . ' metadata files failed to upgrade through the silent upgrader:');
            $this->log(print_r($failures, true));
        } else {
            $this->log('Sidecar Upgrade: Mobile/portal metadata upgrade ran with no failures:');
            $this->log($smdUpgrader->getCountOfFilesForUpgrade() . ' files were upgraded.');
        }
        $this->upgrader->fileToDelete(SidecarMetaDataUpgrader::getFilesForRemoval(), $this);
    }
}

/**
 * Decorator class to override logging behavior of SidecarMetaDataUpgrader
 */
class SidecarMetaDataUpgrader2 extends SidecarMetaDataUpgrader
{
    public function __construct($upgrade)
    {
        $this->upgrade = $upgrade;
    }

    public function logUpgradeStatus($msg)
    {
        $this->upgrade->log($msg);
    }

    public function getMBModules()
    {
        if(!empty($this->upgrade->state['MBModules'])) {
            return $this->upgrade->state['MBModules'];
        }
        return array();
    }
}
