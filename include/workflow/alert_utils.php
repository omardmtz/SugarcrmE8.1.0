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

// $Id: alert_utils.php 54289 2010-02-05 14:08:48Z jmertic $

include_once('include/workflow/workflow_utils.php');
include_once('include/workflow/field_utils.php');
include_once('include/utils/expression_utils.php');

function process_workflow_alerts(& $target_module, $alert_user_array, $alert_shell_array, $check_for_bridge=false){

	$admin = Administration::getSettings();


	/*
	What is shadow module for? - Shadow module is when you are using this function to process invites for
	meetings and calls.  This is down via child module (bridge), however we still use the parent base_module
	to gather our recipients, since this is how our UI handles it.

	When shadow module is set, we should use that as the target module.

	*/

	if(!empty($target_module->bridge_object) && $check_for_bridge==true){

		$temp_target_module = $target_module->bridge_object;
	} else {
		$temp_target_module = $target_module;
	}

	$alert_msg = $alert_shell_array['alert_msg'];

    $address_array = array();
    $address_array['to'] = array();
    $address_array['cc'] = array();
    $address_array['bcc'] = array();

    //loop through get each users token information
    if(!empty($alert_user_array)){
        foreach($alert_user_array as $user_meta_array){

            get_user_alert_details($temp_target_module, $user_meta_array, $address_array);

        //end foreach alert_user_array
        }
    }

    //now you have the bucket so you can send out the alert to all the recipients
    send_workflow_alert($target_module, $address_array, $alert_msg, $admin, $alert_shell_array, $check_for_bridge, $alert_user_array);

//end function process_workflow_alerts
}

function get_manager_info($user_id){

	$notify_user = BeanFactory::getBean('Users', $user_id);
	return $notify_user->reports_to_id;

//end function get_manager_info
}

function get_user_alert_details(& $focus, $user_meta_array, & $address_array){

	//kbrill Bug#16322
	if($user_meta_array['user_type'] == "current_user"){
		if($user_meta_array['array_type'] == 'past'){
			$target_user_id = $focus->fetched_row[$user_meta_array['field_value']];
		} else {
            $target_user_id = $focus->{$user_meta_array['field_value']};
		}
	//END Bug Fix
		//Get user's manager id?
		if($user_meta_array['relate_type'] != "Self"){
			$target_user_id = get_manager_info($target_user_id);
		//end if we need to get the user's manager id
		}

		$user_array = get_alert_recipient($target_user_id);

		if($user_array!=false){
			//add user info to main address bucket
			$address_array[$user_meta_array['address_type']][] = $user_array;
		}

	//end if user_type is current
	}


	if( $user_meta_array['user_type'] == "rel_user" || $user_meta_array['user_type'] == "rel_user_custom")
	{
		get_related_array($focus, $user_meta_array, $address_array);
	} //end if user_type is related

	if($user_meta_array['user_type'] == "trig_user_custom"){

		$user_array = get_alert_recipient_from_custom_fields($focus, $user_meta_array['rel_email_value'], $user_meta_array['field_value']);

		if($user_array!=false){
			//add user info to main address bucket
			$address_array[$user_meta_array['address_type']][] = $user_array;
		}


	//end if user type is trig_user_custom
	}


	//if specific role, user, team
	if( $user_meta_array['user_type'] == "specific_user")
	{

		$user_array = get_alert_recipient($user_meta_array['field_value']);

		if($user_array!=false){
			//add user info to main address bucket
			$address_array[$user_meta_array['address_type']][] = $user_array;
		}
	//end if specific user
	}
	if($user_meta_array['user_type'] == "specific_team"){
		$team_object = BeanFactory::getBean('Teams', $user_meta_array['field_value']);
		$team_user_list = $team_object->get_team_members(true);

		foreach($team_user_list as $user_object){
			$user_array = get_alert_recipient($user_object->id);

			if($user_array!=false){
				//add user info to main address bucket
				$address_array[$user_meta_array['address_type']][] = $user_array;
			}

		//end for each team member
		}
	//end if specific team
	}


	//If the user selected the team assigned to the module.
	if($user_meta_array['user_type'] == "assigned_team_target")
	{
	    if( empty($focus->team_set_id) && empty($focus->team_id) )
	       return;

	    $GLOBALS['log']->debug("Processing alerts for team assigned to module {$focus->team_set_id}");

	    if( ! empty($focus->team_set_id) )
	    {
            /** @var TeamSet $ts */
    	    $ts = BeanFactory::newBean('TeamSets');
    	    $teams = $ts->getTeams($focus->team_set_id);
	    }
	    else
	        $teams = array($focus->team_id => '');

	    $tmpUserList = array();

	    //Iterate over all teams associated with the team set and grab all users.
	    foreach ($teams as $singleTeamId => $singleTeam)
	    {
	        $team_object = BeanFactory::getBean('Teams', $singleTeamId);
		    $team_user_list = $team_object->get_team_members(true);

		    //De dup the users list in case a user is in multiple teams.
		    foreach($team_user_list as $user_object)
			    $tmpUserList[$user_object->id] = $user_object;
	   }

	   //Check if admins have overriden the default limit.
	   $maxAlloweedUsers = !empty($GLOBALS['sugar_config']['max_users_team_notification'] ) ? $GLOBALS['sugar_config']['max_users_team_notification'] : 100;

	   if (count($tmpUserList) > $maxAlloweedUsers) //Ensure the list isn't too large, hard coded for now.
	   {
	       $GLOBALS['log']->fatal("When sending alerts to associated team, max number of users alloweed exceeded.  Refusing to send notification.");
	       return;
	   }

	   //For the clean user list, now grab the email information.
	   foreach($tmpUserList as $tmpUserId => $tmpUserObject)
	   {
	       $user_array = get_alert_recipient($tmpUserObject->id);
		   if($user_array != FALSE)
		   {
		       //add user info to main address bucket
		      $address_array[$user_meta_array['address_type']][] = $user_array;
		   }
	   }
	} //End assigned team target type


	if($user_meta_array['user_type'] == "specific_role")
	{
		$role_object = BeanFactory::getBean('ACLRoles', $user_meta_array['field_value']);
		$role_user_list = $role_object->get_linked_beans('users','User');

		foreach($role_user_list as $user_object){

			$user_array = get_alert_recipient($user_object->id);

			if($user_array!=false){
				//add user info to main address bucket
				$address_array[$user_meta_array['address_type']][] = $user_array;
			}

		//end for each role member
		}

	//end if specific role
	}


	//if login user
	if( $user_meta_array['user_type'] == "login_user")
	{

		global $current_user;

		if(!empty($current_user)){

		$user_array = get_alert_recipient($current_user->id);

		if($user_array!=false){
			//add user info to main address bucket
			$address_array[$user_meta_array['address_type']][] = $user_array;
		}

		//if current user is not empty
		}

	//end if login_user
	}



//end function get_user_alert_details
}

