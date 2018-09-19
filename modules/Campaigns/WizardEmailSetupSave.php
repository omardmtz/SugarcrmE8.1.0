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
 * $Id: Save.php 17399 2006-10-31 19:18:15 +0000 (Tue, 31 Oct 2006) eddy $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('include/formbase.php');


global $mod_strings;


//get new administration bean for setup
$focus = BeanFactory::newBean('Administration');
$camp_steps[] = 'wiz_step_';
$camp_steps[] = 'wiz_step1_';
$camp_steps[] = 'wiz_step2_';

    //name is used as key in post, it is also used in creation of summary page for wizard,
    //so let's clean up the posting so we can reuse the save functionality for inbound emails and
    //from existing save.php's  
    foreach($camp_steps as $step){
        clean_up_post($step);
    }
/**************************** Save general Email Setup  *****************************/

//we do not need to track location if location type is not set
if(isset($_POST['tracking_entities_location_type'])) {
    if ($_POST['tracking_entities_location_type'] != '2') {
        unset($_POST['tracking_entities_location']);
        unset($_POST['tracking_entities_location_type']);
    }
}
//if the check box is empty, then set it to 0
if(!isset($_POST['mail_smtpauth_req'])) { $_POST['mail_smtpauth_req'] = 0; }
//default ssl use to false
if(!isset($_POST['mail_smtpssl'])) { $_POST['mail_smtpssl'] = 0; }
//reuse existing saveconfig functionality
$focus->saveConfig();



/**************************** Add New Monitored Box  *****************************/
//perform this if the option to create new mail box has been checked
if(isset($_REQUEST['wiz_new_mbox']) && ($_REQUEST['wiz_new_mbox']=='1')){
    
   //Populate the Request variables that inboundemail expects
    $_REQUEST['mark_read'] = 1;
    $_REQUEST['only_since'] = 1;
    $_REQUEST['mailbox_type'] = 'bounce';
    $_REQUEST['from_name'] = $_REQUEST['name'];
    $_REQUEST['group_id'] = 'new';
//    $_REQUEST['from_addr'] = $_REQUEST['wiz_step1_notify_fromaddress'];
    //reuse save functionality for inbound email
    require_once('modules/InboundEmail/Save.php');    

}
    if (!empty($_REQUEST['error'])){
            //an error was found during inbound save.  This means the save was allowed but the inbound box had problems, return user to wizard
            //and display error message
            header("Location: index.php?action=WizardEmailSetup&module=Campaigns&error=true");
    }else{
        //set navigation details
        header("Location: index.php?action=index&module=Campaigns");
    }

/*
 * This function will re-add the post variables that exist with the specified prefix.  
 * It will add them minus the specified prefix.  This is needed in order to reuse the save functionality,
 * which does not expect the prefix, and still use the generic create summary functionality in wizard, which
 * does expect the prefix.  
 */
function clean_up_post($prefix){

    foreach ($_REQUEST as $key => $val) {
              if((strstr($key, $prefix )) && (strpos($key, $prefix )== 0)){
              $newkey  =substr($key, strlen($prefix)) ;
              $_REQUEST[$newkey] = $val;
         }               
    }

    foreach ($_POST as $key => $val) {
              if((strstr($key, $prefix )) && (strpos($key, $prefix )== 0)){
              $newkey  =substr($key, strlen($prefix)) ;
              $_POST[$newkey] = $val;
              
         }               
    }
}

?>
