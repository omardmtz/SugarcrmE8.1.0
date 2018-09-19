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


class Meeting extends SugarBean {
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $team_id;
	var $description;
	var $name;
	var $location;
	var $status;
	var $type;
	var $date_start;
	var $time_start;
	var $date_end;
	var $duration_hours;
	var $duration_minutes;
	var $time_meridiem;
	var $parent_type;
	var $parent_type_options;
	var $parent_id;
	var $contact_id;
	var $user_id;
	var $meeting_id;
	var $reminder_time;
	var $reminder_checked;
	var $email_reminder_time;
	var $email_reminder_checked;
	var $email_reminder_sent;
	var $required;
	var $accept_status;
	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;
	var $outlook_id;
	var $sequence;
	var $recurring_source;

	var $team_name;
	var $update_vcal = true;
	var $contacts_arr = array();
	var $users_arr = array();
	var $leads_arr = array();
	var $meetings_arr;
	// when assoc w/ a user/contact:
	var $minutes_value_default = 15;
	var $minutes_values = array('0'=>'00','15'=>'15','30'=>'30','45'=>'45');
	var $table_name = "meetings";
	var $rel_users_table = "meetings_users";
	var $rel_contacts_table = "meetings_contacts";
	var $rel_leads_table = "meetings_leads";
	var $module_dir = "Meetings";
	var $object_name = "Meeting";

	var $importable = true;
	var $fill_additional_column_fields = true;
	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name', 'accept_status');
	var $relationship_fields = array(
        'account_id' => 'accounts',
        'opportunity_id' => 'opportunity',
        'case_id' => 'case',
        'assigned_user_id' => 'users',
        'contact_id' => 'contacts',
        'user_id' => 'users',
    );
	// so you can run get_users() twice and run query only once
	var $cached_get_users = null;
	var $new_schema = true;
    var $date_changed = false;

	public $send_invites = false;

    /**
     * Parent id of recurring.
     * @var string
     */
    public $repeat_parent_id = null;

    /**
     * Recurrence id. Original start date of event.
     * @var string
     */
    public $recurrence_id = null;

	/**
	 * sole constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->setupCustomFields('Meetings');
		foreach($this->field_defs as $field) {
		    if(empty($field['name'])) {
		        continue;
		    }
            $this->field_defs[$field['name']] = $field;
		}

        if(!empty($GLOBALS['app_list_strings']['duration_intervals'])) {
            $this->minutes_values = $GLOBALS['app_list_strings']['duration_intervals'];
        }
	}

	/**
	 * Stub for integration
	 * @return bool
	 */
	function hasIntegratedMeeting() {
		return false;
	}

