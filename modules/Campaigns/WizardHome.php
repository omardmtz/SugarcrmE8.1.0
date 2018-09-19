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
 * $Id: EditView.php 15417 2006-08-03 00:21:27 +0000 (Thu, 03 Aug 2006) wayne $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/************** general UI Stuff *************/


require_once('modules/Campaigns/utils.php');

$focus = BeanFactory::newBean('Campaigns');
if(isset($_REQUEST['record']) &&  !empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;


//if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
//account for use within wizards
if($focus->campaign_type == 'NewsLetter'){
    echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_NEWSLETTER_WIZARD_START_TITLE'].$focus->name), true, false);
}else{
    echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_CAMPAIGN_WIZARD_START_TITLE'].$focus->name), true, false);
}

global $theme;
global $currentModule;

    $ss = new Sugar_Smarty();
    $ss->assign("MOD", $mod_strings);
    $ss->assign("APP", $app_strings);

    //if this page has been refreshed as a result of sending emails, then display status
    if(isset($_REQUEST['from'])){
        $mess = $mod_strings['LBL_TEST_EMAILS_SENT'];
        if($_REQUEST['from']=='send'){ $mess = $mod_strings['LBL_EMAILS_SCHEDULED'];  }
        $confirm_msg = "var ajaxWizStatus = new SUGAR.ajaxStatusClass(); ";
        $confirm_msg .= "window.setTimeout(\"ajaxWizStatus.showStatus('".$mess."')\",1000); ";
        $confirm_msg .= "window.setTimeout('ajaxWizStatus.hideStatus()', 1500); ";
        $confirm_msg .= "window.setTimeout(\"ajaxWizStatus.showStatus('".$mess."')\",2000); ";
        $confirm_msg .= "window.setTimeout('ajaxWizStatus.hideStatus()', 5000); ";
        $ss->assign("MSG_SCRIPT",$confirm_msg);
    }

    if (isset($_REQUEST['return_module'])) $ss->assign("RETURN_MODULE", $_REQUEST['return_module']);
    if (isset($_REQUEST['return_action'])) $ss->assign("RETURN_ACTION", $_REQUEST['return_action']);
    if (isset($_REQUEST['return_id'])) $ss->assign("RETURN_ID", $_REQUEST['return_id']);
    if (isset($_REQUEST['record'])) $ss->assign("ID", $_REQUEST['record']);
    // handle Create $module then Cancel
    if (empty($_REQUEST['return_id'])) {
        $ss->assign("RETURN_ACTION", 'index');
    }



    $ss->assign("CAMPAIGN_TBL", create_campaign_summary ($focus));
    $ss->assign("TARGETS_TBL", create_target_summary ($focus));
    $ss->assign("TRACKERS_TBL", create_tracker_summary ($focus));
    if($focus->campaign_type =='NewsLetter' || $focus->campaign_type =='Email'){
        $ss->assign("MARKETING_TBL", create_marketing_summary ($focus));
    }

    $camp_url = "index.php?action=WizardNewsletter&module=Campaigns&return_module=Campaigns&return_action=WizardHome";
    $camp_url .= "&return_id=".$focus->id."&record=".$focus->id."&direct_step=";
    $ss->assign("CAMP_WIZ_URL", $camp_url);

    $mrkt_string = $mod_strings['LBL_NAVIGATION_MENU_MARKETING'];
    if(!empty($focus->id)){
        $mrkt_url = "<a  href='index.php?action=WizardMarketing&module=Campaigns&return_module=Campaigns&return_action=WizardHome";
        $mrkt_url .= "&return_id=".$focus->id."&campaign_id=".$focus->id;
        $mrkt_url .= "'>". $mrkt_string."</a>";
        $mrkt_string = $mrkt_url;
    }

        $mrkt_url = "<a  href='index.php?action=WizardMarketing&module=Campaigns&return_module=Campaigns&return_action=WizardHome";
        $mrkt_url .= "&return_id=".$focus->id."&campaign_id=".$focus->id;
        $mrkt_url .= "'>". $mod_strings['LBL_NAVIGATION_MENU_MARKETING']."</a>";
    $ss->assign("MRKT_WIZ_URL", $mrkt_url);

    $summ_url = "<a  href='index.php?action=WizardHome&module=Campaigns";
    $summ_url .= "&return_id=".$focus->id."&record=".$focus->id;
    $summ_url .= "'> ". $mod_strings['LBL_NAVIGATION_MENU_SUMMARY']."</a>";


    //Create the html to fill in the wizard steps
    if($focus->campaign_type == 'NewsLetter'){
        $ss->assign('NAV_ITEMS',create_wiz_menu_items('newsletter',$mrkt_string,$camp_url,$summ_url ));
        $ss->assign("CAMPAIGN_DIAGNOSTIC_LINK", diagnose());
    }elseif($focus->campaign_type == 'Email'){
        $ss->assign('NAV_ITEMS',create_wiz_menu_items('email',$mrkt_string,$camp_url,$summ_url ));
        $ss->assign("CAMPAIGN_DIAGNOSTIC_LINK", diagnose());
     }else{
        $ss->assign('NAV_ITEMS',create_wiz_menu_items('general',$mrkt_string,$camp_url,$summ_url ));
    }


    /********** FINAL END OF PAGE UI Stuff ********/
    $ss->display(SugarAutoLoader::existingCustomOne('modules/Campaigns/WizardHome.html'));

}else{
    //there is no record to retrieve, so ask which type of campaign wizard to launch
/*    $header_URL = "Location: index.php?module=Campaigns&action=index";
    $GLOBALS['log']->debug("about to post header URL of: $header_URL");
    header($header_URL);
*/
    global $mod_strings;
    global $app_list_strings;
    global $app_strings;
    global $current_user;

    //if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
    //account for use within wizards
        echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_CAMPAIGN_WIZARD'].$focus->name), true, false);


    $ss = new Sugar_Smarty();
    $ss->assign("MOD", $mod_strings);
    $ss->assign("APP", $app_strings);
    $ss->display(SugarAutoLoader::existingCustomOne('modules/Campaigns/tpls/WizardHomeStart.tpl'));
}

