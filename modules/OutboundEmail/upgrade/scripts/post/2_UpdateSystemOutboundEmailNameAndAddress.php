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

class SugarUpgradeUpdateSystemOutboundEmailNameAndAddress extends UpgradeDBScript
{
    public $order = 2200;

    /**
     * {@inheritdoc}
     *
     * Update the System and System-Override Outbound Email Records and populate the
     * name and email_address_id fields as follows:
     *    system record
     *        - name: from Admin settings 'from_name'
     *        - email_address_id: id of Admin settings 'from_addr' email address
     *    User system-override record
     *        - name: User's Name
     *        - email_address_id: id of User's primary email address
     *
     * This upgrade script only runs when upgrading from a version prior to 7.10.
     */
    public function run()
    {
        // are we coming from anything before 7.10?
        if (!version_compare($this->from_version, '7.10', '<')) {
            return;
        }

        $this->log('Updating System Outbound Email Names and EmailAddresses');

        // Process Type 'system' Outbound Email record
        $this->processSystemOutboundEmailRecord();

        // Process Type 'system-override' Outbound Email records
        $this->processSystemOverrideOutboundEmailRecords();

        $this->log('Done Updating System Outbound Email Names and EmailAddresses');
    }

    /**
     * Process Type 'system' Outbound Email record
     * Populate the name and email_address_id on the system outbound_email record with the notify_fromname
     * and the email address id of the notify_fromaddress values found in Admin settings.
     */
    protected function processSystemOutboundEmailRecord()
    {
        $adminSettings = Administration::getSettings('notify');
        $systemAddress = $adminSettings->settings['notify_fromaddress'];
        $systemName = $adminSettings->settings['notify_fromname'];

        $sea = BeanFactory::newBean('EmailAddresses');
        $systemEmailAddressId = $sea->getEmailGUID($systemAddress);

        $this->log('Update System OutboundEmail Record: name=' . $systemName . ' address=' . $systemAddress);

        $sql = "UPDATE outbound_email SET email_address_id = ?, name = ? WHERE type = 'system'";
        if ($this->executeUpdate($sql, [$systemEmailAddressId, $systemName]) > 0) {
            $this->log('Updated System Outbound Email Record.');
        } else {
            $this->log('System Outbound Email Record Was Not Updated.');
        }
    }

    /**
     * Process each of the existing user 'system-override' Outbound Email records
     * Populate the name and email_address_id on the system-override outbound_email record with the users
     * full name and primary email address id.
     */
    protected function processSystemOverrideOutboundEmailRecords()
    {
        $systemOverrideRecords = 0;
        $sql = "SELECT id from outbound_email WHERE type='system-override' AND deleted=0";
        $conn = $GLOBALS['db']->getConnection();
        $stmt = $conn->executeQuery($sql);
        while ($row = $stmt->fetch()) {
            $oe = BeanFactory::retrieveBean(
                'OutboundEmail',
                $row['id'],
                array('disable_row_level_security' => true)
            );
            if ($oe && $oe->type === 'system-override' && !empty($oe->user_id)) {
                $user = BeanFactory::retrieveBean(
                    'Users',
                    $oe->user_id,
                    array('disable_row_level_security' => true)
                );
                if ($user) {
                    $userData = $user->getUsersNameAndEmail();
                    $userName = $userData['name'];
                    $addressId = $user->emailAddress->getEmailGUID($userData['email']);
                    $sql = "UPDATE outbound_email" .
                        " SET email_address_id = ?, name = ?" .
                        " WHERE user_id = ? AND type='system-override'";
                    if ($this->executeUpdate($sql, [$addressId, $userName, $user->id]) > 0) {
                        $systemOverrideRecords++;
                    }
                }
            }
        }

        $this->log("Updated {$systemOverrideRecords} System-Override Outbound Email Records.");
    }
}
