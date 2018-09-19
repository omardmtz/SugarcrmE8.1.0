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
 * Fix up primary flagged relationships if there are more than one "primary" record
 */
class SugarUpgradePrimaryRelationshipAdd extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.1.5', '<')) {
            // Hardcoded for the accounts_contacts relationship for now
            // Everybody becomes the primary account
            $this->db->query("UPDATE accounts_contacts "
                    . "SET primary_account = 1 "
                    . "WHERE deleted = 0"
            );

            // Find relationships where there are more than one "primary" record
            while (true) {
                $ret = $this->db->limitQuery("SELECT COUNT(id) duplicates, contact_id child_id "
                        . "FROM accounts_contacts "
                        . "WHERE primary_account = 1 "
                        . "AND deleted = 0 "
                        . "GROUP BY contact_id "
                        . "HAVING COUNT(id) > 1", 0, 200
                );
                $fixupRecords = array();

                while ($row = $this->db->fetchByAssoc($ret)) {
                $fixupRecords[] = $this->db->quoted($row['child_id']);
                }
                if (empty($fixupRecords)) {
                    // We have fixed everything
                    break;
                }

                // Find the most recent record for child and we'll unset the rest of them as primary
                $ret = $this->db->query("SELECT id, contact_id child_id, date_modified "
                        . "FROM accounts_contacts WHERE "
                                    ."contact_id IN (" . implode (',', $fixupRecords) . ") "
                        . "AND deleted = 0 ORDER BY date_modified DESC"
                );

                $fixedRecords = array();
                while ($row = $this->db->fetchByAssoc($ret)) {
                    if (!isset($fixedRecords[$row['child_id']])) {
                        // First time we've found this child
                        $fixedRecords[$row['child_id']] = true;
                        $this->db->query("UPDATE accounts_contacts "
                                . "SET primary_account = 0 "
                                . "WHERE deleted = 0 "
                                . "AND id <> " . $this->db->quoted($row['id'])
                                . " AND contact_id = " . $this->db->quoted($row['child_id'])
                        );
                    }
                }
            }
        }
    }
}
