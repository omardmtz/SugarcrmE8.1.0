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
 * DeleteTestCampaigns.php
 *
 * This is a class to encapsulate deleting test campaigns
 * @author Collin Lee
 */
class DeleteTestCampaigns {

/**
 * deleteTestRecords
 *
 * This method deletes the test records for a given Campaign instance
 * @param Campaign $focus The Campaign instance
 */
function deleteTestRecords($focus)
{
    if(empty($focus) || empty($focus->id))
    {
        return;
    }

    $res = $focus->db->query("SELECT DISTINCT campaign_log.related_id emailid, prospect_lists.id as listid FROM campaign_log
            JOIN prospect_lists on campaign_log.list_id = prospect_lists.id
            WHERE campaign_log.campaign_id = ".$focus->db->quoted($focus->id)." AND prospect_lists.list_type='test'");
    $test_ids = array();
    $test_list_ids = array();
    while($row = $focus->db->fetchByAssoc($res)) {
            $test_ids[] = $focus->db->quoted($row['emailid']);
            $test_list_ids[] = $focus->db->quoted($row['listid']);
    }
    unset($res);
    if(!empty($test_ids)) {
            $joinedIds = join(",", $test_ids);
            $focus->db->query("UPDATE emails SET deleted=1 WHERE id IN (".$joinedIds.")");
            $focus->db->query("UPDATE emails_text SET deleted=1 WHERE email_id IN (".$joinedIds.")");
            $focus->db->query("UPDATE folders_rel SET deleted=1 WHERE polymorphic_module = 'Emails' AND
                polymorphic_id IN (".$joinedIds.")");
    }

    if(!empty($test_list_ids)) {
            $query = "DELETE FROM emailman WHERE campaign_id = ".$focus->db->quoted($focus->id).
            " AND list_id IN (".join(",", $test_list_ids).")";
        $focus->db->query($query);

            $query = "UPDATE campaign_log SET deleted=1 WHERE campaign_id = ".$focus->db->quoted($focus->id).
            " AND list_id IN (".join(",", $test_list_ids).")";

        $focus->db->query($query);
    }
}

}
