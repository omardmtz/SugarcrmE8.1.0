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

use Sugarcrm\Sugarcrm\Util\Uuid;

class Email extends SugarBean {

    const STATE_READY = 'Ready';
    const STATE_DRAFT = 'Draft';
    const STATE_ARCHIVED = 'Archived';

    /**
     * A flag to toggle when synchronizing the email's sender and recipients. See
     * {@link Email::synchronizeEmailParticipants()} for a full description.
     *
     * @var bool
     * @internal Do not use or override this property.
     * @deprecated This property will be removed once the sender and recipients for all emails have been synchronized.
     */
    private $isSynchronizingEmailParticipants = false;

	/* SugarBean schema */
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $assigned_user_name;
	var $modified_user_id;
	var $created_by;
	var $team_id;
	var $deleted;
    /**
     * The name and email address of the email's sender, formatted for use in the email's FROM header.
     *
     * @var string
     * @deprecated Use {@link Email::$from} to link the sender to the email.
     */
	var $from_addr;
    /**
     * @var string
     * @deprecated Replies are directed to the sender. Use {@link Email::$from} to retrieve the sender.
     */
	var $reply_to_addr;
    /**
     * The names and email addresses of the email's recipients, formatted for use in the email's TO header.
     *
     * @var string
     * @deprecated Use {@link Email::$to} to link the recipients to the email.
     */
	var $to_addrs;
    /**
     * The names and email addresses of the email's recipients, formatted for use in the email's CC header.
     *
     * @var string
     * @deprecated Use {@link Email::$cc} to link the recipients to the email.
     */
    var $cc_addrs;
    /**
     * The names and email addresses of the email's recipients, formatted for use in the email's BCC header.
     *
     * @var string
     * @deprecated Use {@link Email::$bcc} to link the recipients to the email.
     */
    var $bcc_addrs;
	var $message_id;

	/* Bean Attributes */
	var $name;
    /**
     * @var string
     * @deprecated {@link Email::$type} and {@link Email::$status} have been merged into one field named
     * {@link Email::$state}.
     */
    var $type = 'archived';
    var $date_sent;
    /**
     * @var string
     * @deprecated {@link Email::$type} and {@link Email::$status} have been merged into one field named
     * {@link Email::$state}.
     */
	var $status;
	var $intent;
	var $mailbox_id;
    /**
     * @var string
     * @deprecated Use {@link Email::$from} to link the sender to the email.
     */
	var $from_name;

	var $reply_to_status;
    /**
     * @var string
     * @deprecated Replies are directed to the sender. Use {@link Email::$from} to retrieve the sender.
     */
	var $reply_to_name;
    /**
     * @var string
     * @deprecated Replies are directed to the sender. Use {@link Email::$from} to retrieve the sender.
     */
	var $reply_to_email;
	var $description;
	var $description_html;
	var $raw_source;
	var $parent_id;
	var $parent_type;

	/* link attributes */
	var $parent_name;


	/* legacy */
	var $date_start; // legacy
	var $time_start; // legacy
    /**
     * @var string
     * @deprecated Use {@link Email::$from} to link the sender to the email.
     */
	var $from_addr_name;
    /**
     * @var array
     * @deprecated Use {@link Email::$to} to link the recipients to the email.
     */
	var $to_addrs_arr;
    /**
     * @var array
     * @deprecated Use {@link Email::$cc} to link the recipients to the email.
     */
    var $cc_addrs_arr;
    /**
     * @var array
     * @deprecated Use {@link Email::$bcc} to link the recipients to the email.
     */
    var $bcc_addrs_arr;
    /**
     * @var string
     * @deprecated Use {@link Email::$to} to link the recipients to the email.
     */
	var $to_addrs_ids;
    /**
     * @var string
     * @deprecated Use {@link Email::$to} to link the recipients to the email.
     */
	var $to_addrs_names;
    /**
     * @var string
     * @deprecated Use {@link Email::$to} to link the recipients to the email.
     */
	var $to_addrs_emails;
    /**
     * @var string
     * @deprecated Use {@link Email::$cc} to link the recipients to the email.
     */
	var $cc_addrs_ids;
    /**
     * @var string
     * @deprecated Use {@link Email::$cc} to link the recipients to the email.
     */
	var $cc_addrs_names;
    /**
     * @var string
     * @deprecated Use {@link Email::$cc} to link the recipients to the email.
     */
	var $cc_addrs_emails;
    /**
     * @var string
     * @deprecated Use {@link Email::$bcc} to link the recipients to the email.
     */
	var $bcc_addrs_ids;
    /**
     * @var string
     * @deprecated Use {@link Email::$bcc} to link the recipients to the email.
     */
	var $bcc_addrs_names;
    /**
     * @var string
     * @deprecated Use {@link Email::$bcc} to link the recipients to the email.
     */
	var $bcc_addrs_emails;
	var $contact_id;
	var $contact_name;

	/* Archive Email attrs */
	var $duration_hours;



	var $new_schema = true;
	var $table_name = 'emails';
	var $module_dir = 'Emails';
    var $module_name = 'Emails';
	var $object_name = 'Email';
	var $db;

	/* private attributes */
	var $rolloverStyle		= "<style>div#rollover {position: relative;float: left;margin: none;text-decoration: none;}div#rollover a:hover {padding: 0;text-decoration: none;}div#rollover a span {display: none;}div#rollover a:hover span {text-decoration: none;display: block;width: 250px;margin-top: 5px;margin-left: 5px;position: absolute;padding: 10px;color: #333;	border: 1px solid #ccc;	background-color: #fff;	font-size: 12px;z-index: 1000;}</style>\n";
    /**
     * @var string
     * @deprecated This property is no longer used.
     */
	var $cachePath;
    /**
     * @var string
     * @deprecated This property is no longer used.
     */
	var $cacheFile			= 'robin.cache.php';
    /**
     * The prefix for any lines in a reply that come from the original email.
     *
     * @var string
     * @deprecated This property is only used in {@link Email::getForwardHeader()}, which has been deprecated.
     */
	var $replyDelimiter	= "> ";
    /**
     * @var string
     * @deprecated Use {@link Email::$description} to store the plain-text body of the email.
     */
	var $emailDescription;
    /**
     * @var string
     * @deprecated Use {@link Email::$description_html} to store the HTML body of the email.
     */
	var $emailDescriptionHTML;
    /**
     * @var string
     * @deprecated Use {@link Email::$raw_source} to store the raw contents of the email.
     */
	var $emailRawSource;
    /**
     * @var string
     * @deprecated This property is only used in {@link Email::fill_in_additional_list_fields()},
     * {@link Email::fill_in_additional_detail_fields()}, and {@link Email::get_list_view_data()}.
     */
	var $link_action;
    /**
     * @var EmailAddress
     * @deprecated Use `BeanFactory::newBean('EmailAddresses')` to create an instance as needed.
     */
	var $emailAddress;
	var $attachments = array();
    /**
     * @var array
     * @deprecated This property is only used in {@link Email::send()} and {@link Email::handleAttachments()}.
     */
    var $saved_attachments = array();

	/* to support Email 2.0 */
	var $isDuplicate;
	var $uid;
	var $to;
    /**
     * The IMAP flag for an email.
     *
     * @var int
     * @deprecated The BWC Emails UI is no longer being used.
     */
	var $flagged;
	var $answered;
	var $seen;
	var $draft;
    /**
     * @var array
     * @deprecated This property is no longer used.
     */
	var $relationshipMap = array(
		'Contacts'	=> 'emails_contacts_rel',
		'Accounts'	=> 'emails_accounts_rel',
		'Leads'		=> 'emails_leads_rel',
		'Users'		=> 'emails_users_rel',
		'Prospects'	=> 'emails_prospects_rel',
	);

	/* public */
    /**
     * @var EmailUI
     * @deprecated The BWC Emails UI is no longer being used.
     */
    public $et;
    /**
     * Prefix to use when importing inlinge images in emails.
     *
     * @var string
     * @deprecated This property is only used in {@link Email::cid2Link()}.
     */
	public $imagePrefix;

    /**
     * A reference to the attachment icon for the selected theme.
     *
     * @var string
     * @deprecated This property is only used in {@link Email::fill_in_additional_list_fields()} and
     * {@link Email::get_list_view_data()}.
     */
    public $attachment_image;

    private $MockMailerFactoryClass = 'MailerFactory';

    /**
     * Used for keeping track of field defs that have been modified
     *
     * @var array
     * @deprecated This property is only used in {@link Email::setFieldNullable()} and
     * {@link Email::revertFieldNullable()}.
     */
    public $modifiedFieldDefs = array();

    /**
     * Used for keeping track of field defs that have been added
     *
     * @var array
     * @deprecated This property is only used in {@link Email::setFieldNullable()} and
     * {@link Email::revertFieldNullable()}.
     */
    protected $addedFieldDefs = array();

    /**
     * @var string
     * @deprecated {@link Email::$type} and {@link Email::$status} have been merged into one field named
     * {@link Email::$state}.
     */
    public $type_name;

    /**
     * @var string
     * @deprecated {@link Email::$type} and {@link Email::$status} have been merged into one field named
     * {@link Email::$state}.
     */
    public $status_name;

    /**
     * @var string
     */
    public $state;

	/**
	 * sole constructor
	 */
	public function __construct()
	{
	    global $current_user;
	    $this->cachePath = sugar_cached('modules/Emails');
		parent::__construct();
		$this->team_id = 1; // make the item globally accessible

		$this->emailAddress = BeanFactory::newBean('EmailAddresses');

		$this->imagePrefix = rtrim($GLOBALS['sugar_config']['site_url'], "/")."/cache/images/";
	}

    /**
     * {@inheritDoc}
     *
     * All Emails are initialized with an assigned_user_id equal to the signed in user
     */
    public function populateDefaultValues($force = false)
    {
        parent::populateDefaultValues($force);

        if (!empty($GLOBALS['current_user'])) {
            $this->assigned_user_id = $GLOBALS['current_user']->id;
        }
    }

    /**
     * @deprecated {@link EmailUI} is no longer used, making this method unnecessary.
     */
	function email2init() {
        LoggerManager::getLogger()->deprecated('Email::email2init() has been deprecated. EmailUI is no longer used.');

		$this->et = new EmailUI();
	}

    /**
     * This method is here solely to allow for the MailerFactory Class to be mocked for testing
     * It should never be used outside of the PHP Unit Test Framework
     *
     * @param $className
     * @deprecated This method is only used in a test for {@link Email::send()}.
     */
    public function _setMailerFactoryClassName($className) {
        LoggerManager::getLogger()->deprecated('Email::_setMailerFactoryClassName() has been deprecated.');

        $this->MockMailerFactoryClass = $className;
    }


    function bean_implements($interface){
		switch($interface){
			case 'ACL': return true;
			default: return false;
		}

	}

    /**
     * Use this method to determine if the email can be transitioned to the desired state.
     *
     * A new email's state can be "Archived" or "Draft", or "Ready" to send it. An existing draft can remain a draft. An
     * existing draft can transition to "Ready" to send it. An archived email cannot change states.
     *
     * @param string $newState
     * @return bool
     */
    public function isStateTransitionAllowed($newState)
    {
        // A new email can be placed in any valid state.
        if (!$this->isUpdate()) {
            return in_array($newState, [Email::STATE_ARCHIVED, Email::STATE_DRAFT, Email::STATE_READY]);
        }

        // An existing email must already have a valid state.
        if (!in_array($this->state, [Email::STATE_ARCHIVED, Email::STATE_DRAFT])) {
            return false;
        }

        // Noop's are ok.
        if ($this->state === $newState) {
            return true;
        }

        // A draft can be sent.
        if ($this->state === Email::STATE_DRAFT) {
            return $newState === Email::STATE_READY;
        }

        return false;
    }

    /**
     * Some data -- like the sender, recipients, subject, body, and attachments -- cannot be changed after an email is
     * archived. This method is used to determine if that data can be changed.
     *
     * @return bool
     */
    public function isArchived()
    {
        $isUpdateOfArchivedEmail = $this->isUpdate() && $this->state === static::STATE_ARCHIVED;

        return !$this->isSynchronizingEmailParticipants && $isUpdateOfArchivedEmail;
    }

	/**
	 * Presaves one attachment for new email 2.0 spec
	 * DOES NOT CREATE A NOTE
     *
     * @deprecated Use the File API to upload files and {@link Email::$attachments} in the Emails API to move files to
     * their final destinations and attach them to the email.
	 * @return string ID of note associated with the attachment
	 */
	public function email2saveAttachment()
	{
        LoggerManager::getLogger()->deprecated('Email::email2saveAttachment() has been deprecated. Use the File API '.
            'to upload files and Email::$attachments in the Emails API to move files to their final destinations and ' .
            'attach them to the email.');

        $email_uploads = "modules/Emails/{$GLOBALS['current_user']->id}";
	    $upload = new UploadFile('email_attachment');
		if(!$upload->confirm_upload()) {
		    $err = $upload->get_upload_error();
   		    if($err) {
   		        $GLOBALS['log']->error("Email Attachment could not be attached due to error: $err");
   		    }
   		    return array();
		}

		$guid = create_guid();
		$fileName = $upload->create_stored_filename();
        $GLOBALS['log']->debug("Email Attachment [$fileName]");
        if($upload->final_move($guid)) {
			sugar_mkdir(sugar_cached("$email_uploads/"));
			copy("upload://$guid", sugar_cached("$email_uploads/$guid"));
			return array(
					'guid' => $guid,
					'name' => $GLOBALS['db']->quote($fileName),
					'nameForDisplay' => $fileName
				);
        } else {
			$GLOBALS['log']->debug("Email Attachment [$fileName] could not be moved to upload dir");
			return array();
        }
	}

    /**
     * @deprecated Use the File API to upload files and {@link Email::$attachments} in the Emails API to move files to
     * their final destinations and attach them to the email.
     * @param string $filename
     * @return bool
     */
	function safeAttachmentName($filename) {
		global $sugar_config;

        LoggerManager::getLogger()->deprecated('Email::safeAttachmentName() has been deprecated. Use the File API ' .
            'to upload files and Email::$attachments in the Emails API to move files to their final destinations and ' .
            'attach them to the email.');

		$badExtension = false;
		//get position of last "." in file name
		$file_ext_beg = strrpos($filename, ".");
		$file_ext = "";

		//get file extension
		if($file_ext_beg !== false) {
			$file_ext = substr($filename, $file_ext_beg + 1);
		}

		//check to see if this is a file with extension located in "badext"
		foreach($sugar_config['upload_badext'] as $badExt) {
			if(strtolower($file_ext) == strtolower($badExt)) {
				//if found, then append with .txt and break out of lookup
				$filename = $filename . ".txt";
				$badExtension = true;
				break; // no need to look for more
			} // if
		} // foreach

		return $badExtension;
	} // fn

    /**
     * takes output from email 2.0 to/cc/bcc fields and returns appropriate arrays for usage by PHPMailer
     *
     * @deprecated This method is no longer used.
     * @param string addresses
     * @return array
     */
    public function email2ParseAddresses($addresses)
    {
        LoggerManager::getLogger()->deprecated('Email::email2ParseAddresses() has been deprecated.');

        $ret = array();
        if (!empty($addresses)) {
            $addresses = from_html($addresses);
            $addresses = $this->et->unifyEmailString($addresses);
            $pattern   = '/@.*,/U';
            preg_match_all($pattern, $addresses, $matchs);
            if (!empty($matchs[0])) {
                $total = $matchs[0];
                foreach ($total as $match) {
                    $convertedPattern = str_replace(',', '::;::', $match);
                    $addresses        = str_replace($match, $convertedPattern, $addresses);
                } //foreach
            }
            $exAddr = explode("::;::", $addresses);
            $clean  = array("<", ">");
            $dirty  = array("&lt;", "&gt;");
            foreach ($exAddr as $addr) {
                $name = '';
                $addr = str_replace($dirty, $clean, $addr);
                $lbpos = strrpos($addr, "<");
                if ($lbpos === false) {
                    $rbpos = strrpos($addr, ">");
                } else {
                    $rbpos = strpos($addr, ">", $lbpos);
                }
                if (($lbpos === false) && ($rbpos === false)) {
                    $address = $addr;
                } else {
                    $address = substr($addr, $lbpos + 1, $rbpos - 1 - $lbpos);
                    $name = substr($addr, 0, $lbpos);
                }
                $addrTemp            = array();
                $addrTemp['email']   = trim($address);
                $addrTemp['display'] = trim($name);
                $ret[]               = $addrTemp;
            }
        }
        return $ret;
    }

	/**
	 * takes output from email 2.0 to/cc/bcc fields and returns appropriate arrays for usage by PHPMailer
     *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to link recipients to the email.
     * Use {@link Email::$from} to link the sender to the email.
	 * @param string addresses
	 * @return array
	 */
	function email2ParseAddressesForAddressesOnly($addresses) {
        LoggerManager::getLogger()->deprecated('Email::email2ParseAddressesForAddressesOnly() has been deprecated.' .
            'Use Email::$to, Email::$cc, and Email::$bcc to link recipients to the email. Use Email::$from to link ' .
            'the sender to the email.');

		$addresses = from_html($addresses);
		$pattern = '/@.*,/U';
		preg_match_all($pattern, $addresses, $matchs);
		if (!empty($matchs[0])){
			$total = $matchs[0];
			foreach ($total as $match) {
				$convertedPattern = str_replace(',', '::;::', $match);
				$addresses = str_replace($match, $convertedPattern, $addresses);
			} //foreach
		}

		$exAddr = explode("::;::", $addresses);

		$ret = array();
		$clean = array("<", ">");
		$dirty = array("&lt;", "&gt;");

		foreach($exAddr as $addr) {
            $addr = str_replace($dirty, $clean, $addr);
            $lbpos = strrpos($addr, "<");
            if ($lbpos === false) {
                $rbpos = strrpos($addr, ">");
            } else {
                $rbpos = strpos($addr, ">", $lbpos);
            }
            if (($lbpos === false) && ($rbpos === false)) {
                $address = $addr;
            } else {
                $address = substr($addr, $lbpos + 1, $rbpos - 1 - $lbpos);
            }

			$ret[] = trim($address);
		}

		return $ret;
	}

	/**
	 * Determines MIME-type encoding as possible.
     *
     * @deprecated Use get_file_mime_type() instead.
	 * @param string $fileLocation relative path to file
	 * @return string MIME-type
	 */
	function email2GetMime($fileLocation) {
        LoggerManager::getLogger()->deprecated('Email::email2GetMime() has been deprecated. Use get_file_mime_type() ' .
            'instead.');

	    return get_file_mime_type($fileLocation, 'application/octet-stream');
	}

    /**
     * @deprecated The BWC Emails UI is no longer being used.
     * @param string $mailserver_url
     * @param string $port
     * @param string $ssltls
     * @param string $smtp_auth_req
     * @param string $smtp_username
     * @param string $smtppassword
     * @param string $fromaddress
     * @param string $toaddress
     * @param string $mail_sendtype
     * @param string $fromname
     * @return array
     */
    public static function sendEmailTest(
        $mailserver_url,
        $port,
        $ssltls,
        $smtp_auth_req,
        $smtp_username,
        $smtppassword,
        $fromaddress,
        $toaddress,
        $mail_sendtype = 'SMTP',
        $fromname = ''
    ) {
		global $current_user,
               $app_strings;

        LoggerManager::getLogger()->deprecated('Email::sendEmailTest() has been deprecated. The BWC Emails UI is no ' .
            'longer being used.');

		$mod_strings = return_module_language($GLOBALS['current_language'], 'Emails'); //Called from EmailMan as well.

        $fromname = (!empty($fromname)) ? html_entity_decode($fromname, ENT_QUOTES) : $current_user->name;

        $configurations                 = array();
        $configurations["from_email"]   = $fromaddress;
        $configurations["from_name"]    = $fromname;
        $configurations["display_name"] = "{$fromname} ({$fromaddress})";
        $configurations["personal"]     = 0;

        $outboundEmail                    = new OutboundEmail();
        $outboundEmail->mail_sendtype     = $mail_sendtype;
        $outboundEmail->mail_smtpserver   = $mailserver_url;
        $outboundEmail->mail_smtpport     = $port;
        $outboundEmail->mail_smtpauth_req = $smtp_auth_req;
        $outboundEmail->mail_smtpuser     = $smtp_username;
        $outboundEmail->mail_smtppass     = $smtppassword;
        $outboundEmail->mail_smtpssl      = $ssltls;

        $return = array();

        try {
            $outboundEmailConfiguration = OutboundEmailConfigurationPeer::buildOutboundEmailConfiguration(
                $current_user,
                $configurations,
                $outboundEmail
            );

            $mailer = MailerFactory::getMailer($outboundEmailConfiguration);

            $mailer->setSubject($mod_strings['LBL_TEST_EMAIL_SUBJECT']);
            $mailer->addRecipientsTo(new EmailIdentity($toaddress));
            $mailer->setTextBody($mod_strings['LBL_TEST_EMAIL_BODY']);

            $mailer->send();
            $return['status'] = true;
        } catch (MailerException $me) {
            $GLOBALS["log"]->error($me->getLogMessage());
            ob_clean();
            $return['status']       = false;
            $return['errorMessage'] = $app_strings['LBL_EMAIL_ERROR_PREPEND'] . $me->getMessage();
        }

        return $return;
	} // fn

