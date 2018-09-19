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

class SugarUpgradeAccountUpdateDeletedRelationships extends UpgradeScript
{
    public $order = 2195;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        $this->log('Updating accounts_opportunities Deleted Status on Deleted Accounts');
        $sql = "UPDATE accounts_opportunities
                SET    deleted = 1
                WHERE
                       deleted = 0 AND
                       account_id IN ( SELECT id FROM accounts WHERE deleted = 1 )";
        $this->db->query($sql);

        $this->log('Done Updating accounts_opportunities Deleted Status on Deleted Accounts');
    }
}
