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


class SugarUpgradeRemoveMyForecastingChartDashlet extends UpgradeScript
{
    public $order = 7999;
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        if (file_exists(
            'modules/Charts/Dashlets/MyForecastingChartDashlet/MyForecastingChartDashlet.php'
        )) {
            $this->upgrader->fileToDelete('modules/Charts/Dashlets/MyForecastingChartDashlet', $this);
        }
    }
}