///////////////////////////Email sending


	function get_alert_recipient($user_id)
	{
	    global $locale;
		$notify_user = BeanFactory::getBean('Users', $user_id);

		if (empty($notify_user->email1) && empty($notify_user->email2)) {
			//return false if there is no email set
			return false;
		}

		if ($notify_user->status == "Inactive")
		{
			$GLOBALS['log']->fatal("workflow attempting to send alert to inactive user {$notify_user->name}");
            return false;
		}

		$notify_address = (empty($notify_user->email1)) ? from_html($notify_user->email2) : from_html($notify_user->email1);
        $notify_name = $locale->formatName($notify_user);
        if ($notify_name == '') {
            $notify_name = $notify_user->user_name;
        }
        $notify_user = from_html($notify_user);


		//return true if address is present
		$user_array['address'] = $notify_address;
		$user_array['name'] = $notify_name;
		$user_array['id'] = $notify_user->id;
		$user_array['type'] = "internal";
		$user_array['notify_user'] = $notify_user;

		return $user_array;

	//end get_alert_recipient
	}

	function get_alert_recipient_from_custom_fields($target_module, $target_email, $target_name)
	{

		//user type is trig_user_custom

		if (empty($target_module->$target_email)) {
			//return false if there is no email set
			return false;
		}

		$notify_address = (empty($target_module->$target_email)) ? '' : from_html($target_module->$target_email);
		$notify_name = check_special_fields($target_name, $target_module);

		//return true if address is present
		$user_array['address'] = $notify_address;
		$user_array['name'] = $notify_name;
		$user_array['id'] = $target_module->id;
		$user_array['type'] = "external";
		$user_array['external_type'] = $target_module->module_dir;
		$user_array['notify_user'] = $target_module;

		return $user_array;

	//end get_alert_recipient_from_custom_fields
	}


/**
 * @deprecated 7.0
 * @param $notify_user
 * @return mixed
 */
function create_alert_email($notify_user) {
		global $sugar_version, $sugar_config, $app_list_strings, $current_user;

		if (empty($_SESSION['authenticated_user_language'])) {
			$current_language = $sugar_config['default_language'];
		}
		else {
			$current_language = $_SESSION['authenticated_user_language'];
		}


		$xtpl = new XTemplate(get_notify_template_file($current_language));

		$template_name = $focus->object_name;

		$focus->current_notify_user = $notify_user;

		if (in_array('set_notification_body', get_class_methods($focus)))
		{
			$xtpl = $focus->set_notification_body($xtpl, $focus);
		}
		else
		{
			$xtpl->assign("OBJECT", $focus->object_name);
			$template_name = "Default";
		}

		$xtpl->assign("ASSIGNED_USER", $focus->new_assigned_user_name);
		$xtpl->assign("ASSIGNER", $current_user->user_name);
		$xtpl->assign("URL", "{$sugar_config['site_url']}/index.php?module={$focus->module_dir}&action=DetailView&record={$focus->id}");
		$xtpl->assign("SUGAR", "Sugar v{$sugar_version}");
		$xtpl->parse($template_name);
		$xtpl->parse($template_name . "_Subject");

		$notify_mail->Body = from_html(trim($xtpl->text($template_name)));
		$notify_mail->Subject = from_html($xtpl->text($template_name . "_Subject"));

		return $notify_mail;
	//end function create_alert_email
	}


