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
 * Remove unsupported modules described in HealthCheckScanner::unsupportedModules.
 * @see HealthCheckScanner::unsupportedModules
 */
class SugarUpgradeRemoveModules extends UpgradeScript
{
    public $order = 8600;

    public $type = self::UPGRADE_ALL;

    protected $modules = array(
        'iFrames',
        'Feeds',
    );

    public function run()
    {
        if (version_compare($this->from_version, 7, '<')) {
            $path = 'custom/Extension/application/Ext/Include/';
            sugar_mkdir($path . 'Disabled/', null, true);
            foreach ($this->modules as $module) {
                $file = $module . '.php';
                if (file_exists($path . $file)) {
                    sugar_rename($path . $file, $path . "Disabled/" . $file);
                    $this->log("Unsupported module {$module} disabled");
                }
            }
        }
    }
}
