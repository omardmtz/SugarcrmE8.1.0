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
 * Create Forecasting settings
 */
class SugarUpgradeForecastsSetup extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!$this->toFlavor('pro')) {
            return;
        }

        if (!version_compare($this->from_version, '7.0', "<")) {
            return;
        }

        ForecastsDefaults::setupForecastSettings(true, $this->from_version, $this->to_version);
        ForecastsDefaults::upgradeColumns();
    }
}
