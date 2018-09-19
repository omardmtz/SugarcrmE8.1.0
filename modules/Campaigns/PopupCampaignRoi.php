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
 * $Id: PopupCampaignRoi.php 13782 2006-06-06 17:58:55 +0000 (22 Nov 2006) Vineet $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/






global $mod_strings;
global $app_strings;
global $app_list_strings;
global $sugar_version, $sugar_config;

global $theme;




$GLOBALS['log']->info("Campaign detail view");

$xtpl=new XTemplate ('modules/Campaigns/PopupCampaignRoi.html');

$campaign_id=$_REQUEST['id'];
$campaign = BeanFactory::newBean('Campaigns');
$opp_query1  = "select camp.name, camp.actual_cost,camp.budget,camp.expected_revenue,count(*) opp_count,SUM(opp.amount) as Revenue, SUM(camp.actual_cost) as Investment,
                            ROUND((SUM(opp.amount) - SUM(camp.actual_cost))/(SUM(camp.actual_cost)), 2)*100 as ROI";
            $opp_query1 .= " from opportunities opp";
            $opp_query1 .= " right join campaigns camp on camp.id = opp.campaign_id";
            $opp_query1 .= " where opp.sales_stage = '".Opportunity::STAGE_CLOSED_WON."' and camp.id=" .
                $campaign->db->quoted($campaign_id);
            $opp_query1 .= " group by camp.name";
            //$opp_query1 .= " and deleted=0";
            $opp_result1=$campaign->db->query($opp_query1);
            $opp_data1=$campaign->db->fetchByAssoc($opp_result1);
 //get the click-throughs
 $query_click = "SELECT count(*) hits ";
			$query_click.= " FROM campaign_log ";
            $query_click.= " WHERE campaign_id = " . $campaign->db->quoted($campaign_id) .
                " AND activity_type='link' AND related_type='CampaignTrackers' AND archived=0 AND deleted=0";

            //if $marketing id is specified, then lets filter the chart by the value
            if (!empty($marketing_id)){
                $query_click.= " AND marketing_id ='$marketing_id'";
            }

			$query_click.= " GROUP BY  activity_type, target_type";
			$query_click.= " ORDER BY  activity_type, target_type";
			$result = $campaign->db->query($query_click);


  $xtpl->assign("OPP_COUNT", $opp_data1['opp_count']);
  $xtpl->assign("ACTUAL_COST",$opp_data1['actual_cost']);
  $xtpl->assign("PLANNED_BUDGET",$opp_data1['budget']);
  $xtpl->assign("EXPECTED_REVENUE",$opp_data1['expected_revenue']);




	$currency = BeanFactory::newBean('Currencies');
if(isset($focus->currency_id) && !empty($focus->currency_id))
{
	$currency->retrieve($focus->currency_id);
	if( $currency->deleted != 1){
		$xtpl->assign("CURRENCY", $currency->iso4217 .' '.$currency->symbol );
	}else $xtpl->assign("CURRENCY", $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol() );
}else{

	$xtpl->assign("CURRENCY", $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol() );

}

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDIT_LAYOUT'])."</a>");

}

//add chart
$seps				= array("-", "/");
$dates				= array(date($GLOBALS['timedate']->dbDayFormat), $GLOBALS['timedate']->dbDayFormat);
$dateFileNameSafe	= str_replace($seps, "_", $dates);
$cache_file_name_roi	= $current_user->getUserPrivGuid()."_campaign_response_by_roi_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml";
$chart= new campaign_charts();

    //if marketing id has been selected, then set "latest_marketing_id" to the selected value
    //latest marketing id will be passed in to filter the charts and subpanels

    if(!empty($selected_marketing_id)){$latest_marketing_id = $selected_marketing_id;}
    if(empty($latest_marketing_id) ||  $latest_marketing_id === 'all'){
        $xtpl->assign("MY_CHART_ROI", $chart->campaign_response_roi_popup($app_list_strings['roi_type_dom'],$app_list_strings['roi_type_dom'],$campaign_id,sugar_cached("xml/") . $cache_file_name_roi,true));
    }else{

    $xtpl->assign("MY_CHART_ROI", $chart->campaign_response_roi_popup($app_list_strings['roi_type_dom'],$app_list_strings['roi_type_dom'],$campaign_id,sugar_cached("xml/") .$cache_file_name_roi,true));
    }
//end chart

$xtpl->parse("main");
$xtpl->out("main");
