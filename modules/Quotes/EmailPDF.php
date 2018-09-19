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


function email_layout ($layout) {
    //Focus is quote bean
    global $focus;
	global $log;
    global $mod_strings;
	global $layouts;
	global $current_user;
	global $locale;



	//First Create e-mail draft
	$email_object = BeanFactory::newBean('Emails');
	// set the id for relationships
	$email_object->id = create_guid();
	$email_object->new_with_id = true;

	//subject
	$email_object->name = $mod_strings['LBL_EMAIL_QUOTE_FOR'].$focus->name."";
	//body
	$email_object->description_html = $mod_strings['LBL_EMAIL_DEFAULT_DESCRIPTION'];
	//parent type, id
	$email_object->parent_type = "Quotes";
	$email_object->parent_id = $focus->id;
	//type is draft
	$email_object->type = "draft";
	$email_object->status = "draft";

	// link the sent pdf to the relevant account
	if(isset($focus->billing_account_id) && !empty($focus->billing_account_id)) {
		$email_object->load_relationship('accounts');
		$email_object->accounts->add($focus->billing_account_id);
	}

	//check to see if there is a billing contact associated with this quote
	if(!empty($focus->billing_contact_id) && $focus->billing_contact_id!="") {
		global $beanFiles;
		require_once($beanFiles['Contact']);
		$contact = BeanFactory::getBean('Contacts', $focus->billing_contact_id);

		if(!empty($contact->email1) || !empty($contact->email2)) {
			//contact email is set
			$email_object->to_addrs_ids = $focus->billing_contact_id;
			$email_object->to_addrs_names = $focus->billing_contact_name.";";

			if(!empty($contact->email1)){
				$email_object->to_addrs_emails = $contact->email1.";";
				$email_object->to_addrs = $focus->billing_contact_name." <".$contact->email1.">";
			} elseif(!empty($contact->email2)){
				$email_object->to_addrs_emails = $contact->email2.";";
				$email_object->to_addrs = $focus->billing_contact_name." <".$contact->email2.">";
			}

			// create relationship b/t the email(w/pdf) and the contact
			$contact->load_relationship('emails');
			$contact->emails->add($email_object->id);
		}//end if contact name is set
	} elseif(isset($focus->billing_account_id) && !empty($focus->billing_account_id)) {

		$acct = BeanFactory::getBean('Accounts', $focus->billing_account_id);

		if(!empty($acct->email1) || !empty($acct->email2)) {
			//acct email is set
			$email_object->to_addrs_ids = $focus->billing_account_id;
			$email_object->to_addrs_names = $focus->billing_account_name.";";

			if(!empty($acct->email1)){
				$email_object->to_addrs_emails = $acct->email1.";";
				$email_object->to_addrs = $focus->billing_account_name." <".$acct->email1.">";
			} elseif(!empty($acct->email2)){
				$email_object->to_addrs_emails = $acct->email2.";";
				$email_object->to_addrs = $focus->billing_account_name." <".$acct->email2.">";
			}

			// create relationship b/t the email(w/pdf) and the acct
			$acct->load_relationship('emails');
			$acct->emails->add($email_object->id);
		}//end if acct name is set
	}



	//team id
	$email_object->team_id  = $current_user->default_team;
	//assigned_user_id
	$email_object->assigned_user_id = $current_user->id;
	//Save the email object
	global $timedate;
	$email_object->date_start = $timedate->now();
	$email_object->save(FALSE);
	$email_id = $email_object->id;

	//Handle PDF Attachment
	$file_name = get_quote_pdf($layout);
	$note = BeanFactory::newBean('Notes');
    $note->id = \Sugarcrm\Sugarcrm\Util\Uuid::uuid1();
    $note->new_with_id = true;
	$note->filename = $file_name;
	$note->team_id = "";
	$note->file_mime_type = "application/pdf";
	$lbl_email_attachment = $locale->translateCharset($mod_strings['LBL_EMAIL_ATTACHMENT'],$locale->getExportCharset(),'utf-8');
	$note->name = $lbl_email_attachment.$file_name;

	//save the pdf attachment note
	$note->email_id = $email_object->id;
	$note->email_type = "Emails";

    // Move the file before saving so that the file size is captured during save.
	$source = "upload://$file_name";
    $destination = "upload://{$note->id}";

	if (!rename($source, $destination)){
		$msg = str_replace('$destination', $destination, $mod_strings['LBL_RENAME_ERROR']);
		die($msg);
    }

    $note->save();

	//return the email id
	return $email_id;
}



function get_quote_pdf($layout) {
	global $log, $mod_strings;
	global $layouts;
	global $current_user;

	if(!isset($layouts[$layout])) {
		$msg = $mod_strings['LBL_QUOTE_LAYOUT_REGISTERED_ERROR'];
		$log->fatal($msg);
		sugar_die($msg);
	} elseif (!is_file($layouts[$layout])) {
		$msg = str_replace('$layout', $layouts[$layout], $mod_strings['LBL_QUOTE_LAYOUT_DOES_NOT_EXIST_ERROR']);
		$log->fatal($msg);
		sugar_die($msg);
	} else {
		include_once($layouts[$layout]);
		return $filename;
	}
	//end function get_quote_pdf
}
?>
