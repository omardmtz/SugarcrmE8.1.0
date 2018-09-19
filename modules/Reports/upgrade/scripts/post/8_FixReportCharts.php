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
 * Upgrades chartEngine to `nvd3` while updating to Sugar7.
 */
class SugarUpgradeFixReportCharts extends UpgradeScript
{
    public $order = 8400;

    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.10', '<')) {
            $this->upgrader->config['chartEngine'] = 'sucrose';
            $this->log('Chart engine changed to Sucrose');
        }
    }
}
