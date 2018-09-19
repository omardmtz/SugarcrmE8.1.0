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
 * Remove the possibility of disabling/uninstalling the web upgrader after the upgrade
 */
class SugarUpgradeLockWebUpgrader extends UpgradeScript
{
    public $order = 9990;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        $upgrader = $this->db->getOne("SELECT id FROM upgrade_history WHERE name='SugarCRM Upgrader 2.0' AND type='module' AND status='installed'");
        if (!empty($upgrader)) {
            $this->log("Making Sugar Web Upgrader not uninstallable");
            $this->db->query("UPDATE upgrade_history SET filename='' WHERE id=".$this->db->quoted($upgrader));
        }

    }
}
