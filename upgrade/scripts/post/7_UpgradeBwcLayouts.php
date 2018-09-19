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
 * Upgrade BWC layouts to Sidecar
 */
class SugarUpgradeUpgradeBwcLayouts extends UpgradeScript
{
    public $order = 7001;
    public $type = self::UPGRADE_CUSTOM;
    public $sidecarMetaDataUpgraderBwcUpgrader;

    public function run()
    {
        if (empty($this->upgrader->state['bwc_modules'])) {
            $this->log('BWC modules are not registered by pre-upgrade script.');

            return null;
        }

        $oldBwcModules = $this->upgrader->state['bwc_modules'];
        $newBwcModules = $this->getBwcModules();

        $modulesToUpgrade = array_diff($oldBwcModules, $newBwcModules);
        if (!$modulesToUpgrade) {
            $this->log('Nothing to upgrade. Exiting.');

            return null;
        }

        $this->prepareBwcUpgraderModules($modulesToUpgrade);
        $this->runBwcUpgraderModules();
    }

    protected function prepareBwcUpgraderModules($modulesToUpgrade)
    {
        $this->sidecarMetaDataUpgraderBwcUpgrader = new SidecarMetaDataUpgraderBwc($modulesToUpgrade);
    }

    protected function runBwcUpgraderModules()
    {
        $this->sidecarMetaDataUpgraderBwcUpgrader->upgrade();
    }

    protected function getBwcModules()
    {
        $bwcModules = array();
        include 'include/modules.php';

        return $bwcModules;
    }

}

/**
 * Metadata upgrader which upgrades the metadata of only specified modules
 */
class SidecarMetaDataUpgraderBwc extends SidecarMetaDataUpgrader
{
    protected $modules;

    public function __construct(array $modules)
    {
        $this->modules = $modules;
    }

    public function getMBModules()
    {
        return array();
    }

    public function setQuickCreateFiles()
    {
    }

    public function upgrade()
    {
        if ($this->modules) {
            foreach ($this->modules as $module) {
                $this->prepareUpgradeOneModule($module);
                $this->upgradeOneModule();
            }
        }
    }

    public function prepareUpgradeOneModule($module)
    {
        $this->setModule($module);
    }

    public function upgradeOneModule()
    {
        parent::upgrade();
    }
}
