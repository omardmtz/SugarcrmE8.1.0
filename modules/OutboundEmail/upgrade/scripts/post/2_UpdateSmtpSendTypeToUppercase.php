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

class SugarUpgradeUpdateSmtpSendTypeToUppercase extends UpgradeDBScript
{
    public $order = 2200;

    /**
     * {@inheritdoc}
     *
     * Update Outbound Email Records to uppercase all mail_sendtype field values from 'smtp' to 'SMTP'
     *
     * This upgrade script only runs when upgrading from a version prior to 7.10.
     */
    public function run()
    {
        // are we coming from anything before 7.10?
        if (!version_compare($this->from_version, '7.10', '<')) {
            return;
        }

        $this->log('Updating mail_sendtype in outbound_email to uppercase to SMTP');

        $sql = "UPDATE outbound_email SET mail_sendtype = ? WHERE mail_sendtype = ?";
        $this->executeUpdate($sql, ['SMTP', 'smtp']);
    }
}
