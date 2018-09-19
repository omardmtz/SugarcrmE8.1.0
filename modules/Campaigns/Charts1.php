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
/*********************************************************************************
* $Id: Charts1.php 45763 2009-04-01 19:16:18Z majed $
* Description:  Includes the functions for Customer module specific charts.
********************************************************************************/
//todo: experimental class for chart data handling..not used in the application at this time.



require_once('include/charts/Charts.php');




class charts {

    /* @function:
     *
     * @param array targets: translated list of all activity types, targeted, bounced etc..
     * @param string campaign_id: chart for this campaign.
     */
    function campaign_response_chart($targets,$campaign_id) {

        $focus = BeanFactory::newBean('Campaigns');
        $leadSourceArr = array();

        $query = "SELECT activity_type,target_type, count(*) hits ";
        $query.= " FROM campaign_log ";
        $query.= " WHERE campaign_id = '$campaign_id' AND archived=0 AND deleted=0";
        $query.= " GROUP BY  activity_type, target_type";
        $query.= " ORDER BY  activity_type, target_type";

        $result = $focus->db->query($query);
        while($row = $focus->db->fetchByAssoc($result, false)) {

            if (isset($leadSourceArr[$row['activity_type']]['value'])) {
                $leadSourceArr[$row['activity_type']]['value']=0;
            }

            $leadSourceArr[$row['activity_type']]['value']=  $leadSourceArr[$row['activity_type']]['value'] + $row['hits'];

            if (!empty($row['target_type'])) {
                $leadSourceArr[$row['activity_type']]['bars'][$row['target_type']]['value']=$row['hits'];
            }
        }

        foreach ($targets as $key=>$value) {
            if (!isset($leadSourceArr[$key])) {
                $leadSourceArr[$key]['value']=0;
            }
        }

        //use the new template.
        $xtpl=new XTemplate ('modules/Campaigns/chart.tpl');
        $xtpl->assign("GRAPHTITLE",'Campaign Response by Recipient Activity');
        $xtpl->assign("Y_DEFAULT_ALT_TEXT",'Rollover a bar to view details.');

        //process rows
        foreach ($leadSourceArr as $key=>$values) {
            if (isset($values['bars'])) {
                foreach ($values['bars'] as $bar_id=>$bar_value) {
                    $xtpl->assign("Y_BAR_ID",$bar_id);
                }
            }

        }
    }
    }// end charts class
?>