	function decodeDuringSend($htmlData) {
	    $htmlData = str_replace("sugarLessThan", "&lt;", $htmlData);
	    $htmlData = str_replace("sugarGreaterThan", "&gt;", $htmlData);
		return $htmlData;
	}

	/**
	 * Returns true or false if this email is a draft.
	 *
     * @deprecated Check if {@link Email::$state} equals {@link Email::STATE_DRAFT} instead.
	 * @param array $request
	 * @return bool True indicates this email is a draft.
	 */
	function isDraftEmail($request)
	{
        LoggerManager::getLogger()->deprecated('Email::isDraftEmail() has been deprecated. Check if Email::$state ' .
            'equals Email::STATE_DRAFT instead.');

        return isset($request['saveDraft']) ||
            ($this->type == 'draft' && $this->status == 'draft') ||
            $this->state === static::STATE_DRAFT;
	}

	/**
	 * Sends Email for Email 2.0
     *
     * @deprecated Use {@link Email::sendEmail()} to send the email and {@link Email::save()} to save a draft.
	 */
	function email2Send($request) {
		global $current_user;
		global $timedate;

        $this->in_save = true;

        LoggerManager::getLogger()->deprecated('Email::email2Send() has been deprecated. Use Email::sendEmail() to ' .
            'send the email and Email::save() to save a draft.');

        // The fully constructed MIME message -- the email as it was transmitted to the mail server, complete with
        // headers and message parts -- is stored in this variable to allow the caller to choose to do something with
        // original content that was delivered.
        $sentMessage = null;

        $saveAsDraft = !empty($request['saveDraft']);
        if (!$saveAsDraft && !empty($request["MAIL_RECORD_STATUS"]) &&  $request["MAIL_RECORD_STATUS"]=='archived') {
            $archived = true;
            $this->type = 'archived';
        } else {
            $archived = false;
            if (!empty($request['MAIL_RECORD_STATUS']) && $request['MAIL_RECORD_STATUS'] === 'ready') {
                $this->type = 'out';
            }
        }

		/**********************************************************************
		 * Sugar Email PREP
		 */
		/* preset GUID */

		$orignialId = "";
		if(!empty($this->id)) {
			$orignialId = 	$this->id;
		} // if

		if(empty($this->id)) {
			$this->id = create_guid();
			$this->new_with_id = true;
		}

		/* satisfy basic HTML email requirements */
		$this->name = $request['sendSubject'];

        if(isset($_REQUEST['setEditor']) && $_REQUEST['setEditor'] == 1) {
            $_REQUEST['description_html'] = $_REQUEST['sendDescription'];
            $this->description_html = $_REQUEST['description_html'];
        } else {
            $this->description_html = '';
            $this->description = $_REQUEST['sendDescription'];
        }

        if ($this->isDraftEmail($request)) {
            if ($this->type != 'draft' && $this->status != 'draft') {
                $this->id = create_guid();
                $this->new_with_id = true;
                $this->date_entered = "";
            }
            global $dictionary;
            $this->db->updateParams(
                'emails_email_addr_rel',
                $dictionary['emails_email_addr_rel']['fields'],
                array('deleted' => 1),
                array('email_id' => $this->id)
            );
        }

        if ($saveAsDraft) {
            $this->type = 'draft';
            $this->status = 'draft';
            $this->state = static::STATE_DRAFT;
        } else {
            if ($archived) {
                $this->type = 'archived';
                $this->status = 'archived';
                $this->state = static::STATE_ARCHIVED;
            }

			/* Apply Email Templates */
			// do not parse email templates if the email is being saved as draft....
		    $toAddresses = $this->email2ParseAddresses($_REQUEST['sendTo']);
	        $sea = BeanFactory::newBean('EmailAddresses');
	        $object_arr = array();

			if( !empty($_REQUEST['parent_type']) && !empty($_REQUEST['parent_id']) &&
				($_REQUEST['parent_type'] == 'Accounts' ||
				$_REQUEST['parent_type'] == 'Contacts' ||
				$_REQUEST['parent_type'] == 'Leads' ||
				$_REQUEST['parent_type'] == 'Users' ||
				$_REQUEST['parent_type'] == 'Prospects')) {
			        $bean = BeanFactory::getBean($_REQUEST['parent_type'], $_REQUEST['parent_id']);
			        if(!empty($bean->id)) {
			            $object_arr[$bean->module_dir] = $bean->id;
			        }
			}
			foreach ($toAddresses as $addrMeta) {
			    $addr = $addrMeta['email'];
			    $beans = $sea->getBeansByEmailAddress($addr);
			    if (count($beans) == 1) {
			        if (!isset($object_arr[$beans[0]->module_dir])) {
			            $object_arr[$beans[0]->module_dir] = $beans[0]->id;
			        }
			    } else {
			        foreach ($beans as $bean) {
			            if (!isset($object_arr[$bean->module_dir]) &&
			                !empty($addrMeta['display']) && $addrMeta['display'] == $bean->name) {
			                $object_arr[$bean->module_dir] = $bean->id;
			                break;
			            }
			        }
			    }
			}

            if ($this->type != 'archived') {
                /* template parsing */
                if (empty($object_arr)) {
                    $object_arr = array('Contacts' => '123');
                }
                $object_arr['Users'] = $current_user->id;
                $this->description_html = EmailTemplate::parse_template($this->description_html, $object_arr, true);
                $this->name = EmailTemplate::parse_template($this->name, $object_arr);
                $this->description = EmailTemplate::parse_template($this->description, $object_arr);
            }

            $this->description = html_entity_decode($this->description, ENT_COMPAT, 'UTF-8');

            if ($this->type != 'draft' && $this->status != 'draft' &&
                $this->type != 'archived' && $this->status != 'archived'
            ) {
                $this->id = create_guid();
                $this->date_entered = "";
                $this->new_with_id = true;
                $this->type = 'out';
                $this->status = 'sent';
                $this->state = static::STATE_ARCHIVED;
            }
        }

        // Register the Email so it can be used in relationship logic hooks even before it is saved. As recommended by
        // BeanFactory::registerBean, this is done once the object has an ID. It just so happens that the ID could have
        // been set up to 3 times prior to this point. So this is done as late as possible -- after the last potential
        // opportunity to set the ID and before the first opportunity to use the object in a logic hook.
        BeanFactory::registerBean($this);

        if(isset($_REQUEST['parent_type']) && empty($_REQUEST['parent_type']) &&
			isset($_REQUEST['parent_id']) && empty($_REQUEST['parent_id']) ) {
				$this->parent_id = "";
				$this->parent_type = "";
		} // if

        $forceSave = false;
        $subject   = $this->name;
        $textBody  = from_html($this->description);
        $htmlBody  = from_html($this->description_html);

        //------------------- HANDLEBODY() ---------------------------------------------
        if ((isset($_REQUEST['setEditor']) /* from Email EditView navigation */
             && $_REQUEST['setEditor'] == 1
             && trim($_REQUEST['description_html']) != '')
            || trim($this->description_html) != '' /* from email templates */
               && $current_user->getPreference('email_editor_option', 'global') !== 'plain' //user preference is not set to plain text
        ) {
            $textBody = strip_tags(br2nl($htmlBody));
        } else {
            // plain-text only
            $textBody = str_replace("&nbsp;", " ", $textBody);
            $textBody = str_replace("</p>", "</p><br />", $textBody);
            $textBody = strip_tags(br2nl($textBody));
            $textBody = html_entity_decode($textBody, ENT_QUOTES, 'UTF-8');

            $this->description_html = ""; // make sure it's blank to avoid any mishaps
            $htmlBody               = $this->description_html;
        }

        $textBody               = $this->decodeDuringSend($textBody);
        $htmlBody               = $this->decodeDuringSend($htmlBody);
        $this->description      = $textBody;
        $this->description_html = $htmlBody;

        $mailConfig = null;

        // Even when saving a draft, we want to store the outbound email configuration that is to be used.
        if (isset($request['fromAccount']) && !empty($request['fromAccount'])) {
            $mailConfig = OutboundEmailConfigurationPeer::getMailConfigurationFromId(
                $current_user,
                $request['fromAccount']
            );
        }

        // Only fall back to the system outbound email configuration if sending the email now.
        if (!$saveAsDraft && !$archived) {
            if (!isset($request['fromAccount']) || empty($request['fromAccount'])) {
                $mailConfig = OutboundEmailConfigurationPeer::getSystemMailConfiguration($current_user);
            }

            if (is_null($mailConfig)) {
                throw new MailerException("No Valid Mail Configurations Found", MailerException::InvalidConfiguration);
            }
        }

        if ($mailConfig instanceof OutboundEmailConfiguration) {
            $this->outbound_email_id = $mailConfig->getConfigId();
        }

        try {
            $mailer = null;
            if (!$saveAsDraft && !$archived) {
                $mailerFactoryClass = $this->MockMailerFactoryClass;
                $mailer = $mailerFactoryClass::getMailer($mailConfig);
                $mailer->setSubject($subject);
                $mailer->setHtmlBody($htmlBody);
                $mailer->setTextBody($textBody);

                $replyTo = $mailConfig->getReplyTo();
                if (!empty($replyTo)) {
                    $replyToEmail = $replyTo->getEmail();
                    if (!empty($replyToEmail)) {
                        $mailer->setHeader(
                            EmailHeaders::ReplyTo,
                            new EmailIdentity($replyToEmail, $replyTo->getName())
                        );
                    }
                }
            }

            if (!is_null($mailer)) {
                // Any individual Email Address that is not valid will be logged and skipped
                // If all email addresses in the request are skipped, an error "No Recipients" is reported for the request
                foreach ($this->email2ParseAddresses($request['sendTo']) as $addr_arr) {
                    try {
                        $mailer->addRecipientsTo(new EmailIdentity($addr_arr['email'], $addr_arr['display']));
                    } catch (MailerException $me) {
                        // Invalid Email Address - Log it and Skip
                        $GLOBALS["log"]->warning($me->getLogMessage());
                    }
                }

                foreach ($this->email2ParseAddresses($request['sendCc']) as $addr_arr) {
                    try {
                        $mailer->addRecipientsCc(new EmailIdentity($addr_arr['email'], $addr_arr['display']));
                    } catch (MailerException $me) {
                        // Invalid Email Address - Log it and Skip
                        $GLOBALS["log"]->warning($me->getLogMessage());
                    }
                }

                foreach ($this->email2ParseAddresses($request['sendBcc']) as $addr_arr) {
                    try {
                        $mailer->addRecipientsBcc(new EmailIdentity($addr_arr['email'], $addr_arr['display']));
                    } catch (MailerException $me) {
                        // Invalid Email Address - Log it and Skip
                        $GLOBALS["log"]->warning($me->getLogMessage());
                    }
                }
            }

            /* handle attachments */
            if (!empty($request['attachments'])) {
                $exAttachments = explode("::", $request['attachments']);

                foreach ($exAttachments as $file) {
                    $file = trim(from_html($file));
                    $file = str_replace("\\", "", $file);
                    if (!empty($file)) {
                        $fileGUID = preg_replace('/[^a-z0-9\-]/', "", substr($file, 0, 36));
                        $fileLocation = $this->et->userCacheDir . "/{$fileGUID}";
                        $filename     = substr($file, 36, strlen($file)); // strip GUID	for PHPMailer class to name outbound file

                        // only save attachments if we're archiving or drafting
                        if ((($this->type == 'draft') && !empty($this->id)) || (isset($request['saveToSugar']) && $request['saveToSugar'] == 1)) {
                            $note                 = new Note();
                            $note->id             = create_guid();
                            $note->new_with_id    = true; // duplicating the note with files
                            $note->email_id = $this->id;
                            $note->email_type = $this->module_dir;
                            $note->name           = $filename;
                            $note->filename       = $filename;
                            $note->file_mime_type = $this->email2GetMime($fileLocation);
                            $note->team_id     = (isset($_REQUEST['primaryteam']) ? $_REQUEST['primaryteam'] : $current_user->getPrivateTeamID());
                            $noteTeamSet       = new TeamSet();
                            $noteteamIdsArray  = (isset($_REQUEST['teamIds']) ? explode(",", $_REQUEST['teamIds']) : array($current_user->getPrivateTeamID()));
                            $note->team_set_id = $noteTeamSet->addTeams($noteteamIdsArray);
                            $dest = "upload://{$note->id}";

                            if (!file_exists($fileLocation) || (!copy($fileLocation, $dest))) {
                                $GLOBALS['log']->debug("EMAIL 2.0: could not copy attachment file to $fileLocation => $dest");
                            } else {
                                $note->save();
                                $validNote = true;
                            }
                        } else {
                            $note      = new Note();
                            $validNote = (bool)$note->retrieve($fileGUID);
                        }

                        if (isset($validNote) && $validNote === true) {
                            $attachment = AttachmentPeer::attachmentFromSugarBean($note);
                            if (!is_null($mailer)) {
                                $mailer->addAttachment($attachment);
                            }
                        }
                    }
                }
            }

            /* handle sugar documents */
            if (!empty($request['documents'])) {
                $exDocs = explode("::", $request['documents']);

                foreach ($exDocs as $docId) {
                    $docId = trim($docId);
                    if (!empty($docId)) {
                        $doc = new Document();
                        $doc->retrieve($docId);

                        if (empty($doc->id) || $doc->id != $docId) {
                            throw new Exception("Document Not Found: Id='". $request['documents'] . "'");
                        }

                        $documentRevision                             = new DocumentRevision();
                        $documentRevision->retrieve($doc->document_revision_id);
                        //$documentRevision->x_file_name   = $documentRevision->filename;
                        //$documentRevision->x_file_path   = "upload/{$documentRevision->id}";
                        //$documentRevision->x_file_exists = (bool) file_exists($documentRevision->x_file_path);
                        //$documentRevision->x_mime_type   = $documentRevision->file_mime_type;

                        $filename     = $documentRevision->filename;
                        $docGUID = preg_replace('/[^a-z0-9\-]/', "", $documentRevision->id);
                        $fileLocation = "upload://{$docGUID}";

                        if (empty($documentRevision->id) || !file_exists($fileLocation)) {
                            throw new Exception("Document Revision Id Not Found");
                        }

                        // only save attachments if we're archiving or drafting
                        if ((($this->type == 'draft') && !empty($this->id)) || (isset($request['saveToSugar']) && $request['saveToSugar'] == 1)) {
                            $note                 = new Note();
                            $note->id             = create_guid();
                            $note->new_with_id    = true; // duplicating the note with files
                            $note->email_id = $this->id;
                            $note->email_type = $this->module_dir;
                            $note->name           = $filename;
                            $note->filename       = $filename;
                            $note->file_mime_type = $documentRevision->file_mime_type;
                            $note->team_id     = $this->team_id;
                            $note->team_set_id = $this->team_set_id;
                            $dest = "upload://{$note->id}";
                            if (!file_exists($fileLocation) || (!copy($fileLocation, $dest))) {
                                $GLOBALS['log']->debug("EMAIL 2.0: could not copy SugarDocument revision file $fileLocation => $dest");
                            }
                            $note->save();
                        }

                        $attachment = AttachmentPeer::attachmentFromSugarBean($documentRevision);
                        //print_r($attachment);
                        if (!is_null($mailer)) {
                            $mailer->addAttachment($attachment);
                        }
                    }
                }
            }

            /* handle template attachments */
            if (!empty($request['templateAttachments'])) {
                $exNotes = explode("::", $request['templateAttachments']);

                foreach ($exNotes as $noteId) {
                    $noteId = trim($noteId);

                    if (!empty($noteId)) {
                        $note = new Note();
                        $note->retrieve($noteId);

                        if (!empty($note->id)) {
                            $filename     = $note->filename;
                            $noteGUID = preg_replace('/[^a-z0-9\-]/', "", $note->id);
                            $fileLocation = "upload://{$noteGUID}";
                            $mime_type    = $note->file_mime_type;

                            if (!$note->embed_flag) {
                                $attachment = AttachmentPeer::attachmentFromSugarBean($note);
                                //print_r($attachment);
                                if (!is_null($mailer)) {
                                    $mailer->addAttachment($attachment);
                                }

                                // only save attachments if we're archiving or drafting
                                if ((($this->type == 'draft') && !empty($this->id)) || (isset($request['saveToSugar']) && $request['saveToSugar'] == 1)) {
                                    if ($note->email_id != $this->id) {
                                        $this->saveTempNoteAttachments($filename, $fileLocation, $mime_type, $noteGUID);
                                    }
                                } // if
                            } // if
                        } else {
                            $fileGUID = preg_replace('/[^a-z0-9\-]/', "", substr($noteId, 0, 36));
                            $fileLocation = $this->et->userCacheDir . "/{$fileGUID}";
                            $filename = substr($noteId, 36, strlen($noteId)); // strip GUID	for PHPMailer class to name outbound file

                            $mimeType = $this->email2GetMime($fileLocation);
                            $note = $this->saveTempNoteAttachments($filename, $fileLocation, $mimeType);

                            $attachment = AttachmentPeer::attachmentFromSugarBean($note);
                            //print_r($attachment);
                            if (!is_null($mailer)) {
                                $mailer->addAttachment($attachment);
                            }
                        }
                    }
                }
            }

            /**********************************************************************
             * Final Touches
             */
            if ($this->type == 'draft' && !$saveAsDraft) {
                // sending a draft email
                $this->type   = 'out';
                $this->status = 'sent';
                $this->state = static::STATE_ARCHIVED;
                $forceSave    = true;
            } elseif ($saveAsDraft) {
                $this->type   = 'draft';
                $this->status = 'draft';
                $this->state = static::STATE_DRAFT;
                $forceSave    = true;
            }

            if (!is_null($mailer)) {
                $mailer->setMessageId($this->id);
                $this->message_id = $mailer->getHeader(EmailHeaders::MessageId);
                $sentMessage = $mailer->send();
            }
        }
        catch (MailerException $me) {
            $GLOBALS["log"]->error($me->getLogMessage());
            throw($me);
        }
        catch (Exception $e) {
            // eat the phpmailerException but use it's message to provide context for the failure
            $me = new MailerException("Email2Send Failed: " . $e->getMessage(), MailerException::FailedToSend);
            $GLOBALS["log"]->error($me->getLogMessage());
            $GLOBALS["log"]->info($me->getTraceMessage());
            if (!empty($mailConfig)) {
                $GLOBALS["log"]->info($mailConfig->toArray(),true);
            }
            throw($me);
        }


		if ((!(empty($orignialId) || $saveAsDraft || ($this->type == 'draft' && $this->status == 'draft'))) &&
			(($_REQUEST['composeType'] == 'reply') || ($_REQUEST['composeType'] == 'replyAll') || ($_REQUEST['composeType'] == 'replyCase')) && ($orignialId != $this->id)) {
			$originalEmail = BeanFactory::getBean('Emails', $orignialId);
			$originalEmail->reply_to_status = 1;
			$originalEmail->save();
			$this->reply_to_status = 0;
		} // if

        if (isset($_REQUEST['composeType']) && ($_REQUEST['composeType'] == 'reply' || $_REQUEST['composeType'] == 'replyCase')) {
			if (isset($_REQUEST['ieId']) && isset($_REQUEST['mbox'])) {
				$emailFromIe = BeanFactory::getBean('InboundEmail', $_REQUEST['ieId'], array('disable_row_level_security' => true));
				$emailFromIe->mailbox = $_REQUEST['mbox'];
				if (isset($emailFromIe->id) && $emailFromIe->is_personal) {
					if ($emailFromIe->isPop3Protocol()) {
						$emailFromIe->mark_answered($this->uid, 'pop3');
					}
					elseif ($emailFromIe->connectMailserver() == 'true') {
						$emailFromIe->markEmails($this->uid, 'answered');
						$emailFromIe->mark_answered($this->uid);
					}
				}
			}
		}


		if(	$forceSave ||
			$this->type == 'draft' ||
            $this->type == 'archived' ||
			(isset($request['saveToSugar']) && $request['saveToSugar'] == 1)) {

            // Set Up From Name and Address Information
            if ($this->type == 'archived') {
                $this->from_addr = empty($request['archive_from_address']) ? '' : $request['archive_from_address'];
            } elseif (!empty($mailConfig)) {
                $sender = $mailConfig->getFrom();
                $decodedFromName = mb_decode_mimeheader($sender->getName());
                $this->from_addr = "{$decodedFromName} <" . $sender->getEmail() . ">";
            } else {
                $ret = $current_user->getUsersNameAndEmail();
                if (empty($ret['email'])) {
                    $systemReturn  = $current_user->getSystemDefaultNameAndEmail();
                    $ret['email']  = $systemReturn['email'];
                    $ret['name']   = $systemReturn['name'];
                }
                $decodedFromName = mb_decode_mimeheader($ret['name']);
                $this->from_addr = "{$decodedFromName} <" . $ret['email'] . ">";
            }

			$this->from_addr_name = $this->from_addr;
			$this->to_addrs = $_REQUEST['sendTo'];
			$this->to_addrs_names = $_REQUEST['sendTo'];
			$this->cc_addrs = $_REQUEST['sendCc'];
			$this->cc_addrs_names = $_REQUEST['sendCc'];
			$this->bcc_addrs = $_REQUEST['sendBcc'];
			$this->bcc_addrs_names = $_REQUEST['sendBcc'];
			$this->team_id = (!empty($_REQUEST['primaryteam']) ?  $_REQUEST['primaryteam'] : $current_user->getPrivateTeamID());
			/* @var TeamSet $teamSet */
			$teamSet = BeanFactory::newBean('TeamSets');
			$teamIdsArray = (!empty($_REQUEST['teamIds']) ?  explode(",", $_REQUEST['teamIds']) : array($current_user->getPrivateTeamID()));
			$this->team_set_id = $teamSet->addTeams($teamIdsArray);
            $selectedTeamIdsArray = !empty($_REQUEST['selectedTeam'])
				? explode(",", $_REQUEST['selectedTeam'])
				: array();
			if (!empty($selectedTeamIdsArray)) {
                $this->acl_team_set_id = $teamSet->addTeams($selectedTeamIdsArray);
			} else {
                $this->acl_team_set_id = '';
			}

            if ($archived && !empty($request['assignedUser'])) {
                $this->assigned_user_id = $request['assignedUser'];
            } else {
                $this->assigned_user_id = $current_user->id;
            }

            if ($archived && !empty($request['dateSent'])) {
                $this->date_sent = $request['dateSent'];
            } else {
                $this->date_sent = $timedate->now();
            }

			///////////////////////////////////////////////////////////////////
			////	LINK EMAIL TO SUGARBEANS BASED ON EMAIL ADDY

			if(!empty($_REQUEST['parent_type']) && !empty($_REQUEST['parent_id']) ) {
	                $this->parent_id = $this->db->quote($_REQUEST['parent_id']);
	                $this->parent_type = $this->db->quote($_REQUEST['parent_type']);
				} else {
                    $c = BeanFactory::newBean('Cases');
                    $ie = BeanFactory::newBean('InboundEmail');
                    if ($caseId = $ie->getCaseIdFromCaseNumber($subject, $c)) {
                        $this->parent_type = "Cases";
                        $this->parent_id = $caseId;
                    } // if
				} // else

			////	LINK EMAIL TO SUGARBEANS BASED ON EMAIL ADDY
			///////////////////////////////////////////////////////////////////
			$this->save();
		}

        if (!empty($request['fromAccount']) && !empty($sentMessage) && !empty($this->outbound_email_id)) {
            $ie = BeanFactory::getBean('InboundEmail', $request['fromAccount']);
            $oe = new OutboundEmail();
            $oe->retrieve($this->outbound_email_id);

            if (isset($ie->id) && !$ie->isPop3Protocol() && !empty($oe->id) && $oe->mail_smtptype != 'gmail') {
                $sentFolder = $ie->get_stored_options('sentFolder');

                if (!empty($sentFolder)) {
                    $ie->mailbox = $sentFolder;

                    if ($ie->connectMailserver() == 'true') {
                        $connectString = $ie->getConnectString($ie->getServiceString(), $ie->mailbox);

                        if (imap_append($ie->conn, $connectString, $sentMessage, '\\Seen')) {
                            $GLOBALS['log']->info("copied email ({$this->id}) to {$ie->mailbox} for {$ie->name}");
                        } else {
                            $GLOBALS['log']->debug("could not copy email to {$ie->mailbox} for {$ie->name}");
                        }
                    } else {
                        $GLOBALS['log']->debug(
                            "could not connect to mail server for folder {$ie->mailbox} for {$ie->name}"
                        );
                    }
                } else {
                    $GLOBALS['log']->debug("could not copy email to {$ie->mailbox} sent folder as its empty");
                }
            }
        }

		return true;
	} // end email2Send

