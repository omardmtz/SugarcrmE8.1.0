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

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

$data = array(
		'parent_type' => InputValidation::getService()->getValidInputRequest('parent_type', 'Assert\Mvc\ModuleName'),
		'parent_id' => InputValidation::getService()->getValidInputRequest('parent_id', 'Assert\Guid'),
		'ListView' => InputValidation::getService()->getValidInputRequest('ListView'),
		'replyForward' => InputValidation::getService()->getValidInputRequest('replyForward'),
		'to_email_addrs' => InputValidation::getService()->getValidInputRequest('to_email_addrs'),
		'recordId' => InputValidation::getService()->getValidInputRequest('recordId', 'Assert\Guid'),
		'record' => InputValidation::getService()->getValidInputRequest('record', 'Assert\Guid'),
		'reply' => InputValidation::getService()->getValidInputRequest('reply'),
		'forQuickCreate' => InputValidation::getService()->getValidInputRequest('forQuickCreate'),
);

if (!empty($_REQUEST['listViewExternalClient'])) {
    $email = BeanFactory::newBean('Emails');
	$module = InputValidation::getService()->getValidInputRequest('action_module', 'Assert\Mvc\ModuleName');
	$uid = InputValidation::getService()->getValidInputRequest('uid', array('Assert\Delimited' => array('constraints' => 'Assert\Guid')));
    echo $email->getNamePlusEmailAddressesForCompose($module, $uid);
}
//For the full compose/email screen, the compose package is generated and script execution
//continues to the Emails/index.php page.
else if(empty($data['forQuickCreate'])) {
	$ret = generateComposeDataPackage($data);
}

/**
 * Initialize the full compose window by creating the compose package
 * and then including Emails index.php file.
 *
 * @param Array $ret
 */
function initFullCompose($ret)
{
	global $current_user;
	$json = getJSONobj();
	$composeOut = $json->encode($ret);

	//For listview 'Email' call initiated by subpanels, just returned the composePackage data, do not
	//include the entire Emails page
	if ( isset($_REQUEST['ajaxCall']) && $_REQUEST['ajaxCall'])
	{
	    echo $composeOut;
	}
	else
	{
	   //For normal full compose screen
	   include('modules/Emails/index.php');
	   echo "<script type='text/javascript' language='javascript'>\ncomposePackage = {$composeOut};\n</script>";
	}
}

/**
 * Generate the compose data package consumed by the full and quick compose screens.
 *
 * @param Array $data
 * @param Bool $forFullCompose If full compose is set to TRUE, then continue execution and include the full Emails UI.  Otherwise
 *             the data generated is returned.
 * @param SugarBean $bean Optional - parent object with data
 */