    // save date_end by calculating user input
    public function save($check_notify = false)
    {
        global $timedate, $current_user;

        $isUpdate = $this->isUpdate();

        if (isset($this->date_start)) {
            $td = $timedate->fromDb($this->date_start);
            if (!$td) {
                $this->date_start = $timedate->to_db($this->date_start);
                $td = $timedate->fromDb($this->date_start);
            }
            if ($td) {
                $calEvent = new CalendarEvents();
                $calEvent->setStartAndEndDateTime($this, $td);
            }
        }

        if ($this->repeat_type && $this->repeat_type != 'Weekly') {
                $this->repeat_dow = '';
        }

        if ($this->repeat_selector == 'None') {
            $this->repeat_unit = '';
            $this->repeat_ordinal = '';
            $this->repeat_days = '';
        }

        $check_notify = $this->send_invites;
        if ($this->send_invites == false && $this->isEmailNotificationNeeded()) {
            $this->special_notification = true;
            $check_notify = true;
            CalendarEvents::setOldAssignedUserValue($this->assigned_user_id);
            if (isset($_REQUEST['assigned_user_name'])) {
                $this->new_assigned_user_name = $_REQUEST['assigned_user_name'];
            }
        }

        // prevent a mass mailing for recurring meetings created in Calendar module
        $isRecurringInCalendar = empty($this->id) && !empty($_REQUEST['module']) && $_REQUEST['module'] == "Calendar" &&
            !empty($_REQUEST['repeat_type']) && !empty($this->repeat_parent_id);
        if ($isRecurringInCalendar) {
            $check_notify = false;
        }

        if (empty($this->status) ) {
            $this->status = $this->getDefaultStatus();
        }

        // Do any external API saving
        // Clear out the old external API stuff if we have changed types
        if (isset($this->fetched_row) && $this->fetched_row['type'] != $this->type ) {
            $this->join_url = null;
            $this->host_url = null;
            $this->external_id = null;
            $this->creator = null;
        }

        if (!empty($this->type) && $this->type != 'Sugar' ) {
            $api = ExternalAPIFactory::loadAPI($this->type);
        }

        if (empty($this->type)) {
			$this->type = 'Sugar';
		}

        if ( isset($api) && is_a($api,'WebMeeting') && empty($this->in_relationship_update) ) {
            // Make sure the API initialized and it supports Web Meetings
            // Also make sure we have an ID, the external site needs something to reference
            if (!isset($this->id) || empty($this->id)) {
                $this->id = create_guid();
                $this->new_with_id = true;
            }
            // formatting fix required because our schedule meeting APIs expect data in a specific format
            $this->fixUpFormatting();
            $response = $api->scheduleMeeting($this);
            if ( $response['success'] == TRUE ) {
                // Need to send out notifications
                if ( $api->canInvite ) {
                    $notifyList = $this->get_notification_recipients();
                    foreach($notifyList as $person) {
                        $api->inviteAttendee($this,$person,$check_notify);
                    }

                }
            } else {
                // Generic Message Provides no value to End User - Log the issue with message detail and continue
                // SugarApplication::appendErrorMessage($GLOBALS['app_strings']['ERR_EXTERNAL_API_SAVE_FAIL']);
                $GLOBALS['log']->warn('ERR_EXTERNAL_API_SAVE_FAIL' . ": " . $this->type . " - " .  $response['errorMessage']);
            }

            $api->logoff();
        }

        $return_id = parent::save($check_notify);

        // This function requires that the ID be set and therefore must come after parent::save()
        $this->handleInviteesForUserAssign($isUpdate);

        if ($this->update_vcal) {
            $assigned_user = BeanFactory::getBean('Users', $this->assigned_user_id);
            vCal::cache_sugar_vcal($assigned_user);
            if ($this->assigned_user_id != $GLOBALS['current_user']->id) {
                vCal::cache_sugar_vcal($current_user);
            }
        }

        // CCL - Comment out call to set $current_user as invitee
        // set organizer to auto-accept
        // if there isn't a fetched row its new
        if (!$isUpdate) {
            $organizer = ($this->assigned_user_id == $GLOBALS['current_user']->id) ?
                $GLOBALS['current_user'] : BeanFactory::getBean('Users', $this->assigned_user_id);
            $this->set_accept_status($organizer, 'accept');
        }

		return $return_id;
	}

    /**
     * @inheritdoc
     */
    function mark_deleted($id)
    {
        if (!$id) {
            return null;
        }
        if ($this->id != $id) {
            BeanFactory::getBean($this->module_name, $id)->mark_deleted($id);
            return null;
        }
        CalendarUtils::correctRecurrences($this, $id);
        parent::mark_deleted($id);
        if ($this->update_vcal) {
            global $current_user;
            vCal::cache_sugar_vcal($current_user);
        }
    }

	function get_summary_text() {
		return "$this->name";
	}