function send_workflow_alert(&$focus, $address_array, $alert_msg, &$admin, $alert_shell_array, $check_for_bridge = false, $alert_user_array = array()) {
    $invitePerson = false;

    $users    = array();
    $contacts = array();

    // Handle inviting users/contacts to meetings/calls
    if ($focus->module_dir == "Calls" || $focus->module_dir == "Meetings") {
        if ($check_for_bridge == true && !empty($focus->bridge_object)) {
            // we are inviting people
            $invitePerson = true;
        }
    }

    if ($alert_shell_array['source_type'] == "System Default") {
        get_invite_email($focus, $admin, $address_array, $invitePerson, $alert_msg, $alert_shell_array);
    } elseif ($alert_shell_array['source_type'] == "Custom Template" && $invitePerson == true) {
        // you are using a custom template and this is a meeting/call child invite
        get_invite_email($focus, $admin, $address_array, $invitePerson, $alert_msg, $alert_shell_array);
    } else {
        $mailTransmissionProtocol = "unknown";

        try {
            $mailer                   = MailerFactory::getSystemDefaultMailer();
            $mailer->getConfig()->setEncoding(Encoding::Base64);
            $mailTransmissionProtocol = $mailer->getMailTransmissionProtocol();

            foreach ($address_array['to'] as $userInfo) {
                $mailer->addRecipientsTo(new EmailIdentity($userInfo['address'], $userInfo['name']));

                if ($invitePerson == true) {
                    populate_usr_con_arrays($userInfo, $users, $contacts);
                }
            }

            foreach ($address_array['cc'] as $userInfo) {
                $mailer->addRecipientsCc(new EmailIdentity($userInfo['address'], $userInfo['name']));

                if ($invitePerson == true) {
                    populate_usr_con_arrays($userInfo, $users, $contacts);
                }
            }

            foreach ($address_array['bcc'] as $userInfo) {
                $mailer->addRecipientsBcc(new EmailIdentity($userInfo['address'], $userInfo['name']));

                if ($invitePerson == true) {
                    populate_usr_con_arrays($userInfo, $users, $contacts);
                }
            }

            if ($invitePerson == true) {
                // Handle inviting users/contacts to meetings/calls
                if (!empty($address_array['invite_only'])) {
                    foreach ($address_array['invite_only'] as $userInfo) {
                        populate_usr_con_arrays($userInfo, $users, $contacts);
                    }
                }

                // use the user_arr & contact_arr to add these people to the meeting
                $focus->users_arr    = $users;
                $focus->contacts_arr = $contacts;

                invite_people($focus);
            }

            // add the message content to the mailer
            // return: true=encountered an error; false=no errors
            $error = create_email_body($focus, $mailer, $admin, $alert_msg, $alert_shell_array, "", $alert_user_array);

            if ($error) {
                throw new MailerException("Failed to add message content", MailerException::InvalidMessageBody);
            }

            $mailer->send();
        } catch (MailerException $me) {
            $message = $me->getMessage();
            $GLOBALS["log"]->warn("Notifications: error sending e-mail (method: {$mailTransmissionProtocol}), (error: {$message})");
        }
    }
}

/**
 * @deprecated 7.0
 * @param $mail_object
 * @param $admin
 */
function setup_mail_object(&$mail_object, &$admin) {
	if ($admin->settings['mail_sendtype'] == "SMTP") {
		$mail_object->Mailer = "smtp";
		$mail_object->Host = $admin->settings['mail_smtpserver'];
		$mail_object->Port = $admin->settings['mail_smtpport'];
		if ($admin->settings['mail_smtpssl'] == 1)
    	   $mail_object->SMTPSecure = 'ssl';
    	else if ($admin->settings['mail_smtpssl'] == 2)
    	   $mail_object->SMTPSecure = 'tls';
    	else
    	   $mail_object->SMTPSecure = '';

		if ($admin->settings['mail_smtpssl'] == 1)
    	   $mail_object->SMTPSecure = 'ssl';
    	else if ($admin->settings['mail_smtpssl'] == 2)
    	   $mail_object->SMTPSecure = 'tls';
    	else
    	   $mail_object->SMTPSecure = '';

		if ($admin->settings['mail_smtpauth_req']) {
			$mail_object->SMTPAuth = TRUE;
			$mail_object->Username = $admin->settings['mail_smtpuser'];
			$mail_object->Password = $admin->settings['mail_smtppass'];
		}
	//end if sendtype is SMTP
	} else {
        $mail_object->Mailer = 'sendmail';
    }

	$mail_object->From = $admin->settings['notify_fromaddress'];
	$mail_object->FromName = (empty($admin->settings['notify_fromname'])) ? "" : $admin->settings['notify_fromname'];
}