function generateComposeDataPackage($data,$forFullCompose = TRUE, $bean = null)
{
    $to = [];

    /**
     * Returns an array of email addresses extracted from a string of delimited email addresses.
     *
     * The delimiter may be a comma or semi-colon. The email address may include the name associated with it, in which
     * case < and > will be present. Each email address is extracted from those strings using
     * {@see SugarEmailAddress::splitEmailAddress}.
     *
     * @param string $str
     * @return array The keys are the IDs of the email addresses and the values are the email addresses themselves.
     */
    $getEmailAddresses = function ($str) {
        $addresses = [];
        $ea = BeanFactory::newBean('EmailAddresses');
        $str = str_replace([',', ';'], '::', $str);
        $arr = explode('::', $str);

        foreach ($arr as $address) {
            $parts = $ea->splitEmailAddress($address);

            if (!empty($parts['email'])) {
                $id = $ea->getGuid($parts['email']);
                $addresses[$id] = $parts['email'];
            }
        }

        return $addresses;
    };

	// we will need the following:
	if( isset($data['parent_type']) && !empty($data['parent_type']) &&
	isset($data['parent_id']) && !empty($data['parent_id']) &&
	!isset($data['ListView']) && !isset($data['replyForward'])) {
	    if(empty($bean)) {
    		global $mod_strings;
            $bean = BeanFactory::getBean($data['parent_type'], $data['parent_id']);
	    }
		if (isset($bean->full_name)) {
			$parentName = $bean->full_name;
		} elseif(isset($bean->name)) {
			$parentName = $bean->name;
		}else{
			$parentName = '';
		}
		$parentName = from_html($parentName);
		$namePlusEmail = '';
		if (isset($data['to_email_addrs'])) {
			$namePlusEmail = $data['to_email_addrs'];
			$namePlusEmail = from_html(str_replace("&nbsp;", " ", $namePlusEmail));

            // Get the IDs for each email address found in the string.
            $addresses = $getEmailAddresses($namePlusEmail);

            foreach ($addresses as $id => $address) {
                $to[] = [
                    'email_address_id' => $id,
                    'email_address' => $address,
                ];
            }
        } elseif (isset($bean->emailAddress)) {
            $primaryAddress = $bean->emailAddress->getPrimaryAddress($bean);
            $recipient = [
                'email_address_id' => $bean->emailAddress->getGuid($primaryAddress),
                'email_address' => $primaryAddress,
                'parent_type' => $bean->getModuleName(),
                'parent_id' => $bean->id,
            ];

            if (isset($bean->full_name)) {
                $namePlusEmail = from_html($bean->full_name) . ' <' . from_html($primaryAddress) . '>';
                $recipient['parent_name'] = from_html($bean->full_name);
            } else {
                $namePlusEmail = '<' . from_html($primaryAddress) . '>';
                $recipient['parent_name'] = '';
            }

            $to[] = $recipient;
		}

		$subject = "";
		$body = "";
		$email_id = "";
		$attachments = array();
		if ($bean->module_dir == 'Cases') {
			$subject = str_replace('%1', $bean->case_number, $bean->getEmailSubjectMacro() . " ". from_html($bean->name)) ;//bug 41928
			$bean->load_relationship("contacts");
			$contact_ids = $bean->contacts->get();
			$contact = BeanFactory::newBean('Contacts');
			foreach($contact_ids as $cid)
			{
				$contact->retrieve($cid);
                $primaryAddress = $contact->emailAddress->getPrimaryAddress($contact);
                $to[] = [
                    'email_address' => $primaryAddress,
                    'email_address_id' => $contact->emailAddress->getGuid($primaryAddress),
                    'parent_type' => $contact->getModuleName(),
                    'parent_id' => $contact->id,
                    'parent_name' => from_html($contact->full_name),
                ];
				$namePlusEmail .= empty($namePlusEmail) ? "" : ", ";
                $namePlusEmail .= from_html($contact->full_name) . ' <' . from_html($primaryAddress) . '>';
			}
		}
		if ($bean->module_dir == 'Quotes' && isset($data['recordId'])) {
			$quotesData = getQuotesRelatedData($bean,$data);
			global $current_language;
			$namePlusEmail = $quotesData['toAddress'];
			$subject = $quotesData['subject'];
			$body = $quotesData['body'];
			$attachments = $quotesData['attachments'];
			$email_id = $quotesData['email_id'];

            // Get the IDs for each email address found in the string.
            $addresses = $getEmailAddresses($namePlusEmail);

            foreach ($addresses as $id => $address) {
                $to[] = [
                    'email_address_id' => $id,
                    'email_address' => $address,
                ];
            }
		} // if
		$ret = array(
        'to' => $to,
		'to_email_addrs' => $namePlusEmail,
		'parent_type'	 => $data['parent_type'],
		'parent_id'	     => $data['parent_id'],
		'parent_name'    => $parentName,
		'subject'		 => $subject,
		'body'			 => $body,
		'attachments'	 => $attachments,
		'email_id'		 => $email_id,

	);
} else if(isset($_REQUEST['ListView'])) {

	$email = BeanFactory::newBean('Emails');
	$namePlusEmail = $email->getNamePlusEmailAddressesForCompose($_REQUEST['action_module'], (explode(",", $_REQUEST['uid'])));

        // Get the IDs for each email address found in the string.
        $addresses = $getEmailAddresses($namePlusEmail);

        foreach ($addresses as $id => $address) {
            $to[] = [
                'email_address_id' => $id,
                'email_address' => $address,
            ];
        }

	$ret = array(
        'to' => $to,
		'to_email_addrs' => $namePlusEmail,
		);
	} else if (isset($data['replyForward'])) {


		$ret = array();
		$ie = BeanFactory::newBean('InboundEmail');
        $ie->disable_row_level_security = true;
		$ie->email = BeanFactory::newBean('Emails');
		$ie->email->email2init();
		$replyType = $data['reply'];
		$email_id = $data['record'];
		$ie->email->retrieve($email_id);
		$emailType = "";
		if ($ie->email->type == 'draft') {
			$emailType = $ie->email->type;
		}
		$ie->email->from_addr = $ie->email->from_addr_name;
        $ie->email->to_addrs = to_html(!empty($ie->email->to_addrs_names)? $ie->email->to_addrs_names : $ie->email->to_addrs);
		$ie->email->cc_addrs = to_html($ie->email->cc_addrs_names);
		$ie->email->bcc_addrs = $ie->email->bcc_addrs_names;
		$ie->email->from_name = $ie->email->from_addr;
		$preBodyHTML = "&nbsp;<div><hr></div>";
		if ($ie->email->type != 'draft') {
			$email = $ie->email->et->handleReplyType($ie->email, $replyType);
		} else {
			$email = $ie->email;
			$preBodyHTML = "";
		} // else
		if ($ie->email->type != 'draft') {
			$emailHeader = $email->description;
		}
		$ret = $ie->email->et->displayComposeEmail($email);
		if ($ie->email->type != 'draft') {
			$ret['description'] = $emailHeader;
		}
		if ($replyType == 'forward' || $emailType == 'draft') {
			$ret = $ie->email->et->getDraftAttachments($ret);
		}
		$return = $ie->email->et->getFromAllAccountsArray($ie, $ret);

		if ($replyType == "forward") {
			$return['to'] = '';
		} else {
			if ($email->type != 'draft') {
				$return['to'] = from_html($ie->email->from_addr);
			}
		} // else

        // Get the IDs for each email address found in the string.
        $addresses = $getEmailAddresses($return['to']);

        foreach ($addresses as $id => $address) {
            $to[] = [
                'email_address_id' => $id,
                'email_address' => $address,
            ];
        }

		$ret = array(
        'to' => $to,
		'to_email_addrs' => $return['to'],
		'parent_type'	 => $return['parent_type'],
		'parent_id'	     => $return['parent_id'],
		'parent_name'    => $return['parent_name'],
		'subject'		 => $return['name'],
		'body'			 => $preBodyHTML . $return['description'],
		'attachments'	 => (isset($return['attachments']) ? $return['attachments'] : array()),
		'email_id'		 => $email_id,
		'fromAccounts'   => $return['fromAccounts'],
		);

        // If it's a 'Reply All' action, append the CC addresses
        if ($data['reply'] == 'replyAll') {
            $cc_addrs = from_html($ie->email->cc_addrs);
            $to_addrs = from_html(
                !empty($ie->email->to_addrs_names) ? $ie->email->to_addrs_names : $ie->email->to_addrs
            );
            if (!empty($to_addrs)) {
                $cc_addrs = $cc_addrs . ", " . $to_addrs;
            }
            $ret['cc_addrs'] = $cc_addrs;
            $ret['cc'] = [];

            // Get the IDs for each email address found in the string.
            $addresses = $getEmailAddresses($cc_addrs);

            foreach ($addresses as $id => $address) {
                $ret['cc'][] = [
                    'email_address_id' => $id,
                    'email_address' => $address,
                ];
            }
        }
	} else {
		$ret = array(
		'to_email_addrs' => '',
		);
	}

	if($forFullCompose)
		initFullCompose($ret);
	else
		return $ret;
}

function getQuotesRelatedData($bean,$data) {
	$return = array();
	$emailId = $data['recordId'];

	$email = BeanFactory::getBean('Emails', $emailId);
	$return['subject'] = $email->name;
	$return['body'] = from_html($email->description_html);
	$return['toAddress'] = $email->to_addrs;
	$ret = array();
	$ret['uid'] = $emailId;
	$ret = EmailUI::getDraftAttachments($ret);
	$return['attachments'] = $ret['attachments'];
	$return['email_id'] = $emailId;
	return $return;
} // fn
