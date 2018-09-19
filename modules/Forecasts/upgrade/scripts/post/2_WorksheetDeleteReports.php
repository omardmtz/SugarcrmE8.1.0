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
 * Since we remove the Worksheet bean in 8_ForecastRemoveFiles, we should mark all the reports
 * that are generated off the worksheet module as deleted
 */
class SugarUpgradeWorksheetDeleteReports extends UpgradeScript
{
    public $order = 2191;
    public $type = self::UPGRADE_DB;

    public function run()
    {

        // we only need to remove these files if the from_version is less than 7.0 but greater or equal than 6.7.0
        if (version_compare($this->from_version, '7.0', '<')) {
            $sql = "UPDATE saved_reports SET deleted = 1 WHERE module = 'Worksheet'";
            $this->db->query($sql);
        }
    }
}