	function fill_in_additional_detail_fields() {
		global $locale;

		if ($this->fill_additional_column_fields) {
			parent::fill_in_additional_detail_fields();
		}

		if (!isset($this->time_hour_start)) {
			$this->time_start_hour = intval(substr($this->time_start, 0, 2));
		} //if-else

		if (isset($this->time_minute_start)) {
			$time_start_minutes = $this->time_minute_start;
		} else {
			$time_start_minutes = substr($this->time_start, 3, 5);
			if ($time_start_minutes > 0 && $time_start_minutes < 15) {
				$time_start_minutes = "15";
			} else if ($time_start_minutes > 15 && $time_start_minutes < 30) {
				$time_start_minutes = "30";
			} else if ($time_start_minutes > 30 && $time_start_minutes < 45) {
				$time_start_minutes = "45";
			} else if ($time_start_minutes > 45) {
				$this->time_start_hour += 1;
				$time_start_minutes = "00";
		    } //if-else
		} //if-else


		if (isset($this->time_hour_start)) {
			$time_start_hour = $this->time_hour_start;
		} else {
			$time_start_hour = intval(substr($this->time_start, 0, 2));
		}

		global $timedate;
        $this->time_meridiem = $timedate->AMPMMenu('', $this->time_start, 'onchange="SugarWidgetScheduler.update_time();"');
		$hours_arr = array ();
		$num_of_hours = 13;
		$start_at = 1;

		if (empty ($time_meridiem)) {
			$num_of_hours = 24;
			$start_at = 0;
		} //if

		for ($i = $start_at; $i < $num_of_hours; $i ++) {
			$i = $i."";
			if (strlen($i) == 1) {
				$i = "0".$i;
			}
			$hours_arr[$i] = $i;
		} //for

        if (!isset($this->duration_minutes)) {
			$this->duration_minutes = $this->minutes_value_default;
		}

        //setting default date and time
		if (is_null($this->date_start))
			$this->date_start = $timedate->now();
		if (is_null($this->time_start))
			$this->time_start = $timedate->to_display_time(TimeDate::getInstance()->nowDb(), true);
		if (is_null($this->duration_hours)) {
			$this->duration_hours = "0";
		}
		if (is_null($this->duration_minutes))
			$this->duration_minutes = "1";

		if(empty($this->id) && !empty($_REQUEST['date_start'])){
			$this->date_start = $_REQUEST['date_start'];
		}
        if(!empty($this->date_start))
        {
            $td = $timedate->fromDb($this->date_start);
            if (!empty($td))
            {
    	        if (!empty($this->duration_hours) && $this->duration_hours != '')
                {
		            $td = $td->modify("+{$this->duration_hours} hours");
		        }
                if (!empty($this->duration_minutes) && $this->duration_minutes != '')
                {
                    $td = $td->modify("+{$this->duration_minutes} mins");
                }
                $this->date_end = $timedate->asDb($td);
            }
            else
            {
                $GLOBALS['log']->fatal("Meeting::save: Bad date {$this->date_start} for format ".$GLOBALS['timedate']->get_date_time_format());
            }
		}

		global $app_list_strings;
		if (empty($this->reminder_time)) {
			$this->reminder_time = -1;
		}

		if ( empty($this->id) ) {
		    $reminder_t = $GLOBALS['current_user']->getPreference('reminder_time');
		    if ( isset($reminder_t) )
		        $this->reminder_time = $reminder_t;
		}
		$this->reminder_checked = $this->reminder_time == -1 ? false : true;

		if (empty($this->email_reminder_time)) {
			$this->email_reminder_time = -1;
		}
		if(empty($this->id)){
			$reminder_t = $GLOBALS['current_user']->getPreference('email_reminder_time');
			if(isset($reminder_t))
		    		$this->email_reminder_time = $reminder_t;
		}
		$this->email_reminder_checked = $this->email_reminder_time == -1 ? false : true;

		if (isset ($_REQUEST['parent_type']) && empty($this->parent_type)) {
			$this->parent_type = $_REQUEST['parent_type'];
		} elseif (is_null($this->parent_type)) {
			$this->parent_type = $app_list_strings['record_type_default_key'];
		}

        // Fill in the meeting url for external account types
        if ( !empty($this->id) && !empty($this->type) && $this->type != 'Sugar' && !empty($this->join_url) ) {
            // It's an external meeting
            global $mod_strings;

            $meetingLink = '';
            if ($GLOBALS['current_user']->id == $this->assigned_user_id ) {
                $meetingLink .= '<a href="index.php?module=Meetings&action=JoinExternalMeeting&meeting_id='.$this->id.'&host_meeting=1" target="_blank">'.SugarThemeRegistry::current()->getImage("start_meeting_inline", 'border="0" ', 18, 19, ".png", translate('LBL_HOST_EXT_MEETING',$this->module_dir)).'</a>';
            }

            $meetingLink .= '<a href="index.php?module=Meetings&action=JoinExternalMeeting&meeting_id='.$this->id.'" target="_blank">'.SugarThemeRegistry::current()->getImage("join_meeting_inline", 'border="0" ', 18, 19, ".png", translate('LBL_JOIN_EXT_MEETING',$this->module_dir)).'</a>';

          $this->displayed_url = $meetingLink;
        }
	}

