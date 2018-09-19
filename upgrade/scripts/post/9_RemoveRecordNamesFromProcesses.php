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
 * Remove the statically stored record name from the pmse_Inbox table.
 */
class SugarUpgradeRemoveRecordNamesFromProcesses extends UpgradeScript
{
    /**
     * {@inheritdoc }
     * @var int
     */
    public $order = 9000;

    /**
     * {@inheritdoc }
     * @var int
     */
    public $type = self::UPGRADE_DB;

    /**
     * Disabled all processes with errors
     */
    protected function removeRecordNames()
    {
        $result = $this->db->query('UPDATE pmse_inbox SET name = NULL, cas_title = NULL');
        if ($result) {
            $this->log('Removed stored record names from the AWF processes.');
        } else {
            $this->log('Failed to remove stored record names from the AWF processes -- ' . $this->db->lastError() . '.');
        }
    }

    /**
     * {@inheritdoc }
     */
    public function run()
    {
        if (version_compare($this->from_version, '8.0.0', '<') &&
            version_compare($this->to_version, '8.0.0', '>=')) {
            $this->removeRecordNames();
        }
    }
}
