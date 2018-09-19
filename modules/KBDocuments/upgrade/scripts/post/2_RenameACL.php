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
 * Move old KBDocuments ACL to new one.
 */
class SugarUpgradeRenameACL extends UpgradeScript
{
    public $order = 2001;
    public $type = self::UPGRADE_DB;
    public $version = '7.5';

    public function run()
    {
        if (version_compare($this->from_version, '7.7.0', '<')) {
            $this->db->query("UPDATE acl_actions set category = 'KBContents' where category = 'KBDocuments'");
        }
    }
}
