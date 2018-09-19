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
 * Class SugarUpgradeDropPDFHeaderLogo
 *
 * Drop header_logo_url from pdfmanager table
 */
class SugarUpgradeDropTrackerSessionsRoundTrips extends UpgradeScript
{
    public $order = 7490;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.7';

    public function run()
    {
        if (!version_compare($this->from_version, '7.7', '<')) {
            return;
        }

        $sql = 'ALTER TABLE tracker_sessions DROP COLUMN round_trips';
        if ($this->db->query($sql)) {
            $this->log('Removed round_trips from tracker_sessions table');
        } else {
            $this->log('Failed to remove round_trips from tracker_sessions table');
        }
    }
}
