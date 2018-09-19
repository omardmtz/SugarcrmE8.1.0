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
 * This upgrader renames the custom Dashboards record view file
 * if it exists. Upgrading from previous versions may have resulted
 * in a custom Dashboards record view being created, but we're not
 * using it for anything and it overrides the base Dashboards
 * record view.
 *
 * Rename it instead of deleting it in case a customized instance
 * was using the custom Dashboards record view for some other purpose.
 */
class SugarUpgradeRenameCustomDashboardRecord extends UpgradeScript
{
    public $order = 7900;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.10', '>=')) {
            return;
        }

        $oldFilename = 'custom/modules/Dashboards/clients/base/views/record/record.php';

        if (!file_exists($oldFilename)) {
            return;
        }

        sugar_rename(
            $oldFilename,
            $oldFilename . '.bak'
        );
    }
}
