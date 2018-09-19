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

class SugarUpgradeFixTextAreaFields extends UpgradeScript
{
    public $order = 2300;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // are we coming from anything before 7.0?
        if (!version_compare($this->from_version, '7.0', '<')) {
            return;
        }
        
        $this->log("Fixing 6.x custom text area fields.");
        $sql = "UPDATE fields_meta_data " .
               "SET ext1 = '', " .
                   "ext4 = '', " .
                   "default_value = '' " .
               "WHERE type = 'text' " .
                   "AND ext1 is null " .
                   "AND ext4 is null";
        
        $r = $this->db->query($sql);
        $this->log("Updated " . $this->db->getAffectedRowCount($r) . " Rows");
        $this->log("Done Fixing 6.x custom text area fields.");
    }
}
