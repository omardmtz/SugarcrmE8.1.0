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
 * Drop name from styleguide table
 */
class SugarUpgradeDropStyleguideName extends UpgradeScript
{
    public $order = 7490;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!version_compare($this->from_version, '7.8.0.0', '<')) {
            return;
        }

        $index = array(
            'name' => 'idx_styleguide_name_del',
            'type' => 'index',
            'fields' => array(),
        );
        $this->db->dropIndexes('styleguide', array($index));

        $sql = 'ALTER TABLE styleguide DROP COLUMN name';
        if ($this->db->query($sql)) {
            $this->log('Removed name from styleguide table');
        } else {
            $this->log('Failed to remove name from styleguide table');
        }
    }
}