	function get_list_view_data() {
		$meeting_fields = $this->get_list_view_array();

		global $app_list_strings, $focus, $action, $currentModule;
		if(isset($this->parent_type))
			$meeting_fields['PARENT_MODULE'] = $this->parent_type;
		if($this->status == "Planned") {
			//cn: added this if() to deal with sequential Closes in Meetings.	this is a hack to a hack(formbase.php->handleRedirect)
			if(empty($action))
			     $action = "index";
            $setCompleteUrl = "<a id='{$this->id}' onclick='SUGAR.util.closeActivityPanel.show(\"{$this->module_dir}\",\"{$this->id}\",\"Held\",\"listview\",\"1\");'>";
			if ($this->ACLAccess('edit')) {
                $meeting_fields['SET_COMPLETE'] = $setCompleteUrl . SugarThemeRegistry::current()->getImage("close_inline"," border='0'",null,null,'.gif',translate('LBL_CLOSEINLINE'))."</a>";
            } else {
                $meeting_fields['SET_COMPLETE'] = '';
            }
		}
		global $timedate;
		$today = $timedate->nowDb();
		$nextday = $timedate->asDbDate($timedate->getNow()->get("+1 day"));
		$mergeTime = $meeting_fields['DATE_START']; //$timedate->merge_date_time($meeting_fields['DATE_START'], $meeting_fields['TIME_START']);
		$date_db = $timedate->to_db($mergeTime);
		if($date_db	< $today	) {
			$meeting_fields['DATE_START']= "<font class='overdueTask'>".$meeting_fields['DATE_START']."</font>";
		}else if($date_db	< $nextday) {
			$meeting_fields['DATE_START'] = "<font class='todaysTask'>".$meeting_fields['DATE_START']."</font>";
		} else {
			$meeting_fields['DATE_START'] = "<font class='futureTask'>".$meeting_fields['DATE_START']."</font>";
		}
		$this->fill_in_additional_detail_fields();

        // make sure we grab the localized version of the contact name, if a contact is provided
        if (!empty($this->contact_id))
        {
            $contact_temp = BeanFactory::getBean("Contacts", $this->contact_id);
            if (!empty($contact_temp))
            {
                // Make first name, last name, salutation and title of Contacts respect field level ACLs
                $contact_temp->_create_proper_name_field();
                $this->contact_name = $contact_temp->full_name;
            }
        }

        $meeting_fields['CONTACT_ID'] = $this->contact_id;
        $meeting_fields['CONTACT_NAME'] = $this->contact_name;
		$meeting_fields['PARENT_NAME'] = $this->parent_name;
        $meeting_fields['REMINDER_CHECKED'] = $this->reminder_time==-1 ? false : true;
        $meeting_fields['EMAIL_REMINDER_CHECKED'] = $this->email_reminder_time==-1 ? false : true;

        $oneHourAgo = gmdate($GLOBALS['timedate']->get_db_date_time_format(), time()-3600);
        if(!empty($this->host_url) && $date_db	>= $oneHourAgo) {
            if($this->assigned_user_id == $GLOBALS['current_user']->id){
                $join_icon = SugarThemeRegistry::current()->getImage('start_meeting_inline', 'border="0"',null,null,'.gif',translate('LBL_HOST_EXT_MEETING',$this->module_dir));
                $meeting_fields['OBJECT_IMAGE_ICON'] = 'start_meeting_inline';
                $meeting_fields['DISPLAYED_URL'] = 'index.php?module=Meetings&action=JoinExternalMeeting&meeting_id='.$this->id.'&host_meeting=1';
            }else{
                $join_icon = SugarThemeRegistry::current()->getImage('join_meeting_inline', 'border="0"',null,null,'.gif',translate('LBL_JOIN_EXT_MEETING',$this->module_dir));
                $meeting_fields['OBJECT_IMAGE_ICON'] = 'join_meeting_inline';
                $meeting_fields['DISPLAYED_URL'] = 'index.php?module=Meetings&action=JoinExternalMeeting&meeting_id='.$this->id.'&host_meeting=0';
            }
        }

		$meeting_fields['JOIN_MEETING']  = '';
		if(!empty($meeting_fields['DISPLAYED_URL'])){
			$meeting_fields['JOIN_MEETING']= '<a href="' . $meeting_fields['DISPLAYED_URL']. '" target="_blank">' . $join_icon . '</a>';
		}

		return $meeting_fields;
	}