function create_campaign_summary  ($focus){
    global $mod_strings,$app_strings;
    $fields = array();
    $fields[] = 'name';
    $fields[] = 'assigned_user_name';
    $fields[] = 'status';
    $fields[] = 'team_name';
    $fields[] = 'start_date';
    $fields[] = 'end_date';
    if($focus->campaign_type=='NewsLetter'){
        $fields[] = 'frequency';
    }
    $fields[] = 'content';
    $fields[] = 'budget';
    $fields[] = 'actual_cost';
    $fields[] = 'expected_revenue';
    $fields[] = 'expected_cost';
    $fields[] = 'impressions';
    $fields[] = 'objective';

    if (!empty($focus->budget)) {
        $focus->budget = SugarCurrency::formatAmountUserLocale($focus->budget, $focus->currency_id);
    }
    if (!empty($focus->actual_cost)) {
        $focus->actual_cost = SugarCurrency::formatAmountUserLocale($focus->actual_cost, $focus->currency_id);
    }
    if (!empty($focus->expected_cost)) {
        $focus->expected_cost = SugarCurrency::formatAmountUserLocale($focus->expected_cost, $focus->currency_id);
    }
    if (!empty($focus->expected_revenue)) {
        $focus->expected_revenue = SugarCurrency::formatAmountUserLocale($focus->expected_revenue, $focus->currency_id);
    }

    //create edit view status and input buttons
    $cmp_input = '';

    //create edit campaign button
    $cmp_input =  "<input id='wiz_next_button' name='SUBMIT'  ";
    $cmp_input.= "onclick=\"this.form.return_module.value='Campaigns';";
    $cmp_input.= "this.form.module.value='Campaigns';";
    $cmp_input.= "this.form.action.value='WizardNewsletter';";
    $cmp_input.= "this.form.return_action.value='WizardHome';";
    $cmp_input.= "this.form.direct_step.value='1';";
    $cmp_input.= "this.form.record.value='".$focus->id."';";
    $cmp_input.= "this.form.return_id.value='".$focus->id."';\" ";
    $cmp_input.= "class='button' value='".$mod_strings['LBL_EDIT_EXISTING']."' type='submit'> ";

    //create view status button
    if(($focus->campaign_type == 'NewsLetter') || ($focus->campaign_type == 'Email')){
        $cmp_input .=  " <input id='wiz_status_button' name='SUBMIT'  ";
        $cmp_input.= "onclick=\"this.form.return_module.value='Campaigns';";
        $cmp_input.= "this.form.module.value='Campaigns';";
        $cmp_input.= "this.form.action.value='TrackDetailView';";
        $cmp_input.= "this.form.return_action.value='WizardHome';";
        $cmp_input.= "this.form.record.value='".$focus->id."';";
        $cmp_input.= "this.form.return_id.value='".$focus->id."';\" ";
        $cmp_input.= "class='button' value='".$mod_strings['LBL_TRACK_BUTTON_TITLE']."' type='submit'>";
    }
    //create view roi button
    $cmp_input .=  " <input id='wiz_status_button' name='SUBMIT'  ";
    $cmp_input.= "onclick=\"this.form.return_module.value='Campaigns';";
    $cmp_input.= "this.form.module.value='Campaigns';";
    $cmp_input.= "this.form.action.value='RoiDetailView';";
    $cmp_input.= "this.form.return_action.value='WizardHome';";
    $cmp_input.= "this.form.record.value='".$focus->id."';";
    $cmp_input.= "this.form.return_id.value='".$focus->id."';\" ";
    $cmp_input.= "class='button' value='".$mod_strings['LBL_TRACK_ROI_BUTTON_LABEL']."' type='submit'>";

    //Create Campaign Header
    $cmpgn_tbl = "<p><table class='edit view' width='100%' border='0' cellspacing='0' cellpadding='0'>";
    $cmpgn_tbl .= "<tr><td class='dataField' align='left'><h4 class='dataLabel'> ".$mod_strings['LBL_LIST_CAMPAIGN_NAME'].'  '. $mod_strings['LBL_WIZ_NEWSLETTER_TITLE_SUMMARY']." </h4></td>";
    $cmpgn_tbl .= "<td align='right'>$cmp_input</td></tr>";
    $colorclass = '';
    foreach($fields as $key){

        if (!empty($focus->$key) && !empty($mod_strings[$focus->field_defs[$key]['vname']])) {
            $cmpgn_tbl .= "<tr><td scope='row' width='15%'>".$mod_strings[$focus->field_defs[$key]['vname']]."</td>\n";
                    if($key == 'team_name') {
                        require_once 'modules/Teams/TeamSetManager.php';
                        $cmpgn_tbl .= "<td scope='row'>" .
                            TeamSetManager::getFormattedTeamsFromSet($focus, true) . "</td></tr>\n";
		            } else {
                       $cmpgn_tbl .= "<td scope='row'>".$focus->$key."</td></tr>\n";
                    }
                }
    }
    $cmpgn_tbl .= "</table></p>";

    return $cmpgn_tbl ;
}

