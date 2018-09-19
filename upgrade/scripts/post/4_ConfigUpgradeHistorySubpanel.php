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
 * Update config entries for History Supanel admin tool
 */
class SugarUpgradeConfigUpgradeHistorySubpanel extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        // only do it when going to 7.2+
        if (!version_compare($this->from_version, '7.2', '<')) return;

        $defaults = array(
            'hide_history_contacts_emails' => array (
                'Cases' => false,
                'Accounts' => false,
                'Opportunities' => true
            ),
        );

        foreach ($defaults as $key => $values) {
            if (!isset($this->upgrader->config[$key])) {
                $this->upgrader->config[$key] = $values;
            }
        }
    }
}