	function set_notification_body($xtpl, &$meeting) {
		global $sugar_config;
		global $app_list_strings;
		global $current_user;
		global $timedate;

		// cn: bug 9494 - passing a contact breaks this call
		$notifyUser =($meeting->current_notify_user->object_name == 'User') ? $meeting->current_notify_user : $current_user;
		// cn: bug 8078 - fixed call to $timedate

        if (strtolower(get_class($meeting->current_notify_user)) == 'contact') {
            $xtpl->assign(
                "ACCEPT_URL",
                $sugar_config['site_url'] . '/index.php?entryPoint=acceptDecline&module=Meetings&contact_id=' .
                $meeting->current_notify_user->id . '&record=' . $meeting->id
            );
        } elseif (strtolower(get_class($meeting->current_notify_user)) == 'lead') {
            $xtpl->assign(
                "ACCEPT_URL",
                $sugar_config['site_url'] . '/index.php?entryPoint=acceptDecline&module=Meetings&lead_id=' .
                $meeting->current_notify_user->id . '&record=' . $meeting->id
            );
        } else {
            $xtpl->assign(
                "ACCEPT_URL",
                $sugar_config['site_url'] . '/index.php?entryPoint=acceptDecline&module=Meetings&user_id=' .
                $meeting->current_notify_user->id . '&record=' . $meeting->id
            );
        }
		$xtpl->assign("MEETING_TO", $meeting->current_notify_user->new_assigned_user_name);
		$xtpl->assign("MEETING_SUBJECT", trim($meeting->name));
		$xtpl->assign("MEETING_STATUS",(isset($meeting->status)? $app_list_strings['meeting_status_dom'][$meeting->status]:""));
        $typekey = strtolower($meeting->type);
        if (isset($meeting->type)) {
            if (!empty($app_list_strings['eapm_list'][$meeting->type])) {
                $typestring = $app_list_strings['eapm_list'][$meeting->type];
            } elseif (!empty($app_list_strings['eapm_list'][$typekey])) {
                $typestring = $app_list_strings['eapm_list'][$typekey];
            } else {
                $typestring = $app_list_strings['meeting_type_dom'][$meeting->type];
            }
        }
        $xtpl->assign("MEETING_TYPE", isset($meeting->type) ? $typestring : "");
        $startdate = $timedate->fromDb($meeting->date_start);
        $xtpl->assign(
            "MEETING_STARTDATE",
            $timedate->asUser($startdate, $notifyUser) . " " . TimeDate::userTimezoneSuffix($startdate, $notifyUser)
        );
        $enddate = $timedate->fromDb($meeting->date_end);
        $xtpl->assign(
            "MEETING_ENDDATE",
            $timedate->asUser($enddate, $notifyUser) . " " . TimeDate::userTimezoneSuffix($enddate, $notifyUser)
        );
        $xtpl->assign("MEETING_HOURS", $meeting->duration_hours);
        $xtpl->assign("MEETING_MINUTES", $meeting->duration_minutes);
        $xtpl->assign("MEETING_DESCRIPTION", $meeting->description);
        if ( !empty($meeting->join_url) ) {
            $xtpl->assign('MEETING_URL', $meeting->join_url);
            $xtpl->parse('Meeting.Meeting_External_API');
        }

		return $xtpl;
	}