	/**
	 * Generates a config-specified separated name and addresses to be used in compose email screen for
	 * contacts or leads from listview
     * By default, use comma, but allow for non-standard delimeters as specified in email_address_separator
	 *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to retrieve the email's
     * recipients. Use {@link Email::$from} to retrieve the email's sender.
	 * @param $module string module name
	 * @param $idsArray array of record ids to get the email address for
	 * @return string (config-specified) delimited list of email addresses
	 */
	public function getNamePlusEmailAddressesForCompose($module, $idsArray)
	{
		global $locale;
		global $db;

        LoggerManager::getLogger()->deprecated('Email::getNamePlusEmailAddressesForCompose() has been deprecated. ' .
            "Use Email::\$to, Email::\$cc, and Email::\$bcc to retrieve the email's recipients. Use Email::\$from to " .
            "retrieve the email's sender.");

        $result = array();
		$table = BeanFactory::newBean($module)->table_name;
		$returndata = array();
		$idsString = "";
		foreach($idsArray as $id) {
			if ($idsString != "") {
				$idsString = $idsString . ",";
			} // if
			$idsString = $idsString . "'" . $id . "'";
		} // foreach
		$where = "({$table}.deleted = 0 AND {$table}.id in ({$idsString}))";

        foreach ($idsArray as $id)
        {
            // Load bean
            $bean = BeanFactory::getBean($module, $id);
            // Got a bean
            if (!empty($bean))
            {
                $emailAddress = '';
                // If has access to primary mail, use it
                if ($bean->ACLFieldAccess('email1', 'read'))
                {
                    $emailAddress = $bean->email1;
                }
                // Otherwise, try to use secondary
                else if ($bean->ACLFieldAccess('email2', 'read'))
                {
                    $emailAddress = $bean->email2;
                }

                // If we have an e-mail address loaded
                if (!empty($emailAddress))
                {
                    $fullName = from_html($bean->get_summary_text());

                    // Make e-mail address in format "Name <@email>"
                    $result[$bean->id] = $fullName . " <" . from_html($emailAddress) . ">";
                }
            }
        }

        // Broken out of method to facilitate unit testing
        return $this->_arrayToDelimitedString($result);
    }

    /**
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to retrieve the email's
     * recipients. Use {@link Email::$from} to retrieve the email's sender.
     * @param Array $arr - list of strings
     * @return string the list of strings delimited by email_address_separator
     */
    function _arrayToDelimitedString($arr)
    {
        LoggerManager::getLogger()->deprecated('Email::_arrayToDelimitedString() has been deprecated. Use ' .
            "Email::\$to, Email::\$cc, and Email::\$bcc to retrieve the email's recipients. Use Email::\$from to " .
            "retrieve the email's sender.");

        // bug 51804: outlook does not respect the correct email address separator (',') , so let
        // clients override the default.
        $separator = (isset($GLOBALS['sugar_config']['email_address_separator']) &&
                        !empty($GLOBALS['sugar_config']['email_address_separator'])) ?
                     $GLOBALS['sugar_config']['email_address_separator'] :
                     ',';

		return join($separator, array_values($arr));
    }

	/**
	 * Overrides
	 */
	///////////////////////////////////////////////////////////////////////////
	////	SAVERS
	function save($check_notify = false) {
		if($this->isDuplicate) {
			$GLOBALS['log']->debug("EMAIL - tried to save a duplicate Email record");
		} else {
            $td = TimeDate::getInstance();

			if(empty($this->id)) {
				$this->id = create_guid();
				$this->new_with_id = true;
			}

            // The email is guaranteed to have an ID at this point. Any beans with an ID need to be registered, so that
            // we can use them in relationships and other processes prior to saving.
            BeanFactory::registerBean($this);

            // This will prevent the BeanFactory::registerBean from removing the unsaved email bean
            // from the BeanFactory cache while linking large number of recipients
            // via Email::saveEmailAddresses() prior to calling parent::save()
            $this->in_save = true;

            if ($this->state === static::STATE_ARCHIVED) {
                // Copy plain-text email body to HTML field column
                if (empty($this->description_html) && !empty($this->description)) {
                    $this->description_html = str_replace(array("\r\n", "\n", "\r"), '<br />', $this->description);
                }

                // Strip HTML tags from the description_html and save to description
                if (!empty($this->description_html) && empty($this->description)) {
                    // Replace HTML line breaks with non HTML line breaks and strip all remaining HTML tags
                    $this->description = strip_tags(br2nl($this->description_html));
                }
            }

			$this->from_addr_name = $this->cleanEmails($this->from_addr_name);
			$this->to_addrs_names = $this->cleanEmails($this->to_addrs_names);
			$this->cc_addrs_names = $this->cleanEmails($this->cc_addrs_names);
			$this->bcc_addrs_names = $this->cleanEmails($this->bcc_addrs_names);
			$this->reply_to_addr = $this->cleanEmails($this->reply_to_addr);
            $this->description = $this->cleanContent($this->description);
            $this->description_html = $this->cleanContent($this->description_html, true);
            $this->raw_source = $this->cleanContent($this->raw_source, true);

			$GLOBALS['log']->debug('-------------------------------> Email called save()');

            // Overrides SugarBean behavior to use Global as the default team.
            if ($this->state === static::STATE_ARCHIVED && empty($this->team_id)) {
                $this->team_id = '1';
                $this->team_set_id = '1';
            }

            $this->updateAssignedUser();

            // Set date_sent.
            if ($this->state === static::STATE_DRAFT) {
                // Always update the timestamp when saving a draft.
                $this->date_sent = $td->nowDb();
                $this->type = 'draft';
                $this->status = 'draft';
            } elseif (empty($this->date_sent)) {
                // Default the timestamp when it is empty.
                if (!empty($this->date_start) && !empty($this->time_start)) {
                    // Preserve legacy concatenation of date_start and time_start.
                    // SI Bug #39503: SugarBean is not setting date_sent when seconds are missing.
                    $mergedDateTime = $td->merge_date_time($this->date_start, $this->time_start);
                    $dateSent = $td->fromUser($mergedDateTime, $GLOBALS['current_user']);

                    if ($dateSent) {
                        $this->date_sent = $dateSent->asDb();
                    }
                } else {
                    $this->date_sent = $td->nowDb();
                }
            }

            // Linking the email addresses must precede saving the emails_text data so that the sender and recipients
            // are linked to the email before an attempt is made to recalculate those fields in the emails_text table.
            // And linking the email addresses must precede saving the email to avoid exceptions when creating an
            // archived email.
            $this->saveEmailAddresses();

			$parentSaveResult = parent::save($check_notify);

            // Add the current user as the sender when the email is a draft.
            if ($this->state === static::STATE_DRAFT) {
                // Don't allow exceptions to bubble up for this action. No user input is used in this action, so there
                // is not a need to report errors due to invalid data in the REST API request and no exceptions from
                // EmailSenderRelationship should cause the save operation to halt. It is safe to allow the email to be
                // fully saved and for any errors to be corrected on subsequent saves.
                try {
                    $this->setSender($GLOBALS['current_user']);
                } catch (Exception $e) {
                    $GLOBALS['log']->error('Failed to set the current user as the sender when saving a draft email');
                }
            }

            $this->linkParentBeanUsingRelationship();
            $this->saveEmailText();
            $this->updateAttachmentsVisibility();

            return $parentSaveResult;
		}
		$GLOBALS['log']->debug('-------------------------------> Email save() done');
	}

    /**
     * Updates assigned user based on the email state
     */
    private function updateAssignedUser()
    {
        global $current_user;

        // Assign the email to the current user if it is a draft.
        if ($this->state === static::STATE_DRAFT) {
            $this->assigned_user_id = $current_user->id;
        }
    }

    /**
     * Clean string from potential XSS problems.
     *
     * @see SugarCleaner::cleanHtml()
     * @param string $content
     * @param bool $encoded
     * @return string
     */
    protected function cleanContent($content, $encoded = false)
    {
        return SugarCleaner::cleanHtml($content, $encoded);
    }

    /**
     * Sets the bean as the sender of the email. The bean's module must use the "email_address" template because only
     * beans with an email address can be used as a sender.
     *
     * @param SugarBean $bean
     * @throws SugarException if the "from" relationship could not be loaded.
     */
    protected function setSender(SugarBean $bean)
    {
        if (!$this->load_relationship('from')) {
            throw new SugarException('Could not find a relationship named: from');
        }

        $ep = BeanFactory::newBean('EmailParticipants');
        $ep->new_with_id = true;
        $ep->id = Uuid::uuid1();
        BeanFactory::registerBean($ep);
        $ep->parent_type = BeanFactory::getModuleName($bean);
        $ep->parent_id = $bean->id;
        $this->from->add($ep);
    }

    /**
     * If the parent fields are set, then link the email to the bean on the emails_beans join table, as long as there is
     * a link that matches the module name (or <module_name>_activities_1_emails for the Activities relationship added
     * via Studio or <module_name>_activities_emails for the Activities relationship added via Module Builder).
     *
     * @see SI Bug 22504
     * @uses Email::findEmailsLink()
     */
    protected function linkParentBeanUsingRelationship()
    {
        if (empty($this->parent_type) || empty($this->parent_id)) {
            return;
        }

        // Unlink the previous parent.
        if (!empty($this->fetched_row) &&
            !empty($this->fetched_row['parent_id']) &&
            !empty($this->fetched_row['parent_type'])
        ) {
            if ($this->fetched_row['parent_id'] != $this->parent_id ||
                $this->fetched_row['parent_type'] != $this->parent_type
            ) {
                $parent = BeanFactory::retrieveBean(
                    $this->fetched_row['parent_type'],
                    $this->fetched_row['parent_id'],
                    [
                        'disable_row_level_security' => true,
                    ]
                );

                if ($parent) {
                    $linkName = $this->findEmailsLink($parent);

                    if ($parent->load_relationship($linkName)) {
                        $parent->$linkName->delete($parent->id, $this->id);
                    }
                }
            }
        }

        // Link the new parent.
        $parent = BeanFactory::retrieveBean(
            $this->parent_type,
            $this->parent_id,
            [
                'disable_row_level_security' => true,
            ]
        );

        if ($parent) {
            $linkName = $this->findEmailsLink($parent);

            if ($parent->load_relationship($linkName)) {
                $parent->$linkName->add($this->id);
            }
        }
    }

    /**
     * Updates the visibility on all attachments to match the visibility of the email.
     */
    protected function updateAttachmentsVisibility()
    {
        if ($this->load_relationship('attachments')) {
            $this->attachments->resetLoaded();
            $attachments = $this->attachments->get();

            $message = 'Updating the teams for %d attachment(s) for Emails/%s in %s.';
            $GLOBALS['log']->debug(sprintf($message, count($attachments), $this->id, __METHOD__));

            foreach ($attachments as $attachmentId) {
                $attachment = BeanFactory::retrieveBean(
                    'Notes',
                    $attachmentId,
                    array('disable_row_level_security' => true)
                );

                if ($attachment) {
                    $this->updateAttachmentVisibility($attachment);
                } else {
                    $message = 'Failed to load the attachment Notes/%s for Emails/%s in %s.';
                    $GLOBALS['log']->error(sprintf($message, $attachmentId, $this->id, __METHOD__));
                }
            }
        } else {
            $message = 'Failed to load the attachments for the email Emails/%s in %s.';
            $GLOBALS['log']->error(sprintf($message, $this->id, __METHOD__));
        }
    }

    /**
     * Updates an attachment's teams fields to match the teams on the email. An attachment is only visible to those who
     * have access to the email. When an email is a draft, the owner's private team is used for its attachments since
     * only the owner can access a draft.
     *
     * The attachment is also assigned to the same user to which the email is assigned.
     *
     * @param Note $attachment
     */
    public function updateAttachmentVisibility(Note $attachment)
    {
        if ($this->state === static::STATE_DRAFT) {
            $message = 'Setting the teams for attachment Notes/%s to the private team for Users/%s for the draft ' .
                'Emails/%s in %s.';
            $GLOBALS['log']->debug(sprintf($message, $attachment->id, $this->assigned_user_id, $this->id, __METHOD__));

            $user = BeanFactory::retrieveBean(
                'Users',
                $this->assigned_user_id,
                array('disable_row_level_security' => true)
            );
            $privateTeam = $user ? $user->getPrivateTeam() : null;

            if (!$privateTeam) {
                $message = 'Could not get the private team for Users/%s. The fields team_set_id, team_id, and ' .
                    'team_set_selected_id could not be assigned appropriately for attachment Notes/%s in %s.';
                $GLOBALS['log']->error(sprintf($message, $this->assigned_user_id, $attachment->id, __METHOD__));
                return;
            }

            $message = "Attachment Notes/%s's team_set_id, team_id, and team_set_selected_id are all %s.";
            $GLOBALS['log']->debug(sprintf($message, $attachment->id, $privateTeam));

            $attachment->team_set_id = $privateTeam;
            $attachment->team_id = $privateTeam;
            $attachment->team_set_selected_id = $privateTeam;
        } else {
            $message = "Attachment Notes/%s's team_set_id, team_id, and team_set_selected_id are %s, %s, and %s, " .
                'respectively.';
            $message = sprintf(
                $message,
                $attachment->id,
                $this->team_set_id,
                $this->team_id,
                $this->team_set_selected_id
            );
            $GLOBALS['log']->debug($message);

            $attachment->team_set_id = $this->team_set_id;
            $attachment->team_id = $this->team_id;
            $attachment->team_set_selected_id = $this->team_set_selected_id;
        }

        $message = "Assigning attachment Note/%s to User/%s in %s.";
        $GLOBALS['log']->debug(sprintf($message, $attachment->id, $this->assigned_user_id, __METHOD__));
        $attachment->assigned_user_id = $this->assigned_user_id;

        if (static::inOperation('saving_related')) {
            $message = 'In operation saving_related, so attachment Notes/%s is not saved in %s.';
            $GLOBALS['log']->debug(sprintf($message, $attachment->id, __METHOD__));
        } elseif (static::inOperation('updating_relationships')) {
            $message = 'In operation updating_relationships, so attachment Notes/%s is not saved in %s.';
            $GLOBALS['log']->debug(sprintf($message, $attachment->id, __METHOD__));
        } else {
            $message = 'Attachment Notes/%s is being saved in %s.';
            $GLOBALS['log']->debug(sprintf($message, $attachment->id, __METHOD__));

            $attachment->save();
        }
    }

	/**
	 * Helper function to save temporary attachments assocaited to an email as note.
	 *
     * @deprecated Use {@link Email::$attachments} to link attachments to the email.
	 * @param string $filename
	 * @param string $fileLocation
	 * @param string $mimeType
	 */
	function saveTempNoteAttachments($filename,$fileLocation, $mimeType, $uploadId = null)
	{
        LoggerManager::getLogger()->deprecated('Email::saveTempNoteAttachments() has been deprecated. Use ' .
            'Email::$attachments to link attachments to the email.');

	    $tmpNote = BeanFactory::newBean('Notes');
	    $tmpNote->id = create_guid();
	    $tmpNote->new_with_id = true;
	    $tmpNote->email_id = $this->id;
	    $tmpNote->email_type = $this->module_dir;
	    $tmpNote->name = $filename;
	    $tmpNote->filename = $filename;
	    $tmpNote->file_mime_type = $mimeType;
	    $tmpNote->team_id = $this->team_id;
	    $tmpNote->team_set_id = $this->team_set_id;
        if (!empty($uploadId)) {
            // do not duplicate actual file
            $uploadNote = BeanFactory::getBean('Notes', $uploadId);
            $tmpNote->upload_id = $uploadNote->getUploadId();
        }
        else {
            $noteFile = "upload://{$tmpNote->id}";
            if(!file_exists($fileLocation) || (!copy($fileLocation, $noteFile))) {
                $GLOBALS['log']->fatal("EMAIL 2.0: could not copy SugarDocument revision file $fileLocation => $noteFile");
            }
        }
	    $tmpNote->save();
        return $tmpNote;
	}
	/**
	 * Handles normalization of Email Addressess
     *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to link recipients to the email.
     * Use {@link Email::$from} to link the sender to the email.
	 */
	function saveEmailAddresses() {
        LoggerManager::getLogger()->deprecated('Email::saveEmailAddresses() has been deprecated. Use Email::$to, ' .
            'Email::$cc, and Email::$bcc to link recipients to the email. Use Email::$from to link the sender to ' .
            'the email.');

        $ea = BeanFactory::newBean('EmailAddresses');

		// from, single address
        // Only link the sender if the email is archived because the sender's email address for drafts is defined by the
        // outbound email configuration.
        if ($this->state === static::STATE_ARCHIVED) {
            $fromId = $ea->getEmailGUID(from_html($this->from_addr));

            if (!empty($fromId)) {
                $this->linkEmailToAddress($fromId, 'from');
            }
        }

		// to, multiple
		$replace = array(",",";");
		$toaddrs = str_replace($replace, "::", from_html($this->to_addrs));
		$exToAddrs = explode("::", $toaddrs);

		if(!empty($exToAddrs)) {
			foreach($exToAddrs as $toaddr) {
				$toaddr = trim($toaddr);
				if(!empty($toaddr)) {
                    $toId = $ea->getEmailGUID($toaddr);
					$this->linkEmailToAddress($toId, 'to');
				}
			}
		}

		// cc, multiple
		$ccAddrs = str_replace($replace, "::", from_html($this->cc_addrs));
		$exccAddrs = explode("::", $ccAddrs);

		if(!empty($exccAddrs)) {
			foreach($exccAddrs as $ccAddr) {
				$ccAddr = trim($ccAddr);
				if(!empty($ccAddr)) {
                    $ccId = $ea->getEmailGUID($ccAddr);
					$this->linkEmailToAddress($ccId, 'cc');
				}
			}
		}

		// bcc, multiple
		$bccAddrs = str_replace($replace, "::", from_html($this->bcc_addrs));
		$exbccAddrs = explode("::", $bccAddrs);
		if(!empty($exbccAddrs)) {
			foreach($exbccAddrs as $bccAddr) {
				$bccAddr = trim($bccAddr);
				if(!empty($bccAddr)) {
                    $bccId = $ea->getEmailGUID($bccAddr);
					$this->linkEmailToAddress($bccId, 'bcc');
				}
			}
		}
	}

