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
 * Update acl_roles_actions.access_override values to actual ACL_ALLOW_SELECTED_TEAMS value.
 */
class SugarUpgradeUpdateTBAConstant extends UpgradeScript
{
    public $order = 9999;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // this upgrade script is for 7.8RC1->7.8RC3 only
        if (version_compare($this->from_version, '7.8.0.0RC1', '==')
                && version_compare($this->to_version, '7.8.0.0RC3', '==')) {
            $this->db->query(
                "UPDATE acl_roles_actions SET access_override = 78 WHERE access_override = 72"
            );
        }
        return;
    }
}