function create_marketing_summary  ($focus){
    global $mod_strings,$app_strings;
    $colorclass = '';

    //create new marketing button input
    $new_mrkt_input =  "<input id='wiz_new_mrkt_button' name='SUBMIT' ";
    $new_mrkt_input .= "onclick=\"this.form.return_module.value='Campaigns';";
    $new_mrkt_input .= "this.form.module.value='Campaigns';";
    $new_mrkt_input .= "this.form.record.value='';";
    $new_mrkt_input .= "this.form.return_module.value='Campaigns';";
    $new_mrkt_input .= "this.form.action.value='WizardMarketing';";
    $new_mrkt_input .= "this.form.return_action.value='WizardHome';";
    $new_mrkt_input .= "this.form.direct_step.value='1';";
    $new_mrkt_input .= "this.form.campaign_id.value='".$focus->id."';";
    $new_mrkt_input .= "this.form.return_id.value='".$focus->id."';\" ";
    $new_mrkt_input .= "class='button' value='".$mod_strings['LBL_CREATE_NEW_MARKETING_EMAIL']."' type='submit'>";

        //create marketing email table
    $mrkt_tbl='';

    $focus->load_relationship('emailmarketing');
    $mrkt_lists = $focus->emailmarketing->get();


    $mrkt_tbl = "<p><table  class='list view' width='100%' border='0' cellspacing='1' cellpadding='1'>";
    $mrkt_tbl .= "<tr class='detail view'><td colspan='3'><h4> ".$mod_strings['LBL_WIZ_MARKETING_TITLE']." </h4></td>" .
                 "<td colspan=2 align='right'>$new_mrkt_input</td></tr>";
    $mrkt_tbl .= "<tr  class='listViewHRS1'><td scope='col' width='15%'><b>".$mod_strings['LBL_MRKT_NAME']."</b></td><td width='15%' scope='col'><b>".$mod_strings['LBL_FROM_MAILBOX_NAME']."</b></td><td width='15%' scope='col'><b>".$mod_strings['LBL_STATUS_TEXT']."</b></td><td scope='col' colspan=2>&nbsp;</td></tr>";

    if(count($mrkt_lists)>0){
            $mrkt_focus = BeanFactory::newBean('EmailMarketing');
            foreach($mrkt_lists as $mrkt_id){
                $mrkt_focus->retrieve($mrkt_id);

                //create send test marketing button input
                $test_mrkt_input =  "<input id='wiz_new_mrkt_button' name='SUBMIT'  ";
                $test_mrkt_input .= "onclick=\"this.form.return_module.value='Campaigns'; ";
                $test_mrkt_input .= "this.form.module.value='Campaigns'; ";
                $test_mrkt_input .= "this.form.record.value='';";
                $test_mrkt_input .= "this.form.return_module.value='Campaigns'; ";
                $test_mrkt_input .= "this.form.action.value='QueueCampaign'; ";
                $test_mrkt_input .= "this.form.return_action.value='WizardHome'; ";
                $test_mrkt_input .= "this.form.wiz_mass.value='".$mrkt_focus->id."'; ";
                $test_mrkt_input .= "this.form.mode.value='test'; ";
                $test_mrkt_input .= "this.form.direct_step.value='1'; ";
                $test_mrkt_input .= "this.form.record.value='".$focus->id."'; ";
                $test_mrkt_input .= "this.form.return_id.value='".$focus->id."';\" ";
                $test_mrkt_input .= "class='button' value='".$mod_strings['LBL_TEST_BUTTON_LABEL']."' type='submit'>";

                //create send marketing button input
                $send_mrkt_input =  "<input id='wiz_new_mrkt_button' name='SUBMIT'  ";
                $send_mrkt_input .= "onclick=\"this.form.return_module.value='Campaigns'; ";
                $send_mrkt_input .= "this.form.module.value='Campaigns'; ";
                $send_mrkt_input .= "this.form.record.value='';";
                $send_mrkt_input .= "this.form.return_module.value='Campaigns'; ";
                $send_mrkt_input .= "this.form.action.value='QueueCampaign'; ";
                $send_mrkt_input .= "this.form.return_action.value='WizardHome'; ";
                $send_mrkt_input .= "this.form.wiz_mass.value='".$mrkt_focus->id."'; ";
                $send_mrkt_input .= "this.form.mode.value='send'; ";
                $send_mrkt_input .= "this.form.direct_step.value='1'; ";
                $send_mrkt_input .= "this.form.record.value='".$focus->id."'; ";
                $send_mrkt_input .= "this.form.return_id.value='".$focus->id."';\" ";
                $send_mrkt_input .= "class='button' value='".$mod_strings['LBL_SEND_EMAIL']."' type='submit'>";



             if( $colorclass== "class='evenListRowS1'"){
                    $colorclass= "class='oddListRowS1'";
                }else{
                    $colorclass= "class='evenListRowS1'";
                }

              if(isset($mrkt_focus->name) && !empty($mrkt_focus->name)){
                $mrkt_tbl  .= "<tr $colorclass>";
                $mrkt_tbl  .= "<td scope='row' width='40%'><a href='index.php?action=WizardMarketing&module=Campaigns&return_module=Campaigns&return_action=WizardHome";
                $mrkt_tbl  .= "&return_id=" .$focus->id. "&campaign_id=" .$focus->id."&record=".$mrkt_focus->id."'>".$mrkt_focus->name."</a></td>";
                $mrkt_tbl  .= "<td scope='row' width='25%'>".$mrkt_focus->from_name."</td>";
                $mrkt_tbl  .= "<td scope='row' width='15%'>".$mrkt_focus->status."</td>";
                $mrkt_tbl  .= "<td scope='row' width='10%'>$test_mrkt_input</td>";
                $mrkt_tbl  .= "<td scope='row' width='10%'>$send_mrkt_input</td>";
                $mrkt_tbl  .= "</tr>";

              }
            }
    }else{
        $mrkt_tbl  .= "<tr><td colspan='3'>".$mod_strings['LBL_NONE']."</td></tr>";
    }
    $mrkt_tbl .= "</table></p>";
    return $mrkt_tbl ;
}