function create_email_body(&$focus, &$mail_object, &$admin, $alert_msg, $alert_shell_array, $notify_user_id = "", $alert_user_array = array()) {
    global $current_language;
    $modStrings = return_module_language($current_language, 'WorkFlow');

    if ($alert_shell_array['source_type'] == "Custom Template") {
        // use custom template
        $error = fill_mail_object($mail_object, $focus, $alert_msg, "body_html", $notify_user_id, $alert_user_array);
        return $error;
    }

    if ($alert_shell_array['source_type'] == "Normal Message") {
        //use standard message
        $body = trim($alert_msg);

        $textOnly = EmailFormatter::isTextOnly($body);
        if ($textOnly) {
            $mail_object->setTextBody($body);
        } else {
            $textBody = strip_tags(br2nl($body)); // need to create the plain-text part
            $mail_object->setTextBody($textBody);
            $mail_object->setHtmlBody($body);
        }

        $mail_object->setSubject($modStrings['LBL_ALERT_SUBJECT']);
        return false;
    }

    return false; // false=no errors
}

function get_related_array(& $focus, & $user_meta_array, & $address_array){

	///Build the relationship information using the Relationship handler
	$rel_handler = & $focus->call_relationship_handler("module_dir", true);
	$rel_handler->set_rel_vardef_fields($user_meta_array['rel_module1'], $user_meta_array['rel_module2']);
	//$rel_handler->base_bean = & $focus;
	$rel_handler->build_info(true);
	//get related bean
	$rel_list = $rel_handler->build_related_list("base");

	////Filter the first related module
	$rel_list = process_rel_type("rel_module1_type", "rel1_filter", $rel_list, $user_meta_array);

	////Filter using second filter if necessary
	if(!empty($user_meta_array['expression']) && $user_meta_array['rel_module2']==""){
		$rel_list = process_rel_type("filter", "expression", $rel_list, $user_meta_array, true);
	//end second filter if necessary
	}



	////Begin loop on first related module
	foreach($rel_list as $rel_object){


////////		//if second is set, then call second loop
		if($user_meta_array['rel_module2']!=""){

				$rel_handler->rel1_bean = $rel_object;
				$rel_list2 = $rel_handler->build_related_list("rel1");

				////Filter the second related module
				$rel_list2 = process_rel_type("rel_module2_type", "rel2_filter", $rel_list2, $user_meta_array);

				////Filter using second filter if necessary
				if(!empty($user_meta_array['expression'])){
					$rel_list2 = process_rel_type("filter", "expression", $rel_list2, $user_meta_array, true);
					//end second filter if necessary
				}



				///second loop
				foreach($rel_list2 as $rel_object2){

					compile_rel_user_info($rel_object2, $user_meta_array, $address_array);

				//end forloop
				}

////////
		} else {
			//if not, then call compile function
			compile_rel_user_info($rel_object, $user_meta_array, $address_array);
		//end if else no second module is present
		}

	//end for loop on the first related module
	}


//end function get_related_array2
}


function compile_rel_user_info($target_object, $user_meta_array, &$address_array){
		//compile user address info based on target object

		if($user_meta_array['rel_email_value']==""){
            $target_user_id = $target_object->{$user_meta_array['field_value']};
			//Get user's manager id?
			if($user_meta_array['relate_type'] != "Self"){
				$target_user_id = get_manager_info($target_user_id);
			//end if we need to get the user's manager id
			}
			$user_array = get_alert_recipient($target_user_id);
		} else {
			//use the custom fields
            if ($target_object->{$user_meta_array['rel_email_value']} == '') {
				//no address;
				return;
			} else {
                $notify_address = $target_object->{$user_meta_array['rel_email_value']};
			}
			$notify_name = check_special_fields($user_meta_array['field_value'], $target_object);
			$user_array['address'] = $notify_address;
			$user_array['name'] = $notify_name;
			$user_array['type'] = "external";
			$user_array['id'] = $target_object->id;
			$user_array['external_type'] = $target_object->module_dir;
			$user_array['notify_user'] = $target_object;
		//end if else use custom fields or not
		}

		//add user info to main address bucket
		$address_type = $user_meta_array['address_type'];
		//array_push($address_array[$address_type][], $user_array);
		$address_array[$address_type][] = $user_array;

//end function compile_rel_user_info
}


/////////////////////////////////////////Parsing Custom Templates//////////