    /**
     * Link an email address to the email.
     *
     * The email address might already be linked to the email, in which case the ID of the existing row is returned. An
     * empty string is returned if a failure occurs that cannot allow for the email address to be linked.
     *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to link recipients to the email.
     * Use {@link Email::$from} to link the sender to the email.
     * @param string $id The ID of the email address
     * @param string $type from, to, cc, or bcc
     * @return string The ID of the row added to the emails_email_addr_rel table.
     */
    public function linkEmailToAddress($id, $type)
    {
        $logger = LoggerManager::getLogger();
        $logger->deprecated('Email::linkEmailToAddress() has been deprecated. Use Email::$to, Email::$cc, and ' .
            'Email::$bcc to link recipients to the email. Use Email::$from to link the sender to the email.');

        if ($this->isArchived()) {
            $logger->warn("Cannot add EmailAddresses/{$id} to link {$type} when Emails/{$this->id} is archived");
            return '';
        }

        $emailAddress = BeanFactory::retrieveBean('EmailAddresses', $id);

        if (!$emailAddress) {
            $logger->error("Invalid ID for an EmailAddresses record: {$id}");
            return '';
        }

        if (!$this->load_relationship($type)) {
            $logger->error("Failed to load link {$type} for Emails record: {$this->id}");
            return '';
        }

        $ep = BeanFactory::newBean('EmailParticipants');
        $ep->new_with_id = true;
        $ep->id = Uuid::uuid1();
        BeanFactory::registerBean($ep);
        $ep->email_address_id = $id;
        $failures = $this->$type->add($ep);

        if ($failures === true) {
            $logger->debug("Linked EmailAddresses/{$id} to Emails/{$this->id} on link {$type}");

            return $ep->id;
        }

        $logger->error("Failed to link EmailAddresses/{$id} to Emails/{$this->id} on link {$type}");
        $logger->error('failures=' . var_export($failures, true));

        // The email address might already be linked to the email, in which case the ID of the existing row from the
        // emails_email_addr_rel table should be returned.
        $joinParams = array(
            'alias' => 'eear',
            'joinType' => 'LEFT',
            'linkingTable' => true,
        );
        $q = new SugarQuery();
        $q->from($this);
        $q->joinTable('emails_email_addr_rel', $joinParams)
            ->on()
            ->equalsField('id', 'eear.email_id', $this)
            ->equals('eear.email_id', $this->id)
            ->equals('eear.email_address_id', $id)
            ->equals('eear.address_type', $type)
            ->equals('eear.deleted', 0);
        // The calls to `from` and `joinTable` must happen before the call to `select`.
        $q->select(array(array('eear.id', 'eear_id')));
        $q->where()->equals('id', $this->id, $this);
        $guid = $q->getOne();

        return empty($guid) ? '' : $guid;
    }

    protected $email_to_text = array(
        "email_id" => "id",
        "description" => "description",
        "description_html" => "description_html",
        "raw_source" => "raw_source",
        "from_addr" => "from_addr_name",
        "reply_to_addr" => "reply_to_addr",
    	"to_addrs" => "to_addrs_names",
        "cc_addrs" => "cc_addrs_names",
        "bcc_addrs" => "bcc_addrs_names",
    );

    /**
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to link recipients to the email.
     * Use {@link Email::$from} to link the sender to the email.
     * @param string $emails
     * @return string
     */
	function cleanEmails($emails)
	{
        LoggerManager::getLogger()->deprecated('Email::cleanEmails() has been deprecated. Use Email::$to, ' .
            'Email::$cc, and Email::$bcc to link recipients to the email. Use Email::$from to link the sender to ' .
            'the email.');

        $ea = BeanFactory::newBean('EmailAddresses');

	    if(empty($emails)) return '';
		$emails = str_replace(array(",",";"), "::", from_html($emails));
		$addrs = explode("::", $emails);
		$res = array();
		foreach($addrs as $addr) {
            $parts = $ea->splitEmailAddress($addr);
            if(empty($parts["email"])) {
                continue;
            }
            if(!empty($parts["name"])) {
                $res[] = "{$parts['name']} <{$parts['email']}>";
            } else {
                $res[] .= $parts["email"];
            }
		}
		return join(", ", $res);
	}

    /**
     * Save data to the emails_text table.
     */
    public function saveEmailText()
    {
        $ea = BeanFactory::newBean('EmailAddresses');

        // Get the linked sender and recipients.
        $participants = array(
            'from' => array(),
            'to' => array(),
            'cc' => array(),
            'bcc' => array(),
        );

        foreach (array_keys($participants) as $linkName) {
            $beans = $this->getParticipants($linkName);

            foreach ($beans as $bean) {
                // Use the participant's primary email address if no email address has been chosen.
                if (empty($bean->email_address_id)) {
                    $parent = BeanFactory::retrieveBean(
                        $bean->parent_type,
                        $bean->parent_id,
                        ['disable_row_level_security' => true]
                    );

                    if ($parent) {
                        $bean->email_address = $ea->getPrimaryAddress($parent);
                    }
                }

                $participants[$linkName][] = $bean->getRecordName();
            }
        }

        // Populate the sender and recipient properties on the email so they can be mapped to $text.
        $this->{$this->email_to_text['from_addr']} = implode(', ', $participants['from']);
        $this->{$this->email_to_text['to_addrs']} = implode(', ', $participants['to']);
        $this->{$this->email_to_text['cc_addrs']} = implode(', ', $participants['cc']);
        $this->{$this->email_to_text['bcc_addrs']} = implode(', ', $participants['bcc']);

        $text = BeanFactory::newBean('EmailText');

        foreach ($this->email_to_text as $textField => $emailField) {
            $text->$textField = $this->$emailField;
        }

        // Verify that a row exists in the emails_text table for this email.
        $stmt = $this->db->getConnection()->executeQuery(
            'SELECT email_id FROM emails_text WHERE email_id = ?',
            array($this->id)
        );
        $guid = $stmt->fetchColumn();

        // Get and save the current Database Encoding setting and force it to be enabled
        $encodeVal = $GLOBALS['db']->getEncode();
        $GLOBALS['db']->setEncode(true);

        if ($guid) {
            $this->db->update($text);
        } else {
            $this->db->insert($text);
        }

        // Restore previous Database Encoding setting
        $GLOBALS['db']->setEncode($encodeVal);
    }

    ///////////////////////////////////////////////////////////////////////////
    ////	RETRIEVERS
    public function retrieve($id = '-1', $encoded = true, $deleted = true)
    {
        // cn: bug 11915, return SugarBean's retrieve() call bean instead of $this
        $bean = parent::retrieve($id, $encoded, $deleted);

        if ($bean) {
            $bean->loadAdditionalEmailData($bean);
        }

        return $bean;
    }

