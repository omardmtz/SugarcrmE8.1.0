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
 * Switch to new WebUpgrader from the package to force the Upgrade Wizard to use new driver instead of original.
 */
class SugarUpgradeSwitchWebUpgrader extends UpgradeScript
{
    public $order = 300;
    public $version = '7.7.0';

    public function run()
    {
        if (empty($_SESSION['upgrade_dir']) || empty($this->manifest['copy_files']['from_dir'])) {
            return;
        }

        if (!is_file($_SESSION['upgrade_dir'] . 'modules/UpgradeWizard/UpgradeDriver.php'))
        {
            return;
        }
        $this->log('Switch to WebUpgrader from the package.');
        // The UpgradeWizard closes session, session_status() check should be added when no instances
        // with PHP less 5.4 exist to avoid an error notice when session_start is called twice.
        session_start();

        $_SESSION['upgrade_dir'] = $this->context['extract_dir'] . '/' .
            $this->manifest['copy_files']['from_dir'] . '/' . 'modules/UpgradeWizard/';

        session_write_close();
    }

}