function fill_mail_object(&$mail_object, &$focus, $template_id, $source_field, $notify_user_id = "", $alert_user_array = array()) {
    $template = BeanFactory::newBean('EmailTemplates');
    $template->disable_row_level_security = true;

    if (isset($template_id) && $template_id != "") {
        $template->retrieve($template_id);
    }

    if ($template->id = "") {
        return true; // true=encountered an error
    }

    // override the From email header if the template provides the necessary values
    if ($template->from_address != "" || $template->from_name != "") {
        $from      = $mail_object->getHeader(EmailHeaders::From);
        $fromEmail = $from->getEmail();
        $fromName  = $from->getName();

        // retain the email address of the From header if the template doesn't provide one
        if ($template->from_address != "") {
            $fromEmail = $template->from_address;
        }

        // retain the name of the From header if the template doesn't provide one
        if ($template->from_name != "") {
            $fromName = $template->from_name;
        }

        $mail_object->setHeader(EmailHeaders::From, new EmailIdentity($fromEmail, $fromName));
    }

    if (!empty($template->body)) {
        $mail_object->setTextBody(trim(parse_alert_template($focus, $template->body, $notify_user_id, $alert_user_array)));
    }

    if (!empty($template->body_html)) {
        $mail_object->setHtmlBody(parse_alert_template($focus, $template->body_html, $notify_user_id, $alert_user_array));
    }

    $mail_object->setSubject(parse_alert_template($focus, $template->subject, $notify_user_id, $alert_user_array));
    // Adding attachments if they exist
    $note = BeanFactory::newBean('Notes');
    //FIXME: notes.email_type should be EmailTemplates
    $notes = $note->get_full_list("notes.name", "notes.email_id=" . $GLOBALS['db']->quoted($template_id), true);
    handle_email_attachments($mail_object, $notes);
    return false; // false=no errors
}

/**
 * Add email attachments if exists
 * @param object $mail_object
 * @param array $notes
 */
function handle_email_attachments(&$mail_object, $notes) 
{
    if (!empty($notes)) {
        foreach($notes as $note) {
            $mime_type = 'text/plain';
            $file_location = '';
            $filename = '';

            if($note->object_name == 'Note') {
                if (! empty($note->file->temp_file_location) && is_file($note->file->temp_file_location)) {
                    $file_location = $note->file->temp_file_location;
                    $filename = $note->file->original_file_name;
                    $mime_type = $note->file->mime_type;
                } else {
                    $file_location = "upload://{$note->id}";
                    $filename = $note->id.$note->filename;
                    $mime_type = $note->file_mime_type;
                }
            } elseif($note->object_name == 'DocumentRevision') { // from Documents
                $filename = $note->id.$note->filename;
                $file_location = "upload://$filename";
                $mime_type = $note->file_mime_type;
            }
        
            $filename = substr($filename, 36, strlen($filename)); // strip GUID 
            if (!$note->embed_flag) {
                $mail_object->addAttachment(new Attachment($file_location, $filename, Encoding::Base64, $mime_type));
            } // else
        }
    }
}

function parse_alert_template($focus, $target_body, $notify_user_id="", $alert_user_array = array()){

	//Parse target body and return an array of components
	$component_array = parse_target_body($target_body, $focus->module_dir);
	$parsed_target_body = reconstruct_target_body($focus, $target_body, $component_array, $notify_user_id, $alert_user_array);
	return $parsed_target_body;

//end function parse_alert_template
}

function parse_target_body($target_body, $base_module){

	$component_array = Array();
	$component_array[$base_module] = Array();

	preg_match_all("/(({::)[^>]*?)(.*?)((::})[^>]*?)/", $target_body, $matches, PREG_SET_ORDER);

	foreach ($matches as $val) {
   		$matched_component = $val[0];
   		$matched_component_core = $val[3];

   		$split_array = preg_split('{::}', $matched_component_core);

   		if(!empty($split_array[3])){
   			//related module
   			//0 - future/past/href_link 1 - base_module 2 - rel_module 3 - field

   			$component_array[$split_array[2]][$split_array[3]]['name'] = $split_array[3];
   			$component_array[$split_array[2]][$split_array[3]]['value_type'] = $split_array[0];
   			$component_array[$split_array[2]][$split_array[3]]['original'] = $matched_component;

   		//end if related module
   		} else {
   			//base module
   			//0 - future/past/href_link 1 - base_module 2 - field
   			$meta_name = $split_array[2]."_".$split_array[0];
   			$component_array[$base_module][$meta_name]['name'] = $split_array[2];
   			$component_array[$base_module][$meta_name]['value_type'] = $split_array[0];
   			$component_array[$base_module][$meta_name]['original'] = $matched_component;

   		//end if else related or base module
   		}

	//end loop through components
	}

	return $component_array;

//end function parse_target_body
}

function decodeMultienumField($field) {
    return implode(', ', unencodeMultienum($field));
}