    /**
     * Load any additional data and perform any additional postRetrieve processing
     *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to retrieve the email's
     * recipients. Use {@link Email::$from} to retrieve the email's sender. Use {@link Email::retrieveEmailText()} to
     * retrieve the email's {@link Email::description} and {@link Email::description_html}.
     */
    function loadAdditionalEmailData(SugarBean $emailBean = null)
    {
        LoggerManager::getLogger()->deprecated('Email::loadAdditionalEmailData() has been deprecated. Use ' .
            "Email::\$to, Email::\$cc, and Email::\$bcc to retrieve the email's recipients. Use Email::\$from to " .
            "retrieve the email's sender. Use @link Email::retrieveEmailText() to retrieve the email's " .
            'Email::description and Email::description_html');

        if (is_null($emailBean)) {
            $bean = $this;
        } else {
            $bean = $emailBean;
        }
        $bean->retrieveEmailText();
        $bean->description = to_html($bean->description);
        $bean->retrieveEmailAddresses();
        $bean->synchronizeEmailParticipants();

        $bean->date_start = '';
        $bean->time_start = '';
        $dateSent = explode(' ', $bean->date_sent);
        if (!empty($dateSent)) {
            $bean->date_start = $dateSent[0];
            if (isset($dateSent[1])) {
                $bean->time_start = $dateSent[1];
            }
        }
        if ($bean !== $this) {
           foreach ($bean as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    /**
     * From 7.0.x through 7.9.x, REST API clients, like OPI/LPI, could create emails using Emails API by specifying the
     * sender and recipients with from_addr_name, to_addrs_names, cc_addrs_names, and bcc_addrs_names in the request
     * body. This led to the sender and recipients being stored in the emails_text table but not synchronized to the
     * emails_email_addr_rel table. This method will test the email to determine if the sender and recipients need to be
     * synchronized and then perform the synchronization.
     *
     * @internal This method is called when retrieving the email to perform a lazy upgrade of the email's data. It will
     * only need to synchronize the data once, on the first retrieval. Do not call or override this method.
     * @uses Email::saveEmailAddresses()
     * @deprecated This method will be removed once the sender and recipients for all emails have been synchronized.
     */
    public function synchronizeEmailParticipants()
    {
        if ($this->isSynchronizingEmailParticipants) {
            return;
        }

        BeanFactory::registerBean($this);
        $this->isSynchronizingEmailParticipants = true;

        // Find address types that aren't represented in emails_email_addr_rel.
        $sql = "SELECT address_type FROM emails_email_addr_rel WHERE email_id=? AND deleted=? GROUP BY address_type";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($sql, [$this->id, 0]);
        $missingAddressTypes = [
            'from' => true,
            'to' => true,
            'cc' => true,
            'bcc' => true,
        ];

        while ($row = $stmt->fetch()) {
            $missingAddressTypes[$row['address_type']] = false;
        }

        $fromNeedsUpgrade = !empty($this->from_addr_name) && !!$missingAddressTypes['from'];
        $toNeedsUpgrade = !empty($this->to_addrs_names) && !!$missingAddressTypes['to'];
        $ccNeedsUpgrade = !empty($this->cc_addrs_names) && !!$missingAddressTypes['cc'];
        $bccNeedsUpgrade = !empty($this->bcc_addrs_names) && !!$missingAddressTypes['bcc'];

        if ($fromNeedsUpgrade || $toNeedsUpgrade || $ccNeedsUpgrade || $bccNeedsUpgrade) {
            if ($fromNeedsUpgrade) {
                $this->from_addr = $this->from_addr_name;
            }

            if ($toNeedsUpgrade) {
                $this->to_addrs = $this->to_addrs_names;
            }

            if ($ccNeedsUpgrade) {
                $this->cc_addrs = $this->cc_addrs_names;
            }

            if ($bccNeedsUpgrade) {
                $this->bcc_addrs = $this->bcc_addrs_names;
            }

            $this->saveEmailAddresses();
        }

        $this->isSynchronizingEmailParticipants = false;
    }

    /**
     * Retrieves email addresses from GUIDs
     *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to retrieve the email's
     * recipients. Use {@link Email::$from} to retrieve the email's sender.
     */
    public function retrieveEmailAddresses()
    {
        LoggerManager::getLogger()->deprecated('Email::retrieveEmailAddresses() has been deprecated. Use ' .
            "Email::\$to, Email::\$cc, and Email::\$bcc to retrieve the email's recipients. Use Email::\$from to " .
            "retrieve the email's sender.");

        if (empty($this->id)) {
            $GLOBALS['log']->warn('Skipping Email::retrieveEmailAddresses because of missing bean.id value');
            return;
        }

        $query = "SELECT email_address, address_type FROM emails_email_addr_rel eam " .
            "JOIN email_addresses ea ON ea.id = eam.email_address_id " .
            "WHERE eam.email_id = ? AND eam.deleted = ?";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id, 0));

        $return = array();
        while ($row = $stmt->fetch()) {
            $return[$row['address_type']][] = $row['email_address'];
        }

        if (count($return) > 0) {
            if (isset($return['from'])) {
                $this->from_addr = implode(", ", $return['from']);
            }
            if (isset($return['to'])) {
                $this->to_addrs = implode(", ", $return['to']);
            }
            if (isset($return['cc'])) {
                $this->cc_addrs = implode(", ", $return['cc']);
            }
            if (isset($return['bcc'])) {
                $this->bcc_addrs = implode(", ", $return['bcc']);
            }
        }
    }

    /**
     * Handles longtext fields
     */
    public function retrieveEmailText()
    {
        if (empty($this->id)) {
            $GLOBALS['log']->warn('Skipping Email::retrieveEmailText because of missing bean.id value');
            return;
        }

        $query = "SELECT from_addr, reply_to_addr, to_addrs, cc_addrs, bcc_addrs, " .
            "description, description_html, raw_source " .
            " FROM emails_text WHERE email_id = ?";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id));
        $row = $stmt->fetch();
        if (!empty($row)) {
            $this->description = $row['description'];
            $this->description_html = $row['description_html'];
            $this->raw_source = $row['raw_source'];
            $this->from_addr_name = $row['from_addr'];
            $this->reply_to_addr = $row['reply_to_addr'];
            $this->to_addrs_names = $row['to_addrs'];
            $this->cc_addrs_names = $row['cc_addrs'];
            $this->bcc_addrs_names = $row['bcc_addrs'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function populateFromRow(array $row, $convert = false)
    {
        $row = parent::populateFromRow($row, $convert);

        $this->loadAdditionalEmailData();

        return $row;
    }

    /**
     * This marks an item as deleted.
     *
     * @param $id String id of the record to be marked as deleted.
     */
    public function mark_deleted($id)
    {
        global $dictionary;
        $this->db->updateParams(
            'emails_text',
            $dictionary['emails_text']['fields'],
            array('deleted' => 1),
            array('email_id' => $id)
        );

        $this->db->updateParams(
            'folders_rel',
            $dictionary['folders_rel']['fields'],
            array('deleted' => 1),
            array('polymorphic_id' => $id, 'polymorphic_module' => 'Emails')
        );

        parent::mark_deleted($id);
    }

    public function delete($id = '')
    {
        if (empty($id)) {
            $id = $this->id;
        }

        global $dictionary;
        $this->db->updateParams(
            'emails',
            $dictionary['Email']['fields'],
            array('deleted' => 1),
            array('id' => $id)
        );

        $this->db->updateParams(
            'emails_text',
            $dictionary['emails_text']['fields'],
            array('deleted' => 1),
            array('email_id' => $id)
        );

        $this->db->updateParams(
            'folders_rel',
            $dictionary['folders_rel']['fields'],
            array('deleted' => 1),
            array('polymorphic_id' => $id, 'polymorphic_module' => 'Emails')
        );
    }

	/**
	 * creates the standard "Forward" info at the top of the forwarded message
     *
     * @deprecated The BWC Emails UI is no longer being used.
	 * @return string
	 */
	function getForwardHeader() {
		global $mod_strings;
		global $current_user;

        LoggerManager::getLogger()->deprecated('Email::getForwardHeader() has been deprecated. The BWC Emails UI is ' .
            'no longer being used.');

		//$from = str_replace(array("&gt;","&lt;"), array(")","("), $this->from_name);
		$from = to_html($this->from_name);
		$subject = to_html($this->name);
		$ret  = "<br /><br />";
		$ret .= $this->replyDelimiter."{$mod_strings['LBL_FROM']} {$from}<br />";
		$ret .= $this->replyDelimiter."{$mod_strings['LBL_DATE_SENT']} {$this->date_sent}<br />";
		$ret .= $this->replyDelimiter."{$mod_strings['LBL_TO']} {$this->to_addrs}<br />";
		$ret .= $this->replyDelimiter."{$mod_strings['LBL_CC']} {$this->cc_addrs}<br />";
		$ret .= $this->replyDelimiter."{$mod_strings['LBL_SUBJECT']} {$subject}<br />";
		$ret .= $this->replyDelimiter."<br />";

		return $ret;
		//return from_html($ret);
	}

    /**
     * retrieves Notes that belong to this Email and stuffs them into the "attachments" attribute
     *
     * @deprecated {@link Email::$attachments} is now a link, while this method assumes that it is an array. Use
     * `$attachments = $bean->get_linked_beans('attachments', 'Note');` instead.
     */
    protected function getNotes($id, $duplicate = false)
    {
        LoggerManager::getLogger()->deprecated(
            "Email::getNotes() has been deprecated. Use Email::get_linked_beans('attachments', 'Note') instead."
        );

        $exRemoved = array();
        if (isset($_REQUEST['removeAttachment'])) {
            $exRemoved = explode('::', $_REQUEST['removeAttachment']);
        }

        $noteArray = array();
        $query = 'SELECT id FROM notes WHERE email_id = ?';
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($id));

        while ($noteId = $stmt->fetchColumn()) {
            if (!in_array($noteId, $exRemoved)) {
                $note = BeanFactory::getBean('Notes', $noteId);

                // duplicate actual file when creating forwards
                if ($duplicate) {
                    if (!class_exists('UploadFile')) {
                    }

                    $note->id = create_guid();

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($noteId, $note->id, $note->filename);

                    $note->save();
                }
                // add Note to attachments array
                $this->attachments[] = $note;
            }
        }
    }

    /**
     * creates the standard "Reply" info at the top of the forwarded message
     *
     * @deprecated The BWC Emails UI is no longer being used.
     * @return string
     */
    public function getReplyHeader()
    {
        global $mod_strings;

        LoggerManager::getLogger()->deprecated('Email::getReplyHeader() has been deprecated. The BWC Emails UI is no ' .
            'longer being used.');

		$from = str_replace(array("&gt;","&lt;", ">","<"), array(")","(",")","("), $this->from_name);
		$ret  = "<br>{$mod_strings['LBL_REPLY_HEADER_1']} {$this->date_start}, {$this->time_start}, {$from} {$mod_strings['LBL_REPLY_HEADER_2']}";

		return from_html($ret);
	}

	/**
	 * Quotes plain-text email text
     *
     * @deprecated This method is no longer used.
	 * @param string $text
	 * @return string
	 */
	function quotePlainTextEmail($text) {
        LoggerManager::getLogger()->deprecated('Email::quotePlainTextEmail() has been deprecated.');

		$quoted = "\n";

		// plain-text
		$desc = nl2br(trim($text));
		$exDesc = explode('<br />', $desc);

		foreach($exDesc as $k => $line) {
			$quoted .= '> '.trim($line)."\r";
		}

		return $quoted;
	}

	/**
	 * "quotes" (i.e., "> my text yadda" the HTML part of an email
     *
     * @deprecated The BWC Emails UI is no longer being used.
	 * @param string $text HTML text to quote
	 * @return string
	 */
	function quoteHtmlEmail($text) {
        LoggerManager::getLogger()->deprecated('Email::quoteHtmlEmail() has been deprecated. The BWC Emails UI is no ' .
            'longer being used.');

		$text = trim(from_html($text));

		if(empty($text)) {
			return '';
		}
        // <p></p> is not really needed here and it will make TinyMCE to inert <br> between them and
        // cause more display issues
        $text = preg_replace('/<p[^>]*><\/p>/i', '', $text);
		$out = "<div style='border-left:1px solid #00c; padding:5px; margin-left:10px;'>{$text}</div>";

		return $out;
	}

	/**
	 * "quotes" (i.e., "> my text yadda" the HTML part of an email
     *
     * @deprecated This method is no longer used.
	 * @param string $text HTML text to quote
	 * @return string
	 */
	function quoteHtmlEmailForNewEmailUI($text) {
        LoggerManager::getLogger()->deprecated('Email::quoteHtmlEmailForNewEmailUI() has been deprecated.');

		$text = trim($text);

		if(empty($text)) {
			return '';
		}
		$text = str_replace("\n", "<BR/>", $text);
		$out = "<div style='border-left:1px solid #00c; padding:5px; margin-left:10px;'>{$text}</div>";

		return $out;
	}

	/**
	 * Ensures that the user is able to send outbound emails
     *
     * @deprecated Use {@link OutboundEmailConfigurationPeer::getMailConfigurationStatusForUser()} instead. Compare the
     * return value to {@link OutboundEmailConfigurationPeer::STATUS_VALID_CONFIG} to determine if the use is able to
     * send emails.
	 */
	function check_email_settings() {
		global $current_user;

        LoggerManager::getLogger()->deprecated('Email::check_email_settings() has been deprecated. Use ' .
            'OutboundEmailConfigurationPeer::getMailConfigurationStatusForUser() instead. Compare the return value ' .
            'to OutboundEmailConfigurationPeer::STATUS_VALID_CONFIG to determine if the use is able to send emails.');

		$mail_fromaddress = $current_user->emailAddress->getPrimaryAddress($current_user);
		$replyToName = $current_user->getPreference('mail_fromname');
		$mail_fromname = (!empty($replyToName)) ? $current_user->getPreference('mail_fromname') : $current_user->full_name;

		if(empty($mail_fromaddress)) {
			return false;
		}
		if(empty($mail_fromname)) {
	  		return false;
		}

    	$send_type = $current_user->getPreference('mail_sendtype') ;
		if (!empty($send_type) && $send_type == "SMTP") {
			$mail_smtpserver = $current_user->getPreference('mail_smtpserver');
			$mail_smtpport = $current_user->getPreference('mail_smtpport');
			$mail_smtpauth_req = $current_user->getPreference('mail_smtpauth_req');
			$mail_smtpuser = $current_user->getPreference('mail_smtpuser');
			$mail_smtppass = $current_user->getPreference('mail_smtppass');
			if (empty($mail_smtpserver) ||
				empty($mail_smtpport) ||
                (!empty($mail_smtpauth_req) && ( empty($mail_smtpuser) || empty($mail_smtppass)))
			) {
				return false;
			}
		}
		return true;
	}

	/**
	 * outputs JS to set fields in the MassUpdate form in the "My Inbox" view
     *
     * @deprecated This method is no longer used.
	 */
	function js_set_archived() {
		global $mod_strings;

        LoggerManager::getLogger()->deprecated('Email::js_set_archived() has been deprecated.');

		$script = '
		<script type="text/javascript" language="JavaScript"><!-- Begin
			function setArchived() {
				var form = document.getElementById("MassUpdate");
				var status = document.getElementById("mass_status");
				var ok = false;

				for(var i=0; i < form.elements.length; i++) {
					if(form.elements[i].name == "mass[]") {
						if(form.elements[i].checked == true) {
							ok = true;
						}
					}
				}

				if(ok == true) {
					var user = document.getElementById("mass_assigned_user_name");
					var team = document.getElementById("team");

					user.value = "";
					for(var j=0; j<status.length; j++) {
						if(status.options[j].value == "archived") {
							status.options[j].selected = true;
							status.selectedIndex = j; // for IE
						}
					}

					form.submit();
				} else {
					alert("'.$mod_strings['ERR_ARCHIVE_EMAIL'].'");
				}

			}
		//  End --></script>';
		return $script;
	}

	/**
	 * replaces the javascript in utils.php - more specialized
     *
     * @deprecated The BWC Emails UI is no longer being used.
	 */
	function u_get_clear_form_js($type='', $group='', $assigned_user_id='') {
        LoggerManager::getLogger()->deprecated('Email::u_get_clear_form_js() has been deprecated. The BWC Emails UI ' .
            'is no longer being used.');

		$uType				= '';
		$uGroup				= '';
		$uAssigned_user_id	= '';

		if(!empty($type)) { $uType = '&type='.$type; }
		if(!empty($group)) { $uGroup = '&group='.$group; }
		if(!empty($assigned_user_id)) { $uAssigned_user_id = '&assigned_user_id='.$assigned_user_id; }

		$the_script = '
		<script type="text/javascript" language="JavaScript"><!-- Begin
			function clear_form(form) {
				var newLoc = "index.php?action=" + form.action.value + "&module=" + form.module.value + "&query=true&clear_query=true'.$uType.$uGroup.$uAssigned_user_id.'";
				if(typeof(form.advanced) != "undefined"){
					newLoc += "&advanced=" + form.advanced.value;
				}
				document.location.href= newLoc;
			}
		//  End --></script>';
		return $the_script;
	}

    /**
     * @deprecated This method is no longer used.
     * @return string
     */
	function pickOneButton() {
		global $theme;
		global $mod_strings;

        LoggerManager::getLogger()->deprecated('Email::pickOneButton() has been deprecated.');

		$out = '<div><input	title="'.$mod_strings['LBL_BUTTON_GRAB_TITLE'].'"
						class="button"
						type="button" name="button"
						onClick="window.location=\'index.php?module=Emails&action=Grab\';"
						style="margin-bottom:2px"
						value="  '.$mod_strings['LBL_BUTTON_GRAB'].'  "></div>';
		return $out;
	}

	/**
	 * Determines what Editor (HTML or Plain-text) the current_user uses;
     *
     * @deprecated The BWC Emails UI is no longer being used.
	 * @return string Editor type
	 */
	function getUserEditorPreference() {
		global $sugar_config;
		global $current_user;

        LoggerManager::getLogger()->deprecated('Email::getUserEditorPreference() has been deprecated. The BWC Emails ' .
            'UI is no longer being used.');

		$editor = '';

		if(!isset($sugar_config['email_default_editor'])) {
			$sugar_config = $current_user->setDefaultsInConfig();
		}

		$userEditor = $current_user->getPreference('email_editor_option');
		$systemEditor = $sugar_config['email_default_editor'];

		if($userEditor != '') {
			$editor = $userEditor;
		} else {
			$editor = $systemEditor;
		}

		return $editor;
	}

	/**
	 * takes the mess we pass from EditView and tries to create some kind of order
     *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to link recipients to the email.
     * Use {@link Email::$from} to link the sender to the email.
	 * @param array addrs
	 * @param array addrs_ids (from contacts)
	 * @param array addrs_names (from contacts);
	 * @param array addrs_emails (from contacts);
	 * @return array Parsed assoc array to feed to PHPMailer
	 */
	function parse_addrs($addrs, $addrs_ids, $addrs_names, $addrs_emails) {
        LoggerManager::getLogger()->deprecated('Email::parse_addrs() has been deprecated. Use Email::$to, ' .
            'Email::$cc, and Email::$bcc to link recipients to the email. Use Email::$from to link the sender to ' .
            'the email.');

		// cn: bug 9406 - enable commas to separate email addresses
		$addrs = str_replace(",", ";", $addrs);

		$ltgt = array('&lt;','&gt;');
		$gtlt = array('<','>');

		$return				= array();
		$addrs				= str_replace($ltgt, '', $addrs);
		$addrs_arr			= explode(";",$addrs);
		$addrs_arr			= $this->remove_empty_fields($addrs_arr);
		$addrs_ids_arr		= explode(";",$addrs_ids);
		$addrs_ids_arr		= $this->remove_empty_fields($addrs_ids_arr);
		$addrs_emails_arr	= explode(";",$addrs_emails);
		$addrs_emails_arr	= $this->remove_empty_fields($addrs_emails_arr);
		$addrs_names_arr	= explode(";",$addrs_names);
		$addrs_names_arr	= $this->remove_empty_fields($addrs_names_arr);

		///////////////////////////////////////////////////////////////////////
		////	HANDLE EMAILS HAND-WRITTEN
		$contactRecipients = array();
		$knownEmails = array();

		foreach($addrs_arr as $i => $v) {
			if(trim($v) == "")
				continue; // skip any "blanks" - will always have 1

			$recipient = array();

			//// get the email to see if we're dealing with a dupe
			//// what crappy coding
			preg_match("/[A-Z0-9._%-\']+@[A-Z0-9.-]+\.[A-Z]{2,}/i",$v, $match);


			if(!empty($match[0]) && !in_array(trim($match[0]), $knownEmails)) {
				$knownEmails[] = $match[0];
				$recipient['email'] = $match[0];

				//// handle the Display name
				$display = trim(str_replace($match[0], '', $v));

				//// only trigger a "displayName" <email@address> when necessary
				if(isset($addrs_names_arr[$i])){
						$recipient['display'] = $addrs_names_arr[$i];
				}
				else if(!empty($display)) {
					$recipient['display'] = $display;
				}
				if(isset($addrs_ids_arr[$i]) && $addrs_emails_arr[$i] == $match[0]){
					$recipient['contact_id'] = $addrs_ids_arr[$i];
				}
				$return[] = $recipient;
			}
		}

		return $return;
	}

    /**
     * @deprecated This method is no longer used.
     * @param array $arr
     * @return array
     */
	function remove_empty_fields(&$arr) {
        LoggerManager::getLogger()->deprecated('Email::remove_empty_fields() has been deprecated.');

		$newarr = array();

		foreach($arr as $field) {
			$field = trim($field);
			if(empty($field)) {
				continue;
			}
			array_push($newarr,$field);
		}
		return $newarr;
	}

    /**
     * Used to find a usable Emails relationship link.
     *
     * Emails has special relationships that should be used to link an email to another bean when the other bean is the
     * email's parent. Typically, these relationships add the relationship to the emails_beans join table.
     *
     * If the related module has a link named "emails", then this link will be returned. Core modules with these special
     * relationships to Emails have links named "emails". Examples are Accounts, Bugs, Cases, and Contacts.
     *
     * The <module>_activities_1_emails link is created when an admin adds the Activities relationship to a module in
     * Studio. <module> is the lower case form of the module to which the Activities relationship was added. This
     * activities link is another link that should be handled specially when the email's parent is a record from the
     * module on the other side.
     *
     * The <module>_activities_emails link is used when the Activities relationship is created using Module Builder,
     * before the module is deployed. Prior to 6.3, it was also possible for this activities link name to be generated
     * by Studio. This should have been corrected during an upgrade from a version less than 6.5.7. However, in case
     * this link name is still used, <module>_activities_emails will be returned if no match was found for the link
     * names mentioned above.
     *
     * The name of the first link to Emails that is found is returned if none of the above link names were identified.
     *
     * @see SI Bug 22504
     * @see SI Bug 49024
     * @param SugarBean $bean A bean from the related module.
     * @return bool|string Name of an Emails relationship link or false.
     */
    public function findEmailsLink(SugarBean $bean)
    {
        $moduleName = BeanFactory::getModuleName($bean);
        $moduleNameLower = strtolower($moduleName);

        // Get the names of all links that $bean has pointing to Emails.
        $linkFields = VardefManager::getLinkFieldsForModule($moduleName, BeanFactory::getObjectName($moduleName));

        // `false` is returned for the Empty module.
        if (!is_array($linkFields)) {
            $linkFields = [];
        }

        $linksToEmails = array_filter($linkFields, function ($def) use ($bean) {
            if (!empty($def['module'])) {
                return $def['module'] === 'Emails';
            }

            // The module isn't defined on the link, so use the relationship to determine if the module on the other
            // side is Emails.
            if ($bean->load_relationship($def['name'])) {
                return $bean->{$def['name']}->getRelatedModuleName() === 'Emails';
            }

            return false;
        });
        $linksToEmails = array_keys($linksToEmails);

        $studioGeneratedLinkName = "{$moduleNameLower}_activities_1_emails";
        $moduleBuilderGeneratedLinkName = "{$moduleNameLower}_activities_emails";

        // Does the bean's module have a link field named "emails"?
        if (in_array('emails', $linksToEmails)) {
            return 'emails';
        }

        // Does the bean's module have a link field generated by Studio for the Activities relationship?
        if (in_array($studioGeneratedLinkName, $linksToEmails)) {
            return $studioGeneratedLinkName;
        }

        // Does the bean's module have a link field generated by Module Builder for the Activities relationship?
        if (in_array($moduleBuilderGeneratedLinkName, $linksToEmails)) {
            return $moduleBuilderGeneratedLinkName;
        }

        // Use the first link that was found.
        if (count($linksToEmails) > 0) {
            return array_shift($linksToEmails);
        }

        return false;
    }

	/**
	 * handles attachments of various kinds when sending email
     *
     * @deprecated Use {@link Email::$attachments} to link attachments to the email.
	 */
	function handleAttachments() {

		global $mod_strings;

        LoggerManager::getLogger()->deprecated('Email::handleAttachments() has been deprecated. Use ' .
            'Email::$attachments to link attachments to the email.');

        $attachmentsToCopy = array();
        $attachmentBean = BeanFactory::getBean('Notes');

        ///////////////////////////////////////////////////////////////////////////
        ////    ATTACHMENTS FROM DRAFTS
        if(($this->type == 'out' || $this->type == 'draft') && $this->status == 'draft' && isset($_REQUEST['record'])) {
            // cn: get notes from OLD email for use in new email
            //FIXME: notes.email_type should be Emails
            $attachmentsToCopy = array_merge(
                $attachmentsToCopy,
                $attachmentBean->get_full_list('', 'notes.email_id=' . $this->db->quoted($_REQUEST['record']), true)
            );
        }
        ////    END ATTACHMENTS FROM DRAFTS
        ///////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////
        ////    ATTACHMENTS FROM FORWARDS
        // Bug 8034 Jenny - Need the check for type 'draft' here to handle cases where we want to save
        // forwarded messages as drafts.  We still need to save the original message's attachments.
        if(($this->type == 'out' || $this->type == 'draft') &&
        	isset($_REQUEST['origType']) && $_REQUEST['origType'] == 'forward' &&
        	isset($_REQUEST['return_id']) && !empty($_REQUEST['return_id'])
        ) {
            //FIXME: notes.email_type should be Emails
            $where = 'notes.email_id=' . $this->db->quoted($_REQUEST['return_id']);
            $attachmentsFromForwards = $attachmentBean->get_full_list('', $where, true);

            // Duplicate the attachments.
            foreach ($attachmentsFromForwards as $attachment) {
                $note = BeanFactory::getBean($attachment->getModuleName(), $attachment->id);
                $note->id = Uuid::uuid1();
                $note->new_with_id = true;

                // Duplicate the file before saving so that the file size is captured during save.
                // Note: This may not help since $note->email_id is not being set, as noted below.
                UploadFile::duplicate_file($attachment->id, $note->id, $note->filename);

                // By not setting $note->email_id = $this->id, it may be possible for some attachments to have the wrong
                // parent after the request is done. This has been left as is to maintain the state of legacy code,
                // given that there are no known bugs regarding this behavior.
                // The same is true of $note->email_type, although it is guaranteed that the framework cannot find the
                // related email without an email_type.
                $note->save();

                $attachmentsToCopy[] = $note;
            }
        }

        // cn: bug 8034 - attachments from forward/replies lost when saving in draft
        if(isset($_REQUEST['prior_attachments']) && !empty($_REQUEST['prior_attachments']) && $this->new_with_id == true) {
        	$exIds = explode(",", $_REQUEST['prior_attachments']);
        	if(!isset($_REQUEST['template_attachment'])) {
        		$_REQUEST['template_attachment'] = array();
        	}
        	$_REQUEST['template_attachment'] = array_merge($_REQUEST['template_attachment'], $exIds);
        }
        ////    END ATTACHMENTS FROM FORWARDS
        ///////////////////////////////////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////
		////	ATTACHMENTS FROM TEMPLATES
		// to preserve individual email integrity, we must dupe Notes and associated files
		// for each outbound email - good for integrity, bad for filespace
		if(isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])) {
			$removeArr = array();

			if(isset($_REQUEST['temp_remove_attachment']) && !empty($_REQUEST['temp_remove_attachment'])) {
				$removeArr = $_REQUEST['temp_remove_attachment'];
			}


			foreach($_REQUEST['template_attachment'] as $noteId) {
				if(in_array($noteId, $removeArr)) {
					continue;
				}
				$noteTemplate = BeanFactory::getBean('Notes', $noteId);
				$noteTemplate->id = create_guid();
				$noteTemplate->new_with_id = true; // duplicating the note with files
				$noteTemplate->email_id = $this->id;
				$noteTemplate->email_type = $this->module_dir;
				$noteTemplate->date_entered = '';
				$noteTemplate->team_id = $this->team_id;

                // Duplicate the file before saving so that the file size is captured during save.
                UploadFile::duplicate_file($noteId, $noteTemplate->id, $noteTemplate->filename);
                $noteTemplate->save();
                $attachmentsToCopy[] = $noteTemplate;
			}
		}
		////	END ATTACHMENTS FROM TEMPLATES
		///////////////////////////////////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////
		////	ADDING NEW ATTACHMENTS
		$max_files_upload = 10;
        // Jenny - Bug 8211 Since attachments for drafts have already been processed,
        // we don't need to re-process them.
        if($this->status != "draft") {
    		if(!empty($this->id) && !$this->new_with_id) {
                //FIXME: notes.email_type should be Emails
                $attachmentsToCopy = array_merge(
                    $attachmentsToCopy,
                    $attachmentBean->get_full_list('', "notes.email_id='{$this->id}'", true)
                );
    		}
        }
		// cn: Bug 5995 - rudimentary error checking
		$filesError = array(
			0 => 'UPLOAD_ERR_OK - There is no error, the file uploaded with success.',
			1 => 'UPLOAD_ERR_INI_SIZE - The uploaded file exceeds the upload_max_filesize directive in php.ini.',
			2 => 'UPLOAD_ERR_FORM_SIZE - The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
			3 => 'UPLOAD_ERR_PARTIAL - The uploaded file was only partially uploaded.',
			4 => 'UPLOAD_ERR_NO_FILE - No file was uploaded.',
			5 => 'UNKNOWN ERROR',
			6 => 'UPLOAD_ERR_NO_TMP_DIR - Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.',
			7 => 'UPLOAD_ERR_CANT_WRITE - Failed to write file to disk. Introduced in PHP 5.1.0.',
		);

		for($i = 0; $i < $max_files_upload; $i++) {
			// cn: Bug 5995 - rudimentary error checking
			if (!isset($_FILES["email_attachment{$i}"])) {
				$GLOBALS['log']->debug("Email Attachment {$i} does not exist.");
				continue;
			}
			if($_FILES['email_attachment'.$i]['error'] != 0 && $_FILES['email_attachment'.$i]['error'] != 4) {
				$GLOBALS['log']->debug('Email Attachment could not be attach due to error: '.$filesError[$_FILES['email_attachment'.$i]['error']]);
				continue;
			}

			$note = BeanFactory::newBean('Notes');
			$note->email_id = $this->id;
			$note->email_type = $this->module_dir;
			$upload_file = new UploadFile('email_attachment'.$i);

			if(empty($upload_file)) {
				continue;
			}

			if(isset($_FILES['email_attachment'.$i]) && $upload_file->confirm_upload()) {
				$note->filename = $upload_file->get_stored_file_name();
				$note->file = $upload_file;
				$note->name = $mod_strings['LBL_EMAIL_ATTACHMENT'].': '.$note->file->original_file_name;
				$note->team_id = $this->team_id;
                $attachmentsToCopy[] = $note;
			}
		}

		$this->saved_attachments = array();
		foreach($attachmentsToCopy as $note) {
			if(!empty($note->id)) {
				array_push($this->saved_attachments, $note);
				continue;
			}
			$note->email_id = $this->id;
			$note->email_type = 'Emails';
			$note->file_mime_type = $note->file->mime_type;
            $note->save();
            $note->file->final_move($note->id);

			$this->saved_attachments[] = $note;
		}
		////	END NEW ATTACHMENTS
		///////////////////////////////////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////
		////	ATTACHMENTS FROM DOCUMENTS
		for($i=0; $i<10; $i++) {
			if(isset($_REQUEST['documentId'.$i]) && !empty($_REQUEST['documentId'.$i])) {
				$doc = BeanFactory::newBean('Documents');
				$docRev = BeanFactory::newBean('DocumentRevisions');
				$docNote = BeanFactory::newBean('Notes');
                $docNote->id = Uuid::uuid1();
                $docNote->new_with_id = true;

				$doc->retrieve($_REQUEST['documentId'.$i]);
				$docRev->retrieve($doc->document_revision_id);

				$this->saved_attachments[] = $docRev;

				// cn: bug 9723 - Emails with documents send GUID instead of Doc name
				$docNote->name = $docRev->getDocumentRevisionNameForDisplay();
				$docNote->filename = $docRev->filename;
				$docNote->description = $doc->description;
				$docNote->email_id = $this->id;
				$docNote->email_type = 'Emails';
				$docNote->file_mime_type = $docRev->file_mime_type;

                // Duplicate the file before saving so that the file size is captured during save.
                UploadFile::duplicate_file($docRev->id, $docNote->id, $docRev->filename);
                $docNote->save();
			}
		}

		////	END ATTACHMENTS FROM DOCUMENTS
		///////////////////////////////////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////
		////	REMOVE ATTACHMENTS
        global $dictionary;

        if(isset($_REQUEST['remove_attachment']) && !empty($_REQUEST['remove_attachment'])) {
            foreach($_REQUEST['remove_attachment'] as $noteId) {
                $this->db->updateParams(
                    'notes',
                    $dictionary['Note']['fields'],
                    array('deleted' => 1),
                    array('id' => $noteId)
                );
            }
        }

