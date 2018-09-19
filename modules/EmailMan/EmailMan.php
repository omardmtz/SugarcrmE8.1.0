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


class EmailMan extends SugarBean{
	var $id;
	var $deleted;
	var $date_created;
	var $date_modified;
	var $module;
	var $module_id;
	var $marketing_id;
	var $campaign_id;
	var $user_id;
	var $list_id;
	var $invalid_email;
	var $from_name;
	var $from_email;
	var $in_queue;
	var $in_queue_date;
	var $template_id;
	var $send_date_time;
	var $table_name = "emailman";
	var $object_name = "EmailMan";
	var $module_dir = "EmailMan";
	var $send_attempts;
	var $related_id;
	var $related_type;
	var $test=false;
	var $notes_array = array();
    var $verified_email_marketing_ids =  array();
	function toString(){
		return "EmailMan:\nid = $this->id ,user_id= $this->user_id module = $this->module , related_id = $this->related_id , related_type = $this->related_type ,list_id = $this->list_id, send_date_time= $this->send_date_time\n";
	}

    // This is used to retrieve related fields from form posts.
	var $additional_column_fields = array();


	public function __construct() {
		parent::__construct();
		$this->disable_row_level_security=true;

	}

	var $new_schema = true;

    public function create_new_list_query($order_by, $where, $filter = array(), $params = array(), $show_deleted = 0, $join_type = '', $return_array = false, $parentbean = null, $singleSelect = false, $ifListForExport = false)
    {
		$query = array('select' => '', 'from' => '', 'where' => '', 'order_by' => '');



		$query['select'] = "SELECT $this->table_name.* ,
					campaigns.name as campaign_name,
					email_marketing.name as message_name,
					(CASE related_type
						WHEN 'Contacts' THEN ".$this->db->concat('contacts', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Leads' THEN ".$this->db->concat('leads', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Accounts' THEN accounts.name
						WHEN 'Users' THEN ".$this->db->concat('users', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Prospects' THEN ".$this->db->concat('prospects', array('first_name', 'last_name'), '&nbsp;')."
					END) recipient_name";
		$query['from'] = "	FROM $this->table_name
					LEFT JOIN users ON users.id = $this->table_name.related_id and $this->table_name.related_type ='Users'
					LEFT JOIN contacts ON contacts.id = $this->table_name.related_id and $this->table_name.related_type ='Contacts'
					LEFT JOIN leads ON leads.id = $this->table_name.related_id and $this->table_name.related_type ='Leads'
					LEFT JOIN accounts ON accounts.id = $this->table_name.related_id and $this->table_name.related_type ='Accounts'
					LEFT JOIN prospects ON prospects.id = $this->table_name.related_id and $this->table_name.related_type ='Prospects'
					LEFT JOIN prospect_lists ON prospect_lists.id = $this->table_name.list_id
                    LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = $this->table_name.related_id and $this->table_name.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0
					LEFT JOIN campaigns ON campaigns.id = $this->table_name.campaign_id
					LEFT JOIN email_marketing ON email_marketing.id = $this->table_name.marketing_id ";

                $where_auto = " $this->table_name.deleted=0";

		$this->addVisibilityFrom($query['from'], array('where_condition' => true));

        if($where != "")
			$query['where'] = "WHERE $where AND ".$where_auto;
		else
			$query['where'] = "WHERE ".$where_auto;

		$this->addVisibilityWhere($query['where'], array('where_condition' => true));

    	if(isset($params['group_by'])) {
            $query['group_by'] .= " GROUP BY {$params['group_by']}";
		}

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query['order_by'] = ' ORDER BY ' . $order_by;
        }

		if ($return_array) {
			return $query;
		}

		return  $query['select'] . $query['from'] . $query['where']. $query['order_by'];

    } // if