function create_target_summary  ($focus){
    global $mod_strings,$app_strings,$app_list_strings;
    $colorclass = '';
    $camp_type = $focus->campaign_type;


    //create schedule table
    $pltbl='';
    //set the title based on campaign type
    $target_title = $mod_strings['LBL_TARGET_LISTS'];
    if($camp_type=='NewsLetter'){
        $target_title = $mod_strings['LBL_NAVIGATION_MENU_SUBSCRIPTIONS'];
    }


    $focus->load_relationship('prospectlists');
    $pl_lists = $focus->prospectlists->get();

    $pl_tbl = "<p><table align='center' class='list view' width='100%' border='0' cellspacing='1' cellpadding='1'>";
    $pl_tbl .= "<tr class='detail view'><td colspan='4'><h4> ".$target_title." </h4></td></tr>";
    $pl_tbl .= "<tr class='listViewHRS1'><td width='50%' scope='col'><b>".$mod_strings['LBL_LIST_NAME']."</b></td><td width='30%' scope='col'><b>".$mod_strings['LBL_LIST_TYPE']."</b></td>";
    $pl_tbl .= "<td width='15%' scope='col'><b>".$mod_strings['LBL_TOTAL_ENTRIES']."</b></td><td width='5%' scope='col'>&nbsp;</td></tr>";

    if(count($pl_lists)>0){

            
            $pl_focus = BeanFactory::newBean('ProspectLists');
            foreach($pl_lists as $pl_id){

             if( $colorclass== "class='evenListRowS1'"){
                    $colorclass= "class='oddListRowS1'";
                }else{
                    $colorclass= "class='evenListRowS1'";
                }

              $pl_focus->retrieve($pl_id);
              //set the list type if this is a newsletter
              $type=$pl_focus->list_type;
              if($camp_type=='NewsLetter'){
                  if (($pl_focus->list_type == 'default') || ($pl_focus->list_type == 'seed')){$type = $mod_strings['LBL_SUBSCRIPTION_TYPE_NAME'];}
                  if($pl_focus->list_type == 'exempt'){$type = $mod_strings['LBL_UNSUBSCRIPTION_TYPE_NAME'];}
                  if($pl_focus->list_type == 'test'){$type = $mod_strings['LBL_TEST_TYPE_NAME'];}
              }else{
                $type = $app_list_strings['prospect_list_type_dom'][$pl_focus->list_type];
              }
              if(isset($pl_focus->id) && !empty($pl_focus->id)){
                $pl_tbl  .= "<tr $colorclass>";
                $pl_tbl  .= "<td scope='row' width='50%'><a href='index.php?action=DetailView&module=ProspectLists&return_module=Campaigns&return_action=WizardHome&return_id=" .$focus->id. "&record=".$pl_focus->id."'>";
                $pl_tbl  .=  $pl_focus->name."</a></td>";
                $pl_tbl  .= "<td scope='row' width='30%'>$type</td>";
                $pl_tbl  .= "<td scope='row' width='15%'>".$pl_focus->get_entry_count()."</td>";
                $pl_tbl  .= "<td scope='row' width='5%' align='right'><a href='index.php?action=EditView&module=ProspectLists&return_module=Campaigns&return_action=WizardHome&return_id=" .$focus->id. "&record=".$pl_focus->id."'>";
                $pl_tbl  .= SugarThemeRegistry::current()->getImage('edit_inline', 'border=0', null, null, ".gif", $mod_strings['LBL_EDIT_INLINE']) . "</a>&nbsp;";


                $pl_tbl  .= "<a href='index.php?action=DetailView&module=ProspectLists&return_module=Campaigns&return_action=WizardHome&return_id=" .$focus->id. "&record=".$pl_focus->id."'>";
                $pl_tbl  .= SugarThemeRegistry::current()->getImage('view_inline', 'border=0', null, null, ".gif", $mod_strings['LBL_VIEW_INLINE'])."</a></td>";


              }
            }
    }else{
     $pl_tbl .= "<tr><td class='$colorclass' scope='row' colspan='2'>".$mod_strings['LBL_NONE']."</td></tr>";
    }

    $pl_tbl .= "</table></p>";
    return $pl_tbl;

}

