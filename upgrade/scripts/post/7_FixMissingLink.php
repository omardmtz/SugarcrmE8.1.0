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
* Class SugarUpgradeFixMissingLabels
*
* Add missing link for Accounts Email subpanel
*/
class SugarUpgradeFixMissingLink extends UpgradeScript
{
    public $order = 7410;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.2';

    public function run()
    {
        if (version_compare($this->from_version, '7.2.0', '<') || version_compare($this->from_version, '7.2.2', '>=')) {
            // only need to run this upgrading from 7.2.0 or 7.2.1
            return;
        }

        $file = 'custom/modules/Emails/clients/base/views/subpanel-for-accounts/subpanel-for-accounts.php';
        if (!file_exists($file)) {
            return;
        }

        require $file;

        $rewrite = false;

        if (isset($viewdefs['Emails']['base']['view']['subpanel-for-accounts']['panels'][0]['fields'])) {
            foreach ($viewdefs['Emails']['base']['view']['subpanel-for-accounts']['panels'][0]['fields'] as &$field) {
                if (isset($field['name']) && $field['name'] == 'name') {
                    if (!isset($field['link'])) {
                        $field['link'] = true;
                        $rewrite = true;
                    }
                }
            }
        }
        if ($rewrite) {
            $this->log('Adding link=true to subpanel-for-accounts.php');
            write_array_to_file("viewdefs['Emails']['base']['view']['subpanel-for-accounts']", $viewdefs['Emails']['base']['view']['subpanel-for-accounts'], $file);
        }
    }
}