function reconstruct_target_body($focus, $target_body, $component_array, $notify_user_id="", $alert_user_array = array()){
	global $beanList;

	$replace_array = Array();

	foreach($component_array as $module_name => $module_array){

		if($module_name==$focus->module_dir){
			//base module

			foreach($module_array as $field => $field_array){

				if($field_array['value_type'] == 'href_link'){
					//Create href link to target record
					$replacement_value = get_href_link($focus);
				}

 				if($field_array['value_type'] == 'invite_link'){
					//Create href link to target record
					$replacement_value = get_invite_link($focus, $notify_user_id);
				}

				if($field_array['value_type'] == 'future'){
					$replacement_value = check_special_fields($field_array['name'], $focus, false, array());
				}
				if($field_array['value_type'] == 'past'){
					$replacement_value = check_special_fields($field_array['name'], $focus, true, array());
				}

				$replace_array[$field_array['original']] = $replacement_value;


			//end foreach module_array
			}

		//end if base module array
		} else {

			//Confirm this is an actual module in the beanlist
			if(isset($beanList[$module_name]) || isset($focus->field_defs[$module_name])){
				///Build the relationship information using the Relationship handler
				$rel_handler = $focus->call_relationship_handler("module_dir", true);

                if(isset($focus->field_defs[$module_name])) {
                    $rel_handler->rel1_relationship_name = $focus->field_defs[$module_name]['relationship'];
                    $rel_module = get_rel_module_name($focus->module_dir, $rel_handler->rel1_relationship_name, $focus->db);
                    $rel_handler->rel1_module = $rel_module;
                    $rel_handler->rel1_bean = BeanFactory::newBean($rel_module);
                }
                else {
                    $rel_handler->process_by_rel_bean($module_name);
                }

				foreach($focus->field_defs as $field => $attribute_array){
					if(!empty($attribute_array['relationship']) && $attribute_array['relationship'] ==$rel_handler->rel1_relationship_name){
						//$relationship_name = $field;
						$rel_handler->base_vardef_field = $field;
						break;
					}
				}
				//obtain the rel_module object
				$rel_list = $rel_handler->build_related_list("base");

                foreach ($alert_user_array as $user_meta_array) {
                    ////Filter the first related module
                    $rel_list = process_rel_type("rel_module1_type", "rel1_filter", $rel_list, $user_meta_array);

                    ////Filter using second filter if necessary
                    if (!empty($user_meta_array['expression']) && $user_meta_array['rel_module2']=="") {
                        $rel_list = process_rel_type("filter", "expression", $rel_list, $user_meta_array, true);
                        //end second filter if necessary
                    }
                }
				//$rel_list = $focus->get_linked_beans($relationship_name, $bean_name);
				if(!empty($rel_list[0]))
				{
					$rel_object = $rel_list[0];
					$rel_module_present = true;
				} else {
					$rel_module_present = false;
				}

				foreach($module_array as $field => $field_array){

					if($rel_module_present == true){

						if($field_array['value_type'] == 'href_link'){
							//Create href link to target record
							$replacement_value = get_href_link($rel_object);
						} elseif($field_array['value_type'] == 'invite_link'){
							//Create href link to target record
							$replacement_value = get_invite_link($rel_object, $notify_user_id);
						} else {
                            //with the exception of date fields,
                            //use future always for rel because fetched should always be the same
                            if(($rel_object->field_defs[$field_array['name']]['type'] == 'datetime')
                                || (isset($rel_object->field_defs[$field_array['name']]['dbType'])
                                    && $rel_object->field_defs[$field_array['name']]['dbType'] == 'datetime')
                            ) {
                                //this is a date field on a related object so use the fetched row
                                $replacement_value = check_special_fields($field_array['name'], $rel_object, true, array());
                            } else {
                                //use the future value on the related object
                                $replacement_value = check_special_fields($field_array['name'], $rel_object, false, array());
                            }
						}
					} else {
						$replacement_value = "Invalid Value";
					}
					$replace_array[$field_array['original']] = $replacement_value;

				//end foreach module_array
				}


			//end check to see if this is an actual module in the beanlist
			}

		//end if else base or related module array
		}



	//end outside foreach
	}

	$parsed_target_body = replace_target_body_items($target_body, $replace_array);
	return $parsed_target_body;

//end function reconstruct_target_body
}


function replace_target_body_items($target_body, $replace_array){

	foreach ($replace_array as $name => $replacement_value){
		$replacement_value=nl2br($replacement_value);
		$target_body = str_replace($name, $replacement_value, $target_body);
	}
	return $target_body;

//end function replace_target_body_items
}

/**
 * Format a link to a record.
 *
 * @param SugarBean $focus The record.
 * @return string The formatted HTML link.
 */
function get_href_link($focus)
{
    global $sugar_config;

    $link = $sugar_config['site_url'];

    if (isModuleBWC($focus->module_name)) {
        $link .= "/#bwc/index.php?module={$focus->module_dir}&action=DetailView&record={$focus->id}";
    } else {
        $link .= '/#' . buildSidecarRoute($focus->module_dir, $focus->id);
    }

    if (!empty($focus->name)) {
        $label = $focus->name;
    } else {
        $label = translate('LBL_EMAIL_LINK_RECORD', $focus->module_dir);
    }

    return '<a href="' . $link . '">' . $label . '</a>';
}