function create_tracker_summary  ($focus){
    global $mod_strings,$app_strings;
    $colorclass = '';
    $trkr_tbl='';
    //create tracker table
    $focus->load_relationship('tracked_urls');
    $trkr_lists = $focus->tracked_urls->get();

    $trkr_tbl = "<p><table align='center' class='list view' width='100%' border='0' cellspacing='1' cellpadding='1'>";
    $trkr_tbl .= "<tr class='detail view'><td colspan='6'><h4> ".$mod_strings['LBL_NAVIGATION_MENU_TRACKERS']." </h4></td></tr>";
    $trkr_tbl .= "<tr class='listViewHRS1'><td width='15%' scope='col'><b>".$mod_strings['LBL_EDIT_TRACKER_NAME']."</b></td><td width='15%' scope='col'><b>".$mod_strings['LBL_EDIT_TRACKER_URL']."</b></td><td width='15%' scope='col'><b>".$mod_strings['LBL_EDIT_OPT_OUT']."</b></td></tr>";

    if(count($trkr_lists)>0){

        foreach($trkr_lists as $trkr_id){
             if( $colorclass== "class='evenListRowS1'"){
                    $colorclass= "class='oddListRowS1'";
                }else{
                    $colorclass= "class='evenListRowS1'";
                }        
            
            
            $ct_focus = BeanFactory::getBean('CampaignTrackers', $trkr_id);
          if(isset($ct_focus->tracker_name) && !empty($ct_focus->tracker_name)){
            if($ct_focus->is_optout){$opt = 'checked';}else{$opt = '';}
            $trkr_tbl  .= "<tr $colorclass>";
            $trkr_tbl  .= "<td scope='row' ><a href='index.php?action=DetailView&module=CampaignTrackers&return_module=Campaigns&return_action=WizardHome&return_id=" .$focus->id. "&record=".$ct_focus->id."'>";
            $trkr_tbl  .= $ct_focus->tracker_name."</a></td>";
            $trkr_tbl  .= "<td scope='row' width='15%'>".$ct_focus->tracker_url."</td>";
            $trkr_tbl  .= "<td scope='row' width='15%'>&nbsp;&nbsp;<input type='checkbox' class='checkbox' $opt disabled></td>";
            $trkr_tbl  .= "</tr>";
          }
        }
    }else{
        $trkr_tbl  .= "<tr ><td colspan='3'>".$mod_strings['LBL_NONE']."</td>";
    }
    $trkr_tbl .= "</table></p>";
    return $trkr_tbl ;

}


