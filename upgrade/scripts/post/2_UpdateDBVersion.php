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
 * Update sugar_version in the config table
 */
class SugarUpgradeUpdateDBVersion extends UpgradeScript
{
    public $order = 2000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
	    $this->log('Deleting old DB version info from config table');
	    $this->db->query("DELETE FROM config WHERE category = 'info' AND name = 'sugar_version'");

        $this->log('Inserting updated version info into config table');
    	$this->db->query("INSERT INTO config (category, name, value) VALUES ('info', 'sugar_version', '{$this->to_version}')");
    }
}
