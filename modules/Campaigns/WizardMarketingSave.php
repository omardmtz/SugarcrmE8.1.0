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

global $timedate;
global $current_user;

$master = 'save';
if (!empty($_REQUEST['wiz_home_next_step'])) {

    if($_REQUEST['wiz_home_next_step']==3){
        //user has chosen to save and schedule this campaign for email
        $master = 'send';
    }elseif($_REQUEST['wiz_home_next_step']==2){
        //user has chosen to save and send this campaign in test mode
        $master = 'test';
    }else{
        //user has chosen to simply save
        $master  = 'save';
    }

}else{
     //default to just saving and exiting wizard
     $master = 'save';
}




$prefix = 'wiz_step3_';
$marketing = BeanFactory::newBean('EmailMarketing');
if (!empty($_REQUEST['record'])) {
    $marketing->retrieve($_REQUEST['record']);
}
if(!$marketing->ACLAccess('Save')){
        ACLController::displayNoAccess(true);
        sugar_cleanup(true);
}

if (!empty($_REQUEST['assigned_user_id']) && ($marketing->assigned_user_id != $_REQUEST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
    $check_notify = TRUE;
}
else {
    $check_notify = FALSE;
}

    foreach ($_REQUEST as $key => $val) {
              if((strstr($key, $prefix )) && (strpos($key, $prefix )== 0)){
              $newkey  =substr($key, strlen($prefix)) ;
              $_REQUEST[$newkey] = $val;
         }
    }

    foreach ($_REQUEST as $key => $val) {
              if((strstr($key, $prefix )) && (strpos($key, $prefix )== 0)){
              $newkey  =substr($key, strlen($prefix)) ;
              $_REQUEST[$newkey] = $val;
         }
    }

if(!empty($_REQUEST['meridiem'])){
    $_REQUEST['time_start'] = $timedate->merge_time_meridiem($_REQUEST['time_start'],$timedate->get_time_format(), $_REQUEST['meridiem']);
}

if(empty($_REQUEST['time_start'])) {
  $_REQUEST['date_start'] = date($timedate->get_date_time_format(), strtotime($_REQUEST['date_start']));
} else {
  $_REQUEST['date_start'] = $_REQUEST['date_start'] . ' ' . $_REQUEST['time_start'];
}

foreach($marketing->column_fields as $field)
{
    if ($field == 'all_prospect_lists') {
        if(isset($_REQUEST[$field]) && $_REQUEST[$field]='on' )
        {
            $marketing->$field = 1;
        } else {
            $marketing->$field = 0;
        }
    }else {
        if(isset($_REQUEST[$field]))
        {
            $value = $_REQUEST[$field];
            $marketing->$field = trim($value);
        }
    }
}

foreach($marketing->additional_column_fields as $field)
{
    if(isset($_REQUEST[$field]))
    {
        $value = $_REQUEST[$field];
        $marketing->$field = $value;
    }
}

$marketing->campaign_id = $_REQUEST['campaign_id'];
$marketing->save($check_notify);

//add prospect lists to campaign.
$marketing->load_relationship('prospectlists');
$prospectlists=$marketing->prospectlists->get();
if ($marketing->all_prospect_lists==1) {
    //remove all related prospect lists.
    if (!empty($prospectlists)) {
        $marketing->prospectlists->delete($marketing->id);
    }
} else {
    if (isset($_REQUEST['message_for']) && is_array($_REQUEST['message_for'])) {
        foreach ($_REQUEST['message_for'] as $prospect_list_id) {

            $key=array_search($prospect_list_id,$prospectlists);
            if ($key === null or $key === false) {
                $marketing->prospectlists->add($prospect_list_id);
            } else {
                unset($prospectlists[$key]);
            }
        }
        if (count($prospectlists) != 0) {
            foreach ($prospectlists as $key=>$list_id) {
                $marketing->prospectlists->delete($marketing->id,$list_id);
            }
        }
    }
}

//populate an array with marketing email id to use
$mass[] = $marketing->id;
//if sending an email was chosen, set all the needed variables for queuing campaign

if($master !='save'){
    $_REQUEST['mass']= $mass;
    $_POST['mass']=$mass;
    $_REQUEST['record'] =$marketing->campaign_id;
    $_POST['record']=$marketing->campaign_id;
    $_REQUEST['mode'] = $master;
     $_POST['mode'] = $master;
     $_REQUEST['from_wiz']= 'true';
    require_once('modules/Campaigns/QueueCampaign.php');
}

$header_URL = "Location: index.php?action=WizardHome&module=Campaigns&record=".$marketing->campaign_id;
$GLOBALS['log']->debug("about to post header URL of: $header_URL");
header($header_URL);

?>