    function create_queue_items_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean=null, $singleSelect = false) {

		if ($return_array) {
			return parent::create_new_list_query($order_by, $where,$filter,$params, $show_deleted,$join_type, $return_array,$parentbean, $singleSelect);
		}

		$query = "SELECT $this->table_name.* ,
					campaigns.name as campaign_name,
					email_marketing.name as message_name,
					(CASE related_type
						WHEN 'Contacts' THEN ".$this->db->concat('contacts', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Leads' THEN ".$this->db->concat('leads', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Accounts' THEN accounts.name
						WHEN 'Users' THEN ".$this->db->concat('users', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Prospects' THEN ".$this->db->concat('prospects', array('first_name', 'last_name'), '&nbsp;')."
					END) recipient_name";

		 $query .= " FROM $this->table_name
		            LEFT JOIN users ON users.id = $this->table_name.related_id and $this->table_name.related_type ='Users'
					LEFT JOIN contacts ON contacts.id = $this->table_name.related_id and $this->table_name.related_type ='Contacts'
					LEFT JOIN leads ON leads.id = $this->table_name.related_id and $this->table_name.related_type ='Leads'
					LEFT JOIN accounts ON accounts.id = $this->table_name.related_id and $this->table_name.related_type ='Accounts'
					LEFT JOIN prospects ON prospects.id = $this->table_name.related_id and $this->table_name.related_type ='Prospects'
					LEFT JOIN prospect_lists ON prospect_lists.id = $this->table_name.list_id
                    LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = $this->table_name.related_id and $this->table_name.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0
					LEFT JOIN campaigns ON campaigns.id = $this->table_name.campaign_id
					LEFT JOIN email_marketing ON email_marketing.id = $this->table_name.marketing_id ";

		 //B.F. #37943
		if( isset($params['group_by']) )
		{
			$group_by = str_replace("emailman", "em", $params['group_by']);
		    $query .= "INNER JOIN (select min(id) as id from emailman  em GROUP BY $group_by  ) secondary
			           on {$this->table_name}.id = secondary.id	";
		}

                $where_auto = " $this->table_name.deleted=0";

        if($where != "")
			$query .= "WHERE $where AND ".$where_auto;
		else
			$query .= "WHERE ".$where_auto;

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
        }

		return $query;

    }

	function create_list_query($order_by, $where, $show_deleted = 0){

		$query = "SELECT $this->table_name.* ,
					campaigns.name as campaign_name,
					email_marketing.name as message_name,
					(CASE related_type
						WHEN 'Contacts' THEN ".$this->db->concat('contacts', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Leads' THEN ".$this->db->concat('leads', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Accounts' THEN accounts.name
						WHEN 'Users' THEN ".$this->db->concat('users', array('first_name', 'last_name'), '&nbsp;')."
						WHEN 'Prospects' THEN ".$this->db->concat('prospects', array('first_name', 'last_name'), '&nbsp;')."
					END) recipient_name";
		$query .= "	FROM $this->table_name
					LEFT JOIN users ON users.id = $this->table_name.related_id and $this->table_name.related_type ='Users'
					LEFT JOIN contacts ON contacts.id = $this->table_name.related_id and $this->table_name.related_type ='Contacts'
					LEFT JOIN leads ON leads.id = $this->table_name.related_id and $this->table_name.related_type ='Leads'
					LEFT JOIN accounts ON accounts.id = $this->table_name.related_id and $this->table_name.related_type ='Accounts'
					LEFT JOIN prospects ON prospects.id = $this->table_name.related_id and $this->table_name.related_type ='Prospects'
					LEFT JOIN prospect_lists ON prospect_lists.id = $this->table_name.list_id
                    LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = $this->table_name.related_id and $this->table_name.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0
					LEFT JOIN campaigns ON campaigns.id = $this->table_name.campaign_id
					LEFT JOIN email_marketing ON email_marketing.id = $this->table_name.marketing_id ";

                $where_auto = " $this->table_name.deleted=0";

        if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
        }

		return $query;
	}

    function get_list_view_data()
    {
    	global $locale, $current_user;
        $temp_array = parent::get_list_view_array();

        $related_type = $temp_array['RELATED_TYPE'];
        $related_type = strtolower($related_type);
        $related_type = ucfirst($related_type);
        $related_id   = $temp_array['RELATED_ID'];

        $related_bean = BeanFactory::getBean($related_type, $related_id);
        if ($related_bean) {
            $temp_array['RECIPIENT_NAME'] = $related_bean->get_summary_text();
        }

        //also store the recipient_email address
        $query = "SELECT addr.email_address FROM email_addresses addr,email_addr_bean_rel eb WHERE eb.deleted=0 AND addr.id=eb.email_address_id AND bean_id ='". $related_id ."' AND primary_address = '1'";

        $result=$this->db->query($query);
        $row=$this->db->fetchByAssoc($result);
        if ($row)
        {
            $temp_array['RECIPIENT_EMAIL']=$row['email_address'];
        }

        $this->email1 = $temp_array['RECIPIENT_EMAIL'];
		$temp_array['EMAIL_LINK'] = $current_user->getEmailLink('email1', $this, '', '', 'ListView');

        return $temp_array;
    }


	function set_as_sent($email_address, $delete= true,$email_id=null, $email_type=null,$activity_type=null){

		global $timedate;

		$this->send_attempts++;
		$this->id = (int)$this->id;
		if($delete || $this->send_attempts > 5){

			//create new campaign log record.

			$campaign_log = BeanFactory::newBean('CampaignLog');
			$campaign_log->campaign_id=$this->campaign_id;
			$campaign_log->target_tracker_key=$this->target_tracker_key;
			$campaign_log->target_id= $this->related_id;
			$campaign_log->target_type=$this->related_type;
            $campaign_log->marketing_id=$this->marketing_id;
			//if test suppress duplicate email address checking.
			if (!$this->test) {
				$campaign_log->more_information=$email_address;
			}
			$campaign_log->activity_type=$activity_type;
			$campaign_log->activity_date=$timedate->now();
			$campaign_log->list_id=$this->list_id;
			$campaign_log->related_id= $email_id;
			$campaign_log->related_type=$email_type;
            $campaign_log->save();

			$query = "DELETE FROM emailman WHERE id = $this->id";
			$this->db->query($query);
		}else{
			//try to send the email again a day later.
			$query = 'UPDATE ' . $this->table_name . " SET in_queue='1', send_attempts='$this->send_attempts', in_queue_date=". $this->db->now() ." WHERE id = $this->id";
			$this->db->query($query);
		}
	}

    /**
     * Function finds the reference email for the campaign. Since a campaign can have multiple email templates
     * the reference email has same id as the marketing id.
     * this function will create an email if one does not exist. also the function will load these relationships leads, accounts, contacts
     * users and targets
     *
     * The Message-ID is not stored on the reference email because the reference email is a single Emails record that
     * represents N emails, all with their own Message-ID values.
     *
     * @param varchar marketing_id message id
     * @param string $subject email subject
     * @param string $body_text Email Body Text
     * @param string $body_html Email Body HTML
     * @param string $campaign_name Campaign Name
     * @param string from_address Email address of the sender, usually email address of the configured inbox.
     * @param string sender_id If of the user sending the campaign.
     * @param array  macro_nv array of name value pair, one row for each replacable macro in email template text.
     * @param string from_address_name The from address eg markeing <marketing@sugar.net>
     * @return
     */
    function create_ref_email($marketing_id,$subject,$body_text,$body_html,$campagin_name,$from_address,$sender_id,$notes,$macro_nv,$newmessage,$from_address_name) {

       global $mod_Strings, $timedate;
       $upd_ref_email=false;
       if ($newmessage or empty($this->ref_email->id)) {
           $this->ref_email = BeanFactory::newBean('Emails');
           $this->ref_email->retrieve($marketing_id, true, false);

           //the reference email should be updated when user swithces from test mode to regular mode,and, for every run in test mode, and is user
           //switches back to test mode.
           //this is to account for changes to email template.
           $upd_ref_email=(!empty($this->ref_email->id) and $this->ref_email->parent_type=='test' and $this->ref_email->parent_id=='test');
          //following condition is for switching back to test mode.
           if (!$upd_ref_email) $upd_ref_email=($this->test and !empty($this->ref_email->id) and empty($this->ref_email->parent_type) and empty($this->ref_email->parent_id));
           if (empty($this->ref_email->id) or $upd_ref_email) {
                //create email record.
                $this->ref_email->id=$marketing_id;
                $this->ref_email->date_sent = $timedate->nowDb();

                if ($upd_ref_email==false) {
                    $this->ref_email->new_with_id=true;
                }

                $this->ref_email->team_id = 1;
                $this->ref_email->to_addrs= '';
                $this->ref_email->to_addrs_ids = '';
                $this->ref_email->to_addrs_names = '';
                $this->ref_email->to_addrs_emails ='';
                $this->ref_email->type= 'campaign';
                $this->ref_email->deleted = '0';
                $this->ref_email->name = $campagin_name.': '.$subject ;
                $this->ref_email->description_html = $body_html;
                $this->ref_email->description = $body_text;
                $this->ref_email->from_addr = $from_address;
                $this->ref_email->from_addr_name = $from_address_name;
                $this->ref_email->assigned_user_id = $sender_id;
                if ($this->test) {
                    $this->ref_email->parent_type = 'test';
                    $this->ref_email->parent_id =  'test';
                } else {
                    $this->ref_email->parent_type = '';
                    $this->ref_email->parent_id =  '';
                }

                $this->ref_email->status='sent';
                $this->ref_email->state = Email::STATE_ARCHIVED;
                $retId = $this->ref_email->save();

                foreach($notes as $note) {
                    list($filename, $mime_type) = $this->getFileInfo($note);
                    $noteAudit = BeanFactory::newBean('Notes');
                    $noteAudit->email_id = $retId;
                    $noteAudit->email_type = $this->ref_email->module_dir;
                    $noteAudit->description = "[".$note->filename."] ".$mod_strings['LBL_ATTACHMENT_AUDIT'];
                    $noteAudit->name = $note->name;
                    $noteAudit->filename=$filename;
                    $noteAudit->file_mime_type=$mime_type;
                    $noteAudit->upload_id = $note->getUploadId();
                    $noteAudit_id=$noteAudit->save();
                }
            }

            //load relationships.
            $this->ref_email->load_relationship('users');
            $this->ref_email->load_relationship('prospects');
            $this->ref_email->load_relationship('contacts');
            $this->ref_email->load_relationship('leads');
            $this->ref_email->load_relationship('accounts');
       }

       if (!empty($this->related_id ) && !empty($this->related_type)) {

            //save relationships.
            switch ($this->related_type)  {
                case 'Users':
                    $rel_name="users";
                    break;

                case 'Prospects':
                    $rel_name="prospects";
                    break;

                case 'Contacts':
                    $rel_name="contacts";
                    break;

                case 'Leads':
                    $rel_name="leads";
                    break;

                case 'Accounts':
                    $rel_name="accounts";
                    break;
            }

            //required for one email per campaign per marketing message.
            $this->ref_email->$rel_name->add($this->related_id,array('campaign_data'=>serialize($macro_nv)));
       }
       return $this->ref_email->id;
    }

    /**
     * Gets filename and mime_type for a note
     * @param SugarBean $note
     * @return array
     */
    protected function getFileInfo($note)
    {
        $filename = $mime_type = '';
        if($note->object_name == 'Note') {
            if (! empty($note->file->temp_file_location) && is_file($note->file->temp_file_location)) {
                $filename = $note->file->original_file_name;
                $mimetype = $note->file->mime_type;
            } else {
                $filename = $note->id.$note->filename;
                $mimetype = $note->file_mime_type;
            }
        } elseif($note->object_name == 'DocumentRevision') { // from Documents
            $filename = $note->id.$note->filename;
            $mimetype = $note->file_mime_type;
        }
        return array($filename, $mimetype);
    }

   /**
    * The function creates a copy of email send to each target.
    *
    * @param $module
    * @param BaseMailer $mail
    */
    public function create_indiv_email($module, $mail) {
        global $timedate,
               $mod_strings;

        $email = BeanFactory::newBean('Emails');

        $email->team_id = 1;

        $email->to_addrs         = "{$module->name}&lt;{$module->email1}&gt;";
        $email->to_addrs_ids     = "{$module->id};";
        $email->to_addrs_names   = "{$module->name};";
        $email->to_addrs_emails  = "{$module->email1};";
        $email->type             = 'archived';
        $email->deleted          = '0';
        $email->name             = "{$this->current_campaign->name}: " . $mail->getHeader(EmailHeaders::Subject);
        $email->description      = $mail->getTextBody();
        $email->description_html = $mail->getHtmlBody();
        $email->from_addr        = $mail->getHeader(EmailHeaders::From)->getEmail();
        $email->assigned_user_id = $this->user_id;
        $email->parent_type      = $this->related_type;
        $email->parent_id        = $this->related_id;
        $email->date_sent = $timedate->nowDb();
        $email->status           = 'sent';
        $email->state = Email::STATE_ARCHIVED;
        $email->message_id = $mail->getHeader(EmailHeaders::MessageId);
        $retId                   = $email->save();

        foreach ($this->notes_array as $note) {
            list($filename, $mime_type) = $this->getFileInfo($note);
            // create "audit" email without duping off the file to save on disk space
            $noteAudit              = BeanFactory::newBean('Notes');
            $noteAudit->email_id = $retId;
            $noteAudit->email_type = $email->module_dir;
            $noteAudit->name        = $note->name;
            $noteAudit->description = "[{$note->filename}] {$mod_strings['LBL_ATTACHMENT_AUDIT']}";
            $noteAudit->filename=$filename;
            $noteAudit->file_mime_type=$mime_type;
            $noteAudit->upload_id = $note->getUploadId();
            $noteAudit->save();
        }

        if (!empty($this->related_id) && !empty($this->related_type)) {
            //save relationships.
            switch ($this->related_type) {
                case 'Users':
                    $rel_name = "users";
                    break;
                case 'Prospects':
                    $rel_name = "prospects";
                    break;
                case 'Contacts':
                    $rel_name = "contacts";
                    break;
                case 'Leads':
                    $rel_name = "leads";
                    break;
                case 'Accounts':
                    $rel_name = "accounts";
                    break;
            }

            if (!empty($rel_name)) {
                $email->load_relationship($rel_name);
                $email->$rel_name->add($this->related_id);
            }
        }

        return $email->id;
    }

    /*
     * Call this function to verify the email_marketing message and email_template configured
     * for the campaign. If issues are found a fatal error will be logged but processing will not stop.
     * @return Boolean Returns true if all campaign parameters are set correctly
     */
    function verify_campaign($marketing_id) {

        if (!isset($this->verified_email_marketing_ids[$marketing_id])) {
            if (!class_exists('EmailMarketing')) {

            }
            $email_marketing = BeanFactory::newBean('EmailMarketing');
            $ret=$email_marketing->retrieve($marketing_id);
            if (empty($ret)) {
                $GLOBALS['log']->fatal('Error retrieving marketing message for the email campaign. marketing_id = ' .$marketing_id);
                return false;
            }

            //verify the email template.
            if (empty($email_marketing->template_id)) {
                $GLOBALS['log']->fatal('Error retrieving template for the email campaign. marketing_id = ' .$marketing_id);
                return false;
            }

            if (!class_exists('EmailTemplate')) {

            }
            $emailtemplate = BeanFactory::newBean('EmailTemplates');

            $ret=$emailtemplate->retrieve($email_marketing->template_id);
            if (empty($ret)) {
                $GLOBALS['log']->fatal('Error retrieving template for the email campaign. template_id = ' .$email_marketing->template_id);
                return false;
            }

            if (empty($emailtemplate->subject) and empty($emailtemplate->body) and empty($emailtemplate->body_html)) {
                $GLOBALS['log']->fatal('Email template is empty. email_template_id=' .$email_marketing->template_id);
				return false;
            }

        }
        $this->verified_email_marketing_ids[$marketing_id]=1;

        return true;
    }
	function sendEmail($mail,$save_emails=1,$testmode=false){
        $success = false;
	    $this->test=$testmode;

		global $sugar_config;
		global $mod_strings;
        global $locale;
        $OBCharset = $locale->getPrecedentPreference('default_email_charset');
		$mod_strings = return_module_language( $sugar_config['default_language'], 'EmailMan');

		//get tracking entities locations.
		if (!isset($this->tracking_url)) {
			$admin = Administration::getSettings('massemailer'); //retrieve all admin settings.
		    if (isset($admin->settings['massemailer_tracking_entities_location_type']) and $admin->settings['massemailer_tracking_entities_location_type']=='2'  and isset($admin->settings['massemailer_tracking_entities_location']) ) {
				$this->tracking_url=$admin->settings['massemailer_tracking_entities_location'];
		    } else {
				$this->tracking_url=$sugar_config['site_url'];
		    }
		}

        //make sure tracking url ends with '/' character
        $strLen = strlen($this->tracking_url);
        if($this->tracking_url{$strLen-1} !='/'){
            $this->tracking_url .='/';
        }

		$module = BeanFactory::getBean($this->related_type, $this->related_id);
		if(empty($module)) {
		    return false;
		}
		$module->emailAddress->handleLegacyRetrieve($module);

        //check to see if bean has a primary email address
        if (!$this->is_primary_email_address($module)) {
            //no primary email address designated, do not send out email, create campaign log
            //of type send error to denote that this user was not emailed
            $this->set_as_sent($module->email1, true,null,null,'send error');
            //create fatal logging for easy review of cause.
            $GLOBALS['log']->fatal('Email Address provided is not Primary Address for email with id ' . $module->email1 . "' Emailman id=$this->id");
            return true;
        }

		if (!$this->valid_email_address($module->email1)) {
			$this->set_as_sent($module->email1, true,null,null,'invalid email');
			$GLOBALS['log']->fatal('Encountered invalid email address' . $module->email1 . " Emailman id=$this->id");
			return true;
		}

        if ((!isset($module->email_opt_out)
                    || ($module->email_opt_out !== 'on'
                        && $module->email_opt_out !== 1
                        && $module->email_opt_out !== '1'))
            && (!isset($module->invalid_email)
                    || ($module->invalid_email !== 'on'
                        && $module->invalid_email !== 1
                        && $module->invalid_email !== '1'))){
            $lower_email_address=strtolower($module->email1);
			//test against indivdual address.
			if (isset($this->restricted_addresses) and isset($this->restricted_addresses[$lower_email_address])) {
				$this->set_as_sent($lower_email_address, true,null,null,'blocked');
				return true;
			}
			//test against restricted domains
			$at_pos=strrpos($lower_email_address,'@');
			if ($at_pos !== false) {
				foreach ($this->restricted_domains as $domain=>$value) {
					$pos=strrpos($lower_email_address,$domain);
					if ($pos !== false && $pos > $at_pos) {
						//found
						$this->set_as_sent($lower_email_address, true,null,null,'blocked');
						return true;
					}
				}
			}

            if ($this->hasEmailBeenSent($module->email1, $this->marketing_id)) {
                // A non-Test version of this marketing email was previously sent to this Email address
                $this->set_as_sent($module->email1, true, null, null, 'blocked');
                return true;
            }

			$this->target_tracker_key=create_guid();

			//fetch email marketing.
			if (empty($this->current_emailmarketing) or !isset($this->current_emailmarketing)) {
				if (!class_exists('EmailMarketing')) {

				}

				$this->current_emailmarketing = BeanFactory::newBean('EmailMarketing');

			}
			if (empty($this->current_emailmarketing->id) or $this->current_emailmarketing->id !== $this->marketing_id) {
				$this->current_emailmarketing->retrieve($this->marketing_id);

                $this->newmessage = true;
			}
			//fetch email template associate with the marketing message.
			if (empty($this->current_emailtemplate) or $this->current_emailtemplate->id !== $this->current_emailmarketing->template_id) {
				if (!class_exists('EmailTemplate')) {

				}
				$this->current_emailtemplate = BeanFactory::newBean('EmailTemplates');

				$this->current_emailtemplate->retrieve($this->current_emailmarketing->template_id);

				//escape email template contents.
				$this->current_emailtemplate->subject=from_html($this->current_emailtemplate->subject);
				$this->current_emailtemplate->body_html=from_html($this->current_emailtemplate->body_html);
				$this->current_emailtemplate->body=from_html($this->current_emailtemplate->body);

                //FIXME: notes.email_type should be EmailTemplates
                $stmt = $this->db->getConnection()->executeQuery(
                    'SELECT id FROM notes WHERE email_id = ? AND deleted = 0',
                    [$this->current_emailtemplate->id]
                );

				// cn: bug 4684 - initialize the notes array, else old data is still around for the next round
				$this->notes_array = array();
                while ($noteId = $stmt->fetchColumn()) {
                    $noteTemplate = BeanFactory::getBean('Notes', $noteId);
					$this->notes_array[] = $noteTemplate;
				}
			}

			// fetch mailbox details..
			if(empty($this->current_mailbox)) {
				if (!class_exists('InboundEmail')) {

				}
				$this->current_mailbox = BeanFactory::newBean('InboundEmail');
                $this->current_mailbox->disable_row_level_security = true;
			}
			if (empty($this->current_mailbox->id) or $this->current_mailbox->id !== $this->current_emailmarketing->inbound_email_id) {
				$this->current_mailbox->retrieve($this->current_emailmarketing->inbound_email_id);
				//extract the email address.
				$this->mailbox_from_addr=$this->current_mailbox->get_stored_options('from_addr','nobody@example.com',null);
			}

			// fetch campaign details..
			if (empty($this->current_campaign)) {
				if (!class_exists('Campaign')) {

				}
				$this->current_campaign = BeanFactory::newBean('Campaigns');
			}
			if (empty($this->current_campaign->id) or $this->current_campaign->id !== $this->current_emailmarketing->campaign_id) {
				$this->current_campaign->retrieve($this->current_emailmarketing->campaign_id);

				//load defined tracked_urls
				$this->current_campaign->load_relationship('tracked_urls');
				$query_array=$this->current_campaign->tracked_urls->getQuery(true);
				$query_array['select']="SELECT tracker_name, tracker_key, id, is_optout ";
				$result=$this->current_campaign->db->query(implode(' ',$query_array));

				$this->has_optout_links=false;
				$this->tracker_urls=array();
				while (($row=$this->current_campaign->db->fetchByAssoc($result)) != null) {
					$this->tracker_urls['{'.$row['tracker_name'].'}']=$row;
					//has the user defined opt-out links for the campaign.
					if ($row['is_optout']==1) {
						$this->has_optout_links=true;
					}
				}
			}

            try {
                $from = new EmailIdentity($this->mailbox_from_addr, $this->current_emailmarketing->from_name);
                $mail->setHeader(EmailHeaders::From, $from);
                $mail->setHeader(EmailHeaders::Sender, $from);

                //CL - Bug 25256 Check if we have a reply_to_name/reply_to_addr value from the email marketing table.  If so use email marketing entry; otherwise current mailbox (inbound email) entry
                $replyToAddr = $this->current_emailmarketing->reply_to_addr;

                if (empty($replyToAddr)) {
                    $replyToAddr = $this->current_mailbox->get_stored_options('reply_to_addr', $this->mailbox_from_addr, null);
                }

                $replyToName = $this->current_emailmarketing->reply_to_name;

                if (empty($replyToName)) {
                    $this->current_mailbox->get_stored_options('reply_to_name', $this->current_emailmarketing->from_name, null);
                }

                $mail->setHeader(EmailHeaders::ReplyTo); // resets Reply-To to null

                if (!empty($replyToAddr)) {
                    $mail->setHeader(EmailHeaders::ReplyTo, new EmailIdentity($replyToAddr, $replyToName));
                }

                $mail->setHeader("X-CampTrackID", $this->target_tracker_key);

                //parse and replace bean variables.
                $macro_nv   = array();
                $focus_name = 'Contacts';
                if ($module->module_dir == 'Accounts') {
                    $focus_name = 'Accounts';
                }


                $template_data = $this->current_emailtemplate->parse_email_template(array('subject'  => $this->current_emailtemplate->subject,
                                                                                          'body_html'=> $this->current_emailtemplate->body_html,
                                                                                          'body'     => $this->current_emailtemplate->body,
                                                                                    )
                    , $focus_name, $module
                    , $macro_nv);

                //add email address to this list.
                $macro_nv['sugar_to_email_address'] = $module->email1;
                $macro_nv['email_template_id']      = $this->current_emailmarketing->template_id;

                //parse and replace urls.
                //this is new style of adding tracked urls to a campaign.
                $tracker_url_template  = $this->tracking_url . 'index.php?entryPoint=campaign_trackerv2&track=%s' . '&identifier=' . $this->target_tracker_key;
                $removeme_url_template = $this->tracking_url . 'index.php?entryPoint=removeme&identifier=' . $this->target_tracker_key;
                $template_data         = $this->current_emailtemplate->parse_tracker_urls($template_data, $tracker_url_template, $this->tracker_urls, $removeme_url_template);

                $mail->clearRecipients();
                $mail->addRecipientsTo(new EmailIdentity($module->email1, $module->name));

                //refetch strings in case they have been changed by creation of email templates or other beans.
                $mod_strings = return_module_language($sugar_config['default_language'], 'EmailMan');

                $subject = $template_data['subject'];

                if ($this->test) {
                    $subject = $mod_strings['LBL_PREPEND_TEST'] . $template_data['subject'];
                }

                $mail->setSubject($subject);

                //check if this template is meant to be used as "text only"
                $text_only = false;
                if (isset($this->current_emailtemplate->text_only) && $this->current_emailtemplate->text_only) {
                    $text_only = true;
                }
                //if this template is textonly, then just send text body.  Do not add tracker, opt out,
                //or perform other processing as it will not show up in text only email
                if ($text_only) {
                    $mail->setTextBody($template_data['body']);
                    $mail->setHtmlBody();
                } else {
                    $textBody = $template_data['body'];
                    $htmlBody = $template_data['body_html'];

                    if (!empty($tracker_url)) {
                        $htmlBody = str_replace('TRACKER_URL_START', "<a href='" . $tracker_url . "'>", $htmlBody);
                        $htmlBody = str_replace('TRACKER_URL_END', "</a>", $htmlBody);
                        $textBody .= "\n" . $tracker_url;
                    }

                    //do not add the default remove me link if the campaign has a trackerurl of the opotout link
                    if ($this->has_optout_links == false) {
                        $htmlBody .= "<br /><span style='font-size:0.8em'>{$mod_strings['TXT_REMOVE_ME']} <a href='" . $this->tracking_url . "index.php?entryPoint=removeme&identifier={$this->target_tracker_key}'>{$mod_strings['TXT_REMOVE_ME_CLICK']}</a></span>";
                        $textBody .= "\n\n\n{$mod_strings['TXT_REMOVE_ME_ALT']} " . $this->tracking_url . "index.php?entryPoint=removeme&identifier=$this->target_tracker_key";
                    }

                    // cn: bug 11979 - adding single quote to comform with HTML email RFC
                    $htmlBody .= "<br /><img alt='' height='1' width='1' src='{$this->tracking_url}index.php?entryPoint=image&identifier={$this->target_tracker_key}' />";

                    $mail->setTextBody($textBody);
                    $mail->setHtmlBody(wordwrap($htmlBody, 900));
                }

                $mail->clearAttachments(); // need to clear the attachments because the mailer is reused for different emails

                // cn: bug 4684, handle attachments in email templates.
                if (!empty($this->notes_array)) {
                    foreach($this->notes_array as $note) {
                        $attachment = AttachmentPeer::attachmentFromSugarBean($note);
                        $mail->addAttachment($attachment);
                    }
                }

                $mail->send();
                $success = true;

                $email_id = null;
                if ($save_emails == 1) {
                    $email_id = $this->create_indiv_email($module, $mail);
                } else {
                    //find/create reference email record. all campaign targets reveiving this message will be linked with this message.
                    $decodedFromName = mb_decode_mimeheader($this->current_emailmarketing->from_name);
                    $fromAddressName = "{$decodedFromName} <{$this->mailbox_from_addr}>";

                    $email_id         = $this->create_ref_email($this->marketing_id,
                                                                $this->current_emailtemplate->subject,
                                                                $this->current_emailtemplate->body,
                                                                $this->current_emailtemplate->body_html,
                                                                $this->current_campaign->name,
                                                                $this->mailbox_from_addr,
                                                                $this->user_id,
                                                                $this->notes_array,
                                                                $macro_nv,
                                                                $this->newmessage,
                                                                $fromAddressName
                    );
                    $this->newmessage = false;
                }

                $this->set_as_sent($module->email1, true, $email_id, 'Emails', 'targeted');
            } catch (MailerException $me) {
                //log send error. save for next attempt after 24hrs. no campaign log entry will be created.
                $this->set_as_sent($module->email1,false,null,null,'send error');
                $GLOBALS['log']->error("Emailman::sendMail - Campaign Email Send Error:" . $me->getMessage());
            }
		}else{
            $this->target_tracker_key=create_guid();

			if (isset($module->email_opt_out) && ($module->email_opt_out === 'on' || $module->email_opt_out == '1' || $module->email_opt_out == 1)) {
				$this->set_as_sent($module->email1,true,null,null,'removed');
			} else {
				if (isset($module->invalid_email) && ($module->invalid_email == 1 || $module->invalid_email == '1')) {
					$this->set_as_sent($module->email1,true,null,null,'invalid email');
				} else {
					$this->set_as_sent($module->email1,true, null,null,'send error');
				}
			}
		}

		return $success;
	}

	/*
	 * Validates the passed email address.
	 * Limitations of this algorithm: does not validate email addressess that end with .meuseum
	 *
	 */
	function valid_email_address($email_address) {

		$email_address=trim($email_address);
		if (empty($email_address)) {
			return false;
		}

		$pattern='/[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Za-z]{2,}$/i';
		$ret=preg_match($pattern, $email_address);
		if ($ret===false or $ret==0) {
			return false;
		}
		return true;
	}

    /*
     * This function takes in the given bean and searches for a related email address
     * that has been designated as primary.  If one is found, true is returned
     * If no primary email address is found, then false is returned
     *
     */
    function is_primary_email_address($bean){
        $email_address=trim($bean->email1);

        if (empty($email_address)) {
            return false;
        }
        //query for this email address rel and see if this is primary address
        $primary_qry = "select email_address_id from email_addr_bean_rel where bean_id = '".$bean->id."' and email_addr_bean_rel.primary_address=1 and deleted=0";
        $res = $bean->db->query($primary_qry);
        $prim_row=$this->db->fetchByAssoc($res);
        //return true if this is primary
        if (!empty($prim_row)) {
            return true;
        }
        return false;

     }

    /**
     * Actuall deletes the emailman record
     * @param int $id
     */
    public function mark_deleted($id)
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = ? ";
        $conn = $this->db->getConnection();
        $conn->executeQuery($query, array($id));
    }

    /**
     * Determine whether a non-Test email has already been sent to the supplied Email Address
     * as part of the supplied EmailMarketing instance
     *
     * @param string $email Email Address
     * @param string $marketingId EmailMarketing Id
     * @return bool  true if a Non-Test Email Has Already Been Sent
     * @throws SugarQueryException
     */
    protected function hasEmailBeenSent($email, $marketingId)
    {
        $q = new SugarQuery();
        $q->select(array('id'));
        $q->from(BeanFactory::newBean('CampaignLog'));
        $q->where()->queryAnd()
            ->equals('more_information', $email)
            ->equals('activity_type', 'targeted')
            ->equals('marketing_id', $marketingId)
            ->equals('deleted', 0);
        $q->limit(1);
        $rows = $q->execute();
        return !empty($rows);
    }
}
