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
 * Check current version of upgrade driver
 */
class SugarUpgradeCheckUpgrader extends UpgradeScript
{
    public $order = 50;
    public $type = self::UPGRADE_ALL;
    const ALLOWED_UPGRADER_VERSION = '7.7.0.0';

    public function run()
    {
        $this->log("Check Upgrade driver version");
        if (empty($this->context['versionInfo'][0]) ||
            version_compare($this->context['versionInfo'][0], self::ALLOWED_UPGRADER_VERSION, '<')
        ) {
            return $this->error(
                'Unsupported Upgrader version. Please install the appropriate SugarUpgradeWizardPrereq package'
            );
        }
        return true;
    }
}