        //this will remove attachments that have been selected to be removed from drafts.
        if(isset($_REQUEST['removeAttachment']) && !empty($_REQUEST['removeAttachment'])) {
            $exRemoved = explode('::', $_REQUEST['removeAttachment']);
            foreach($exRemoved as $noteId) {
                $this->db->updateParams(
                    'notes',
                    $dictionary['Note']['fields'],
                    array('deleted' => 1),
                    array('id' => $noteId)
                );
            }
        }
		////	END REMOVE ATTACHMENTS
		///////////////////////////////////////////////////////////////////////////
	}


	/**
	 * Determines if an email body (HTML or Plain) has a User signature already in the content
     *
     * @deprecated The BWC Emails UI is no longer being used.
	 * @param array Array of signatures
	 * @return bool
	 */
	function hasSignatureInBody($sig) {
        LoggerManager::getLogger()->deprecated('Email::hasSignatureInBody() has been deprecated. The BWC Emails UI ' .
            'is no longer being used.');

		// strpos can't handle line breaks - normalize
		$html = $this->removeAllNewlines($this->description_html);
		$htmlSig = $this->removeAllNewlines($sig['signature_html']);
		$plain = $this->removeAllNewlines($this->description);
		$plainSig = $this->removeAllNewlines($sig['signature']);

		// cn: bug 11621 - empty sig triggers notice error
		if(!empty($htmlSig) && false !== strpos($html, $htmlSig)) {
			return true;
		} elseif(!empty($plainSig) && false !== strpos($plain, $plainSig)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * internal helper
     *
     * @deprecated The BWC Emails UI is no longer being used.
	 * @param string String to be normalized
	 * @return string
	 */
	function removeAllNewlines($str) {
        LoggerManager::getLogger()->deprecated('Email::removeAllNewlines() has been deprecated. The BWC Emails UI is ' .
            'no longer being used.');

		$bad = array("\r\n", "\n\r", "\n", "\r");
		$good = array('', '', '', '');

		return str_replace($bad, $good, strip_tags(br2nl(from_html($str))));
	}



	/**
	 * Set navigation anchors to aid DetailView record navigation (VCR buttons)
     *
     * @deprecated The BWC Emails UI is no longer being used.
	 * @param string uri The URI from the referring page (always ListView)
	 * @return array start Array of the URI broken down with a special "current_view" for My Inbox Navs
	 */
	function getStartPage($uri) {
        LoggerManager::getLogger()->deprecated('Email::getStartPage() has been deprecated. The BWC Emails UI is no ' .
            'longer being used.');

		if(strpos($uri, '&')) { // "&" to ensure that we can explode the GET vars - else we're gonna trigger a Notice error
			$serial = substr($uri, (strpos($uri, '?')+1), strlen($uri));
			$exUri = explode('&', $serial);
			$start = array('module' => '', 'action' => '', 'group' => '', 'record' => '', 'type' => '');

			foreach($exUri as $k => $pair) {
				$exPair = explode('=', $pair);
				$start[$exPair[0]] = $exPair[1];
			}

			// specific views for current_user
			if(isset($start['assigned_user_id'])) {
				$start['current_view'] = "{$start['action']}&module={$start['module']}&assigned_user_id={$start['assigned_user_id']}&type={$start['type']}";
			}

			return $start;
		} else {
			return array();
		}
	}


    /**
     * Sends Email
     *
     * @deprecated Use {@link Email::sendEmail()} instead.
     * @return bool True on success
     */
    function send() {
        global $mod_strings,
               $app_strings,
               $current_user,
               $sugar_config;

        LoggerManager::getLogger()->deprecated('Email::send() has been deprecated. Use Email::sendEmail() instead.');

        try {
            $mailConfig = OutboundEmailConfigurationPeer::getSystemMailConfiguration($current_user);
            $mailerFactoryClass = $this->MockMailerFactoryClass;
            $mailer = $mailerFactoryClass::getMailer($mailConfig);

            if (is_array($this->to_addrs_arr)) {
                foreach ($this->to_addrs_arr as $addr_arr) {
                    try {
                        $mailer->addRecipientsTo(new EmailIdentity($addr_arr['email'], $addr_arr['display']));
                    } catch (MailerException $me) {
                        // eat the exception
                    }
                }
            }
            if (is_array($this->cc_addrs_arr)) {
                foreach ($this->cc_addrs_arr as $addr_arr) {
                    try {
                        $mailer->addRecipientsCc(new EmailIdentity($addr_arr['email'], $addr_arr['display']));
                    } catch (MailerException $me) {
                        // eat the exception
                    }
                }
            }
            if (is_array($this->bcc_addrs_arr)) {
                foreach ($this->bcc_addrs_arr as $addr_arr) {
                    try {
                        $mailer->addRecipientsBcc(new EmailIdentity($addr_arr['email'], $addr_arr['display']));
                    } catch (MailerException $me) {
                        // eat the exception
                    }
                }
            }

            // SENDER Info
            if (empty($this->from_addr)) {
                $this->from_addr = $current_user->getPreference('mail_fromaddress');
            }

            if (empty($this->from_name)) {
                $this->from_name = $current_user->getPreference('mail_fromname');
            }

            // REPLY-TO Info
            if (empty($this->reply_to_addr)) {
                $this->reply_to_addr = $this->from_addr;
                $this->reply_to_name = $this->from_name;
            }

            $mailer->setHeader(EmailHeaders::From, new EmailIdentity($this->from_addr, $this->from_name));
            $mailer->setHeader(EmailHeaders::Sender, new EmailIdentity($this->from_addr, $this->from_name));
            $mailer->setHeader(EmailHeaders::ReplyTo, new EmailIdentity($this->reply_to_addr, $this->reply_to_name));
            $mailer->setSubject($this->name);

            ///////////////////////////////////////////////////////////////////////
            ////	ATTACHMENTS
            if (is_array($this->saved_attachments)) {
                foreach ($this->saved_attachments as $note) {
                    $mime_type = 'text/plain';
                    if ($note->object_name == 'Note') {
                        if (!empty($note->file->temp_file_location) && is_file($note->file->temp_file_location)) { // brandy-new file upload/attachment
                            $file_location = "upload://$note->id";
                            $filename = $note->file->original_file_name;
                            $mime_type = $note->file->mime_type;
                        } else { // attachment coming from template/forward
                            $file_location = "upload://{$note->id}";
                            // cn: bug 9723 - documents from EmailTemplates sent with Doc Name, not file name.
                            $filename = !empty($note->filename) ? $note->filename : $note->name;
                            $mime_type = $note->file_mime_type;
                        }
                    } elseif ($note->object_name == 'DocumentRevision') { // from Documents
                        $filePathName = $note->id;
                        // cn: bug 9723 - Emails with documents send GUID instead of Doc name
                        $filename = $note->getDocumentRevisionNameForDisplay();
                        $file_location = "upload://$note->id";
                        $mime_type = $note->file_mime_type;
                    }

                    // strip out the "Email attachment label if exists
                    $filename = str_replace($mod_strings['LBL_EMAIL_ATTACHMENT'].': ', '', $filename);
                    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
                    //is attachment in our list of bad files extensions?  If so, append .txt to file location
                    //check to see if this is a file with extension located in "badext"
                    foreach ($sugar_config['upload_badext'] as $badExt) {
                        if (strtolower($file_ext) == strtolower($badExt)) {
                            //if found, then append with .txt to filename and break out of lookup
                            //this will make sure that the file goes out with right extension, but is stored
                            //as a text in db.
                            $file_location = $file_location . ".txt";
                            break; // no need to look for more
                        }
                    }

                    $attachment = null;

                    if ($note->embed_flag == true) {
                        $cid = $filename;
                        $attachment = AttachmentPeer::embeddedImageFromSugarBean($note, $cid);
                    } else {
                        $attachment = AttachmentPeer::attachmentFromSugarBean($note);
                    }

                    $mailer->addAttachment($attachment);
                }
            }
            ////	END ATTACHMENTS
            ///////////////////////////////////////////////////////////////////////

            if (isset($_REQUEST['description_html'])) {
                $this->description_html = $_REQUEST['description_html'];
            }

            $htmlBody = $this->description_html;
            $textBody = $this->description;

            //------------------- HANDLEBODY() ---------------------------------------------
            if ((isset($_REQUEST['setEditor']) /* from Email EditView navigation */
                 && $_REQUEST['setEditor'] == 1
                 && trim($this->description_html) != '')
                && $current_user->getPreference('email_editor_option', 'global') !== 'plain' //user preference is not set to plain text
            ) {
                $htmlBody = $this->decodeDuringSend($htmlBody);
                $textBody = $this->decodeDuringSend($textBody);
            } else {
                $textBody = str_replace("&nbsp;", " ", $textBody);
                $textBody = str_replace("</p>", "</p><br />", $textBody);
                $textBody = strip_tags(br2nl($textBody));
                $textBody = str_replace("&amp;", "&", $textBody);
                $textBody = str_replace("&#39;", "'", $textBody);
                $textBody = $this->decodeDuringSend($textBody);
            }

            $mailer->setHtmlBody($htmlBody);
            $mailer->setTextBody($textBody);

            // Use this email's ID in the Message-ID if it exists. Otherwise, let it be auto-generated.
            if (!empty($this->id)) {
                $mailer->setMessageId($this->id);
            }

            $mailer->send();
            $this->message_id = $mailer->getHeader(EmailHeaders::MessageId);

            ///////////////////////////////////////////////////////////////////
            ////	INBOUND EMAIL HANDLING
            // mark replied
            if(!empty($_REQUEST['inbound_email_id'])) {
                $ieMail = new Email();
                $ieMail->retrieve($_REQUEST['inbound_email_id']);
                $ieMail->status = 'replied';
                $ieMail->state = static::STATE_ARCHIVED;
                $ieMail->save();
            }

            return true;
        }
        catch (MailerException $me) {
            $GLOBALS["log"]->error($me->getLogMessage());
        }
        catch (Exception $e) {
            $GLOBALS['log']->error($app_strings['LBL_EMAIL_ERROR_PREPEND'] . $e->getMessage());
        }

        return false;
    }


    function listviewACLHelper(){
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		if(!empty($this->parent_name)){

			if(!empty($this->parent_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->parent_name_owner;
			}
		}
		if(!ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner)){
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

    /**
     * @deprecated Use {OutboundEmail::getSystemMailerSettings()} instead.
     * @return array
     */
    public function getSystemDefaultEmail()
    {
        LoggerManager::getLogger()->deprecated('Email::getSystemDefaultEmail() has been deprecated. Use ' .
            'OutboundEmail::getSystemMailerSettings() instead.');

        $email = array();

        $query = 'SELECT config.value FROM config WHERE name = ?';
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array('fromaddress'));
        $fromAddress = $stmt->fetchColumn();
        $email['email'] = !empty($fromAddress) ? $fromAddress : '';

        $stmt = $conn->executeQuery($query, array('fromname'));
        $fromName = $stmt->fetchColumn();
        $email['name'] = !empty($fromName) ? $fromName : '';

        return $email;
    }

    /**
     * {@inheritdoc}
     * @deprecated Use SugarQuery & $this->fetchFromQuery() instead.
     */
    public function create_new_list_query(
        $order_by,
        $where,
        $filter = array(),
        $params = array(),
        $show_deleted = 0,
        $join_type = '',
        $return_array = false,
        $parentbean = null,
        $singleSelect = false,
        $ifListForExport = false
    ) {

        LoggerManager::getLogger()->deprecated('Email::create_new_list_query() has been deprecated. Use SugarQuery ' .
            'and Email::fetchFromQuery() instead.');

		if ($return_array) {
            return parent::create_new_list_query(
                $order_by,
                $where,
                $filter,
                $params,
                $show_deleted,
                $join_type,
                $return_array,
                $parentbean,
                $singleSelect,
                $ifListForExport
            );
		}
        $custom_join = $this->getCustomJoin();

		$query = "SELECT ".$this->table_name.".*, users.user_name as assigned_user_name\n";

        $query .= $custom_join['select'];
    	$query .= " FROM emails\n";
    	if ($where != "" && (strpos($where, "contacts.first_name") > 0))  {
			$query .= " LEFT JOIN emails_beans ON emails.id = emails_beans.email_id\n";
    	}
		// We need to confirm that the user is a member of the team of the item.
		$this->addVisibilityFrom($query, array('where_condition' => true));
    	$query .= " LEFT JOIN teams ON emails.team_id=teams.id";

    	$query .= " LEFT JOIN users ON emails.assigned_user_id=users.id \n";
    	if ($where != "" && (strpos($where, "contacts.first_name") > 0))  {

        $query .= " JOIN contacts ON contacts.id= emails_beans.bean_id AND emails_beans.bean_module='Contacts' and contacts.deleted=0 \n";
    	}

        $query .= $custom_join['join'];

		if($show_deleted == 0) {
			$where_auto = " emails.deleted=0 \n";
		}else if($show_deleted == 1){
			$where_auto = " emails.deleted=1 \n";
		}

		$this->addVisibilityWhere($where_auto, array('where_condition' => true));

        if($where != "")
			$query .= "WHERE $where AND ".$where_auto;
		else
			$query .= "WHERE ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY date_sent DESC";

		return $query;
    } // fn

    /**
     * {@inheritdoc}
     * @deprecated Not used in the REST API.
     */
    public function fill_in_additional_list_fields()
    {
        global $timedate, $mod_strings;

        LoggerManager::getLogger()->deprecated('Email::fill_in_additional_list_fields() has been deprecated. It is ' .
            'not used in the REST API.');

        $this->fill_in_additional_detail_fields();

        $this->link_action = 'DetailView';
        ///////////////////////////////////////////////////////////////////////
        //populate attachment_image, used to display attachment icon.
        $query =  "select 1 from notes where notes.email_id = ? and notes.deleted = ?";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id, 0));

        $row = $stmt->fetchColumn();
        $this->attachment_image = ($row !=null) ? SugarThemeRegistry::current()->getImage('attachment',"","","") : "";

		if ($row !=null) {
			$this->attachment_image = SugarThemeRegistry::current()->getImage('attachment',"","","",'.gif',translate('LBL_ATTACHMENT', 'Emails'));
		}

		///////////////////////////////////////////////////////////////////////
		if(empty($this->contact_id) && !empty($this->parent_id) && !empty($this->parent_type) && $this->parent_type === 'Contacts' && !empty($this->parent_name) ){
			$this->contact_id = $this->parent_id;
			$this->contact_name = $this->parent_name;
		}
	}

    /**
     * {@inheritdoc}
     * @deprecated Not used in the REST API.
     */
	function fill_in_additional_detail_fields()
	{
		global $app_list_strings,$mod_strings;

        LoggerManager::getLogger()->deprecated('Email::fill_in_additional_detail_fields() has been deprecated. It is ' .
            'not used in the REST API.');

        $mod_strings = return_module_language($GLOBALS['current_language'], 'Emails');

		$query  = "SELECT contacts.first_name, contacts.last_name, contacts.phone_work, contacts.id, contacts.assigned_user_id contact_name_owner, 'Contacts' contact_name_mod FROM contacts, emails_beans
		           WHERE emails_beans.email_id='$this->id' AND emails_beans.bean_id=contacts.id AND emails_beans.bean_module = 'Contacts' AND emails_beans.deleted=0 AND contacts.deleted=0";

			if(!empty($this->parent_id)){
				$query .= " AND contacts.id= '".$this->parent_id."' ";
			}else if(!empty($_REQUEST['record'])){
				$query .= " AND contacts.id= '".$_REQUEST['record']."' ";
			}
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row != null)
			{

				$contact = BeanFactory::getBean('Contacts', $row['id']);
				$this->contact_name = $contact->full_name;
				$this->contact_phone = $row['phone_work'];
				$this->contact_id = $row['id'];
				$this->contact_email = $contact->emailAddress->getPrimaryAddress($contact);
				$this->contact_name_owner = $row['contact_name_owner'];
				$this->contact_name_mod = $row['contact_name_mod'];
				$GLOBALS['log']->debug("Call($this->id): contact_name = $this->contact_name");
				$GLOBALS['log']->debug("Call($this->id): contact_phone = $this->contact_phone");
				$GLOBALS['log']->debug("Call($this->id): contact_id = $this->contact_id");
				$GLOBALS['log']->debug("Call($this->id): contact_email1 = $this->contact_email");
			}
			else {
				$this->contact_name = '';
				$this->contact_phone = '';
				$this->contact_id = '';
				$this->contact_email = '';
				$this->contact_name_owner = '';
				$this->contact_name_mod = '';
				$GLOBALS['log']->debug("Call($this->id): contact_name = $this->contact_name");
				$GLOBALS['log']->debug("Call($this->id): contact_phone = $this->contact_phone");
				$GLOBALS['log']->debug("Call($this->id): contact_id = $this->contact_id");
				$GLOBALS['log']->debug("Call($this->id): contact_email1 = $this->contact_email");
			}
		//}


		$this->link_action = 'DetailView';

		if(!empty($this->type)) {
			if($this->type == 'out' && $this->status == 'send_error') {
				$this->type_name = $mod_strings['LBL_NOT_SENT'];
			} else {
                if (isset($app_list_strings['dom_email_types'][$this->type])) {
                    $this->type_name = $app_list_strings['dom_email_types'][$this->type];
                } else {
                    $this->type_name = $this->type;
                }
			}

			if(($this->type == 'out' && $this->status == 'send_error') || $this->type == 'draft') {
				$this->link_action = 'EditView';
			}
		}

		//todo this  isset( $app_list_strings['dom_email_status'][$this->status]) is hack for 3261.
		if(!empty($this->status) && isset( $app_list_strings['dom_email_status'][$this->status])) {
			$this->status_name = $app_list_strings['dom_email_status'][$this->status];
		}

		parent::fill_in_additional_detail_fields();
	}

	function get_list_view_data() {
		global $app_list_strings;
		global $theme;
		global $current_user;
		global $timedate;
		global $mod_strings;

		$email_fields = $this->get_list_view_array();
		$this->retrieveEmailText();
		$email_fields['FROM_ADDR'] = $this->from_addr_name;
		$mod_strings = return_module_language($GLOBALS['current_language'], 'Emails'); // hard-coding for Home screen ListView

		if($this->status != 'replied') {
			$email_fields['QUICK_REPLY'] = '<a  href="index.php?module=Emails&action=Compose&replyForward=true&reply=reply&record='.$this->id.'&inbound_email_id='.$this->id.'">'.$mod_strings['LNK_QUICK_REPLY'].'</a>';
			$email_fields['STATUS'] = ($email_fields['REPLY_TO_STATUS'] == 1 ? $mod_strings['LBL_REPLIED'] : $email_fields['STATUS']);
		} else {
			$email_fields['QUICK_REPLY'] = $mod_strings['LBL_REPLIED'];
		}
		if(!empty($this->parent_type)) {
			$email_fields['PARENT_MODULE'] = $this->parent_type;
		} else {
			switch($this->intent) {
				case 'support':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Cases&action=EditView&inbound_email_id='.$this->id.'" >' . SugarThemeRegistry::current()->getImage('CreateCases', 'border="0"', null, null, ".gif", $mod_strings['LBL_CREATE_CASES']).$mod_strings['LBL_CREATE_CASE'].'</a>';
				break;

				case 'sales':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Leads&action=EditView&inbound_email_id='.$this->id.'" >'.SugarThemeRegistry::current()->getImage('CreateLeads', 'border="0"', null, null, ".gif", $mod_strings['LBL_CREATE_LEADS']).$mod_strings['LBL_CREATE_LEAD'].'</a>';
				break;

				case 'contact':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Contacts&action=EditView&inbound_email_id='.$this->id.'" >'.SugarThemeRegistry::current()->getImage('CreateContacts', 'border="0"', null, null, ".gif", $mod_strings['LBL_CREATE_CONTACTS']).$mod_strings['LBL_CREATE_CONTACT'].'</a>';
				break;

				case 'bug':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Bugs&action=EditView&inbound_email_id='.$this->id.'" >'.SugarThemeRegistry::current()->getImage('CreateBugs', 'border="0"', null, null, ".gif", $mod_strings['LBL_CREATE_BUGS']).$mod_strings['LBL_CREATE_BUG'].'</a>';
				break;

				case 'task':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Tasks&action=EditView&inbound_email_id='.$this->id.'" >'.SugarThemeRegistry::current()->getImage('CreateTasks', 'border="0"', null, null, ".gif", $mod_strings['LBL_CREATE_TASKS']).$mod_strings['LBL_CREATE_TASK'].'</a>';
				break;

				case 'bounce':
				break;

				case 'pick':
				// break;

				case 'info':
				//break;

				default:
					$email_fields['CREATE_RELATED'] = $this->quickCreateForm();
				break;
			}

		}

		//BUG 17098 - MFH changed $this->from_addr to $this->to_addrs
		$email_fields['CONTACT_NAME']		= empty($this->contact_name) ? '</a>'.$this->trimLongTo($this->to_addrs).'<a>' : $this->contact_name;
		$email_fields['CONTACT_ID']         = empty($this->contact_id) ? '' : $this->contact_id;
        $email_fields['ATTACHMENT_IMAGE']	= empty($this->attachment_image) ? '' : $this->attachment_image;
		$email_fields['LINK_ACTION']		= $this->link_action;

    	if(isset($this->type_name))
	      	$email_fields['TYPE_NAME'] = $this->type_name;

		return $email_fields;
	}

    /**
     * @deprecated The BWC Emails UI is no longer being used.
     * @return string
     */
    function quickCreateForm() {
        global $mod_strings, $app_strings, $currentModule, $current_language;

        LoggerManager::getLogger()->deprecated('Email::quickCreateForm() has been deprecated. The BWC Emails UI is ' .
            'no longer being used.');

        // Coming from the home page via Dashlets
        if($currentModule != 'Email')
        	$mod_strings = return_module_language($current_language, 'Emails');
        return $mod_strings['LBL_QUICK_CREATE']."&nbsp;<a id='$this->id' onclick='return quick_create_overlib(\"{$this->id}\", \"".SugarThemeRegistry::current()->__toString()."\", this);' href=\"#\" >".SugarThemeRegistry::current()->getImage("advanced_search","border='0' align='absmiddle'", null,null,'.gif',$mod_strings['LBL_QUICK_CREATE'])."</a>";
    }

    /**
     * Searches all imported emails and returns the result set as an array.
     *
     * @deprecated Use the Filter API instead.
     */
    function searchImportedEmails($sort = '', $direction='')
    {
		global $timedate;
		global $current_user;
		global $beanList;
		global $sugar_config;
		global $app_strings;

        LoggerManager::getLogger()->deprecated('Email::searchImportedEmails() has been deprecated. Use the Filter ' .
            'API instead.');

		$emailSettings = $current_user->getPreference('emailSettings', 'Emails');
		// cn: default to a low number until user specifies otherwise
		if(empty($emailSettings['showNumInList']))
			$pageSize = 20;
        else
            $pageSize = $emailSettings['showNumInList'];

        if( isset($_REQUEST['start']) && isset($_REQUEST['limit']) )
	       $page = ceil($_REQUEST['start'] / $_REQUEST['limit']) + 1;
	    else
	       $page = 1;

	     //Determine sort ordering

	     //Sort ordering parameters in the request do not coincide with actual column names
	     //so we need to remap them.
	     $hrSortLocal = array(
            'flagged' => 'type',
            'status'  => 'reply_to_status',
            'from'    => 'emails_text.from_addr',
            'subject' => 'name',
            'date'    => 'date_sent',
            'AssignedTo' => 'assigned_user_id',
            'flagged' => 'flagged'
        );

	     $sort = !empty($_REQUEST['sort']) ? $this->db->getValidDBName($_REQUEST['sort']) : "";
         $direction = !empty($_REQUEST['dir'])  && in_array(strtolower($_REQUEST['dir']), array("asc", "desc")) ? $_REQUEST['dir'] : "";

         $order = ( !empty($sort) && !empty($direction) ) ? " ORDER BY {$hrSortLocal[$sort]} {$direction}" : "";

         //Get our main query.
		$fullQuery = $this->_genereateSearchImportedEmailsQuery();

		//Perform a count query needed for pagination.
		$countQuery = $this->create_list_count_query($fullQuery);

		$count_rs = $this->db->query($countQuery, false, 'Error executing count query for imported emails search');
		$count_row = $this->db->fetchByAssoc($count_rs);
		$total_count = ($count_row != null) ? $count_row['c'] : 0;

        $start = ($page - 1) * $pageSize;

        //Execute the query
		$rs = $this->db->limitQuery($fullQuery . $order, $start, $pageSize);

		$return = array();

		while($a = $this->db->fetchByAssoc($rs)) {
			$temp = array();
			$temp['flagged'] = (is_null($a['flagged']) || $a['flagged'] == '0') ? '' : 1;
			$temp['status'] = (is_null($a['reply_to_status']) || $a['reply_to_status'] == '0') ? '' : 1;
			$temp['subject'] = $a['name'];
			$temp['date']  = $timedate->to_display_date_time($this->db->fromConvert($a['date_sent'], 'datetime'));
			$temp['uid'] = $a['id'];
			$temp['ieId'] = $a['mailbox_id'];
			$temp['site_url'] = $sugar_config['site_url'];
			$temp['seen'] = ($a['status'] == 'unread') ? 0 : 1;
			$temp['type'] = $a['type'];
			$temp['mbox'] = 'sugar::Emails';
			$temp['hasAttach'] =  $this->doesImportedEmailHaveAttachment($a['id']);
			//To and from addresses may be stored in emails_text, if nothing is found, revert to
			//regular email addresses.
			$temp['to_addrs'] = preg_replace('/[\x00-\x08\x0B-\x1F]/', '', $a['to_addrs']);
			$temp['from']	= preg_replace('/[\x00-\x08\x0B-\x1F]/', '', $a['from_addr']);
			if( empty($temp['from']) || empty($temp['to_addrs']) )
			{
    			//Retrieve email addresses seperatly.
    			$tmpEmail = BeanFactory::newBean('Emails');
    			$tmpEmail->id = $a['id'];
    			$tmpEmail->retrieveEmailAddresses();
    			$temp['from'] = $tmpEmail->from_addr;
    			$temp['to_addrs'] = $tmpEmail->to_addrs;
			}

			$return[] = $temp;
		}

		$metadata = array();
		$metadata['totalCount'] = $total_count;
		$metadata['out'] = $return;

		return $metadata;
    }

    /**
     * Determine if an imported email has an attachment by examining the relationship to notes.
     *
     * @deprecated Use {@link Email::$total_attachments} instead.
     * @param string $id
     * @return boolean
     */
    function doesImportedEmailHaveAttachment($id)
	{
        LoggerManager::getLogger()->deprecated('Email::doesImportedEmailHaveAttachment() has been deprecated. Use ' .
            'Email::$total_attachments instead.');

	   $hasAttachment = FALSE;
        //FIXME: notes.file_mime_type IS NOT NULL is probably not necessary
        $stmt = $this->db->getConnection()->executeQuery(
            'SELECT id FROM notes where email_id = ? AND email_type = ? AND file_mime_type is not null AND deleted = ?',
            array($id, 'Emails', 0)
        );
        $noteId = $stmt->fetchColumn();
        if (!empty($noteId)) {
            $hasAttachment = true;
        }

	   return (int) $hasAttachment;
	}

    /**
     * Generate the query used for searching imported emails.
     *
     * @deprecated Use the Filter API instead.
     * @return String Query to be executed.
     */
    function _genereateSearchImportedEmailsQuery()
    {
		global $timedate;

        LoggerManager::getLogger()->deprecated('Email::_genereateSearchImportedEmailsQuery() has been deprecated. ' .
            'Use the Filter API instead.');

        $additionalWhereClause = $this->_generateSearchImportWhereClause();

        $query = array();
        $fullQuery = "";
        $query['select'] = "emails.id , emails.mailbox_id, emails.name, emails.date_sent, emails.status, emails.type, emails.flagged, emails.reply_to_status,
		                      emails_text.from_addr, emails_text.to_addrs  FROM emails ";

        $query['joins'] = " JOIN emails_text on emails.id = emails_text.email_id ";

        //Handle from and to addr joins
        if( !empty($_REQUEST['from_addr']) )
        {
            $from_addr = $this->db->quote(strtolower($_REQUEST['from_addr']));
            $query['joins'] .= "INNER JOIN emails_email_addr_rel er_from ON er_from.email_id = emails.id AND er_from.deleted = 0 INNER JOIN email_addresses ea_from ON ea_from.id = er_from.email_address_id
                                AND er_from.address_type='from' AND emails_text.from_addr LIKE '%" . $from_addr . "%'";
        }

        if( !empty($_REQUEST['to_addrs'])  )
        {
            $to_addrs = $this->db->quote(strtolower($_REQUEST['to_addrs']));
            $query['joins'] .= "INNER JOIN emails_email_addr_rel er_to ON er_to.email_id = emails.id AND er_to.deleted = 0 INNER JOIN email_addresses ea_to ON ea_to.id = er_to.email_address_id
                                    AND er_to.address_type='to' AND ea_to.email_address LIKE '%" . $to_addrs . "%'";
        }

        $query['where'] = " WHERE (emails.type= 'inbound' OR emails.type='archived' OR emails.type='out') AND emails.deleted = 0 ";
		if( !empty($additionalWhereClause) )
    	    $query['where'] .= "AND $additionalWhereClause";

    	//If we are explicitly looking for attachments.  Do not use a distinct query as the to_addr is defined
    	//as a text which equals clob in oracle and the distinct query can not be executed correctly.
    	$addDistinctKeyword = "";
        //FIXME: notes.email_type should be Emails
        //FIXME: notes.filename IS NOT NULL is probably not necessary
        if( !empty($_REQUEST['attachmentsSearch']) &&  $_REQUEST['attachmentsSearch'] == 1) //1 indicates yes
            $query['where'] .= " AND EXISTS ( SELECT id FROM notes n WHERE n.email_id = emails.id AND n.deleted = 0 AND n.filename is not null )";
        else if( !empty($_REQUEST['attachmentsSearch']) &&  $_REQUEST['attachmentsSearch'] == 2 )
             $query['where'] .= " AND NOT EXISTS ( SELECT id FROM notes n WHERE n.email_id = emails.id AND n.deleted = 0 AND n.filename is not null )";

        $this->addVisibilityWhere($query['where'], array('where_condition' => true));

        $fullQuery = "SELECT " . $query['select'] . " " . $query['joins'] . " " . $query['where'];

        $GLOBALS['log']->debug("---- Email Search - FullQuery --------------------------------");
        $GLOBALS['log']->debug("FullQuery: ({$fullQuery})");
        $GLOBALS['log']->debug("--------------------------------------------------------------");

        return $fullQuery;
    }
    /**
     * Generate the where clause for searching imported emails.
     *
     * @deprecated Use the Filter API instead.
     */
    function _generateSearchImportWhereClause()
    {
        global $timedate;

        LoggerManager::getLogger()->deprecated('Email::_generateSearchImportWhereClause() has been deprecated. Use ' .
            'the Filter API instead.');

        //The clear button was removed so if a user removes the asisgned user name, do not process the id.
        if( empty($_REQUEST['assigned_user_name']) && !empty($_REQUEST['assigned_user_id'])  )
            unset($_REQUEST['assigned_user_id']);

        $availableSearchParam = array('name' => array('table_name' =>'emails'),
                                      'data_parent_id_search' => array('table_name' =>'emails','db_key' => 'parent_id','opp' => '='),
                                      'assigned_user_id' => array('table_name' => 'emails', 'opp' => '=') );

		$additionalWhereClause = array();
		foreach ($availableSearchParam as $key => $properties)
		{
		      if( !empty($_REQUEST[$key]) )
		      {
		          $db_key =  isset($properties['db_key']) ? $properties['db_key'] : $key;
                  $searchValue = $this->db->quote($_REQUEST[$key]);

		          $opp = isset($properties['opp']) ? $properties['opp'] : 'like';
		          if($opp == 'like')
		              $searchValue = "%" . $searchValue . "%";

		          $additionalWhereClause[] = "{$properties['table_name']}.$db_key $opp '$searchValue' ";
		      }
        }



        $isDateFromSearchSet = !empty($_REQUEST['searchDateFrom']);
        $isdateToSearchSet = !empty($_REQUEST['searchDateTo']);
        $bothDateRangesSet = $isDateFromSearchSet & $isdateToSearchSet;

        //Handle date from and to separately
        $dbFormatDateFrom = '';
        $dbFormatDateTo = '';
        if($bothDateRangesSet)
        {
            $dbFormatDateFrom = $timedate->to_db_date($_REQUEST['searchDateFrom'], false) . " 00:00:00";
            $dbFormatDateFrom = $this->toDatabaseSearchDateTime($dbFormatDateFrom);
            $dbFormatDateFrom = $GLOBALS['db']->convert($GLOBALS['db']->quoted($dbFormatDateFrom), 'datetime');

            $dbFormatDateTo = $timedate->to_db_date($_REQUEST['searchDateTo'], false) . " 23:59:59";
            $dbFormatDateTo = $this->toDatabaseSearchDateTime($dbFormatDateTo);
            $dbFormatDateTo = $GLOBALS['db']->convert($GLOBALS['db']->quoted($dbFormatDateTo), 'datetime');

            $additionalWhereClause[] = "( emails.date_sent >= $dbFormatDateFrom AND emails.date_sent <= $dbFormatDateTo )";
        }
        elseif ($isdateToSearchSet)
        {
            $dbFormatDateTo = $timedate->to_db_date($_REQUEST['searchDateTo'], false) . " 23:59:59";
            $dbFormatDateTo = $this->toDatabaseSearchDateTime($dbFormatDateTo);
            $additionalWhereClause[] = "emails.date_sent <= " . $GLOBALS['db']->convert($GLOBALS['db']->quoted($dbFormatDateTo), 'datetime');
        }
        elseif ($isDateFromSearchSet)
        {
            $dbFormatDateFrom = $timedate->to_db_date($_REQUEST['searchDateFrom'], false) . " 00:00:00";
            $dbFormatDateFrom = $this->toDatabaseSearchDateTime($dbFormatDateFrom);
            $additionalWhereClause[] = "emails.date_sent >= " . $GLOBALS['db']->convert($GLOBALS['db']->quoted($dbFormatDateFrom), 'datetime');
        }

        $GLOBALS['log']->debug("------ EMAIL SEARCH DATETIME Values ---------------------------------------------");
        $GLOBALS['log']->debug("dbFormatDateFrom: {$dbFormatDateFrom}");
        $GLOBALS['log']->debug("dbFormatDateTo: {$dbFormatDateTo}");
        if (count($additionalWhereClause)) {
            $GLOBALS['log']->debug("additionalWhereClause: " .
                $additionalWhereClause[count($additionalWhereClause) - 1]);
        }
        $GLOBALS['log']->debug("---------------------------------------------------------------------------------");

        $additionalWhereClause = implode(" AND ", $additionalWhereClause);

        return $additionalWhereClause;
    }



	/**
	 * takes a long TO: string of emails and returns the first appended by an
	 * elipse
     *
     * @deprecated Use {@link Email::$to}, {@link Email::$cc}, and {@link Email::$bcc} to retrieve the email's
     * recipients.
	 */
	function trimLongTo($str) {
        LoggerManager::getLogger()->deprecated("Email::trimLongTo() has been deprecated. Use Email::\$to, " .
            "Email::\$cc, and Email::\$bcc to retrieve the email's recipients. Use Email::\$from to retrieve the " .
            "email's sender.");

		if(strpos($str, ',')) {
			$exStr = explode(',', $str);
			return $exStr[0].'...';
		} elseif(strpos($str, ';')) {
			$exStr = explode(';', $str);
			return $exStr[0].'...';
		} else {
			return $str;
		}
	}

	function get_summary_text() {
		return $this->name;
	}

    /**
     * @deprecated The BWC Emails UI is no longer being used.
     * @param mixed $where
     * @return string
     */
	function distributionForm($where) {
		global $app_list_strings;
		global $app_strings;
		global $mod_strings;
		global $theme;
		global $current_user;

        LoggerManager::getLogger()->deprecated('Email::distributionForm() has been deprecated. The BWC Emails UI is ' .
            'no longer being used.');

		$distribution	= get_select_options_with_id($app_list_strings['dom_email_distribution'], '');
		$_SESSION['distribute_where'] = $where;

		$teamSetField = new EmailSugarFieldTeamsetCollection($this, $this->field_defs, '', 'Distribute');
		$code = $teamSetField->get_code();
		$sqs_objects = $teamSetField->createQuickSearchCode(true);
		$teamWidget = $code.$sqs_objects;

		$out = '<form name="Distribute" id="Distribute">';
		$out .= get_form_header($mod_strings['LBL_DIST_TITLE'], '', false);
		$out .=<<<eoq
		<script>
			enableQS(true);
		</script>
eoq;
		$out .= '
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td>
					<script type="text/javascript">


						function checkDeps(form) {
							return;
						}

						function mySubmit() {
							var assform = document.getElementById("Distribute");
							var select = document.getElementById("userSelect");
							var assign1 = assform.r1.checked;
							var assign2 = assform.r2.checked;
							var dist = assform.dm.value;
							var assign = false;
							var users = false;
							var rules = false;
							var warn1 = "'.$mod_strings['LBL_WARN_NO_USERS'].'";
							var warn2 = "";

							if(assign1 || assign2) {
								assign = true;

							}

							for(i=0; i<select.options.length; i++) {
								if(select.options[i].selected == true) {
									users = true;
									warn1 = "";
								}
							}

							if(dist != "") {
								rules = true;
							} else {
								warn2 = "'.$mod_strings['LBL_WARN_NO_DIST'].'";
							}

							if(assign && users && rules) {

								if(document.getElementById("r1").checked) {
									var mu = document.getElementById("MassUpdate");
									var grabbed = "";

									for(i=0; i<mu.elements.length; i++) {
										if(mu.elements[i].type == "checkbox" && mu.elements[i].checked && mu.elements[i].name.value != "massall") {
											if(grabbed != "") { grabbed += "::"; }
											grabbed += mu.elements[i].value;
										}
									}
									var formgrab = document.getElementById("grabbed");
									formgrab.value = grabbed;
								}
								assform.submit();
							} else {
								alert("'.$mod_strings['LBL_ASSIGN_WARN'].'" + "\n" + warn1 + "\n" + warn2);
							}
						}

						function submitDelete() {
							if(document.getElementById("r1").checked) {
								var mu = document.getElementById("MassUpdate");
								var grabbed = "";

								for(i=0; i<mu.elements.length; i++) {
									if(mu.elements[i].type == "checkbox" && mu.elements[i].checked && mu.elements[i].name != "massall") {
										if(grabbed != "") { grabbed += "::"; }
										grabbed += mu.elements[i].value;
									}
								}
								var formgrab = document.getElementById("grabbed");
								formgrab.value = grabbed;
							}
							if(grabbed == "") {
								alert("'.$mod_strings['LBL_MASS_DELETE_ERROR'].'");
							} else {
								document.getElementById("Distribute").submit();
							}
						}

					</script>
						<input type="hidden" name="module" value="Emails">
						<input type="hidden" name="action" id="action">
						<input type="hidden" name="grabbed" id="grabbed">

					<table cellpadding="1" cellspacing="0" width="100%" border="0" class="edit view">
						<tr height="20">
							<td scope="col" scope="row" NOWRAP align="center">
								&nbsp;'.$mod_strings['LBL_ASSIGN_SELECTED_RESULTS_TO'].'&nbsp;';
					$out .= $this->userSelectTable();
					$out .=	'</td>
							<td scope="col" scope="row" NOWRAP align="left">
								&nbsp;'.$mod_strings['LBL_USING_RULES'].'&nbsp;
								<select name="distribute_method" id="dm" onChange="checkDeps(this.form);">'.$distribution.'</select>
							</td>';


					$out .= '</td>
							</tr>';

					$out .= '<tr><td/>';
					$out .= '<td>'.translate('LBL_TEAMS', 'EmailTemplates');
					$out .= $teamWidget;
					$out .= '</td>
							</tr>';

					$out .= '<tr>
								<td scope="col" width="50%" scope="row" NOWRAP align="right" colspan="2">
								<input title="'.$mod_strings['LBL_BUTTON_DISTRIBUTE_TITLE'].'"
									id="dist_button"
									class="button" onClick="AjaxObject.detailView.handleAssignmentDialogAssignAction();"
									type="button" name="button"
									value="  '.$mod_strings['LBL_BUTTON_DISTRIBUTE'].'  ">';
					$out .= '</tr>
					</table>
				</td>
			</tr>
		</table>
		</form>';
	return $out;
	}

    /**
     * @deprecated The BWC Emails UI is no longer being used.
     * @return string
     */
	function userSelectTable() {
		global $theme;
		global $mod_strings;

        LoggerManager::getLogger()->deprecated('Email::userSelectTable() has been deprecated. The BWC Emails UI is ' .
            'no longer being used.');

		$colspan = 1;
		$setTeamUserFunction = '';

		$colspan++;
		$teams = array();

		$teamTable = '<table cellpadding="1" cellspacing="0" border="0">';
		$teamTable .= '<tr><td colspan="2"><b>'.$mod_strings['LBL_SELECT_TEAM'].'</b></td></tr>';

		$r = $this->db->query('SELECT teams.id, teams.name FROM teams WHERE deleted=0 AND private = 0');
		while($a = $this->db->fetchByAssoc($r)) {
			$teamTable .= '<tr>';
			$teamTable .= '<td><input type="checkbox" style="border:0px solid #000000" name="'.$a['id'].'" id="'.$a['id'].'" onclick="checkDeps(this.form); setTeamUsers();"></td>';
			$teamTable .= '<td NOWRAP>'.$a['name'].'</td></tr>';
			$teams[$a['id']] = $a['name'];
		}
		$teamTable .= '</table>';

		$q2 = "SELECT t.id, t.name, tm.user_id FROM teams t JOIN team_memberships tm ON t.id = tm.team_id JOIN users u on tm.user_id = u.id WHERE t.deleted = 0 AND u.deleted = 0 AND u.is_group = 0 AND t.private = 0 AND u.status = 'Active' ORDER BY t.id";
		$r2 = $this->db->query($q2);
		$teamIfOpen = array();
		$teamIfDoes = array();
		$teamIfClose = array();

		$ifs = '';
		while($a2 = $this->db->fetchByAssoc($r2)) {
			$ifs .= 'if((document.getElementById("'.$a2['id'].'") != null) && (document.getElementById("'.$a2['id'].'").checked == true)) {';
			$ifs .= 'document.getElementById("'.$a2['user_id'].'").selected=true;';
			$ifs .= '} else if((document.getElementById("'.$a2['id'].'") != null) && (document.getElementById("'.$a2['id'].'").checked == false)) {';
			$ifs .= '   if((document.getElementById("'.$a2['user_id'].'") != null) && (document.getElementById("'.$a2['user_id'].'").selected == true)) {';
			$ifs .= 'document.getElementById("'.$a2['user_id'].'").selected=false; }';
			$ifs .= '}';
		}


		$setTeamUserFunction  = 'function setTeamUsers() {';
		$setTeamUserFunction .= $ifs;
		$setTeamUserFunction .= 'setCheckMark();';
		$setTeamUserFunction .= 'return;';
		$setTeamUserFunction .= '}';

		// get users
		$r = $this->db->query("SELECT users.id, users.user_name, users.first_name, users.last_name FROM users WHERE deleted=0 AND status = 'Active' AND is_group=0 ORDER BY users.last_name, users.first_name");

		$userTable = '<table cellpadding="0" cellspacing="0" border="0">';
		$userTable .= '<tr><td colspan="2"><b>'.$mod_strings['LBL_USER_SELECT'].'</b></td></tr>';
		$userTable .= '<tr><td><input type="checkbox" style="border:0px solid #000000" onClick="toggleAll(this); setCheckMark(); checkDeps(this.form);"></td> <td>'.$mod_strings['LBL_TOGGLE_ALL'].'</td></tr>';
		$userTable .= '<tr><td colspan="2"><select style="visibility:hidden;" name="users[]" id="userSelect" multiple size="12">';

		while($a = $this->db->fetchByAssoc($r)) {
			$userTable .= '<option value="'.$a['id'].'" id="'.$a['id'].'">'.$a['first_name'].' '.$a['last_name'].'</option>';
		}
		$userTable .= '</select></td></tr>';
		$userTable .= '</table>';

		$out  = '<script type="text/javascript">';
		$out .= $setTeamUserFunction;
		$out .= '
					function setCheckMark() {
						var select = document.getElementById("userSelect");

						for(i=0 ; i<select.options.length; i++) {
							if(select.options[i].selected == true) {
								document.getElementById("checkMark").style.display="";
								return;
							}
						}

						document.getElementById("checkMark").style.display="none";
						return;
					}

					function showUserSelect() {
						var targetTable = document.getElementById("user_select");
						targetTable.style.visibility="visible";
						var userSelectTable = document.getElementById("userSelect");
						userSelectTable.style.visibility="visible";
						return;
					}
					function hideUserSelect() {
						var targetTable = document.getElementById("user_select");
						targetTable.style.visibility="hidden";
						var userSelectTable = document.getElementById("userSelect");
						userSelectTable.style.visibility="hidden";
						return;
					}
					function toggleAll(toggle) {
						if(toggle.checked) {
							var stat = true;
						} else {
							var stat = false;
						}
						var form = document.getElementById("userSelect");
						for(i=0; i<form.options.length; i++) {
							form.options[i].selected = stat;
						}
					}


				</script>
			<span id="showUsersDiv" style="position:relative;">
				<a href="#" id="showUsers" onClick="javascript:showUserSelect();">
					'.SugarThemeRegistry::current()->getImage('Users', '', null, null, ".gif", $mod_strings['LBL_USERS']).'</a>&nbsp;
				<a href="#" id="showUsers" onClick="javascript:showUserSelect();">
					<span style="display:none;" id="checkMark">'.SugarThemeRegistry::current()->getImage('check_inline', 'border="0"', null, null, ".gif", $mod_strings['LBL_CHECK_INLINE']).'</span>
				</a>


				<div id="user_select" style="width:200px;position:absolute;left:2;top:2;visibility:hidden;z-index:1000;">
				<table cellpadding="0" cellspacing="0" border="0" class="list view">
					<tr height="20">
						<td  colspan="'.$colspan.'" id="hiddenhead" onClick="hideUserSelect();" onMouseOver="this.style.border = \'outset red 1px\';" onMouseOut="this.style.border = \'inset white 0px\';this.style.borderBottom = \'inset red 1px\';">
							<a href="#" onClick="javascript:hideUserSelect();">'.SugarThemeRegistry::current()->getImage('close', 'border="0"', null, null, ".gif", $mod_strings['LBL_CLOSE']).'</a>
							'.$mod_strings['LBL_USER_SELECT'].'
						</td>
					</tr>
					<tr>';
//<td valign="middle" height="30"  colspan="'.$colspan.'" id="hiddenhead" onClick="hideUserSelect();" onMouseOver="this.style.border = \'outset red 1px\';" onMouseOut="this.style.border = \'inset white 0px\';this.style.borderBottom = \'inset red 1px\';">
		$out .= '		<td style="padding:5px" class="oddListRowS1" bgcolor="#fdfdfd" valign="top" align="left" style="left:0;top:0;">';
		$out .= $teamTable;
		$out .= '		</td>';
		$out .=	'		<td style="padding:5px" class="oddListRowS1" bgcolor="#fdfdfd" valign="top" align="left" style="left:0;top:0;">
							'.$userTable.'
						</td>
					</tr>
				</table></div>
			</span>';
		return $out;
	}

    /**
     * @deprecated The BWC Emails UI is no longer being used.
     * @param string $type
     * @return string
     */
	function checkInbox($type) {
		global $theme;
		global $mod_strings;

        LoggerManager::getLogger()->deprecated('Email::checkInbox() has been deprecated. The BWC Emails UI is no ' .
            'longer being used.');

		$out = '<div><input	title="'.$mod_strings['LBL_BUTTON_CHECK_TITLE'].'"
						class="button"
						type="button" name="button"
						onClick="window.location=\'index.php?module=Emails&action=Check&type='.$type.'\';"
						style="margin-bottom:2px"
						value="  '.$mod_strings['LBL_BUTTON_CHECK'].'  "></div>';
		return $out;
	}

    /**
     * Guesses primary parent id from "To" and "From" email addresses.
     * This will not affect the many-to-many relationships already constructed as this is, at best,
     * informational linking.
     *
     * @deprecated The BWC Emails UI is no longer being used.
     */
    public function fillPrimaryParentFields($table)
    {
        LoggerManager::getLogger()->deprecated('Email::fillPrimaryParentFields() has been deprecated. The BWC Emails ' .
            'UI is no longer being used.');

        $addresses = $this->email2ParseAddressesForAddressesOnly($this->to_addrs);
        $addresses[] = $this->from_addr;

        if (empty($addresses)) {
            return;
        }

        $table = strtolower($table);
        $module = ucfirst($table);

        $addresses = "'" . implode("','", $addresses) . "'";
        $q = "SELECT DISTINCT a.id FROM {$table} a" .
            " INNER JOIN email_addresses ea" .
            " INNER JOIN email_addr_bean_rel eabr ON ea.id = eabr.email_address_id" .
            " WHERE eabr.bean_module = '{$module}' AND email_address IN ({$addresses})" .
            " AND eabr.bean_id = a.id AND a.deleted = 0 LIMIT 1";

        // Get the first bean and set parent id/name, makes little sense since it's a many-to-many relationship
        $r = $this->db->query($q);
        if ($a = $this->db->fetchByAssoc($r)) {
            $parent = BeanFactory::getBean($module, $a['id']);
            $this->parent_type = $parent->module_dir;
            $this->parent_id = $parent->id;
            if (!empty($parent->name)) {
                $this->parent_name = $parent->name;
            }
            return;
        }
    }

        /**
         * Convert reference to inline image (stored as Note) to URL link
         * Enter description here ...
         *
         * @deprecated The BWC Emails UI is no longer being used.
         * @param string $note ID of the note
         * @param string $ext type of the note
         */
        public function cid2Link($noteId, $noteType)
        {
            LoggerManager::getLogger()->deprecated('Email::cid2Link() has been deprecated. The BWC Emails UI is no ' .
                'longer being used.');

            if(empty($this->description_html)) return;
			list($type, $subtype) = explode('/', $noteType);
			if(strtolower($type) != 'image') {
			    return;
			}
            $upload = new UploadFile();
            $this->description_html = preg_replace(
                "#class=\"image\" src=\"cid:" . preg_quote($noteId, '#') . "\.(.+?)\"#",
                "class=\"image\" src=\"{$this->imagePrefix}{$noteId}.\\1\"",
                $this->description_html
            );
	        // ensure the image is in the cache
            sugar_mkdir(sugar_cached("images/"));
			$imgfilename = sugar_cached("images/")."$noteId.".strtolower($subtype);
			$note = BeanFactory::getBean('Notes', $noteId);
			$src = "upload://".$note->getUploadId();
			if(!file_exists($imgfilename) && file_exists($src)) {
				copy($src, $imgfilename);
			}
        }

    /**
     * Convert all cid: links in this email into URLs
     *
     * @deprecated The BWC Emails UI is no longer being used.
     */
    public function cids2Links()
    {
        LoggerManager::getLogger()->deprecated('Email::cids2Links() has been deprecated. The BWC Emails UI is no ' .
            'longer being used.');

        if (empty($this->description_html)) {
            return;
        }
        //FIXME: notes.email_type should be Emails
        $stmt = $this->db->getConnection()->executeQuery(
            'SELECT id, file_mime_type FROM notes WHERE email_id = ? AND deleted = 0',
            array($this->id)
        );
        while ($a = $stmt->fetch()) {
            $this->cid2Link($a['id'], $a['file_mime_type']);
        }
    }

    /**
     * Bugs 50972, 50973
     * Sets the field def for a field to allow null values
     *
     * @todo Consider moving to SugarBean to allow other models to set fields to NULL
     * @deprecated The BWC Emails UI is no longer being used.
     * @param string $field The field name to modify
     * @return void
     */
    public function setFieldNullable($field)
    {
        LoggerManager::getLogger()->deprecated('Email::setFieldNullable() has been deprecated. The BWC Emails UI is ' .
            'no longer being used.');

        if (isset($this->field_defs[$field]) && is_array($this->field_defs[$field]))
        {
            if (empty($this->modifiedFieldDefs[$field]))
            {
                if (
                    isset($this->field_defs[$field]['isnull']) &&
                    (strtolower($this->field_defs[$field]['isnull']) == 'false' || $this->field_defs[$field]['isnull'] === false)
                )
                {
                    $this->modifiedFieldDefs[$field]['isnull'] = $this->field_defs[$field]['isnull'];
                    unset($this->field_defs[$field]['isnull']);
                }

                if (isset($this->field_defs[$field]['dbType']) && $this->field_defs[$field]['dbType'] == 'id')
                {
                    $this->modifiedFieldDefs[$field]['dbType'] = $this->field_defs[$field]['dbType'];
                    unset($this->field_defs[$field]['dbType']);
                }

                if (!isset($this->field_defs[$field]['required'])) {
                    $this->addedFieldDefs[$field]['required'] = true;
                    $this->field_defs[$field]['required'] = false;
                } elseif (!empty($this->field_defs[$field]['required'])) {
                    $this->modifiedFieldDefs[$field]['required'] = $this->field_defs[$field]['required'];
                    $this->field_defs[$field]['required'] = false;
                }
            }
        }
    }

    /**
     * Bugs 50972, 50973
     * Set the field def back to the way it was prior to modification
     *
     * @deprecated The BWC Emails UI is no longer being used.
     * @param $field
     * @return void
     */
    public function revertFieldNullable($field)
    {
        LoggerManager::getLogger()->deprecated('Email::revertFieldNullable() has been deprecated. The BWC Emails UI ' .
            'is no longer being used.');

        if (!empty($this->modifiedFieldDefs[$field]) && is_array($this->modifiedFieldDefs[$field]))
        {
            foreach ($this->modifiedFieldDefs[$field] as $k => $v)
            {
                $this->field_defs[$field][$k] = $v;
            }

            unset($this->modifiedFieldDefs[$field]);
        }

        if (isset($this->addedFieldDefs[$field])) {
            foreach (array_keys($this->addedFieldDefs[$field]) as $param) {
                unset($this->field_defs[$field][$param]);
            }
            unset($this->addedFieldDefs[$field]);
        }
    }

    /**
     * Set the DateTime Search Data based on Current User TimeZone
     *
     * @deprecated This method is no longer used.
     * @param  string $userSearchDateTime  - user Search Datetime
     * @return string $dbSearchDateTime    - database Search Datetime
     */
    public function toDatabaseSearchDateTime($userSearchDateTime) {
        global $timedate;
        global $current_user;

        LoggerManager::getLogger()->deprecated('Email::toDatabaseSearchDateTime() has been deprecated.');

        $usertimezone = $current_user->getPreference('timezone');
        if (empty($usertimezone)) {
           $usertimezone = "UTC";
        }
        $tz = new DateTimeZone($usertimezone);

        $sugarDateTime = new SugarDateTime($userSearchDateTime);
        $sugarDateTime->setTimezone($tz);
        $dbSearchDateTime = $timedate->asDb($sugarDateTime);
        return $dbSearchDateTime;
    }

    /**
     * Sends the email.
     *
     * @param OutboundEmailConfiguration $config
     * @throws Exception
     * @throws MailerException
     */
    public function sendEmail(OutboundEmailConfiguration $config)
    {
        if ($this->state !== static::STATE_DRAFT) {
            throw new SugarException("Cannot send an email with state: {$this->state}");
        }

        // An exception will bubble up if the "from" relationship can't be loaded.
        $this->setSender($GLOBALS['current_user']);

        // Resolve variables in the subject and content.
        // The parent must be listed prior to the current user or any variables associated with the parent will be
        // stripped when substituting variables associated with the user.
        $related = array();

        if (!empty($this->parent_type) && !empty($this->parent_id)) {
            $related[$this->parent_type] = $this->parent_id;
        }

        $related['Users'] = $GLOBALS['current_user']->id;

        $this->name = EmailTemplate::parse_template($this->name, $related);
        $this->description_html = EmailTemplate::parse_template($this->description_html, $related, true);
        $this->description = EmailTemplate::parse_template($this->description, $related);

        $this->description = $this->decodeDuringSend(from_html($this->description));
        $this->description_html = $this->decodeDuringSend(from_html($this->description_html));

        // A plain-text part must be sent with the HTML part.
        if (!empty($this->description_html) && empty($this->description)) {
            $this->description = strip_tags(br2nl($this->description_html));
        }

        try {
            $mailer = MailerFactory::getMailer($config);
            $mailer->setSubject($this->name);
            $mailer->setHtmlBody($this->description_html);
            $mailer->setTextBody($this->description);

            // Set up the Reply-To header.
            $replyTo = $config->getReplyTo();

            if (!empty($replyTo)) {
                $replyToEmail = $replyTo->getEmail();

                if (!empty($replyToEmail)) {
                    $mailer->setHeader(
                        EmailHeaders::ReplyTo,
                        new EmailIdentity($replyToEmail, $replyTo->getName())
                    );
                }
            }

            // Add recipients.
            $this->addEmailRecipients($mailer, 'to');
            $this->addEmailRecipients($mailer, 'cc');
            $this->addEmailRecipients($mailer, 'bcc');

            // Add attachments.
            if ($this->load_relationship('attachments')) {
                $attachments = $this->attachments->getBeans();

                foreach ($attachments as $note) {
                    $attachment = AttachmentPeer::attachmentFromSugarBean($note);
                    $mailer->addAttachment($attachment);
                }
            }

            // Generate the Message-ID header using the ID of this email.
            $mailer->setMessageId($this->id);

            // Send the email.
            $sentMessage = $mailer->send();

            // Archive after sending.
            $this->state = static::STATE_ARCHIVED;
            $this->date_sent = TimeDate::getInstance()->nowDb();
            $this->type = 'out';
            $this->status = 'sent';
            // Store the generated Message-ID header.
            $this->message_id = $mailer->getHeader(EmailHeaders::MessageId);
            $this->save();

            if (!empty($this->reply_to_id)) {
                $replyToEmail = BeanFactory::retrieveBean('Emails', $this->reply_to_id);
                if (!empty($replyToEmail) &&
                    $replyToEmail->state === static::STATE_ARCHIVED &&
                    !$replyToEmail->reply_to_status
                ) {
                    $replyToEmail->reply_to_status = true;
                    $replyToEmail->save();
                }
            }

            //TODO: Push the sent email to the IMAP sent folder.
        } catch (MailerException $me) {
            $GLOBALS['log']->error($me->getLogMessage());
            throw($me);
        } catch (Exception $e) {
            $me = new MailerException('Email::sendEmail() failed: ' . $e->getMessage(), MailerException::FailedToSend);
            $GLOBALS['log']->error($me->getLogMessage());
            $GLOBALS['log']->info($me->getTraceMessage());
            $GLOBALS['log']->info($config->toArray(), true);
            throw($me);
        }
    }

    /**
     * Adds the recipients from the specified role to the mailer.
     *
     * Updates the participant rows where the email address was not chosen prior to send-time.
     *
     * @param IMailer $mailer
     * @param string $role Can be "to", "cc", or "bcc".
     * @return int Number of recipients added.
     */
    protected function addEmailRecipients(IMailer $mailer, $role)
    {
        static $methodMap = array(
            'to' => 'addRecipientsTo',
            'cc' => 'addRecipientsCc',
            'bcc' => 'addRecipientsBcc',
        );

        $ea = BeanFactory::newBean('EmailAddresses');
        $num = 0;
        $beans = $this->getParticipants($role);

        foreach ($beans as $bean) {
            // Set the email address of the recipient to the recipient's primary email address.
            if (empty($bean->email_address_id)) {
                if ($this->load_relationship($role)) {
                    $parent = BeanFactory::retrieveBean(
                        $bean->parent_type,
                        $bean->parent_id,
                        ['disable_row_level_security' => true]
                    );

                    if ($parent) {
                        $bean->email_address = $ea->getPrimaryAddress($parent);
                        $bean->email_address_id = $ea->getEmailGUID($bean->email_address);
                    }

                    $this->$role->add($bean);
                }
            }

            try {
                // Rows that are just an email address don't have names. EmailIdentity can sort that out.
                $identity = new EmailIdentity($bean->email_address, $bean->parent_name);
                $method = $methodMap[$role];
                $mailer->$method($identity);
                $num++;
            } catch (MailerException $me) {
                // Invalid email address. Log it and skip.
                $GLOBALS['log']->warning($me->getLogMessage());
            }
        }

        return $num;
    }

    /**
     * Returns all participants with the specified role.
     *
     * @param string $role Can be "to", "cc", or "bcc".
     * @return array
     * @throws SugarException
     */
    protected function getParticipants($role)
    {
        if (!$this->load_relationship($role)) {
            throw new SugarException("Cannot get participants for link: {$role}");
        }

        $linkModuleName = $this->$role->getRelatedModuleName();
        $seed = BeanFactory::newBean($linkModuleName);
        $fields = [
            'email_address_id',
            'email_address',
            'parent_type',
            'parent_id',
            'parent_name',
        ];

        $q = new SugarQuery();
        $q->from($seed);
        // Must add the fields to select before calling SugarBean::fetchFromQuery() so that the email_address_used
        // field is in SugarQuery::$joinLinkToKey. Otherwise, the joins that SugarQuery::joinSubpanel() adds will be
        // replaced by new joins when the email_address_used field is added in SugarBean::fetchFromQuery(), and the
        // the new joins will not include the role column for address_type, which is very important.
        $q->select($fields);
        $q->joinSubpanel($this, $role);
        // Must also pass the fields here -- even though they have already been added to the query -- because
        // SugarBean::fetchFromQuery() will add all of the fields in the vardefs if we don't.
        $beans = $seed->fetchFromQuery($q, $fields);

        // The fields from the collection field's field_map are not mapped in the returned beans. Consumers will
        // need to do this if they care. Existing use cases don't require it. Should use cases emerge that do, then
        // we can add the field mapping here.
        return $beans;
    }

    /**
     * Returns the outbound email configurations that the current user can use.
     *
     * This method is used by the Emails/enum/outbound_email_id REST API endpoint. If the current user is allowed to use
     * the system configuration, then that configuration is treated as the default and forced to the beginning of the
     * returned array. An enum field in the UI will use the first configuration as the default choice.
     *
     * @return array
     * @throws SugarApiExceptionNotAuthorized
     */
    public function getOutboundEmailDropdown()
    {
        $options = [];
        $hasConfiguredDefault = false;
        $error = false;
        $seed = BeanFactory::newBean('OutboundEmail');

        $q = new SugarQuery();
        $q->from($seed);
        $beans = $seed->fetchFromQuery($q, ['type', 'name', 'email_address', 'mail_smtpserver']);

        foreach ($beans as $bean) {
            if ($bean->isConfigured()) {
                $name = $bean->name;

                if ($bean->type === OutboundEmail::TYPE_SYSTEM && $GLOBALS['current_user']->isAdmin()) {
                    $name = '* ' . $name;
                }

                $option = sprintf('%s <%s> [%s]', $name, $bean->email_address, $bean->mail_smtpserver);

                if ($bean->type === OutboundEmail::TYPE_SYSTEM_OVERRIDE) {
                    // Force this element to the beginning of the array.
                    $options = [$bean->id => $option] + $options;
                } else {
                    $options[$bean->id] = $option;
                }

                if (in_array($bean->type, [OutboundEmail::TYPE_SYSTEM, OutboundEmail::TYPE_SYSTEM_OVERRIDE])) {
                    $hasConfiguredDefault = true;
                }
            } else {
                // The account is not configured. Reporting that the system-override account is not configured is
                // prioritized so that the user will attempt to configure their account on his/her own. Once the user
                // has configured his/her system-override account, the reported error will be for the system account,
                // which tells the user to contact the administrator because there is nothing more he/she can do.
                if ($bean->type === OutboundEmail::TYPE_SYSTEM_OVERRIDE) {
                    $error = 'LBL_EMAIL_INVALID_USER_CONFIGURATION';
                } elseif ($bean->type === 'system' && empty($error)) {
                    $error = 'LBL_EMAIL_INVALID_SYSTEM_CONFIGURATION';
                }
            }
        }

        if (!$hasConfiguredDefault) {
            // There wasn't a system or system-override account. Something must have gone really wrong.
            $error = 'LBL_EMAIL_INVALID_SYSTEM_CONFIGURATION';
        }

        if ($error) {
            throw new SugarApiExceptionNotAuthorized($error, null, $this->getModuleName());
        }

        return $options;
    }

    /**
     * EmailAddresses is needed for enabling users to send emails to addresses, in addition to contacts, leads, etc.,
     * and for displaying email addresses along with the contacts, leads, etc., who sent or received emails.
     * EmailParticipants is needed for enabling users to manage and view the sender and recipients for emails.
     * OutboundEmail is needed for enabling users to select a configuration and send email. UserSignatures is needed for
     * enabling users to manage and use signatures in their emails.
     *
     * {@inheritdoc}
     */
    public static function getMobileSupportingModules()
    {
        return [
            'EmailAddresses',
            'EmailParticipants',
            'OutboundEmail',
            'UserSignatures',
        ];
    }
} // end class def
