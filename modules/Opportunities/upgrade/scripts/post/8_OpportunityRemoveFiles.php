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
 * Removes files that are no longer valid in 7.0 for the Opportunities module.
 */
class SugarUpgradeOpportunityRemoveFiles extends UpgradeScript
{
    public $order = 8501;
    public $type = self::UPGRADE_CORE;

    public function run()
    {

        $files = array();
        // we only need to remove these files if the from_version is less than 7.0.0 but greater or equal than 6.7.0
        if (version_compare($this->from_version, '7.0.0', '<')
            && version_compare($this->from_version, '6.7', '>=')
        ) {
            // files to delete
            $files = array(
                'modules/Opportunities/clients/base/views/forecastInspector/forecastInspector.hbt',
                'modules/Opportunities/clients/base/views/forecastInspector/forecastInspector.js',
                'modules/Opportunities/clients/base/views/forecastInspector/forecastInspector.php',
                'modules/Opportunities/Ext/LogicHooks/OpportunitySetCurrencyToBase.php',
            );

        }

        if (version_compare($this->to_version, '7.6', '<=')) {
            $files[] = 'modules/Opportunities/upgrade/scripts/post/2_OpportunityCreateRLI.php';
        }

        $this->upgrader->fileToDelete($files, $this);
    }
}
