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
 * Remove SugarParsers and ReportBuilder as they are no longer used by Forecasting
 */
class SugarUpgradeSugarParsersDelete extends UpgradeScript
{
    public $order = 7000;
    public $version = '7.0.0';
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        $files = array('include/SugarParsers',
            'include/SugarCharts/ReportBuilder.php');
        $this->upgrader->fileToDelete($files, $this);
    }
}