	/**
	 * Redefine method to attach ics file to notification email
	 */
	public function create_notification_email($notify_user){
        // reset acceptance status for non organizer if date is changed
        if (($notify_user->id != $GLOBALS['current_user']->id) && $this->date_changed) {
            $this->set_accept_status($notify_user, 'none');
        }

		$mailer = parent::create_notification_email($notify_user);

		$path = SugarConfig::getInstance()->get('upload_dir','upload/') . $this->id;

        $content = vCal::get_ical_event($this, $GLOBALS['current_user']);

        if (file_put_contents($path, $content)) {
            $attachment = new Attachment($path, "meeting.ics", Encoding::Base64, "text/calendar");
            $mailer->addAttachment($attachment);
        }

		return $mailer;
	}

	/**
	 * Redefine method to remove ics after email is sent
	 */
	public function send_assignment_notifications($notify_user, $admin){
		parent::send_assignment_notifications($notify_user, $admin);

		$path = SugarConfig::getInstance()->get('upload_dir','upload/') . $this->id;
		if (file_exists($path)) {
			unlink($path);
		}
	}

	function get_meeting_users() {
		// First, get the list of IDs.
		$query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.user_id from meetings_users where meetings_users.meeting_id='$this->id' AND meetings_users.deleted=0";
		$GLOBALS['log']->debug("Finding linked records $this->object_name: ".$query);
		$result = $this->db->query($query, true);
		$list = Array();

		while($row = $this->db->fetchByAssoc($result)) {
			$record = BeanFactory::retrieveBean('Users', $row['user_id']);
			if(!empty($record)) {
    			$record->required = $row['required'];
	    		$record->accept_status = $row['accept_status'];
				$list[] = $record;
			}
		}
		return $list;
	}

	function get_invite_meetings(&$user) {
		$template = $this;
		$query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.meeting_id from meetings_users where meetings_users.user_id='$user->id' AND( meetings_users.accept_status IS NULL OR	meetings_users.accept_status='none') AND meetings_users.deleted=0";
		$result = $this->db->query($query, true);
		$list = Array();

		while($row = $this->db->fetchByAssoc($result)) {
			$record = BeanFactory::retrieveBean($this->module_dir, $row['meeting_id']);
			if(!empty($record)) {
			    $record->required = $row['required'];
			    $record->accept_status = $row['accept_status'];
    			$list[] = $record;
			}
		}
		return $list;
	}


	function set_accept_status($user,$status)
	{
		if($user->object_name == 'User')
		{
			$relate_values = array('user_id'=>$user->id,'meeting_id'=>$this->id);
			$data_values = array('accept_status'=>$status);
			$this->set_relationship($this->rel_users_table, $relate_values, true, true,$data_values);
			global $current_user;

			if($this->update_vcal)
			{
				vCal::cache_sugar_vcal($user);
			}
		}
		else if($user->object_name == 'Contact')
		{
			$relate_values = array('contact_id'=>$user->id,'meeting_id'=>$this->id);
			$data_values = array('accept_status'=>$status);
			$this->set_relationship($this->rel_contacts_table, $relate_values, true, true,$data_values);
		}
        else if($user->object_name == 'Lead')
		{
			$relate_values = array('lead_id'=>$user->id,'meeting_id'=>$this->id);
			$data_values = array('accept_status'=>$status);
			$this->set_relationship($this->rel_leads_table, $relate_values, true, true,$data_values);
        }
	}


