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
 * Merge viewdefs files between old and new code
 */
class SugarUpgradeMergeTemplates extends UpgradeScript
{
    public $order = 400;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (empty($this->context['new_source_dir'])) {
            $this->log("**** Merge skipped - no new source dir");
            return;
        }
        $this->log("**** Merge started ");
        if (file_exists($this->context['new_source_dir'] . '/modules/UpgradeWizard/SugarMerge/SugarMerge.php')) {
            $this->mergeWithNewClasses();
        } else {
            $this->mergeWithExistingClasses();
        }
        $this->log("**** Merge finished ");
    }

    protected function mergeWithNewClasses()
    {
        $this->log("**** Using new merge classes");
        require_once($this->context['new_source_dir'] . '/modules/UpgradeWizard/SugarMerge/SugarMerge.php');
        if ($this->loadSugarMerge7()) {
            $merger = new SugarMerge7($this->context['new_source_dir'], '', 'custom', true);
            $merger->setUpgrader($this->upgrader);
            $merger->mergeAll();
        }
    }

    protected function mergeWithExistingClasses()
    {
        $this->log("**** Using old merge classes");
        if ($this->loadSugarMerge7()) {
            $merger = new SugarMerge7($this->context['new_source_dir']);
            $merger->mergeAll();
        }
    }

    protected function loadSugarMerge7() {
        if (file_exists($this->context['new_source_dir'] . '/modules/UpgradeWizard/SugarMerge/SugarMerge7.php')) {
            require_once($this->context['new_source_dir'] . '/modules/UpgradeWizard/SugarMerge/SugarMerge7.php');
        } else {
            if (file_exists('modules/UpgradeWizard/SugarMerge/SugarMerge7.php')) {
            } else {
                return $this->error('SugarMerge7.php not found, this file is required for Sugar7 Upgrades', true);
            }
        }
        return true;
    }
}
