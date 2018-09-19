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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Util\Serialized;

$request = InputValidation::getService();

//increate timeout for phpo script execution
  if (ini_get('max_execution_time') > 0 && ini_get('max_execution_time') < 300) {
      ini_set('max_execution_time', 300);
  }

  require_once("vendor/ytree/Tree.php");
  require_once("vendor/ytree/ExtNode.php");
  global $mod_strings, $current_user;



  $email = BeanFactory::newBean('Emails');
  if (!$email->ACLAccess('view')) {
      ACLController::displayNoAccess(true);
      sugar_cleanup(true);
  }
  $email->email2init();
  $ie = BeanFactory::newBean('InboundEmail');
  $ie->disable_row_level_security = true;
  $ie->email = $email;
  $json = getJSONobj();

  $rules = new SugarRouting($ie, $current_user);

  $showFolders = Serialized::unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));

 if (isset($_REQUEST['emailUIAction'])) {
  switch($_REQUEST['emailUIAction']) {
    ///////////////////////////////////////////////////////////////////////////
    ////    RULES & ROUTING
    case "loadRulesForSettings":
        $out = $rules->getRulesList($ie);
        echo $out; // returns RAW HTML
        break;

    case "getOneRuleset":
        $id = isset($_REQUEST['rule_id']) ? $_REQUEST['rule_id'] : '';
        $rule = $rules->getRule($id, $ie);

        $out = $json->encode($rule, true);
        echo $out;
        break;
        ////    END RULES & ROUTING
        ///////////////////////////////////////////////////////////////////////////


        ///////////////////////////////////////////////////////////////////////////
        ////    COMPOSE REPLY FORWARD
        // this is used in forward/reply
    case "composeEmail":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: composeEmail");

        $uid = $request->getValidInputRequest('uid', 'Assert\Guid');
        $ieId = $request->getValidInputRequest('ieId', 'Assert\Guid');
        $mbox = $request->getValidInputRequest('mbox');

        if (isset($_REQUEST['sugarEmail']) && $_REQUEST['sugarEmail'] == 'true' && !empty($uid)) {
            $ie->email->retrieve($uid);
            $ie->email->from_addr = $ie->email->from_addr_name;
            $ie->email->to_addrs = to_html($ie->email->to_addrs_names);
            $ie->email->cc_addrs = to_html($ie->email->cc_addrs_names);
            $ie->email->bcc_addrs = $ie->email->bcc_addrs_names;
            $ie->email->from_name = $ie->email->from_addr;
            $email = $ie->email->et->handleReplyType($ie->email, $_REQUEST['composeType']);
            $ret = $ie->email->et->displayComposeEmail($email);

            if (empty($email->description_html)) {
                $ret['description'] = str_replace("\n", "<BR/>", $email->description);
            } else {
                $ret['description'] = $ie->getHTMLDisplay(SugarCleaner::cleanHtml($email->description_html));
                $ret['description'] = str_replace(array("\r\n", "\n"), "", $ret['description']);
            }

			//get the forward header and add to description
            $forward_header = $email->getForwardHeader();

            $ret['description'] = $forward_header . $ret['description'];
            if ($_REQUEST['composeType'] == 'forward') {
            	$ret = $ie->email->et->getDraftAttachments($ret);
            }
            $ret = $ie->email->et->getFromAllAccountsArray($ie, $ret);
            $ret['from'] = from_html($ret['from']);
            $ret['name'] = from_html($ret['name']);
            $out = $json->encode($ret, true);
            echo $out;
        } elseif (!empty($uid) && !empty($ieId)) {
            $ie->retrieve($ieId);
            $ie->mailbox = $mbox;
			global $timedate;
            $ie->setEmailForDisplay($uid);
			$ie->email->date_start = $timedate->to_display_date($ie->email->date_sent);
			$ie->email->time_start = $timedate->to_display_time($ie->email->date_sent);
            $ie->email->date_sent = $timedate->to_display_date_time($ie->email->date_sent);
            $email = $ie->email->et->handleReplyType($ie->email, $_REQUEST['composeType']);
            $ret = $ie->email->et->displayComposeEmail($email);

            if (empty($email->description_html)) {
                $ret['description'] = str_replace("\n", "<BR/>", $email->description);
            } else {
                $ret['description'] = $ie->getHTMLDisplay(SugarCleaner::cleanHtml($email->description_html));
                $ret['description'] = str_replace(array("\r\n", "\n"), "", $ret['description']);
            }

            if ($_REQUEST['composeType'] == 'forward') {
                $ret = $ie->email->et->createCopyOfInboundAttachment($ie, $ret, $uid);
            }
            $ret = $ie->email->et->getFromAllAccountsArray($ie, $ret);
            $ret['from'] = from_html($ret['from']);
            $ret['name'] = from_html($ret['name']);
            $ret['ieId'] = $ieId;
            $ret['mbox'] = $mbox;
            $out = $json->encode($ret, true);
            echo $out;
        }
        break;

        /**
         * sendEmail handles both send and save draft duties
         */
    case "sendEmail":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: sendEmail");

        if (!isset($_REQUEST['saveDraft'])) {
            $email->type = 'out';
            $email->status = 'sent';
        }
            if (isset($_REQUEST['email_id']) && !empty($_REQUEST['email_id'])) {
                $email->retrieve($_REQUEST['email_id']); // uid is GUID in draft cases
            }
        if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid'])) {
        	$email->uid = $_REQUEST['uid'];
        }
        $sendResult = false;
        try {
            $sendResult = $email->email2Send($_REQUEST);
        } catch (Exception $e) {
            ob_clean();
            echo($app_strings['LBL_EMAIL_ERROR_PREPEND']. " " . $e->getMessage());
        }
        if ($sendResult) {
            $ret = array(
                'composeLayoutId'  => $_REQUEST['composeLayoutId'],
            );
            $out = $json->encode($ret, true);
            echo $out; // async call to close the proper compose tab
        }
    break;

    case "uploadAttachment":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: uploadAttachment");
        $metadata = $email->email2saveAttachment();

        if(!empty($metadata)) {
            $out = $json->encode($metadata);
            echo $out;
        }
        break;

    case "removeUploadedAttachment":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: removeUploadedAttachment");
        $fileFromRequest = from_html($_REQUEST['file']);
        $fileGUID = substr($fileFromRequest, 0, 36);
        // Bug52727: sanitize fileGUID to remove path components: /\.
        $fileGUID = cleanDirName($fileGUID);
        $fileName = $email->et->userCacheDir . "/" . $fileGUID;
        $filePath = clean_path($fileName);
        unlink($filePath);
        break;

    case "fillComposeCache": // fills client-side compose email cache with signatures and email templates
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: fillComposeCache");
        $out = array();
        $email_templates_arr = $email->et->getEmailTemplatesArray();
        natcasesort($email_templates_arr);
        $out['emailTemplates'] = $email_templates_arr;
        $sigs = $current_user->getSignaturesArray();
        // clean "none"
        foreach($sigs as $k => $v) {
            if($k == "") {
                 $sigs[$k] = $app_strings['LBL_NONE'];
			} else if (is_array($v) && isset($v['name'])){
				$sigs[$k] = $v['name'];
			} else{
			    $sigs[$k] = $v;
			}
        }

        $configArray=array();
        $configs = OutboundEmailConfigurationPeer::listMailConfigurations($current_user);
        foreach ($configs as $config) {
            $config_id = $config->getInboxId();
            if (empty($config_id)) {
                $config_id = $config->getConfigId();
            }
            $configItem = array(
                "value" => $config_id,
                "text"  => $config->getDisplayName()
            );
            $configArray[] = $configItem;
        }

        $out['signatures'] = $sigs;
        $out['fromAccounts'] = $configArray;
        $out['errorArray'] = array();

        $oe = new OutboundEmail();
        if( $oe->doesUserOverrideAccountRequireCredentials($current_user->id) )
        {
            $overideAccount = $oe->getUsersMailerForSystemOverride($current_user->id);
            //If the user override account has not been created yet, create it for the user.
            if($overideAccount == null)
                $overideAccount = $oe->createUserSystemOverrideAccount($current_user->id);

		    $out['errorArray'] = array($overideAccount->id => $app_strings['LBL_EMAIL_WARNING_MISSING_USER_CREDS']);
        }

        $ret = $json->encode($out);
        echo $ret;
        break;

    case "getSignature":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getSignature");
        if(isset($_REQUEST['id'])) {
            $signature = $current_user->getSignature($_REQUEST['id']);
            $signature['signature_html'] = from_html($signature['signature_html']);
            $out = $json->encode($signature);
            echo $out;
        } else {
            die();
        }
        break;

    case "deleteSignature":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: deleteSignature");
        if(isset($_REQUEST['id'])) {

  			BeanFactory::deleteBean('UserSignatures', $_REQUEST['id']);
            $signatureArray = $current_user->getSignaturesArray();
	        // clean "none"
	        foreach($signatureArray as $k => $v) {
	            if($k == "") {
                 $sigs[$k] = $app_strings['LBL_NONE'];
	            } else if (is_array($v) && isset($v['name'])){
	                $sigs[$k] = $v['name'];
	            } else{
	                $sigs[$k] = $v;
	            }
	        }
	        $out['signatures'] = $sigs;
            $ret = $json->encode($out);
            echo $ret;
        } else {
            die();
        }
    	break;
    case "getTemplateAttachments":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getTemplateAttachments");
        if(isset($_REQUEST['email_id']) && !empty($_REQUEST['email_id'])) {

            //FIXME: Should notes.email_type be Emails or EmailTemplates?
                $where = 'email_id=' . $this->db->quoted($_REQUEST['parent_id']);
            $order = "";
            $seed = BeanFactory::newBean('Notes');
            $fullList = $seed->get_full_list($order, $where, '');
            $all_fields = array_merge($seed->column_fields, $seed->additional_column_fields);

            $js_fields_arr = array();

            $i=1; // js doesn't like 0 index?
            if (!empty($fullList)) {
                foreach($fullList as $note) {
                    $js_fields_arr[$i] = array();

                    foreach($all_fields as $field) {
                        if(isset($note->$field)) {
                            $note->$field = from_html($note->$field);
                            $note->$field = preg_replace('/\r\n/','<BR>',$note->$field);
                            $note->$field = preg_replace('/\n/','<BR>',$note->$field);
                            $js_fields_arr[$i][$field] = addslashes($note->$field);
                        }
                    }
                    $i++;
                }
            }

            $out = $json->encode($js_fields_arr);
            echo $out;
        }
        break;
        ////    END COMPOSE REPLY FORWARD
        ///////////////////////////////////////////////////////////////////////////



        ///////////////////////////////////////////////////////////////////////////
        ////    MESSAGE HANDLING
    case "displayView":
        $ret = array();
        $ie->retrieve($_REQUEST['ieId']);
        $ie->mailbox = $_REQUEST['mailbox'];
        $ie->connectMailserver();

        switch($_REQUEST['type']) {
            case "headers":
                $title = "{$app_strings['LBL_EMAIL_VIEW_HEADERS']}";
                $text = $ie->getFormattedHeaders($_REQUEST['uid']);
                break;

            case "raw":
                $title = "{$app_strings['LBL_EMAIL_VIEW_RAW']}";
                $text = $ie->getFormattedRawSource($_REQUEST['uid']);
                break;

            case "printable":

                break;
        }

        $ret['html'] = $text;
        $ret['title'] = $title;

        $out = $json->encode($ret, true);
        echo $out;
        break;

    case "getQuickCreateForm":
    	$GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getQuickCreateForm");

        $uid = $request->getValidInputRequest('uid', 'Assert\Guid');
        $ieId = $request->getValidInputRequest('ieId', 'Assert\Guid');
        $mailbox = $request->getValidInputRequest('mailbox');
        $qcModule = $request->getValidInputRequest('qc_module', 'Assert\Mvc\ModuleName');

        if(!empty($qcModule)) {
        	if (!ACLController::checkAccess($qcModule,'edit', true)) {
        		echo trim($json->encode(array('html' => translate('LBL_NO_ACCESS', 'ACL')), true));
        		break;
        	}
            $people = array("Users", "Contacts", "Leads", "Prospects");
                $showSaveToAddressBookButton = false;

            if(isset($_REQUEST['sugarEmail']) && !empty($_REQUEST['sugarEmail'])) {
                $ie->email->retrieve($uid); // uid is a sugar GUID in this case
            } else {
                $ie->retrieve($ieId);
                $ie->mailbox = $mailbox;
                $ie->setEmailForDisplay($uid);
            }
            $ret = $email->et->getQuickCreateForm($_REQUEST, $ie->email, $showSaveToAddressBookButton);
            $ret['ieId'] = $ieId;
            $ret['mbox'] = $mailbox;
            $ret['uid'] = $uid;
            $ret['module'] = $qcModule;
            if (!isset($_REQUEST['iframe'])) {
                $out = trim($json->encode($ret, false));
            } else {
                $out = $ret['html'];
            }
            echo $out;
        }
        break;

    case 'saveQuickCreate':
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveQuickCreate");
        if (isset($_REQUEST['qcmodule'])) {
            $GLOBALS['log']->debug("********** QCmodule was set: {$_REQUEST['qcmodule']}");
        }
        $controller = ControllerFactory::getController($_REQUEST['qcmodule']);
        $controller->loadBean();
        $controller->pre_save();
        $GLOBALS['log']->debug("********** saving new {$controller->module}");
        $controller->action_save();
        //Relate the email to the new bean
        if(isset($_REQUEST['sugarEmail']) && $_REQUEST['sugarEmail'] == 'true' && isset($_REQUEST['uid']) && !empty($_REQUEST['uid'])) {
            $ie->email->retrieve($_REQUEST['uid']);
        } elseif(isset($_REQUEST['uid']) && !empty($_REQUEST['uid']) && isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            $GLOBALS['log']->debug("********** Quick Create from non-imported message");
            $ie->retrieve($_REQUEST['ieId']);
            $ie->mailbox = $_REQUEST['mbox'];
            $ie->connectMailserver();
            $uid = $_REQUEST['uid'];
            if($ie->protocol == 'imap') {
                $_REQUEST['uid'] = imap_msgno($ie->conn, $_REQUEST['uid']);
            } else {
                $_REQUEST['uid'] = $ie->getCorrectMessageNoForPop3($_REQUEST['uid']);
            }

            if (!$ie->importOneEmail($_REQUEST['uid'], $uid)) {
            	$ie->getDuplicateEmailId($_REQUEST['uid'], $uid);
            } // id
            $ie->email->retrieve($ie->email->id);
            $GLOBALS['log']->debug("**********Imported Email");
            $ie->email->assigned_user_id = $controller->bean->assigned_user_id;
            $ie->email->assigned_user_name = $controller->bean->assigned_user_name;
            $ie->email->team_id = $controller->bean->team_id;
            $ie->email->team_set_id = $controller->bean->team_set_id;
        }
        if (isset($ie->email->id)) {
        	if (empty($ie->email->parent_id)) {
	            $ie->email->parent_id = $controller->bean->id;
	            $ie->email->parent_type = $controller->module;
        	} // if
            $ie->email->status = 'read';
            $ie->email->save();
            $mod = strtolower($controller->module);
            $ie->email->load_relationship($mod);
            $ie->email->$mod->add($controller->bean->id);
            if($controller->bean->load_relationship('emails')) {
                $controller->bean->emails->add($ie->email->id);
            }
            if ($controller->bean->module_dir == 'Cases') {
	            if($controller->bean->load_relationship('contacts')) {
	            	$emailAddressWithName = $ie->email->from_addr;
	            	if (!empty($ie->email->reply_to_addr)) {
	            		$emailAddressWithName = $ie->email->reply_to_addr;
	            	} // if

	            	$emailAddress = SugarEmailAddress::_cleanAddress($emailAddressWithName);
	            	$contactIds = $ie->email->emailAddress->getRelatedId($emailAddress, 'contacts');
	            	if (!empty($contactIds)) {
	                	$controller->bean->contacts->add($contactIds);
	            	} // if
	            } // if
            } // if
            echo $json->encode(array('id' => $ie->email->id));
        }
        break;

    case "getImportForm":

        $uid = $request->getValidInputRequest('uid', 'Assert\Guid');
        $ieId = $request->getValidInputRequest('ieId', 'Assert\Guid');

        $ie->retrieve($ieId);
        $ie->setEmailForDisplay($uid);
        $ret = $email->et->getImportForm($_REQUEST, $ie->email);
        $out = trim($json->encode($ret, false));
        echo $out;
        break;

    case "getRelateForm":
    	if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid'])) {
    		$uids = $json->decode(from_html($_REQUEST['uid']));
    		$email->retrieve($uids[0]);
            $ret = $email->et->getImportForm(array('showTeam' => false, 'showAssignTo' => false, 'showDelete' => false), $email,'RelateEditView');
            $out = trim($json->encode($ret, false));
        echo $out;
    	}
    break;

    case "getEmail2DetailView":
    	if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid'])) {
            $ret = $email->et->getDetailViewForEmail2($_REQUEST['uid']);
            if( !isset($_REQUEST['print']) ||  $_REQUEST['print'] === FALSE)
            {
                $out = trim($json->encode($ret, false));
                echo $out;
            }
            else
                echo $ret['html'];

    	}
    break;

    case "relateEmails":
    	if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid']) &&
    	       isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id']) &&
    	       isset($_REQUEST['parent_type']) && !empty($_REQUEST['parent_type'])) {
    	    $uids = explode($app_strings['LBL_EMAIL_DELIMITER'], $_REQUEST['uid']);
    	    $mod = strtolower($_REQUEST['parent_type']);
    	    $modId = $_REQUEST['parent_id'];
    	    foreach($uids as $id) {
    	        $email = BeanFactory::getBean('Emails', $id);
    	        $email->parent_id = $modId;
                $email->parent_type = $_REQUEST['parent_type'];
                $email->status = 'read';

                // BUG FIX BEGIN
                // Bug 50979 - relating a message in group inbox removes message
                if (empty($email->assigned_user_id))
                {
                    $email->setFieldNullable('assigned_user_id');
                }
                $email->save();
                // Bug 50979 - reset assigned_user_id field defs
                if (empty($email->assigned_user_id))
                {
                    $email->revertFieldNullable('assigned_user_id');
                }
                // BUG FIX END

                $email->load_relationship($mod);
                $email->$mod->add($modId);
    	    }
    	}
    break;


    case "getAssignmentDialogContent":
        $out = $email->distributionForm("");
        $out = trim($json->encode($out, false));
        echo $out;
        break;
    case "doAssignmentAssign":
        $out = $email->et->doAssignment($_REQUEST['distribute_method'], $_REQUEST['ieId'], $_REQUEST['folder'], $_REQUEST['uids'], $_REQUEST['users']);
        echo $out;
        break;
    case "doAssignmentDelete";
    $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: doAssignmentDelete");
    if(isset($_REQUEST['uids']) && !empty($_REQUEST['uids']) &&
    isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId']) &&
    isset($_REQUEST['folder']) && !empty($_REQUEST['folder'])) {
        $email->et->markEmails("deleted", $_REQUEST['ieId'], $_REQUEST['folder'], $_REQUEST['uids']);
    } else {
    }
    break;
    case "markEmail":
    	global $app_strings;
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: markEmail");
        if(isset($_REQUEST['uids']) && !empty($_REQUEST['uids']) &&
        isset($_REQUEST['type']) && !empty($_REQUEST['type']) &&
        isset($_REQUEST['folder']) && !empty($_REQUEST['folder']) &&
		isset($_REQUEST['ieId']) && (!empty($_REQUEST['ieId']) || (empty($_REQUEST['ieId']) && strpos($_REQUEST['folder'], 'sugar::') !== false))
        ) {
        	$uid = $json->decode(from_html($_REQUEST['uids']));
        	$uids = array();
        	if(is_array($uid)) {
        		$uids = $uid;
        	} else {
				if(strpos($uid, $app_strings['LBL_EMAIL_DELIMITER']) !== false) {
					$uids = explode($app_strings['LBL_EMAIL_DELIMITER'], $uid);
				} else {
					$uids[] = $uid;
				}
        	}   // else
        	$uids = implode($app_strings['LBL_EMAIL_DELIMITER'], $uids);
        	$GLOBALS['log']->debug("********** EMAIL 2.0 - Marking emails $uids as {$_REQUEST['type']}");

        	$ret = array();
        	if(strpos($_REQUEST['folder'], 'sugar::') !== false && ($_REQUEST['type'] == 'deleted') && !ACLController::checkAccess('Emails', 'delete')) {
        		$ret['status'] = false;
        		$ret['message'] = $app_strings['LBL_EMAIL_DELETE_ERROR_DESC'];
        	} else {
            	$email->et->markEmails($_REQUEST['type'], $_REQUEST['ieId'], $_REQUEST['folder'], $uids);
        		$ret['status'] = true;
        	}
	        $out = trim($json->encode($ret, false));
	        echo $out;
        } else {
        }
        break;

    case "checkEmail2":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: checkEmail2");

            $showFolders = Serialized::unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));

        $ret = array();
        $ret['numberAccounts'] = count($showFolders);

        $GLOBALS['log']->info("EMAIL2.0: async checkEmail - found [ ".$ret['numberAccounts']." ] accounts to check");

        if(!empty($showFolders) && is_array($showFolders)) {
            foreach($showFolders as $ieId) {
                $ieId = trim($ieId);

                if(!empty($ieId)) {
                    $GLOBALS['log']->info("INBOUNDEMAIL: trying to check email for GUID [ {$ieId} ]");
                    $ie->disconnectMailserver();
                    $ie->retrieve($ieId);

                    $ret[$ieId] = $ie->checkEmail2_meta();
                }
            }
        } else {
            $GLOBALS['log']->info("EMAIL2.0: at checkEmail() async call - not subscribed accounts to check.");
        }



        $out = $json->encode($ret, true);
        echo $out;
        break;

    case "checkEmail":
        $GLOBALS['log']->info("[EMAIL] - Start checkEmail action for user [{$current_user->user_name}]");
        if(isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            $ie->retrieve($_REQUEST['ieId']);
            $ie->mailbox = (isset($_REQUEST['mbox']) && !empty($_REQUEST['mbox'])) ? $_REQUEST['mbox'] : "INBOX";
            $ie->checkEmail(false);
        } elseif(isset($_REQUEST['all']) && !empty($_REQUEST['all'])) {
                $showFolders = Serialized::unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));

            $GLOBALS['log']->info("[EMAIL] - checkEmail found ".count($showFolders)." accounts to check for user [{$current_user->user_name}]");

            if(!empty($showFolders) && is_array($showFolders)) {
                foreach($showFolders as $ieId) {
                    $ieId = trim($ieId);
                    if(!empty($ieId)) {
                        $GLOBALS['log']->info("[EMAIL] - Start checking email for GUID [{$ieId}] for user [{$current_user->user_name}]");
                        $ie->disconnectMailserver();
                        // If I-E not exist - skip check
                        if (is_null($ie->retrieve($ieId))) {
                            $GLOBALS['log']->info("[EMAIL] - Inbound with GUID [{$ieId}] not exist");
                            continue;
                        }
                        $ie->checkEmail(false);
                        $GLOBALS['log']->info("[EMAIL] - Done checking email for GUID [{$ieId}] for user [{$current_user->user_name}]");
                    }
                }
            } else {
                $GLOBALS['log']->info("EMAIL2.0: at checkEmail() async call - not subscribed accounts to check.");
            }
        }

        $tree = $email->et->getMailboxNodes(true); // preserve cache files
        $return = $tree->generateNodesRaw();
        $out = $json->encode($return);
        echo $out;
        $GLOBALS['log']->info("[EMAIL] - Done checkEmail action for user [{$current_user->user_name}]");
        break;

    case "checkEmailProgress":
        $GLOBALS['log']->info("[EMAIL] - Start checkEmail action for user [{$current_user->user_name}]");
        if(isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            $ie->retrieve($_REQUEST['ieId']);
            $ie->mailbox = (isset($_REQUEST['mbox']) && !empty($_REQUEST['mbox'])) ? $_REQUEST['mbox'] : "INBOX";
            $synch = (isset($_REQUEST['synch']) && ($_REQUEST['synch'] == "true"));
            if (!$ie->is_personal) {
            	$return = array('status' => "done");
            } else {
	            if ($ie->protocol == "pop3") {
	                $return = $ie->pop3_checkPartialEmail($synch);
	            } else {
	                $return = $ie->checkEmailIMAPPartial(false, $synch);
	            } // else
            } // if
            $return['ieid'] = $ie->id;
            $return['synch'] = $synch;
			if(isset($_REQUEST['totalcount']) && !empty($_REQUEST['totalcount']) && $_REQUEST['totalcount'] >= 0) {
				if ($ie->protocol == "pop3") {
					$return['totalcount'] = $_REQUEST['totalcount'];
				} // else
			}
            echo $json->encode($return);
        } // if
        break;

    case "getAllFoldersTree":
        $tree = $email->et->getMailboxNodes(true); // preserve cache files
        $return = $tree->generateNodesRaw();
        $out = $json->encode($return);
        echo $out;
        $GLOBALS['log']->info("[EMAIL] - Done checkEmail action for user [{$current_user->user_name}]");
        break;

    case "synchronizeEmail":
        $GLOBALS['log']->info("[EMAIL] Start action synchronizeEmail for user [{$current_user->user_name}]");
        $ie->syncEmail(true);
        $tree = $email->et->getMailboxNodes(true);
        $return = $tree->generateNodesRaw();
        $out = $json->encode($return);
        echo $out;
        $GLOBALS['log']->info("[EMAIL] Done action synchronizeEmail for user [{$current_user->user_name}]");
        break;

    case "importEmail":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: importEmail");
        $ie->retrieve($_REQUEST['ieId']);
        $ie->mailbox = $_REQUEST['mbox'];
        $ie->connectMailserver();
        $return = array();
        $status = true;
		$count = 1;
        if(strpos($_REQUEST['uid'], $app_strings['LBL_EMAIL_DELIMITER']) !== false) {
            $exUids = explode($app_strings['LBL_EMAIL_DELIMITER'], $_REQUEST['uid']);
            foreach($exUids as $msgNo) {
            	$uid = $msgNo;
                if($ie->protocol == 'imap') {
                    $msgNo = imap_msgno($ie->conn, $msgNo);
                    $status = $ie->importOneEmail($msgNo, $uid);
                } else {
                	$status = $ie->importOneEmail($ie->getCorrectMessageNoForPop3($msgNo), $uid);
                } // else
            	$return[] = $app_strings['LBL_EMAIL_MESSAGE_NO'] . " " . $count . ", " . $app_strings['LBL_STATUS'] . " " . ($status ? $app_strings['LBL_EMAIL_IMPORT_SUCCESS'] : $app_strings['LBL_EMAIL_IMPORT_FAIL']);
            	$count++;
	            if(($_REQUEST['delete'] == 'true') && $status && ($current_user->is_admin == 1 || $ie->group_id == $current_user->id)) {
	                $ie->deleteMessageOnMailServer($uid);
	                $ie->deleteMessageFromCache($uid);
	        	} // if
            } // for
        } else {
            $msgNo = $_REQUEST['uid'];
            if($ie->protocol == 'imap') {
                $msgNo = imap_msgno($ie->conn, $_REQUEST['uid']);
                $status = $ie->importOneEmail($msgNo, $_REQUEST['uid']);
            } else {
            	$status = $ie->importOneEmail($ie->getCorrectMessageNoForPop3($msgNo), $_REQUEST['uid']);
            } // else
            $return[] = $app_strings['LBL_EMAIL_MESSAGE_NO'] . " " . $count . ", " . $app_strings['LBL_STATUS'] . " " . ($status ? $app_strings['LBL_EMAIL_IMPORT_SUCCESS'] : $app_strings['LBL_EMAIL_IMPORT_FAIL']);

            if(($_REQUEST['delete'] == 'true') && $status && ($current_user->is_admin == 1 || $ie->group_id == $current_user->id)) {
                $ie->deleteMessageOnMailServer($_REQUEST['uid']);
                $ie->deleteMessageFromCache($_REQUEST['uid']);
            } // if
        } // else
        echo $json->encode($return);
        break;

    case "setReadFlag":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: setReadFlag");
        $ie->retrieve($_REQUEST['ieId']);
        $ie->setReadFlagOnFolderCache($_REQUEST['mbox'], $_REQUEST['uid']);
        $email->et->getListEmails($_REQUEST['ieId'], $_REQUEST['mbox'], 0, 'true');
        break;

    case "deleteMessage":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: deleteMessage");
        if(isset($_REQUEST['uid']) && !empty($_REQUEST['uid']) && isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            $ie->retrieve($_REQUEST['ieId']);
            $ie->mailbox = $_REQUEST['mbox'];

            if($current_user->is_admin == 1 || $ie->group_id == $current_user->id) {
                $ie->deleteMessageOnMailServer($_REQUEST['uid']);
                $ie->deleteMessageFromCache($_REQUEST['uid']);
            } else {
                $GLOBALS['log']->debug("*** ERROR: tried to delete an email for an account for which {$current_user->full_name} is not the owner!");
                echo $mod_strings['LBL_SEE_LOG'];
            }
        } else {
            echo $mod_strings['ERR_MISSING_CREDENTIALS'];
        }
        break;

    case "getSingleMessage":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getSingleMessage");
        if(isset($_REQUEST['uid']) && !empty($_REQUEST['uid']) && isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            // this method needs to guarantee UTF-8 charset - encoding detection
            // and conversion is unreliable, and can break valid UTF-8 text
            $out = $email->et->getSingleMessage($ie);

            echo $json->encode($out);
        } else {
            echo $mod_strings['ERR_NO_UID'];
        }
        break;

    case "getSingleMessageFromSugar":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getSingleMessageFromSugar");
        if(isset($_REQUEST['uid']) && !empty($_REQUEST['uid'])) {
            $email->retrieve($_REQUEST['uid']);
            $ie->email = $email;

            if($email->status == 'draft' || $email->type == 'draft') {
                // forcing an editview since we are looking at a draft message
                global $current_user;
                $ret = $ie->email->et->displayComposeEmail($email);

                $ret = $ie->email->et->getDraftAttachments($ret, $ie);
            	$ret = $ie->email->et->getFromAllAccountsArray($ie, $ret);

				$teamSetField = new EmailSugarFieldTeamsetCollection($ie->email, $ie->email->field_defs, "get_non_private_teams_array", 'composeEmailForm');
				$teamSetField->user_id = $current_user->id;
				$sqs_objects = $teamSetField->createQuickSearchCode(true);
				$code = $teamSetField->get_code();
	            $ret['teamSetCode'] = $code . $sqs_objects;

                $out = $json->encode($ret, true);
                echo $out;
            } else {
                // Html Description handled in displayOneEmail() including HTML Cleaning if needed
                $out = $ie->displayOneEmail($_REQUEST['uid'], $_REQUEST['mbox']);
                $out['meta']['email']['date_start'] = $email->date_start;
                $out['meta']['email']['time_start'] = $email->time_start;
                $out['meta']['ieId'] = $_REQUEST['ieId'];
                $out['meta']['mbox'] = $_REQUEST['mbox'];
            	$out['meta']['email']['toaddrs'] = $email->et->generateExpandableAddrs($out['meta']['email']['toaddrs']);
        		if(!empty($out['meta']['email']['cc_addrs'])) {
                    $ccs = $email->et->generateExpandableAddrs($out['meta']['email']['cc_addrs']);
        		    $out['meta']['email']['cc'] = <<<eoq
        				<tr>
        					<td NOWRAP valign="top" class="displayEmailLabel">
        						{$app_strings['LBL_EMAIL_CC']}:
        					</td>
        					<td class="displayEmailValue">
        						{$ccs}
        					</td>
        				</tr>
eoq;
        		}
                echo $json->encode($out);
            }
        } else {
            $mod_strings['ERR_NO_UID'];
        }
        break;

    case "getMultipleMessages":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getMultipleMessages");

        $exUids = $request->getValidInputRequest('uid', array('Assert\Delimited' => array('constraints' => 'Assert\Guid')));
        $ieId = $request->getValidInputRequest('ieId', 'Assert\Guid');
            $mbox = $request->getValidInputRequest('mbox');

        if (!empty($exUids) && !empty($ieId)) {

            $out = array();
            foreach($exUids as $k => $uid) {
                    if ($email->et->mboxCacheExists($ieId, $mbox, $uid)) {
                        $msg = $email->et->getMboxCacheValue($ieId, $mbox, $uid);
                } else {
                    $ie->retrieve($ieId);
                    $ie->mailbox = $mbox;
                    $ie->setEmailForDisplay($uid, false, true);
                    $msg = $ie->displayOneEmail($uid, $mbox);
                        $email->et->writeMboxCacheValue($ieId, $mbox, $uid, $msg);
                }

                $out[] = $msg;
            }
            echo $json->encode($out);
        } else {
            echo $mod_strings['ERR_NO_UID'];
        }
        break;

    case "getMultipleMessagesFromSugar":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getMultipleMessagesFromSugar");
        if(isset($_REQUEST['uid']) && !empty($_REQUEST['uid'])) {
            $exIds = explode(",", $_REQUEST['uid']);
            $out = array();

            if (!empty($_REQUEST['ieId'])) {
                $ie->retrieve($_REQUEST['ieId']);
                $ie->mailbox = $_REQUEST['mbox'];
            }

            foreach($exIds as $id) {
                $e = BeanFactory::getBean('Emails', $id);
                $e->description_html = from_html($e->description_html);
                $ie->email = $e;
                $out[] = $ie->displayOneEmail($id, $_REQUEST['mbox']);
            }

            echo $json->encode($out);
        }

        break;
        ////    END MESSAGE HANDLING
        ///////////////////////////////////////////////////////////////////////////



        ///////////////////////////////////////////////////////////////////////////
        ////    LIST VIEW
    case "getMessageCount":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getMessageCount");

        $out = $ie->getCacheCount($_REQUEST['mbox']);
        echo $json->encode($out);
        break;

    case "getMessageList":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getMessageListJSON");
        if(isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            // user view preferences
            $email->et->saveListView($_REQUEST['ieId'], $_REQUEST['mbox']);
            // list output
            $ie->retrieve($_REQUEST['ieId']);
            if(isset($_REQUEST['start']) && isset($_REQUEST['limit'])) {
	            $page = ceil($_REQUEST['start'] / $_REQUEST['limit']) + 1;
	        } else {
	        	$page = 1;
	        }
            $list = $ie->displayFolderContents($_REQUEST['mbox'], $_REQUEST['forceRefresh'], $page);
            $count = $ie->getCacheCount($_REQUEST['mbox']);
            $unread = $ie->getCacheUnread($_REQUEST['mbox']);
            $out = $email->et->jsonOuput($list, 'Email', $count, true, $unread);

            @ob_end_clean();
            ob_start();
            echo $out;
            ob_end_flush();
        } else {
            echo $mod_strings['ERR_NO_IEID'];
        }
        break;

    case "getMessageListSugarFolders":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getMessageListSugarFoldersJSON");
        if(isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            // user view preferences
            $email->et->saveListView($_REQUEST['ieId'], "SUGAR.{$_REQUEST['mbox']}");
            if(isset($_REQUEST['start']) && isset($_REQUEST['limit'])) {
	            $page = ceil($_REQUEST['start'] / $_REQUEST['limit']) + 1;
	        } else {
	        	$page = 1;
	        }
            if(!isset($_REQUEST['sort']) || !isset($_REQUEST['dir'])) {
                $_REQUEST['sort'] = '';
                $_REQUEST['dir']  = '';
            }
            $emailSettings = $current_user->getPreference('emailSettings', 'Emails');
            // cn: default to a low number until user specifies otherwise
            if(empty($emailSettings['showNumInList'])) {
                $emailSettings['showNumInList'] = 20;
            }

			// jchi #9424, get sort and direction from user preference
			$sort = 'date';
			$direction = 'desc';
			$sortSerial = $current_user->getPreference('folderSortOrder', 'Emails');
			if(!empty($sortSerial) && !empty($_REQUEST['ieId']) && !empty($_REQUEST['mbox'])) {
                    $sortArray = Serialized::unserialize($sortSerial);
				$GLOBALS['log']->debug("********** EMAIL 2.0********** ary=".print_r($sortArray,true).' id='.$_REQUEST['ieId'].'; box='.$_REQUEST['mbox']);
				$sort = $sortArray[$_REQUEST['ieId']][$_REQUEST['mbox']]['current']['sort'];
				$direction = $sortArray[$_REQUEST['ieId']][$_REQUEST['mbox']]['current']['direction'];
			}
			//set sort and direction to user predference
			if(!empty($_REQUEST['sort']) && !empty($_REQUEST['dir'])) {
				$email->et->saveListViewSortOrder($_REQUEST['ieId'], $_REQUEST['mbox'], $_REQUEST['sort'], $_REQUEST['dir']);
				$sort = $_REQUEST['sort'];
				$direction = $_REQUEST['dir'];
			} else {
				$_REQUEST['sort'] = '';
				$_REQUEST['dir'] = '';
			}
			//end

            $metalist = $email->et->folder->getListItemsForEmailXML($_REQUEST['ieId'], $page,
            $emailSettings['showNumInList'], $sort, $direction);
            $count = $email->et->folder->getCountItems($_REQUEST['ieId']);
            $unread = $email->et->folder->getCountUnread($_REQUEST['ieId']);
            $out = $email->et->jsonOuput($metalist, 'Email', $count, false, $unread);

            @ob_end_clean();
            ob_start();
            echo $out;
            ob_end_flush();
        } else {
            echo $mod_strings['ERR_NO_IEID'];
        }
        break;
        ////    END LIST VIEW
        ///////////////////////////////////////////////////////////////////////////



        ///////////////////////////////////////////////////////////////////////////
        ////    FOLDER ACTIONS
    case "emptyTrash":
        $email->et->emptyTrash($ie);
        break;

    case "clearInboundAccountCache":
        $email->et->clearInboundAccountCache($_REQUEST['ieId']);
        break;

    case "updateSubscriptions":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: updateSubscriptions");
        if(isset($_REQUEST['subscriptions']) && !empty($_REQUEST['subscriptions']))
        {
            $subs = explode("::", $_REQUEST['subscriptions']);
            $childrenSubs = array();
            //Find all children of the group folder subscribed to and add
            //them to the list of folders to show.
            foreach ($subs as $singleSub)
                $email->et->folder->findAllChildren($singleSub, $childrenSubs);

            $subs = array_merge($subs, $childrenSubs);
            $email->et->folder->setSubscriptions($subs);
        }
        elseif(empty($_REQUEST['subscriptions'])) {
            $email->et->folder->clearSubscriptions();
        }
        break;

    case "refreshSugarFolders":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: refreshSugarFolders");
        $rootNode = new ExtNode('','');
        $folderOpenState = $current_user->getPreference('folderOpenState', 'Emails');
        $folderOpenState = (empty($folderOpenState)) ? "" : $folderOpenState;
            $ret = $email->et->folder->getUserFolders($rootNode, Serialized::unserialize($folderOpenState), $current_user, true);
        $out = $json->encode($ret);
        echo $out;
        break;



    case "getFoldersForSettings":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getFoldersForSettings");
        $ret = $email->et->folder->getFoldersForSettings($current_user);
        $out = $json->encode($ret);
        echo $out;
        break;

    case "moveEmails":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: moveEmails");
        $ie->moveEmails($_REQUEST['sourceIeId'], $_REQUEST['sourceFolder'], $_REQUEST['destinationIeId'], $_REQUEST['destinationFolder'], $_REQUEST['emailUids']);
        break;

    case "saveNewFolder":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveNewFolder");
        if(isset($_REQUEST['folderType']) && !empty($_REQUEST['folderType'])) {
            switch($_REQUEST['folderType']) {
                case "sugar":
                    $ret = $email->et->saveNewFolder($_REQUEST['nodeLabel'], $_REQUEST['parentId']);
                    $out = $json->encode($ret);
                    echo $out;
                    break;

                case "imap":
                    $ie->retrieve($_REQUEST['ieId']);
                    $ie->connectMailserver();
                    $ie->saveNewFolder($_REQUEST['newFolderName'], $_REQUEST['mbox']);
                    break;
            }
        } else {
            echo $mod_strings['LBL_NO_FOLDER_TYPE'];
        }
        break;

    case "setFolderViewSelection": // flows into next case statement
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: setFolderViewSelection");
        $viewFolders = $_REQUEST['ieIdShow'];
        $current_user->setPreference('showFolders', base64_encode(serialize($viewFolders)), '', 'Emails');
        $tree = $email->et->getMailboxNodes(false);
        $return = $tree->generateNodesRaw();
        $out = $json->encode($return);
        echo $out;
        break;

    case "deleteFolder":
        $v = $app_strings['LBL_NONE'];
        $return = array();
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: deleteFolder");
        if(isset($_REQUEST['folderType']) && !empty($_REQUEST['folderType'])) {
            switch($_REQUEST['folderType']) {
                case "sugar":
                    $status = $email->et->folder->deleteChildrenCascade($_REQUEST['folder_id']);
                    if ($status == true) {
                    	$return['status'] = true;
                    	$return['folder_id'] = $_REQUEST['folder_id'];
                    } else {
                    	$return['status'] = false;
                    	$return['errorMessage'] = $app_strings['LBL_EMAIL_ERROR_DELETE_GROUP_FOLDER'];
                    }
                    break;

                case "imap":
                    $ie->retrieve($_REQUEST['ieId']);
                    $ie->connectMailserver();
                    $returnArray = $ie->deleteFolder($_REQUEST['mbox']);
                    $status = $returnArray['status'];
                    $errorMessage = $returnArray['errorMessage'];
                    if ($status == true) {
                    	$return['status'] = true;
                    	$return['mbox'] = $_REQUEST['mbox'];
                    	$return['ieId'] = $_REQUEST['ieId'];
                    } else {
                    	$return['status'] = false;
                    	$return['errorMessage'] = $errorMessage;
                    }
                    break;
            }
        } else {
        	$return['status'] = false;
        	$return['errorMessage'] =  $mod_strings['LBL_NO_FOLDER_TYPE'];
        }
        $out = $json->encode($return);
        echo $out;
        break;
    case "renameFolder":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: renameFolder");

        if(isset($_REQUEST['ieId']) && isset($_REQUEST['oldFolderName']) && !empty($_REQUEST['oldFolderName'])
        && isset($_REQUEST['newFolderName']) && !empty($_REQUEST['newFolderName'])) {
            $ie->retrieve($_REQUEST['ieId']);
            $ie->renameFolder($_REQUEST['oldFolderName'], $_REQUEST['newFolderName']);
        } elseif(isset($_REQUEST['folderId']) && !empty($_REQUEST['folderId']) && isset($_REQUEST['newFolderName']) && !empty($_REQUEST['newFolderName'])) {
            if(is_guid($_REQUEST['folderId'])) {
                $email->et->folder->retrieve($_REQUEST['folderId']);
                $email->et->folder->name = $_REQUEST['newFolderName'];
                $email->et->folder->save();
            } else {
                echo $mod_strings['LBL_NOT_SUGAR_FOLDER'];
            }
        }
    case "moveFolder":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: moveFolder");
        if(isset($_REQUEST['folderId']) && !empty($_REQUEST['folderId']) && isset($_REQUEST['newParentId']) && !empty($_REQUEST['newParentId']) && $_REQUEST['newParentId'] != $_REQUEST['folderId']) {
            if(is_guid($_REQUEST['folderId']) && is_guid($_REQUEST['newParentId'])) {
                $email->et->folder->retrieve($_REQUEST['folderId']);
                $email->et->folder->updateFolder(array(
                    "record"        => $_REQUEST['folderId'],
                    "name"          => $email->et->folder->name,
                    "parent_folder" => $_REQUEST['newParentId'],
                    "team_id"       => $email->et->folder->team_id,
                    "team_set_id"       => $email->et->folder->team_set_id,
                ));
            } else {
                echo $mod_strings['LBL_NOT_SUGAR_FOLDER'];
            }
        }
        break;
    case "getGroupFolder":
            $email->et->folder->retrieve($_REQUEST['folderId']);
            $_REQUEST['record'] = $_REQUEST['folderId'];
            $ret = array();
            $ret['folderId'] = $email->et->folder->id;
            $ret['folderName'] = $email->et->folder->name;
            $ret['parentFolderId'] = $email->et->folder->parent_folder;
			$teamSetField = new EmailSugarFieldTeamsetCollection($email->et->folder, $ie->field_defs, "get_non_private_teams_array", 'EditViewGroupFolder');
			$sqs_objects = $teamSetField->createQuickSearchCode(true);
			$code = $teamSetField->get_code();
            $ret['team_id'] = $code . $sqs_objects;
            $out = $json->encode($ret);
            echo $out;
        break;


    case "retrieveTeamsInfoForSettings":
    	$emailUI = new EmailUI();
    	$returnArray = $emailUI->retrieveTeamInfoForSettingsUI();
        $out = $json->encode($returnArray);
        echo $out;
    	break;

    case "rebuildFolders":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: rebuildFolders");
        $tree = $email->et->getMailboxNodes(false);
        $return = $tree->generateNodesRaw();
        $out = $json->encode($return);
        echo $out;
        break;

    case "setFolderOpenState":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: setFolderOpenState");
        $email->et->saveFolderOpenState($_REQUEST['focusFolder'], $_REQUEST['focusFolderOpen']);
        break;

    case "saveListViewSortOrder":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveListViewSortOrder");
        $email->et->saveListViewSortOrder($_REQUEST['ieId'], $_REQUEST['focusFolder'], $_REQUEST['sortBy'], $_REQUEST['reverse']);
        break;
        ////    END FOLDER ACTIONS
        ///////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////
        ////    INBOUND EMAIL ACCOUNTS

    case "retrieveAllOutbound":
    	$GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: retrieveAllOutbound");
    	global $current_user;
    	$oe = new OutboundEmail();
		$outbounds = $oe->getUserMailers($current_user);
		$results = array('outbound_account_list' => $outbounds, 'count' => count($outbounds));
		$out = $json->encode($results, false);
		echo $out;

    	break;

    case "editOutbound":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: editOutbound");
        if (SugarConfig::getInstance()->get("disable_user_email_config", false)
            && !$current_user->isAdminForModule("Emails")
        ) {
            break;
        }
        if(isset($_REQUEST['outbound_email']) && !empty($_REQUEST['outbound_email'])) {
            $oe = new OutboundEmail();
            $oe->retrieve($_REQUEST['outbound_email']);

            $ret = array();

            foreach($oe->field_defs as $def) {
                $ret[$def['name']] = $oe->$def['name'];
            }
            $ret['mail_smtppass']=''; // don't send back the password
            $ret['has_password'] =  isset($oe->mail_smtppass);

            $out = $json->encode($ret, true);
            echo $out;

        } else {
            echo "NOOP";
        }
        break;

    case "deleteOutbound":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: deleteOutbound");
        if(isset($_REQUEST['outbound_email']) && !empty($_REQUEST['outbound_email']))
        {
            $oe = new OutboundEmail();
            global $current_user;
            $oe->retrieve($_REQUEST['outbound_email']);
            $affectedInboundAccounts = $oe->getAssociatedInboundAccounts($current_user);

            //Check if the user has confirmed he wants to delete the email account even if associated to an inbound accnt.
            $confirmedDelete = ( isset($_REQUEST['confirm']) && $_REQUEST['confirm'] ) ? TRUE : FALSE;

            if( count($affectedInboundAccounts) > 0 && !$confirmedDelete)
            {
                $results = array('is_error' => true, 'error_message' => $app_strings['LBL_EMAIL_REMOVE_SMTP_WARNING'] , 'outbound_email' => $_REQUEST['outbound_email']);
            }
            else
            {
                $oe->delete();
                $results = array('is_error' => false, 'error_message' => '');
            }

            $out = $json->encode($results);
            @ob_end_clean();
            ob_start();
            echo $out;
            ob_end_flush();
            die();
        }
        else
        {
            echo "NOOP";
        }
        break;

    case "saveOutbound":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveOutbound");
        if (SugarConfig::getInstance()->get("disable_user_email_config", false)
            && !$current_user->isAdminForModule("Emails")
        ) {
            break;
        }

        $oe = new OutboundEmail();
        $oe->id = InputValidation::getService()->getValidInputRequest('mail_id', 'Assert\Guid', '');
        $oe->retrieve($oe->id);
        $oe->name = $_REQUEST['mail_name'];
        $type = empty($_REQUEST['type']) ? 'user' : $_REQUEST['type'];
        $oe->type = $type;
        $oe->user_id = $current_user->id;
        $oe->mail_sendtype = "SMTP";
        $oe->mail_smtptype = $_REQUEST['mail_smtptype'];
        $oe->mail_smtpserver = $_REQUEST['mail_smtpserver'];
        $oe->mail_smtpport = $_REQUEST['mail_smtpport'];
        $oe->mail_smtpssl = $_REQUEST['mail_smtpssl'];
        $oe->mail_smtpauth_req = isset($_REQUEST['mail_smtpauth_req']) ? 1 : 0;
        $oe->mail_smtpuser = $_REQUEST['mail_smtpuser'];
        if(!empty($_REQUEST['mail_smtppass'])) {
            $oe->mail_smtppass = $_REQUEST['mail_smtppass'];
        }
        echo $oe->save();
        break;

    case "saveDefaultOutbound":
   		global $current_user;
    	$GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveDefaultOutbound");
    	$outbound_id = empty($_REQUEST['id']) ? "" : $_REQUEST['id'];
    	$ie = BeanFactory::newBean('InboundEmail');
        $ie->disable_row_level_security = true;
   		$ie->setUsersDefaultOutboundServerId($current_user, $outbound_id);
    	break;
    case "testOutbound":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: testOutbound");
        if (SugarConfig::getInstance()->get("disable_user_email_config", false)
            && !$current_user->isAdminForModule("Emails")
        ) {
            break;
        }

        $pass = '';
        if(!empty($_REQUEST['mail_smtppass'])) {
            $pass = $_REQUEST['mail_smtppass'];
        } elseif(isset($_REQUEST['mail_name'])) {
            $oe = new OutboundEmail();
            $oe = $oe->getMailerByName($current_user, $_REQUEST['mail_name']);
            if(!empty($oe)) {
                $pass = $oe->mail_smtppass;
            }
        }
        $out = $email->sendEmailTest($_REQUEST['mail_smtpserver'], $_REQUEST['mail_smtpport'], $_REQUEST['mail_smtpssl'],
        							(isset($_REQUEST['mail_smtpauth_req']) ? 1 : 0), $_REQUEST['mail_smtpuser'],
        							$pass, $_REQUEST['outboundtest_from_address'], $_REQUEST['outboundtest_from_address']);

        $out = $json->encode($out);
        echo $out;
        break;

    case "rebuildShowAccount":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: rebuildShowAccount");
        $ret = $email->et->getShowAccountsOptions($ie);
        $results = array('account_list' => $ret, 'count' => count($ret));
        $out = $json->encode($results);
        echo $out;
        break;

    case "rebuildShowAccountForSearch":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: rebuildShowAccount");
        $ret = $email->et->getShowAccountsOptionsForSearch($ie);
        $out = $json->encode($ret);
        echo $out;
        break;

    case "deleteIeAccount":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: deleteIeAccount");
        if(isset($_REQUEST['group_id']) && $_REQUEST['group_id'] == $current_user->id) {
            $ret = array();
            $updateFolders = array();
        	$ret['id'] = $_REQUEST['ie_id'];
            $out = $json->encode($ret);
            $ie->hardDelete($_REQUEST['ie_id']);
            $out = $json->encode(array('id' => $_REQUEST['ie_id']));
            echo $out;

            foreach ($showFolders as $id) {
                if ($id != $_REQUEST['ie_id']) {
                    $updateFolders[] = $id;
                }
            }

            $showStore = base64_encode(serialize($updateFolders));
            $current_user->setPreference('showFolders', $showStore, 0, 'Emails');
        }
        break;

    case "saveIeAccount":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveIeAccount");
        if (SugarConfig::getInstance()->get("disable_user_email_config", false)
            && !$current_user->isAdminForModule("Emails")
        ) {
            break;
        }
        if(isset($_REQUEST['server_url']) && !empty($_REQUEST['server_url'])) {
            if(false === $ie->savePersonalEmailAccount($current_user->id, $current_user->user_name, false)) {
                $ret = array('error' => 'error');
                $out = $json->encode($ret);
                echo $out;
            } else {
                $ie->retrieve($_REQUEST['ie_id']);
				if (!isset($ie->created_by_link)) {
					$ie->created_by_link = null;
				}
				if (!isset($ie->modified_user_id_link)) {
					$ie->modified_user_id_link = null;
				}
				if (!is_array($showFolders)) {
					$showFolders = array();
				}
                if(!in_array($ie->id, $showFolders)) {
                    $showFolders[] = $ie->id;
                    $showStore = base64_encode(serialize($showFolders));
                    $current_user->setPreference('showFolders', $showStore, 0, 'Emails');
                }

                foreach($ie->field_defs as $k => $v) {
                	if (isset($v['type']) && ($v['type'] == 'link')) {
                		continue;
                	}
                    if($k == 'stored_options') {
                        $ie->$k = Serialized::unserialize($ie->$k, array(), true);
	                    if (isset($ie->stored_options['from_name'])) {
	                    	$ie->stored_options['from_name'] = from_html($ie->stored_options['from_name']);
	                    }
                    } elseif($k == 'service') {
                        $service = explode("::", $ie->$k);
                        $retService = array();

                        foreach($service as $serviceK => $serviceV) {
                            if(!empty($serviceV)) {
                                $retService[$serviceK] = $serviceV;
                            }
                        }

                        $ie->$k = $retService;
                    }

                    if (isset($ie->$k))
                    $ret[$k] = $ie->$k;
                }

                $out = $json->encode($ret);
                echo $out;
            }

            //If the user is saving the username/password then we need to update the outbound account.
            $outboundMailUser = (isset($_REQUEST['mail_smtpuser'])) ? $_REQUEST['mail_smtpuser'] : "";
            $outboundMailPass = (isset($_REQUEST['mail_smtppass'])) ? $_REQUEST['mail_smtppass'] : "";
            $outboundMailId = (isset($_REQUEST['outbound_email'])) ? $_REQUEST['outbound_email'] : "";

            if( !empty($outboundMailUser) && !empty($outboundMailPass) && !empty($outboundMailId) )
            {
                $oe = new OutboundEmail();
                $oe->retrieve($outboundMailId);
                $oe->mail_smtpuser = $outboundMailUser;
                $oe->mail_smtppass = $outboundMailPass;
                $oe->save();
            }

        } else {
            echo "NOOP";
        }
        break;

    case "getIeAccount":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getIeAccount");
        $ie->retrieve($_REQUEST['ieId'], false);
        if($ie->group_id == $current_user->id) {
            $ret = array();

            foreach($ie->field_defs as $k => $v) {
                if($k == 'stored_options') {
                    $ie->$k = Serialized::unserialize($ie->$k, array(), true);
                    if (isset($ie->stored_options['from_name'])) {
                    	$ie->stored_options['from_name'] = from_html($ie->stored_options['from_name']);
                    }
                } elseif($k == 'service') {
                    $service = explode("::", $ie->$k);
                    $retService = array();
                    foreach($service as $serviceK => $serviceV) {
                        if(!empty($serviceV)) {
                            $retService[$serviceK] = $serviceV;
                        }
                    }

                    $ie->$k = $retService;
                }

                $ret[$k] = $ie->$k;
            }
            unset($ret['email_password']); // no need to send the password out

            $out = $json->encode($ret);
        } else {
            $out = $mod_strings['LBL_ID_MISMATCH'];
        }
        echo $out;
        break;
        ////    END INBOUND EMAIL ACCOUNTS
        ///////////////////////////////////////////////////////////////////////////



        ///////////////////////////////////////////////////////////////////////////
        ////    SEARCH
    case "search":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: search");
        if(isset($_REQUEST['subject']) && !empty($_REQUEST['subject']) && isset($_REQUEST['ieId']) && !empty($_REQUEST['ieId'])) {
            $metalist = $ie->search($_REQUEST['ieId'], $_REQUEST['subject']);
            if (!isset($_REQUEST['page'])) {
                $_REQUEST['page'] = 1;
            }
            $_REQUEST['pageSize'] = count($metalist['out']);
            $out = $email->et->xmlOutput($metalist, 'Email', false);
            @ob_end_clean();
            ob_start();
            header("Content-type: text/xml");
            echo $out;
            ob_end_flush();
            die();
        } else {
            echo $mod_strings['LBL_NO_SEARCH_CRITERIA'];
        }
        break;

    case "searchAdvanced":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: searchAdvanced");

        $metalist = $email->searchImportedEmails();
        $out = $email->et->jsonOuput($metalist, 'Email', $metalist['totalCount']);

        @ob_end_clean();
        ob_start();
        echo $out;
        ob_end_flush();
        die();

        break;
        ////    END SEARCH
        ///////////////////////////////////////////////////////////////////////////



        ///////////////////////////////////////////////////////////////////////////
        ////    SETTINGS
    case "loadPreferences":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: loadPreferences");
        $prefs = $email->et->getUserPrefsJS();
        $out = $json->encode($prefs);
        echo $out;
        break;

    case "saveSettingsGeneral":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveSettingsGeneral");
        $emailSettings = array();
        $emailSettings['emailCheckInterval'] = $_REQUEST['emailCheckInterval'];
        $emailSettings['alwaysSaveOutbound'] = '1';
        $emailSettings['sendPlainText'] = isset($_REQUEST['sendPlainText']) ? '1' : '0';
        $emailSettings['showNumInList'] = $_REQUEST['showNumInList'];
        $emailSettings['defaultOutboundCharset'] = $_REQUEST['default_charset'];
        $current_user->setPreference('emailSettings', $emailSettings, '', 'Emails');

        // signature
        $current_user->setPreference('signature_default', $_REQUEST['signature_id']);
        $current_user->setPreference('signature_prepend', (isset($_REQUEST['signature_prepend'])) ? true : false);

        $out = $json->encode($email->et->getUserPrefsJS());
        echo $out;
        break;

    case "setPreference":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: setPreference");
        if(isset($_REQUEST['prefName']) && isset($_REQUEST['prefValue'])) {
            // handle potential JSON encoding
            if(isset($_REQUEST['decode'])) {
                $_REQUEST['prefValue'] = $json->decode(from_html($_REQUEST['prefValue']));
            }

            $current_user->setPreference($_REQUEST['prefName'], $_REQUEST['prefValue'], '', 'Emails');
        }
        break;
        ////    END SETTINGS
        ///////////////////////////////////////////////////////////////////////////




        ///////////////////////////////////////////////////////////////////////////
        ////    ADDRESS BOOK

    case "editContact":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: editContact");
        if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
            $module = "Contacts";
            $ret = $email->et->getEditContact($_REQUEST['id'], $module);
        }
        $out = $json->encode($ret);
        echo $out;
        break;


        /* The four calls below all have the same return signature */
    case "removeContact":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: removeContacts");
        if(strpos($_REQUEST['ids'], "::") !== false) {
            $removeIds = explode("::", $_REQUEST['ids']);
        } else {
            $removeIds[] = $_REQUEST['ids'];
        }
        $email->et->removeContacts($removeIds);

    case "saveContactEdit":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: saveContactEdit");
        if(isset($_REQUEST['args']) && !empty($_REQUEST['args'])) {
            $email->et->saveContactEdit($_REQUEST['args']);
        }
        // flow into getUserContacts();
    case "addContact":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: addContacts");
        $contacts = array();

        if(isset($_REQUEST['bean_module']) && !empty($_REQUEST['bean_module']) && isset($_REQUEST['bean_id']) && !empty($_REQUEST['bean_id'])) {
            $contacts[$_REQUEST['bean_id']] = array(
            'id' => $_REQUEST['bean_id'],
            'module' => $_REQUEST['bean_module']
            );
            $email->et->setContacts($contacts);
        }

    case "addContactsMultiple":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: addContacts");
        if (isset($_REQUEST['contactData'])) {
            $contacts = $json->decode(from_HTML($_REQUEST['contactData']));
            if ($contacts) {
                $email->et->setContacts($contacts);
            }
        }

    case "getUserContacts":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: getUserContacts");
        $contacts = $email->et->getContacts();

        if(is_array($contacts) && !empty($contacts)) {
            $ret = $email->et->getUserContacts($contacts, $current_user);
            $out = $json->encode($ret);
            echo $out;
        } else {
            echo "{}";
        }
   break;

        /* MAILING LISTS */
    case "editMailingList":
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: editMailingList");
        if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
            $ret = $email->et->getEditMailingList($_REQUEST['id']);
        }
        $out = $json->encode($ret);
        echo $out;
        break;

    case "addContactsToMailinglist":
        if(isset($_REQUEST['list_id']) && !empty($_REQUEST['list_id']) && isset($_REQUEST['contacts'])) {
            $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: addContactsToMailinglist");
            $contacts = $json->decode(from_html($_REQUEST['contacts']));
            // the below are used when editting a mailing list in a modal popup
            $newName = isset($_REQUEST['newName']) ? $_REQUEST['newName'] : "";
            $truncate = isset($_REQUEST['remove']) ? true : false; // truncate all contacts when editting from popup

            $email->et->addContactsToList($_REQUEST['list_id'], $contacts, $newName, $truncate);
        }
    case "removeLists":
        if(isset($_REQUEST['removeIds']) && !empty($_REQUEST['removeIds'])) {
            $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: removeLists");
            $ids = explode("::", $_REQUEST['removeIds']);
            $email->et->removeLists($ids);
        }
    case "addList":
        if(isset($_REQUEST['name'])) {
            $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: addList");
            $email->et->createList($_REQUEST['name']);
        }

        $lists = $email->et->getLists();
        $out = $json->encode($lists);
        echo $out;
        break;
        /* END MAILING LISTS */

        // address book search
    case "getUnionData":
        $wheres = array();
        $person;
        if(isset($_REQUEST['first_name']) && !empty($_REQUEST['first_name'])) {
            $wheres['first_name'] = $_REQUEST['first_name'];
        }
        if(isset($_REQUEST['last_name']) && !empty($_REQUEST['last_name'])) {
            $wheres['last_name'] = $_REQUEST['last_name'];
        }
        if(isset($_REQUEST['email_address']) && !empty($_REQUEST['email_address'])) {
            $wheres['email_address'] = $_REQUEST['email_address'];
        }
		if(isset($_REQUEST['person']) && !empty($_REQUEST['person'])) {
            $person = $_REQUEST['person'];
        }
        $q = $email->et->_getPeopleUnionQuery($wheres , $person);
        $r = $ie->db->limitQuery($q, 0, 25, true);

        $out = array();
        while($a = $ie->db->fetchByAssoc($r)) {
            $person = array();
            $person['id'] = $a['id'];
            $person['module'] = $a['module'];
            $person['full_name'] = $locale->formatName($a['module'], $a);
            $person['email_address'] = $a['email_address'];
            $out[] = $person;
        }

        $ret = $json->encode($out, true);
        echo $ret;
        break;

    case "getAddressSearchResults":
        $wheres = array();
        $person = 'contacts';
        $relatedBeanInfo = '';
  		if(isset($_REQUEST['related_bean_id']) && !empty($_REQUEST['related_bean_id'])) {
            $isRelatedSearch = true;
            $relatedBeanInfo['related_bean_id'] = $_REQUEST['related_bean_id'];
            $relatedBeanInfo['related_bean_type'] = ucfirst($_REQUEST['related_bean_type']);
        }

        if (isset($_REQUEST['search_field'])) {
        	$wheres['first_name'] = $_REQUEST['search_field'];
        	$wheres['last_name'] = $_REQUEST['search_field'];
        	$wheres['email_address'] = $_REQUEST['search_field'];
        }

        if(isset($_REQUEST['person']) && !empty($_REQUEST['person'])) {
            $person = $_REQUEST['person'];
        }
        if(!empty($_REQUEST['start'])) {
            $start = intval($_REQUEST['start']);
        } else {
        	$start = 0;
        }

        $qArray = $email->et->getRelatedEmail($person, $wheres, $relatedBeanInfo);
        $out = array();
        $count = 0;
        if (!empty($qArray['query'])) {
	        $countq = $qArray['countQuery'];
	        $time = microtime(true);
	        $r = $ie->db->query($countq);
	        $GLOBALS['log']->debug("***QUERY counted in " . (microtime(true) - $time) . " milisec\n");
	        if($row = $GLOBALS['db']->fetchByAssoc($r)){
	            $count = $row['c'];
	        }
	        $time = microtime(true);

	        //Handle sort and order requests
	        $sort = !empty($_REQUEST['sort']) ? $ie->db->getValidDBName($_REQUEST['sort']) : "id";
	        $sort = ($sort == 'bean_id') ? 'id' : $sort;
	        $sort = ($sort == 'email') ? 'email_address' : $sort;
	        $sort = ($sort == 'name') ? 'last_name' : $sort;
	        $direction = !empty($_REQUEST['dir']) && in_array(strtolower($_REQUEST['dir']), array("asc", "desc")) ? $_REQUEST['dir'] : "asc";
	        $order = ( !empty($sort) && !empty($direction) ) ? " ORDER BY {$sort} {$direction}" : "";

	        $r = $ie->db->limitQuery($qArray['query'] . " $order ", $start, 25, true);
	        $GLOBALS['log']->debug("***QUERY Got results in " . (microtime(true) - $time) . " milisec\n");


	        while($a = $ie->db->fetchByAssoc($r)) {
	            $person = array();
	            $person['bean_id'] = $a['id'];
	            $person['bean_module'] = $a['module'];
	            $person['name'] = $locale->formatName($a['module'], $a);
	            $person['email'] = $a['email_address'];
	            $out[] = $person;
	        }
        }
        $ret = $email->et->jsonOuput(array('out' => $out), 'Person', $count);

        @ob_end_clean();
        ob_start();
        echo $ret;
        ob_end_flush();
    break;

        ////    END ADDRESS BOOK
        ///////////////////////////////////////////////////////////////////////////



        ///////////////////////////////////////////////////////////////////////////
        ////    MISC

    default:
        $GLOBALS['log']->debug("********** EMAIL 2.0 - Asynchronous - at: default");
        echo "NOOP";
        break;
  } // switch
 } // if