    /**
     * @inheritdoc
     */
	function get_notification_recipients() {
		if($this->special_notification) {
			return parent::get_notification_recipients();
		}

        return CalendarUtils::buildInvitesList($this);
	}


	function bean_implements($interface) {
		switch($interface) {
			case 'ACL':return true;
		}
		return false;
	}

	function listviewACLHelper() {
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		if(!empty($this->parent_name)) {

			if(!empty($this->parent_name_owner)) {
				global $current_user;
				$is_owner = $current_user->id == $this->parent_name_owner;
			}
		}

		if(!ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner)) {
			$array_assign['PARENT'] = 'a';
		} else {
			$array_assign['PARENT'] = 'span';
		}

		$is_owner = false;

		if(!empty($this->contact_name)) {
			if(!empty($this->contact_name_owner)) {
				global $current_user;
				$is_owner = $current_user->id == $this->contact_name_owner;
			}
		}

		if(ACLController::checkAccess('Contacts', 'view', $is_owner)) {
			$array_assign['CONTACT'] = 'a';
		} else {
			$array_assign['CONTACT'] = 'span';
		}
		return $array_assign;
	}


	function save_relationship_changes($is_update, $exclude = array()) {
		$exclude = array();
	    if(empty($this->in_workflow)) {
           if(empty($this->in_import)){//if a meeting is being imported then contact_id  should not be excluded
           //if the global soap_server_object variable is not empty (as in from a soap/OPI call), then process the assigned_user_id relationship, otherwise
           //add assigned_user_id to exclude list and let the logic from MeetingFormBase determine whether assigned user id gets added to the relationship
           	if(!empty($GLOBALS['soap_server_object'])){
           		$exclude = array('contact_id', 'user_id');
           	}else{
	    		$exclude = array('contact_id', 'user_id','assigned_user_id');
           	}
           }
           else{
           	$exclude = array('user_id');
           }
        }
       parent::save_relationship_changes($is_update, $exclude);
	}


    public function getDefaultStatus()
    {
         $def = $this->field_defs['status'];
         if (isset($def['default'])) {
             return $def['default'];
         } else {
            $app = return_app_list_strings_language($GLOBALS['current_language']);
            if (isset($def['options']) && isset($app[$def['options']])) {
                $keys = array_keys($app[$def['options']]);
                return $keys[0];
            }
        }
        return '';
    }

    /**
     * Add or delete invitee from Meeting.
     *
     * @param string $link_name
     * @param array $invitees
     * @param array $existing
     */
    public function upgradeAttachInvitees($link_name, $invitees, $existing)
    {
        $this->load_relationship($link_name);
        foreach (array_diff($this->{$link_name}->get(), $invitees) as $id) {
            if ($this->created_by != $id) {
                $this->{$link_name}->delete($this->id, $id);
            }
        }
        foreach (array_diff($invitees, $this->{$link_name}->get()) as $id) {
            if (!isset($existing[$id])) {
                $this->{$link_name}->add($id);
            }
        }
    }

    /**
     * Stores user invitees.
     *
     * @param array $userInvitees Array of user invitees ids
     * @param array $existingUsers
     *
     * @return boolean true if no users given.
     */
    public function setUserInvitees($userInvitees, $existingUsers = array())
    {
        // If both are empty, don't do anything.
        // From the App these will always be set [they are set to at least current-user].
        // For the api, these sometimes will not be set [linking related records]
        if (empty($userInvitees) && empty($existingUsers)) {
            return true;
        }
        $this->users_arr = $userInvitees;
        $this->upgradeAttachInvitees('users', $userInvitees, $existingUsers);
    }

    /**
     * Stores contact invitees.
     *
     * @param array $contactInvitees Array of contact invitees ids
     * @param array $existingContacts
     */
    public function setContactInvitees($contactInvitees, $existingContacts = array())
    {
        $this->contacts_arr = $contactInvitees;
        $this->upgradeAttachInvitees('contacts', $contactInvitees, $existingContacts);
    }

    /**
     * Stores lead invitees.
     *
     * @param array $leadInvitees Array of lead invitees ids
     * @param array $existingLeads
     */
    public function setLeadInvitees($leadInvitees, $existingLeads = array())
    {
        $this->leads_arr = $leadInvitees;
        $this->upgradeAttachInvitees('leads', $leadInvitees, $existingLeads);
    }

    /**
     * {@inheritDoc}
     */
    public function loadFromRow($arr, $convert = false)
    {
        $fields = array(
            'reminder_time' => 'reminder_checked',
            'email_reminder_time' => 'email_reminder_checked',
        );

        foreach ($fields as $value => $flag) {
            if (isset($arr[$value]) && !isset($arr[$flag])) {
                $arr[$flag] = $arr[$value] > -1;
            }
        }

        parent::loadFromRow($arr, $convert);
    }

	/**
	 * @param boolean $fill_additional_column_fields
	 */
	public function setFillAdditionalColumnFields($fill_additional_column_fields)
	{
		$this->fill_additional_column_fields = $fill_additional_column_fields;
	}

    /**
     * Handles invitees list when Meeting is assigned to a user.
     * - new user should be added to invitees, if it is not already there;
     * - on create when current user assigns Meeting not to himself, add current user to invitees.
     * @param boolean $isUpdate Value captured prior to SugarBean Save
     */
    protected function handleInviteesForUserAssign($isUpdate)
    {
        $this->load_relationship('users');
        $existingUsers = $this->users->get();

        if (isset($this->assigned_user_id) && !in_array($this->assigned_user_id, $existingUsers)) {
            $this->users->add($this->assigned_user_id);
        }
    }

} // end class def

