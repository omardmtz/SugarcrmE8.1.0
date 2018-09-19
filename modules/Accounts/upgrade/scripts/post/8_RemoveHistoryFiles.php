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
 * Remove history related files/directories
 */
class SugarUpgradeRemoveHistoryFiles extends UpgradeScript
{
    public $order = 8000;
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        // must be upgrading from 6.7.5+
        if (!version_compare($this->from_version, '6.7.4', '>') || !version_compare($this->from_version, '7.2.0', '<')) {
            return;
        }
        // can be files or directories
        $this->upgrader->fileToDelete('modules/Accounts/clients/base/views/history', $this);
    }
}