function get_invite_link(& $focus, $notify_user_id=""){
	global $app_list_strings;
	global $sugar_config;

	if($notify_user_id!=""){

		$accept_url = $sugar_config['site_url'].'/index.php?entryPoint=acceptDecline&module=Meetings&user_id='.$notify_user_id.'&record='.$focus->id;

		$link =	"\n Accept this meeting: \n
			<".$accept_url."&accept_status=accept>";

		$link .="\n Tentatively Accept this meeting \n
			<".$accept_url."&accept_status=tentative>";

		$link .="\nDecline this meeting \n
				<".$accept_url."&accept_status=decline>";

	return $link;

	}

//end function get_href_link
}


function invite_people( & $focus){

	//invite users and contacts

	//Will have to set this eventually when we allow existing meetings to add people
	//$existing_contacts
	//$existing_users


	if (!empty($focus->users_arr) && is_array($focus->users_arr )) {
	  	foreach ($focus->users_arr as $key => $user_id)
  		{
      	if (empty($user_id) || isset($existing_users[$user_id]))
      	{
         	continue;
      	}
      	  if (!isset($focus->users) || empty($focus->users)) {
      	  	$focus->load_relationship('users');
      	  }
	      $focus->users->add($user_id);
  		}
	}

	if (!empty($focus->contacts_arr) && is_array($focus->contacts_arr )) {
  		foreach ($focus->contacts_arr as $key =>$contact_id)
  		{
      	if (empty($contact_id) || isset($existing_contacts[$contact_id]))
      	{
         	continue;
      	}

      	  if (!isset($focus->contacts)) {
      	  	$focus->load_relationship('contacts');
      	  }
	      $focus->contacts->add($contact_id);
  		}
	}


//end function invite_people
}

function populate_usr_con_arrays($user_info_array, & $users_arr, & $contacts_arr){

	/*

	You can't send system default messages or invite non contact/user users.  The meeting/call modules
	are not designed for this.

	*/



		$possible_invitee = false;

	if(!empty($user_info_array['type']) && $user_info_array['type']=="internal"){

		//Users, so add to user_arr
		$users_arr[] = $user_info_array['id'];
		$possible_invitee = true;
	}

	if(!empty($user_info_array['type']) && $user_info_array['type']=="external" &&
	$user_info_array['external_type']=="Contacts"

	){
		//Contacts, so add to contact_arr
		$contacts_arr[] = $user_info_array['id'];
		$possible_invitee = true;
	}




	return $possible_invitee;

//end function populate_usr_con_arrays
}


