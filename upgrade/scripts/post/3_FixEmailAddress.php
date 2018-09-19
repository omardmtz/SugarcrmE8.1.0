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
 * bug 57426 was introduced in 654, and it's effects need to be repaired during upgrade.
 * Run the repair script if the original version is greater than 654, but less than 661
 * (bug fix was introduced into 658 and 661 branches)
 */
class SugarUpgradeFixEmailAddress extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_DB;

    public $version = '6.6.1';

    public function run()
    {
        if (version_compare($this->from_version, '6.5.4', '>') && version_compare($this->from_version, '6.5.8', '<') || $this->from_version == '6.6.0') {
            $this->process_email_address_relationships();
        }
    }

    /**
     * This function will process email address table and run the repair script to correct the
     * Case sensitive duplicates behavior as outlined in bug 57426
     */
    protected function process_email_address_relationships()
    {
        $broken_escaped_emails = array(); // these are emails where the email_address has escaped quotes (\',\") while email_address_caps does not
        $troubled_emails = array(); // these are emails where the email_address has been changed incorrectly, thus not matching with email_address_caps

        // find troubled rows - ones that violate upper(email_address) <> email_address_caps
        $query = "SELECT * FROM email_addresses WHERE deleted=0 AND upper(email_address) <> email_address_caps";
        $result = $this->db->query($query);
        while ($row = $this->db->fetchByAssoc($result,false)) {// we don't want to be converted to html
            // determine if they are the same up to escaping -- something else bad might have happened
            if (strtoupper(stripslashes($row['email_address'])) == $row['email_address_caps']) {
                $broken_escaped_emails[] = $row;
            }
            else {
                $troubled_emails[] = $row;
            }
        }
        $this->log("Found ".count($broken_escaped_emails).' escaped emails. Ignoring for now');
        $this->log("Found ".count($troubled_emails).' emails whose caps field does not match.');

        // determine if troubled emails have a row (matched by email_address_caps), otherwise we need to create one
        /*
         * logic for troubled rows:
         * 1. find (or create) a row in email_addresses to match the current email_address (by email_address_caps)
         * 2. the troubled row gets updated so that the email_address = some form of email_address_caps (likely strtolower)
         * 3. all relationships having a created date > modified date for the troubled row, will get assigned to the new email address
         * 4. all relationships having a created date < modified date will stay with the old email address.
         * 5. all troubled rows will have upper(email_address) = email_address_caps
         */
        foreach($troubled_emails as $row) {
            $new_email_address = $row['email_address']; // the changed email address is in this field
            $old_email_address = $row['email_address_caps']; // the old email address is in this field
            $old_uuid = $row['id'];
            $time_changed = $row['date_modified']; // the point assumed to be when the email address incorrectly was changed.
            $this->log('Inconsistent row has address '.$new_email_address.' and caps field '.$old_email_address);;

            // attempt to find a better row for the new email address
            $find_new_rows_qry = $this->db->query("SELECT * from email_addresses WHERE email_address_caps = '".$this->db->quote(strtoupper($new_email_address))."' AND deleted=0");
            $first_new_row = $this->db->fetchByAssoc($find_new_rows_qry,false);
            if ($first_new_row) {
                // this will be our new id
                $new_uuid = $first_new_row['id'];
                $this->log('Found a matching row of id '.$new_uuid.' for email address '.$new_email_address);
            }
            else {
                // create new uuid
                $this->log('No matching row for new email address '.$new_email_address.', creating one');
                $new_uuid = create_guid();
                $noMatchQuery = "INSERT INTO email_addresses VALUES ('".$new_uuid."', '".$new_email_address."', '".$this->db->quote(strtoupper($new_email_address))."', '".
                         $row['invalid_email']."', '".$row['opt_out']."', '".$time_changed."', ".$this->db->now().", '0')";
                $this->db->query($noMatchQuery);
                $this->log("Added as $new_uuid, query was ".$noMatchQuery);
            }

            $this->fix_email_address_relationships($old_uuid, $new_uuid, $time_changed);

            $this->log('Restoring old row to proper email address');;
            $restore_old_row_qry = "UPDATE email_addresses SET email_address = '".$this->db->quote(strtolower($old_email_address))."'  where email_address_caps = '".$this->db->quote($old_email_address)."' ";
            $this->db->query($restore_old_row_qry);
        }

        // at this point handle duplicate emails
        /*
         * logic for a duplicate row:
         * 1. match duplicate rows by email_address_caps (handling the troubled rows should make this field reliable)
         * 2. for each matching email_address, chose one of the ids to be the canonical id.  this id/row will be used
         *    for all relationships for that email address.
         *
         */
        $this->log("------------------------------------------------------------------");
        $this->log('Determining which email addresses are duplicated within the system');
        $dupe_email_addresses = array();

        $dupe_query = "SELECT email_address_caps, count(*) AS email_count FROM email_addresses WHERE deleted=0 GROUP BY email_address_caps HAVING COUNT(*) > 1";
        $dupe_results = $this->db->query($dupe_query);
        while ($row = $this->db->fetchByAssoc($dupe_results,false)) {
            $email_address_caps = $row['email_address_caps'];
            $this->log("Found ".$email_address_caps.' with rows='.$row['email_count']);

            $ids = array();
            $opt_out = '0'; // by default don't opt out, unless one of the dupes has an opt-out flag.
            // we want to get id's of all duplicate rows so we can handle relationships
            $find_matching_rows = "SELECT id, opt_out FROM email_addresses WHERE email_address_caps = '".$this->db->quote($email_address_caps)."' AND deleted=0";
            $matchingRowResult = $this->db->query($find_matching_rows);
            while ($matching_email_row = $this->db->fetchByAssoc($matchingRowResult,false)) {
                $matching_email_id = $matching_email_row['id'];
                $this->log("Found duplicate with id=".$matching_email_id);;
                $ids[] = $matching_email_id;
                if (intval($matching_email_row['opt_out']) == 1) {
                    $opt_out = 1;
                    $this->log("Flagged as opted out.");;
                }
            }

            $dupe_email_addresses[$email_address_caps] = array('ids' =>$ids, 'opt_out' =>$opt_out);
        }

        $this->log('Repairing duplicate email address relationships and marking duplicates as deleted');
        foreach ($dupe_email_addresses as $email_address_caps => $data) {
            $this->log('Working on '.$email_address_caps);;
            $ids = $data['ids'];

            // make the first id the canonical one.
            $canonical_id = array_shift($ids);
            $this->log("Canonical ID is now: ".$canonical_id);;

            if ($data['opt_out'] == 1) {
                $this->log("Marking email as opted out due to one of the duplicates being flagged.");
                $this->db->query("UPDATE email_addresses SET opt_out=1 WHERE id='$canonical_id'");
            }
            foreach($ids as $id) {
                $this->log("Duplicate ID: ".$id);
                $this->fix_email_address_relationships($id, $canonical_id);
                $this->log("Marking as deleted");
                $this->db->query("UPDATE email_addresses SET deleted=1 WHERE id='$id'");
            }
        }
    }

    /**
     * Alters email_address relationship tables from the old uuid to the new uuid for 'email_addr_bean_rel' table.  This is relationship
     * linking the email address and the person bean user/lead/contact.
     * This script optionally updates the 'emails_email_addr_rel' using the $time_changed parameter as a flag.  This is the relationship
     * linking the email messages to the email address.
     * @param $old_uuid - this id is to be changed
     * @param $new_uuid - change to this id
     * @param null $time_changed (if this parameter is not set, run the query that updates 'emails_email_addr_rel' relationship table)
     */
    protected function fix_email_address_relationships($old_uuid, $new_uuid, $time_changed=null)
    {
        // if we have time query, we need joins
        //Relates all emails currently related to duplicates of the current email address to the first id in the array of duplicates
        if ($time_changed == null) {
            $stm_emails_email_addr = "UPDATE emails_email_addr_rel SET email_address_id='$new_uuid' WHERE email_address_id='$old_uuid'";
            $this->log($stm_emails_email_addr);;
            $rs = $this->db->query($stm_emails_email_addr);
            $this->log(' Number of row(s) changed = '.$this->db->getAffectedRowCount($rs));
        }

        //Relates all beans(People) currently related to duplicates of the current email address to the first id in the array of duplicates
        // it is highly unlikely that the records using this email address want the old one, so avoid making a bad guess.
        $stm_email_addr_bean = "UPDATE email_addr_bean_rel SET email_address_id='$new_uuid' WHERE email_address_id='$old_uuid'";

        $this->log($stm_email_addr_bean);;
        $rs = $this->db->query($stm_email_addr_bean);
        $this->log(' Number of row(s) changed = '.$this->db->getAffectedRowCount($rs));
    }
}
