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
 * Update modification time of custom/include/language/* files to preserve pre 7.6 lang files customizations hierarchy
 */
class SugarUpgradeTouchStudioLanguageCustomizations extends UpgradeScript
{
    public $order = 9999;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.6', '>=')) {
            return;
        }

        foreach (glob('custom/include/language/*.lang.php') as $file) {
            if (is_dir($file)) {
                continue;
            }

            sugar_touch($file);
        }
    }
}