function get_invite_email($focus, $admin, $address_array, $invite_person, $alert_msg, $alert_shell_array) {
    $type = "Custom";

    if ($alert_shell_array['source_type'] == "System Default") {
        $type = "Default";
    }

    $users    = array();
    $contacts = array();

    $mailTransmissionProtocol = "unknown";

    try {
        $mailer                   = MailerFactory::getMailerForUser($GLOBALS["current_user"]);
        $mailTransmissionProtocol = $mailer->getMailTransmissionProtocol();

        //TO: Addresses
        foreach ($address_array['to'] as $userInfo) {
            try {
                // reuse the mailer, but process one send per recipient
                $mailer->clearRecipients();
                $mailer->addRecipientsTo(new EmailIdentity($userInfo['address'], $userInfo['name']));

                $possibleInvitee = populate_usr_con_arrays($userInfo, $users, $contacts);

                if ($possibleInvitee == true) {
                    $userInfo['notify_user']->new_assigned_user_name =
                        "{$userInfo['notify_user']->first_name} {$userInfo['notify_user']->last_name}";

                    $error = false; // true=encountered an error; false=no errors

                    if ($type == "Default") {
                        $error = get_system_default_body($mailer, $focus, $userInfo['notify_user']);
                    } else {
                        $error = create_email_body(
                            $focus,
                            $mailer,
                            $admin,
                            $alert_msg,
                            $alert_shell_array,
                            $userInfo['notify_user']->id
                        );
                    }

                    if ($error) {
                        throw new MailerException("Failed to add message content", MailerException::InvalidMessageBody);
                    }

                    $mailer->send();
                }
            } catch (MailerException $me) {
                $message = $me->getMessage();
                $GLOBALS["log"]->warn("Notifications: error sending e-mail (method: {$mailTransmissionProtocol}), (error: {$message})");
            }
        }

        //CC: Addresses
        foreach ($address_array['cc'] as $userInfo) {
            try {
                // reuse the mailer, but process one send per recipient
                $mailer->clearRecipients();
                $mailer->addRecipientsCc(new EmailIdentity($userInfo['address'], $userInfo['name']));

                $possibleInvitee = populate_usr_con_arrays($userInfo, $users, $contacts);

                if ($possibleInvitee == true) {
                    $userInfo['notify_user']->new_assigned_user_name =
                        "{$userInfo['notify_user']->first_name} {$userInfo['notify_user']->last_name}";

                    $error = false; // true=encountered an error; false=no errors

                    if ($type == "Default") {
                        $error = get_system_default_body($mailer, $focus, $userInfo['notify_user']);
                    } else {
                        $error = create_email_body(
                            $focus,
                            $mailer,
                            $admin,
                            $alert_msg,
                            $alert_shell_array,
                            $userInfo['notify_user']->id
                        );
                    }

                    if ($error) {
                        throw new MailerException("Failed to add message content", MailerException::InvalidMessageBody);
                    }

                    $mailer->send();
                }
            } catch (MailerException $me) {
                $message = $me->getMessage();
                $GLOBALS["log"]->warn("Notifications: error sending e-mail (method: {$mailTransmissionProtocol}), (error: {$message})");
            }
        }

        //BCC: Addresses
        foreach ($address_array['bcc'] as $userInfo) {
            try {
                // reuse the mailer, but process one send per recipient
                $mailer->clearRecipients();
                $mailer->addRecipientsBcc(new EmailIdentity($userInfo['address'], $userInfo['name']));

                $possibleInvitee = populate_usr_con_arrays($userInfo, $users, $contacts);

                if ($possibleInvitee == true) {
                    $userInfo['notify_user']->new_assigned_user_name =
                        "{$userInfo['notify_user']->first_name} {$userInfo['notify_user']->last_name}";

                    $error = false; // true=encountered an error; false=no errors

                    if ($type == "Default") {
                        $error = get_system_default_body($mailer, $focus, $userInfo['notify_user']);
                    } else {
                        $error = create_email_body(
                            $focus,
                            $mailer,
                            $admin,
                            $alert_msg,
                            $alert_shell_array,
                            $userInfo['notify_user']->id
                        );
                    }

                    if ($error) {
                        throw new MailerException("Failed to add message content", MailerException::InvalidMessageBody);
                    }

                    $mailer->send();
                }
            } catch (MailerException $me) {
                $message = $me->getMessage();
                $GLOBALS["log"]->warn("Notifications: error sending e-mail (method: {$mailTransmissionProtocol}), (error: {$message})");
            }
        }
    } catch (MailerException $me) {
        $message = $me->getMessage();
        $GLOBALS["log"]->warn("Notifications: error sending e-mail (method: {$mailTransmissionProtocol}), (error: {$message})");
    }

    if ($invite_person == true) {
        //Handle inviting users/contacts to meetings/calls
        if (!empty($address_array['invite_only'])) {
            foreach ($address_array['invite_only'] as $userInfo) {
                populate_usr_con_arrays($userInfo, $users, $contacts);
            }
        }

        //use the user_arr & contact_arr to add these people to the meeting
        $focus->users_arr    = $users;
        $focus->contacts_arr = $contacts;

        invite_people($focus);
    }
}

function get_system_default_body(&$mail_object, $focus, &$notify_user) {
    global $sugar_version, $sugar_config, $current_user;

    if (!isset($_SESSION['authenticated_user_language']) || empty($_SESSION['authenticated_user_language'])) {
        $currentLanguage = $sugar_config['default_language'];
    } else {
        $currentLanguage = $_SESSION['authenticated_user_language'];
    }

    $xtpl = new XTemplate("include/language/{$currentLanguage}.notify_template.html");

    $templateName = $focus->object_name;

    $focus->current_notify_user = $notify_user;

    if (in_array('set_notification_body', get_class_methods($focus))) {
        $xtpl = $focus->set_notification_body($xtpl, $focus);
    } else {
        $xtpl->assign("OBJECT", $focus->object_name);
        $templateName = "Default";
    }

    $xtpl->assign("ASSIGNED_USER", $focus->new_assigned_user_name);
    $xtpl->assign("ASSIGNER", $current_user->user_name);
    $xtpl->assign("URL", "{$sugar_config['site_url']}/index.php?module={$focus->module_dir}&action=DetailView&record={$focus->id}");
    $xtpl->assign("SUGAR", "Sugar v{$sugar_version}");
    $xtpl->parse($templateName);
    $xtpl->parse("{$templateName}_Subject");

    $subject = $xtpl->text("{$templateName}_Subject");
    $mail_object->setSubject($subject);

    $body = trim($xtpl->text($templateName));

    $textOnly = EmailFormatter::isTextOnly($body);
    if ($textOnly) {
        $mail_object->setTextBody($body);
    } else {
        $textBody = strip_tags(br2nl($body)); // need to create the plain-text part
        $mail_object->setTextBody($textBody);
        $mail_object->setHtmlBody($body);
    }

    return false; // false=no errors
}

/**
 * @deprecated 7.0
 * @param $mail_object
 * @param $error
 */
function send_mail_object(&$mail_object, $error){

			if($error == false){
				if(!$mail_object->Send()) {
					$GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$mail_object->Mailer}), (error: {$mail_object->ErrorInfo})");
				}
			}

//end function send_mail_object
}



?>
