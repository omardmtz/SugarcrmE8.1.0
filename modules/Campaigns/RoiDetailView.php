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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;



global $mod_strings;
global $app_strings;
global $app_list_strings;
global $sugar_version, $sugar_config;

$focus = BeanFactory::newBean('Campaigns');

$detailView = new DetailView();
$offset = 0;
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("CAMPAIGN", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}

// For all campaigns show the same ROI interface
// ..else default to legacy detail view
    echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$focus->name), true);
    
    $GLOBALS['log']->info("Campaign detail view");
    
	$smarty = new Sugar_Smarty();
    $smarty->assign("MOD", $mod_strings);
    $smarty->assign("APP", $app_strings);
    
    $smarty->assign("THEME", $theme);
    $smarty->assign("GRIDLINE", $gridline);
    $smarty->assign("ID", $focus->id);
    $smarty->assign("ASSIGNED_TO", $focus->assigned_user_name);
    $smarty->assign("STATUS", $app_list_strings['campaign_status_dom'][$focus->status]);
    $smarty->assign("NAME", $focus->name);
    $smarty->assign("TYPE", $app_list_strings['campaign_type_dom'][$focus->campaign_type]);
    $smarty->assign("START_DATE", $focus->start_date);
    $smarty->assign("END_DATE", $focus->end_date);

    if (!empty($focus->budget)) {
        $smarty->assign("BUDGET", SugarCurrency::formatAmountUserLocale($focus->budget, $focus->currency_id));
    }
    if (!empty($focus->actual_cost)) {
        $smarty->assign("ACTUAL_COST", SugarCurrency::formatAmountUserLocale($focus->actual_cost, $focus->currency_id));
    }
    if (!empty($focus->expected_cost)) {
        $smarty->assign("EXPECTED_COST", SugarCurrency::formatAmountUserLocale($focus->expected_cost, $focus->currency_id));
    }
    if (!empty($focus->expected_revenue)) {
        $smarty->assign("EXPECTED_REVENUE", SugarCurrency::formatAmountUserLocale($focus->expected_revenue, $focus->currency_id));
    }

    $smarty->assign("OBJECTIVE", nl2br($focus->objective));
    $smarty->assign("CONTENT", nl2br($focus->content));
    $smarty->assign("DATE_MODIFIED", $focus->date_modified);
    $smarty->assign("DATE_ENTERED", $focus->date_entered);
    
    $smarty->assign("CREATED_BY", $focus->created_by_name);
    $smarty->assign("MODIFIED_BY", $focus->modified_by_name);
    $smarty->assign("TRACKER_URL", $sugar_config['site_url'] . '/campaign_tracker.php?track=' . $focus->tracker_key);
    $smarty->assign("TRACKER_COUNT", intval($focus->tracker_count));
    $smarty->assign("TRACKER_TEXT", $focus->tracker_text);
    $smarty->assign("REFER_URL", $focus->refer_url);
    $smarty->assign("IMPRESSIONS", $focus->impressions);
   $roi_vals = array();
   $roi_vals['budget']= $focus->budget;
   $roi_vals['actual_cost']= $focus->actual_cost;
   $roi_vals['Expected_Revenue']= $focus->expected_revenue;
   $roi_vals['Expected_Cost']= $focus->expected_cost;
   
