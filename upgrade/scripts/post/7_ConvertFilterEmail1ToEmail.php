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
class SugarUpgradeConvertFilterEmail1ToEmail extends UpgradeScript
{
    public $order = 7500;
    public $type = self::UPGRADE_DB;
    
    public function run()
    {
        if (version_compare($this->from_version, '7.6', '>=')) {
            return;
        }

        $this->db->query(sprintf(
            "UPDATE filters SET filter_definition = REPLACE(filter_definition, %s, %s), filter_template = REPLACE(filter_template, %s, %s)", 
            $this->db->quoted('"email1"'),
            $this->db->quoted('"email"'),
            $this->db->quoted('"email1"'),
            $this->db->quoted('"email"')
        ));
        
    }
}