/**
 * Global functions used to get enum list for Meetings Type field
 * TODO: Move these into Meeting class when we no longer need to support BWC
 */

/**
 * External API integration, for the Meetings drop-down list of what external APIs are available

 * @param SugarBean $focus
 * @param string $name
 * @param string $value
 * @param string $view
 * @return array External integrations available for meetings
 */
//TODO: do we really need focus, name and view params for this function
function getMeetingsExternalApiDropDown($focus = null, $name = null, $value = null, $view = null)
{
    global $dictionary, $app_list_strings;

    $cacheKeyName = 'meetings_type_drop_down';
    $apiList = sugar_cache_retrieve($cacheKeyName);
    if ($apiList === null) {

        $apiList = ExternalAPIFactory::getModuleDropDown('Meetings');
        $apiList = array_merge(array('Sugar' => $app_list_strings['eapm_list']['Sugar']), $apiList);
        sugar_cache_put($cacheKeyName, $apiList);
    }

    if (!empty($value) && empty($apiList[$value])) {
        $apiList[$value] = $value;
    }

    // if options list name is defined in vardef and is a different list than eapm_list then use that list
    $typeField = $dictionary['Meeting']['fields']['type'];
    if (isset($typeField['options']) && $typeField['options'] != "eapm_list") {
        $apiList = array_merge(getMeetingTypeOptions($dictionary, $app_list_strings), $apiList);
    }

    return $apiList;
}

/**
 * Meeting Type Options Array for dropdown list
 * @param array $dictionary - getting type name
 * @param array $app_list_strings - getting type options
 * @return array Meeting Type Options Array for dropdown list
 */
function getMeetingTypeOptions($dictionary, $app_list_strings)
{
    $result = array();

    // getting name of meeting type to fill dropdown list by its values
    if (isset($dictionary['Meeting']['fields']['type']['options'])) {
        $typeName = $dictionary['Meeting']['fields']['type']['options'];

        if (!empty($app_list_strings[$typeName])) {
            $typeList = $app_list_strings[$typeName];

            foreach ($typeList as $key => $value) {
                $result[$value] = $value;
            }
        }
    }

    return $result;
}
