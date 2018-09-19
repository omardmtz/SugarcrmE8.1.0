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

class SugarUpgradeOpportunitySetViewBy extends UpgradeScript
{
    public $order = 2100;
    public $version = '7.6.0.0';
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // get the opportunity settings
        $settings = Opportunity::getSettings();

        // if this key is not setup, then there is a 99.99% chance that it has never been set,
        // so it should be setup.
        if (!isset($settings['opps_view_by']) || empty($settings['opps_view_by'])) {
            SugarAutoLoader::load('modules/Opportunities/OpportunitiesDefaults.php');
            OpportunitiesDefaults::setupOpportunitiesSettings();
            // reload the settings now
            Opportunity::getSettings(true);
        }
    }
}
