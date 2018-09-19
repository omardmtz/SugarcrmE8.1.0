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

use Sugarcrm\Sugarcrm\Util\Uuid;
use Sugarcrm\Sugarcrm\Util\Serialized;

class SugarUpgradeUpdateUserOutboundEmailNameAndAddress extends UpgradeDBScript
{
    public $order = 2200;

    /**
     * {@inheritdoc}
     *
     * Update the Type 'user' Outbound Email Records and populate the
     * name and email_address_id fields as follows:
     *   - name: from_name if there is a referencing Inbound Email,
     *           otherwise User Name if this filed in the outbound email is blank.
     *   - email_address_id: id if the email address in from_addr if there is a referencing Inbound Email,
     *           otherwise the id of the Users' primary Email address
     *
     * This upgrade script only runs when upgrading from a version prior to 7.10.
     */
    public function run()
    {
        // are we coming from anything before 7.10?
        if (!version_compare($this->from_version, '7.10', '<')) {
            return;
        }

        $this->log('Updating User Outbound Email Names and EmailAddresses');

        $this->processUserOutboundEmailRecords();

        $this->log('Done Updating User Outbound Email Names and EmailAddresses');
    }

    /**
     * First, collect a list of all ids of outbound email records of type = 'user'. These represent the full set of
     * outbound email record candidates that we will target for update.
     *
     * Examine all Personal User inbound email records for any outbound email references.
     * If there is a referenced Outbound Email Account AND it exists in the outbound email candidates list, then update
     * it with the From Name and From Address from the Inbound Email Account Record and Remove it from the outbound
     * email candidates list.
     *
     * If there is a referenced Outbound Email Account AND it does not exist in the outbound email candidates list,
     * create a new instance of that outbound email record with the From Name and From Address from the Inbound Email
     * Account Record.
     *
     * Under normal circumstances the 'user' outbound email candidates list should be empty when all previous
     * processing has occurred. It not, these 'user' outbound email records are orphans under the Legacy Emails design.
     * We will apply the same policy to these records that we did with the system-override records and simply populate
     * them with the User's full name and the address of their primary Email address if the user_id exists, and
     * will otherwise soft delete them as they are completely unusable under the Current Emails design.
     *
     * Note: Only Personal inboxes will ever have outbound email references. Bounce Handling and Other Group
     * Inbox Accounts will not.
     */
    protected function processUserOutboundEmailRecords()
    {
        $recordsUpdated = 0;
        $recordsCreated = 0;
        $sea = BeanFactory::newBean('EmailAddresses');

        // Collect all 'user' Outbound Email Ids as the full set of outbound email candidates to be processed
        $outboundEmailIds = array();
        $sql = "SELECT id from outbound_email WHERE type='user' AND deleted=0";
        $conn = $GLOBALS['db']->getConnection();
        $stmt = $conn->executeQuery($sql);
        while ($row = $stmt->fetch()) {
            $outboundEmailIds[$row['id']] = true;
        }

        // Process 'user' Outbound Emails referenced in personal Inbound Email records.
        $sql = "SELECT id FROM inbound_email WHERE is_personal='1' AND deleted=0 ORDER by id";
        $conn = $GLOBALS['db']->getConnection();
        $stmt = $conn->executeQuery($sql);
        while ($row = $stmt->fetch()) {
            $ie = BeanFactory::retrieveBean(
                'InboundEmail',
                $row['id'],
                array('disable_row_level_security' => true)
            );
            if ($ie) {
                $so = Serialized::unserialize($ie->stored_options, array(), true);
                $fromName = isset($so['from_name']) ? $so['from_name'] : '';
                $fromAddr = isset($so['from_addr']) ? $so['from_addr'] : '';
                $oeId = empty($so['outbound_email']) ? '' : $so['outbound_email'];
                if (!empty($oeId)) {
                    $oe = BeanFactory::retrieveBean(
                        'OutboundEmail',
                        $oeId,
                        array('disable_row_level_security' => true)
                    );
                    if ($oe && $oe->type === 'user') {
                        $addressId = $sea->getEmailGUID($fromAddr);
                        $key = "|$oeId|$fromName|{$addressId}|";
                        if (isset($outboundEmailIds[$oeId]) && $outboundEmailIds[$oeId] === true) {
                            $sql = "UPDATE outbound_email SET email_address_id=?, name=? WHERE id = ?";
                            if ($this->executeUpdate($sql, [$addressId, $fromName, $oeId]) > 0) {
                                $recordsUpdated++;
                                $outboundEmailIds[$oeId] = [$key => true];
                            }
                        } elseif (isset($outboundEmailIds[$oeId][$key]) && $outboundEmailIds[$oeId][$key] === true) {
                            // Ignore - the referenced Outbound Email exists with the same Name and Email Address Id
                        } else {
                            // Outbound Email previously referenced, but either the Name or Email Address are different
                            // Clone the Outbound Email record with this Name and Email Address Id
                            $oeClone = clone $oe;
                            $oeClone->id = Uuid::uuid1();
                            $oeClone->new_with_id = true;
                            $oeClone->name = $fromName;
                            $oeClone->email_address_id = $addressId;
                            $oeClone->save();
                            $recordsCreated++;

                            $outboundEmailIds[$oeId][$key] = true;
                        }
                    }
                }
            }
        }

        foreach ($outboundEmailIds as $oeId => $value) {
            if ($value === true) {
                // This Outbound Email Record was not Referenced by an Inbound Email Record
                // If the user_id does not reference an existing User, soft-delete the record
                // Otherwise, Set the Email Address Id using the user's primary Email address
                // and Update the Name Field to the user's name Only if the Name field is Blank
                $oe = BeanFactory::retrieveBean(
                    'OutboundEmail',
                    $oeId,
                    array('disable_row_level_security' => true)
                );
                if ($oe && $oe->type === 'user' && !empty($oe->user_id)) {
                    $user = BeanFactory::retrieveBean(
                        'Users',
                        $oe->user_id,
                        array('disable_row_level_security' => true)
                    );
                    if ($user) {
                        $userData = $user->getUsersNameAndEmail();
                        $addressId = $user->emailAddress->getEmailGUID($userData['email']);
                        $sql = "UPDATE outbound_email SET email_address_id = ?";
                        $params = [$addressId];
                        if (trim($oe->name) === '') {
                            $userName = $userData['name'];
                            $sql .= ", name = ?";
                            $params[] = [$userName];
                        }
                        $sql .= " WHERE id = ?";
                        $params[] = $oeId;

                        $recordsUpdated += $this->executeUpdate($sql, $params);
                    }
                }
            }
        }

        $this->log("Updated {$recordsUpdated} User Outbound Email Records.");
        $this->log("Created {$recordsCreated} User Outbound Email Records.");
    }
}
