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
 * Remove WebUpgrader useless files.
 */
class SugarUpgradeRemoveWebUpgrader extends UpgradeScript
{
    public $order = 8500;

    public $type = self::UPGRADE_CUSTOM;

    protected $filesToRemove = array(
        'custom/Extension/application/Ext/Language/en_us.HealthCheck.php',
        'custom/modules/UpgradeWizard/language',
        'custom/Extension/modules/Administration/Ext/Administration/upgrader2.php',
        'custom/Extension/modules/Administration/Ext/Administration/healthcheck.php',
    );

    public function run()
    {
        require_once 'ModuleInstall/PackageManager/PackageManager.php';
        $pm = new PackageManager();
        $packages = $pm->getinstalledPackages(array('module'));
        foreach ($packages as $pack) {
            if (strpos($pack['name'], 'SugarCRM Upgrader') !== false) {
                $uh = new UpgradeHistory();
                $uh->name = $pack['name'];
                $history = $uh->checkForExisting($uh);
                $this->filesToRemove[] = "custom/Extension/application/Ext/Include/{$history->id_name}.php";
                $history->delete();
                $this->upgrader->fileToDelete($this->filesToRemove, $this);
                $this->log("Useless files of {$pack['name']} v{$pack['version']} removed");
            }
        }
        foreach ($pm->getPackagesInStaging() as $pack) {
            if (strpos($pack['name'], 'SugarCRM Upgrader') !== false) {
                $file = UploadStream::path(hashToFile($pack['file']));
                $this->upgrader->fileToDelete($file, $this);
                foreach (array('manifest', 'icon') as $meta) {
                    $this->upgrader->fileToDelete(
                        pathinfo($file, PATHINFO_DIRNAME) . '/' . pathinfo($file, PATHINFO_FILENAME) . "-$meta.php",
                        $this
                    );
                }
            }
        }
    }
}