function create_wiz_menu_items($type,$mrkt_string,$camp_url,$summ_url){
    global $mod_strings;

    $steps[$mod_strings['LBL_NAVIGATION_MENU_GEN1']]          = SugarAutoLoader::existingCustomOne('modules/Campaigns/tpls/WizardCampaignHeader.tpl');
    $steps[$mod_strings['LBL_NAVIGATION_MENU_GEN2']]          = SugarAutoLoader::existingCustomOne('modules/Campaigns/tpls/WizardCampaignBudget.tpl');
    $steps[$mod_strings['LBL_NAVIGATION_MENU_TRACKERS']]      = SugarAutoLoader::existingCustomOne('modules/Campaigns/tpls/WizardCampaignTracker.tpl');
    if($type == 'newsletter'){
        $steps[$mod_strings['LBL_NAVIGATION_MENU_SUBSCRIPTIONS']] = SugarAutoLoader::existingCustomOne('modules/Campaigns/tpls/WizardCampaignTargetList.tpl');
    }else{
        $steps[$mod_strings['LBL_TARGET_LISTS']]                  = SugarAutoLoader::existingCustomOne('modules/Campaigns/tpls/WizardCampaignTargetListForNonNewsLetter.tpl');
    }

    $nav_html = '<table border="0" cellspacing="0" cellpadding="0" width="100%" >';
    if(isset($steps)  && !empty($steps)){
        $i=1;
        foreach($steps as $name=>$step){
            $nav_html .= "<tr><td scope='row' nowrap><div id='nav_step$i'><a href='".$camp_url.$i."'>$name</a></div></td></tr>";
            $i=$i+1;
        }
    }

    if($type == 'newsletter'  ||  $type == 'email'){
        $nav_html .= "<td scope='row' nowrap><div id='nav_step'".($i+1).">$mrkt_string</div></td></tr>";
        $nav_html .= "<td scope='row' nowrap><div id='nav_step'".($i+2).">".$mod_strings['LBL_NAVIGATION_MENU_SEND_EMAIL']."</div></td></tr>";
        $nav_html .= "<td scope='row' nowrap><div id='nav_step'".($i+3).">".$summ_url."</div></td></tr>";
    }else{
     $nav_html .= "<td scope='row' nowrap><div id='nav_step'".($i+1).">".$summ_url."</div></td></tr>";
    }

    $nav_html .= '</table>';

    return $nav_html;
}
