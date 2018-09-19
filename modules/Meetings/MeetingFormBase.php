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

 * Description:  Base Form For Meetings
 * Portions created by SugarCRM are Copyright(C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class MeetingFormBase extends FormBase {

    protected $repeatDataArray = array();
    
    protected $recurringCreated = array();

	function getFormBody($prefix, $mod='', $formname=''){
		if(!ACLController::checkAccess('Meetings', 'edit', true)){
		return '';
	}
		global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		global $current_user;
		global $theme;
		global $timedate;

		$temp_strings = $mod_strings;
		if(!empty($mod)){
			global $current_language;
			$mod_strings = return_module_language($current_language, $mod);
		}
			// Unimplemented until jscalendar language files are fixed
			// global $current_language;
			// global $default_language;
			// global $cal_codes;

	$cal_lang = "en";
$cal_dateformat = $timedate->get_cal_date_format();

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_date = $mod_strings['LBL_DATE'];
$lbl_time = $mod_strings['LBL_TIME'];
$ntc_date_format = $timedate->get_user_date_format();
$ntc_time_format = '('.$timedate->get_user_time_format().')';

	$user_id = $current_user->id;
$default_status = $app_list_strings['meeting_status_default'];
$default_parent_type= $app_list_strings['record_type_default_key'];
$default_date_start = $timedate->nowDbDate();
$default_time_start = $timedate->nowDbTime();
$time_ampm = $timedate->AMPMMenu($prefix, $timedate->nowDbTime());
			// Unimplemented until jscalendar language files are fixed
			// $cal_lang =(empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
$jsCalendarImage = SugarThemeRegistry::current()->getImageURL('jscalendar.gif');
			$form = <<<EOF
					<input type="hidden" name="${prefix}record" value="">
					<input type="hidden" name="${prefix}status" value="${default_status}">
					<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
					<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
					<input type="hidden" name="${prefix}duration_hours" value="1">
					<input type="hidden" name="${prefix}duration_minutes" value="00">
					<p>$lbl_subject<span class="required">$lbl_required_symbol</span><br>
					<input name='${prefix}name' size='25' maxlength='255' type="text"><br>
					$lbl_date&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_date_format</span><br>
					<input name='${prefix}date_start' id='jscal_field' onblur="parseDate(this, '$cal_dateformat');" type="text" maxlength="10" value="${default_date_start}"> <!--not_in_theme!--><img src="{$jscalendarImage}" alt="{$app_strings['LBL_ENTER_DATE']}"  id="jscal_trigger" align="absmiddle"><br>
					$lbl_time&nbsp;<span class="required">$lbl_required_symbol</span>&nbsp;<span class="dateFormat">$ntc_time_format</span><br>
					<input name='${prefix}time_start' type="text" maxlength='5' value="${default_time_start}">{$time_ampm}</p>
					<script type="text/javascript">
					Calendar.setup({
						inputField : "jscal_field", daFormat : "$cal_dateformat", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1, weekNumbers:false
					});
					</script>
EOF;


$javascript = new javascript();
$javascript->setFormName($formname);
$javascript->setSugarBean(BeanFactory::newBean('Meetings'));
$javascript->addRequiredFields($prefix);
$form .=$javascript->getScript();
$mod_strings = $temp_strings;
return $form;
}



function getForm($prefix, $mod='Meetings'){
	if(!ACLController::checkAccess('Meetings', 'edit', true)){
		return '';
	}

		global $app_strings;
		global $app_list_strings;

		if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
		} else {
			global $mod_strings;
		}

		$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
		$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
		$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ


		<form name="${prefix}MeetingSave" onSubmit="return check_form('${prefix}MeetingSave')" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Meetings">

			<input type="hidden" name="${prefix}action" value="Save">

EOQ;
$the_form	.= $this->getFormBody($prefix, 'Meetings',"{$prefix}MeetingSave" );
$the_form .= <<<EOQ
		<p><input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " ></p>
		</form>
EOQ;

$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;
}


/**
 * handles save functionality for meetings
 * @param	string prefix
 * @param	bool redirect default True
 * @param	bool useRequired default True
 */
function handleSave($prefix,$redirect=true, $useRequired=false) {


	require_once('include/formbase.php');

	global $current_user;
	global $timedate;

	$focus = BeanFactory::newBean('Meetings');

	if($useRequired && !checkRequired($prefix, array_keys($focus->required_fields))) {
		return null;
	}

        if (!isset($_POST['reminder_checked']) ||
            ( isset($_POST['reminder_checked']) && $_POST['reminder_checked'] == '0')) {
            $_POST['reminder_time'] = -1;
        }
        if (!isset($_POST['reminder_time'])) {
            $_POST['reminder_time'] = $current_user->getPreference('reminder_time');
            $_POST['reminder_checked']=1;
        }
	
        if (!isset($_POST['email_reminder_checked']) ||
            (isset($_POST['email_reminder_checked']) && $_POST['email_reminder_checked'] == '0')) {
            $_POST['email_reminder_time'] = -1;
        }
        if (!isset($_POST['email_reminder_time'])) {
            $_POST['email_reminder_time'] = $current_user->getPreference('email_reminder_time');
            $_POST['email_reminder_checked'] = 1;
        }
    if (isset($_POST['repeat_parent_id']) && trim($_POST['repeat_parent_id']) == '') {
        unset($_POST['repeat_parent_id']);
    }
	
	// don't allow to set recurring_source from a form
	unset($_POST['recurring_source']);
	
	$time_format = $timedate->get_user_time_format();
    $time_separator = ":";
    if(preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
       $time_separator = $match[1];
    }

	if(!empty($_POST[$prefix.'time_hour_start']) && empty($_POST['time_start'])) {
		$_POST[$prefix.'time_start'] = $_POST[$prefix.'time_hour_start']. $time_separator .$_POST[$prefix.'time_minute_start'];
	}

	if(isset($_POST[$prefix.'meridiem']) && !empty($_POST[$prefix.'meridiem'])) {
		$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'],$timedate->get_time_format(), $_POST[$prefix.'meridiem']);
	}

	if(isset($_POST[$prefix.'time_start']) && strlen($_POST[$prefix.'date_start']) == 10) {
       $_POST[$prefix.'date_start'] = $_POST[$prefix.'date_start'] . ' ' . $_POST[$prefix.'time_start'];
	}

	// retrieve happens here
	$focus = populateFromPost($prefix, $focus);
	if(!$focus->ACLAccess('Save')) {
	   ACLController::displayNoAccess(true);
	   sugar_cleanup(true);
	}

    // if dates changed
    if (!empty($focus->id)) {
        $oldBean = new Meeting();
        $oldBean->retrieve($focus->id);
        if (($focus->date_start != $oldBean->date_start) || ($focus->date_end != $oldBean->date_end)) {
            $focus->date_changed = true;
        } else {
            $focus->date_changed = false;
        }
    }

    $newBean = true;
    if (!empty($focus->id)) {
        $newBean = false;
    }

	//add assigned user and current user if this is the first time bean is saved
  	if(empty($focus->id) && !empty($_REQUEST['return_module']) && $_REQUEST['return_module'] =='Meetings' && !empty($_REQUEST['return_action']) && $_REQUEST['return_action'] =='DetailView'){
		//if return action is set to detail view and return module to meeting, then this is from the long form, do not add the assigned user (only the current user)
		//The current user is already added to UI and we want to give the current user the option of opting out of meeting.
  	 	//add current user if the assigned to user is different than current user.
	  	if($current_user->id != $_POST['assigned_user_id']){
	  		$_POST['user_invitees'] .= ','.$_POST['assigned_user_id'].', ';
    		$_POST['user_invitees'] = str_replace(',,', ',', $_POST['user_invitees']);
	  	}
  	}elseif (empty($focus->id) ){
	  	//this is not from long form so add assigned and current user automatically as there is no invitee list UI.
	  	//This call could be through an ajax call from subpanels or shortcut bar
        if(!isset($_POST['user_invitees']))
        {
           $_POST['user_invitees'] = '';
        }

	  	$_POST['user_invitees'] .= ','.$_POST['assigned_user_id'].', ';

	  	//add current user if the assigned to user is different than current user.
	  	if($current_user->id != $_POST['assigned_user_id'] && $_REQUEST['module'] != "Calendar"){
	  		$_POST['user_invitees'] .= ','.$current_user->id.', ';
	  	}

	  	//remove any double comma's introduced during appending
	    $_POST['user_invitees'] = str_replace(',,', ',', $_POST['user_invitees']);
  	}


	if( (isset($_POST['isSaveFromDetailView']) && $_POST['isSaveFromDetailView'] == 'true') ||
        (isset($_POST['is_ajax_call']) && !empty($_POST['is_ajax_call']) && !empty($focus->id) ||
        (isset($_POST['return_action']) && $_POST['return_action'] == 'SubPanelViewer') && !empty($focus->id))||
         !isset($_POST['user_invitees']) // we need to check that user_invitees exists before processing, it is ok to be empty
    ){
        $focus->save(true);
        $return_id = $focus->id;
	}else{
		if($focus->status == 'Held' && $this->isEmptyReturnModuleAndAction() && !$this->isSaveFromDCMenu()){
    		//if we are closing the meeting, and the request does not have a return module AND return action set and it is not a save
            //being triggered by the DCMenu (shortcut bar) then the request is coming from a dashlet or subpanel close icon and there is no
            //need to process user invitees, just save the current values.
    		$focus->save(true);
	    }else{	    	
            $relate_to = $this->getRelatedModuleName($focus);
            $userInvitees = array();
            $contactInvitees = array();
            $leadInvitees = array();
           
                $existingUserInvitees = array();
            $existingContacts = array();
            $existingLeads =  array();

            if (!empty($_POST['user_invitees'])) {
               $userInvitees = explode(',', trim($_POST['user_invitees'], ','));
            }
            if (!empty($_POST['existing_invitees'])) {
                    $existingUserInvitees = explode(",", trim($_POST['existing_invitees'], ','));
            }
           
            if (!empty($_POST['contact_invitees'])) {
               $contactInvitees = explode(',', trim($_POST['contact_invitees'], ','));
            }
            if (!empty($_POST['existing_contact_invitees'])) {
                $existingContacts =  explode(",", trim($_POST['existing_contact_invitees'], ','));
            }  
	        if (!empty($_POST['parent_id']) && $_POST['parent_type'] == 'Contacts') {
                $contactInvitees[] = $_POST['parent_id'];
            }                  
            if ($relate_to == 'Contacts') {
                if (!empty($_REQUEST['relate_id']) && !in_array($_REQUEST['relate_id'], $contactInvitees)) {
                    $contactInvitees[] = $_REQUEST['relate_id'];
                } 
            }
            
            if (!empty($_POST['lead_invitees'])) {
                $leadInvitees = explode(',', trim($_POST['lead_invitees'], ','));
            }            
            if (!empty($_POST['existing_lead_invitees'])) {
                $existingLeads =  explode(",", trim($_POST['existing_lead_invitees'], ','));
            }
	        if (!empty($_POST['parent_id']) && $_POST['parent_type'] == 'Leads') {
                $leadInvitees[] = $_POST['parent_id'];
            }
            if ($relate_to == 'Leads') {
                if (!empty($_REQUEST['relate_id']) && !in_array($_REQUEST['relate_id'], $leadInvitees)) {
                    $leadInvitees[] = $_REQUEST['relate_id'];
                } 
            }

            // Call the Meeting module's save function to handle saving other fields besides
            // the users and contacts relationships
            $focus->update_vcal = false;    // Bug #49195 : don't update vcal b/s related users aren't saved yet, create vcal cache below
            
            $focus->users_arr = $userInvitees;
            $focus->contacts_arr = $contactInvitees;
            $focus->leads_arr = $leadInvitees;

            $focus->save(true);
            $return_id = $focus->id;
            if (empty($return_id)) {
                //this is to handle the situation where the save fails, most likely because of a failure
                //in the external api. bug: 42200
                $_REQUEST['action'] = 'EditView';
                $_REQUEST['return_action'] = 'EditView';
                handleRedirect('', 'Meetings');
            }

                // Collect existing users after calling save()
                // Note: some users may have been added/removed as part of save()
                $focus->load_relationship('users');
                $existingUsers = array();
                foreach ($focus->users->get() as $userId) {
                    $existingUsers[$userId] = true;
                }
                // Fold in any User Ids that may have peen Posted on the Request
                foreach ($existingUserInvitees as $userId) {
                    $existingUsers[$userId] = true;
                }

            $focus->setUserInvitees($userInvitees, $existingUsers);
            $focus->setContactInvitees($contactInvitees, $existingContacts);

            $focus->setLeadInvitees($focus->leads_arr, $existingLeads);

            // Bug #49195 : update vcal
            vCal::cache_sugar_vcal($current_user);
            
            $this->processRecurring($focus);
		}
	}

    if (isset($_REQUEST['return_module']) && $_REQUEST['return_module'] === 'Home') {
        SugarApplication::redirect(buildRedirectURL('', 'Home'));

    } else if ($redirect) {
        handleRedirect($return_id, 'Meetings');

    } else {
        return $focus;
    }

} // end handleSave();

    /**
     * Saves recurring records if needed. Flushes existing recurrences if needed.
     */
    protected function processRecurring(Meeting $focus)
    {
            if (!empty($_REQUEST['edit_all_recurrences'])) {
                // flush existing recurrence
                CalendarUtils::markRepeatDeleted($focus);
            }            
            if (count($this->repeatDataArray) > 0) {
                // prevent sending invites for recurring activities
                unset($_REQUEST['send_invites'], $_POST['send_invites']);
                $this->recurringCreated = CalendarUtils::saveRecurring($focus, $this->repeatDataArray);
            }
    }

    /**
     * Prepare recurring sequence if needed.
     * @return bool true if recurring records need to be created
     */
    public function prepareRecurring()
    {       
        
        if (empty($_REQUEST['edit_all_recurrences'])) {        
            $repeatFields = array('type', 'interval', 'count', 'until', 'dow', 'parent_id');
            foreach ($repeatFields as $param) {
                unset($_POST['repeat_' . $param]);
            }           
        } else if (!empty($_REQUEST['repeat_type']) && !empty($_REQUEST['date_start'])) {        
            $params = array(
                    'type' => $_REQUEST['repeat_type'],
                    'interval' => $_REQUEST['repeat_interval'],
                    'count' => $_REQUEST['repeat_count'],    
                    'until' => $_REQUEST['repeat_until'],    
                    'dow' => $_REQUEST['repeat_dow'],            
            );                            
            $this->repeatDataArray = CalendarUtils::buildRecurringSequence($_REQUEST['date_start'], $params);
            return true;
        }
        return false;
    }
    
    /**
     * Check if amount of recurring records is exceeding the limit. 
     * @return bool/int Limit if exceeded or fase if not exceeded.
     */
    public function checkRecurringLimitExceeded()
    {
        $limit = SugarConfig::getInstance()->get('calendar.max_repeat_count', 1000);            
        if (count($this->repeatDataArray) > ($limit - 1)) {
            return $limit;
        }
        return false;
    }
    
    /**
     * Returns list of created recurrings records. Id and date start. 
     * @return array
     */
    public function getRecurringCreated()
    {
        return $this->recurringCreated;
    }

} // end Class def
?>