//Query for opportunities won, clickthroughs
$campaign_id = $focus->id;
            $opp_query1  = "select camp.name, count(*) opp_count,SUM(opp.amount) as Revenue, SUM(camp.actual_cost) as Investment, 
                            ROUND((SUM(opp.amount) - SUM(camp.actual_cost))/(SUM(camp.actual_cost)), 2)*100 as ROI";	           
            $opp_query1 .= " from opportunities opp";
            $opp_query1 .= " right join campaigns camp on camp.id = opp.campaign_id";
            $opp_query1 .= " where opp.sales_status = '".Opportunity::STAGE_CLOSED_WON;
            $opp_query1 .= "' and camp.id='$campaign_id'";
            $opp_query1 .= " and opp.deleted=0";
            $opp_query1 .= " group by camp.name";
            $opp_result1=$focus->db->query($opp_query1);              
            $opp_data1=$focus->db->fetchByAssoc($opp_result1);
      if(empty($opp_data1['opp_count'])) $opp_data1['opp_count']=0; 
     $smarty->assign("OPPORTUNITIES_WON",$opp_data1['opp_count']);
          
            $camp_query1  = "select camp.name, count(*) click_thru_link";	           
            $camp_query1 .= " from campaign_log camp_log";
            $camp_query1 .= " right join campaigns camp on camp.id = camp_log.campaign_id";
            $camp_query1 .= " where camp_log.activity_type = 'link' and camp.id='$campaign_id'";
            $camp_query1 .= " group by camp.name";
            $opp_query1 .= " and deleted=0";                                  
            $camp_result1=$focus->db->query($camp_query1);              
            $camp_data1=$focus->db->fetchByAssoc($camp_result1);
            
   if(unformat_number($focus->impressions) > 0){         
    $cost_per_impression= unformat_number($focus->actual_cost)/unformat_number($focus->impressions);
   }
   else{
   	$cost_per_impression = format_number(0);
   }
   $smarty->assign("COST_PER_IMPRESSION", SugarCurrency::formatAmountUserLocale($cost_per_impression, $focus->currency_id));

   if(empty($camp_data1['click_thru_link'])) $camp_data1['click_thru_link']=0;
   $click_thru_links = $camp_data1['click_thru_link'];
   
   if($click_thru_links >0){
    $cost_per_click_thru= unformat_number($focus->actual_cost)/unformat_number($click_thru_links);   	
   }
   else{
   	$cost_per_click_thru = format_number(0);
   }
   $smarty->assign("COST_PER_CLICK_THROUGH", SugarCurrency::formatAmountUserLocale($cost_per_click_thru, $focus->currency_id));


    	$currency = BeanFactory::newBean('Currencies');
    if(isset($focus->currency_id) && !empty($focus->currency_id))
    {
    	$currency->retrieve($focus->currency_id);
    	if( $currency->deleted != 1){
    		$smarty->assign("CURRENCY", $currency->iso4217 .' '.$currency->symbol );
    	}else $smarty->assign("CURRENCY", $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol() );
    }else{
    
    	$smarty->assign("CURRENCY", $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol() );
    
    }
    global $current_user;
    $request = InputValidation::getService();
    $request_module = $request->getValidInputRequest('module', 'Assert\Mvc\ModuleName');
    if (is_admin($current_user) && $request_module != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {
        $request_action = $request->getValidInputRequest('action');
        $request_record = $request->getValidInputRequest('record', 'Assert\Guid');
        $smarty->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=". urlencode($request_action) ."&from_module=". urlencode($request_module) ."&record=". urlencode($request_record) . "'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDIT_LAYOUT'])."</a>");
    }

    $detailView->processListNavigation($xtpl, "CAMPAIGN", $offset, $focus->is_AuditEnabled());
    // adding custom fields:
    global $xtpl;
    $xtpl = $smarty;
    require_once('modules/DynamicFields/templates/Files/DetailView.php');
    

    $smarty->assign("TEAM_NAME", $focus->team_name);
    
    //add chart
    $seps				= array("-", "/");
    $dates				= array(date($GLOBALS['timedate']->dbDayFormat), $GLOBALS['timedate']->dbDayFormat);
    $dateFileNameSafe	= str_replace($seps, "_", $dates);
    $cache_file_name_roi	= $current_user->getUserPrivGuid()."_campaign_response_by_roi_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml";
    $chart= new campaign_charts();
    $smarty->assign("MY_CHART_ROI", $chart->campaign_response_roi($app_list_strings['roi_type_dom'],$app_list_strings['roi_type_dom'],$focus->id,true,true));    
    //end chart
    //custom chart code
    $sugarChart = SugarChartFactory::getInstance();
	$resources = $sugarChart->getChartResources();
	$smarty->assign('chartResources', $resources);

echo $smarty->fetch('modules/Campaigns/RoiDetailView.tpl');
