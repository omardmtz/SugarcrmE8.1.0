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

use Sugarcrm\Sugarcrm\Security\Crypto\CSPRNG;

require_once 'modules/OAuthTokens/OAuthToken.php';

/**
 * SNIP data handling implementation
 */
class SugarSNIP
{
    // Username for SNIP system user
    const SNIP_USER = 'SNIPuser';
    const OAUTH_KEY = 'SNIPOAuthKey';
    const DEFAULT_URL = 'https://ease.sugarcrm.com/';

    /**
     * Singleton instance
     * @var SugarSNIP
     */
    public static $instance;

    /**
     * Sugar configuration
     * @var array
     */
    public $config;

    /**
     * Last REST call result
     * @var mixed
     */
    public $last_result;

    /**
     * SNIP user
     * @var User
     */
    protected $user;

    /**
     * SNIP OAuth token
     * @var OAuthToken
     */
    protected $token;

    /**
     * Get instance of the SNIP client
     * @return SugarSNIP SNIP client instance
     */
    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected function __construct()
    {
        global $sugar_config;
        $this->config = $sugar_config;
        $this->setClient(new SugarHttpClient());
    }

    /**
    * Set client to talk to SNIP
    * @param SugarHttpClient $client
    */
    public function setClient(SugarHttpClient $client)
    {
        $this->client = $client;
        return $this;
    }

    protected function getKey()
    {
        return md5($this->config['unique_key'].$this->getURL());
    }

    /**
     * Generic REST call to SNIP instance
     *
     * @param string $name function to call
     * @param string $params parameters
     * @param bool $json encode params as JSON in data var or send as query?
     * @return bool Success?
     */
    public function callRest($name, $params = array(), $json = false, &$connectionfailed=false)
    {
        if(isset($params['url'])) {
            $url = $params['url'];
            unset($params['url']);
        } else {
            $url = $this->getSnipURL();
        }

        $url .= $name;
        $params["sugarkey"] = $this->config['unique_key'];
        $params["idkey"] = $this->getKey();
        if($json) {
            $postArgs = http_build_query(array('data' => json_encode($params)));
        } else {
            $postArgs = http_build_query($params);
        }
        $this->last_error = '';
        $response = $this->client->callRest($url, $postArgs);

        if(!empty($response)) {
            $result = json_decode($response);
        } else {
            $GLOBALS['log']->debug("SNIP: REST request failed");
            $this->last_error = translate($this->client->getLastError(), 'SNIP');
            $connectionfailed=true;
            return false;
        }
        $this->last_result = $result;
        if(empty($result)) {
            $this->last_error = translate('ERROR_BAD_RESULT', 'SNIP');
        }
        $GLOBALS['log']->debug(var_export($result, true));
        return is_object($result) && $result->result == 'ok';
    }

    /**
     * Returns last error that happened to SNIP
     * @return string
     */
    public function getLastError()
    {
        return $this->last_error;
    }

    /**
     * Register this instance with SNIP server
     * @return mixed server response
     */
    public function registerSnip ()
    {
        global $sugar_config;

        $connectionfailed = false;
        $admin_settings = Administration::getSettings('license');
        $license = $admin_settings->settings['license_key'];

        $snipuser = $this->getSnipUser();

        $request = array (
                        'user' => $snipuser->user_name,
                        'password' => '',
                        'client_api_url' => $this->getURL(),
                        'license' => $license,
            );
        $token = $this->getSnipToken();
        if(!empty($token)) {
            $consumer = $this->getSnipConsumer();
            $request['oauth_token'] = $token->token;
            $request['oauth_secret'] = $token->secret;
            $request['consumer_key'] = $consumer->c_key;
            $request['consumer_secret'] = $consumer->c_secret;
        }

        $response = $this->callRest('register', $request, true, $connectionfailed);

        if ($connectionfailed)
            return false;
        else {
            if (is_object($this->last_result)  && $this->last_result->result == 'ok' && property_exists($this->last_result,'email')) {
                $this->setSnipEmail($this->last_result->email);
            }

            return $this->last_result;
        }
    }

    /**
     * Set SNIP email address
     * @param string $email
     */
    public function setSnipEmail($email)
    {
        $admin = BeanFactory::newBean('Administration');
        $admin->saveSetting('snip', 'email', $email);
        $admin->saveSetting('snip', 'key', $this->getKey());
        return $this;
    }

    /**
     * Get SNIP email address
     * @return string
     */
    public function getSnipEmail()
    {
        $admin = Administration::getSettings('snip');
        if (isset($admin->settings['snip_email']))
            return $admin->settings['snip_email'];
        return '';
    }

    /**
     * Unregister SNIP from server
     * @return mixed server response
     */
    public function unregisterSnip ()
    {
        global $sugar_config;

        $connectionfailed = false;
        $snipuser = $this->getSnipUser();
        $request = array (
                        'user' => $snipuser->user_name,
        // still get the hash because of old instances, see bug 56376
                        'password' => $snipuser->user_hash
        );
        $consumer = $this->getSnipConsumer();
        if(!empty($consumer)) {
            $request['consumer_key'] = $consumer->c_key;
            $request['consumer_secret'] = $consumer->c_secret;
        }
        $response = $this->callRest('unregister', $request, true, $connectionfailed);

        if ($connectionfailed)
            return false;
        else {
            if ($this->last_result->result == 'ok') {
                $this->setSnipEmail('');

                // change snip user's password for security purposes
                $user = $this->getSnipUser();
                $token = $this->deleteSnipTokens($user);
                $user->user_hash = strtolower(md5(time().mt_rand()));
                $user->save();
            }

            return $this->last_result;
        }
    }

    /**
    * Generate the url that the user can visit to purchase SNIP.
    * @return string the generated url
    */
    public function createPurchaseURL($snipuser)
    {
        // NOT ACTIVE right now
        global $sugar_config;
        $msg=base64_encode(json_encode(array('unique_key' => $this->config['unique_key'],
                                               'snipuser'   => $snipuser->user_name,
                                               'password'   => $snipuser->user_hash)));
        return "localhost:8080/purchaseSnip?info=$msg";
    }

    /**
     * Get instance callback URL
     * @return string
     */
    public function getURL()
    {
        return rtrim($this->config['site_url'],'/').'/service/v4/rest.php';
    }

    /**
     * Set SNIP instance URL.
     * @param string $url
     */
    public function setSnipURL($url)
    {
        $cfg = new Configurator();
        $cfg->config['snip_url']=$url;
        $cfg->handleOverride();
        $this->config['snip_url'] = $url;
        return $this;
    }

    /**
     * Get SNIP instance URL
     * @return string
     */
    public function getSnipURL()
    {
        if(!isset($this->config['snip_url'])) {
            return self::DEFAULT_URL;
        }
        return $this->config['snip_url'];
    }

    /**
     * Get result of the last REST call
     * @return mixed
     */
    public function getLastResult()
    {
        return $this->last_result;
    }

    /**
     * Check if SNIP interface is active
     * @return bool
     */
    public function isActive()
    {
        return $this->getSnipURL() != '';
    }

    /**
    * Get status of the SNIP installation
    * @return array Returns an associative array ('status'=>string, 'message'=>string|null), with 'status' as one of the following:
    *  - purchased  (instance has snip license)
    *  - notpurchased     (instance does not have snip license)
    *  - down (snip server unresponsive)
    *  - purchased_error (instance has snip license, server is not down, but server detects something is wrong).
    * Iff 'status' is 'purchased_error', 'message' will be the error returned by the server. Otherwise $message will be NULL.
    */
    public function getStatus()
    {
        //if inactive,
        if(!$this->isActive())
            return array('status'=>'notpurchased','message'=>null);

        $connectionfailed=false;
        $this->callRest('status',false,$json=false,$connectionfailed);

        //check if server is down
        if ($connectionfailed || !is_object($this->last_result) || $this->last_result->result!='ok' && $this->last_result->result!='instance not found'){
            //check to see if we haven't enabled snip (in which case show the welcome screen)
            $email = $this->getSnipEmail();
            if (empty($email)){
                return array('status'=>'notpurchased','message'=>null);
            }

            //we have enabled snip - show the error screen
            return array('status'=>'down','message'=>null);
        }

        //server is up but unable to ping back
        if ($this->last_result->result == 'ping failed')
            return array('status'=>'pingfailed','message'=>null);

        //server is up but snip is not purchased
        if ($this->last_result->result == 'instance not found')
            return array('status'=>'notpurchased','message'=>null);

        //server is up, snip is purchased. check if status is good
        if (isset($this->last_result->status) && $this->last_result->status=='success')
            return array('status'=>'purchased','message'=>null);

        //server is up, snip is purchased, but status is not good. return error message.
        if (!isset($this->last_result->status) || empty($this->last_result->status))
            return array('status'=>'purchased_error','message'=>'');
        else
            return array('status'=>'purchased_error','message'=>$this->last_result->status);
    }

    /**
     * Get consumer key belonging to SNIP
     * @return OAuthKey
     */
    protected function getSnipConsumer()
    {
        $consumer = OAuthKey::fetchKey(self::OAUTH_KEY);
        if(empty($consumer)) {
            $provider = new Zend_Oauth_Provider();
            $consumer = BeanFactory::newBean('OAuthKeys');
            $consumer->c_key = self::OAUTH_KEY;
            $consumer->c_secret = bin2hex($provider->generateToken(16));
            $consumer->name = self::OAUTH_KEY;
            $consumer->description = translate('LBL_SNIP_KEY_DESC', 'SNIP');
            $consumer->save();
        }
        return $consumer;
    }

    /**
     * Get OAuth token for SNIP user
     * @return OAuthToken
     */
    protected function getSnipToken()
    {
        if(empty($this->token)) {
            $user = $this->getSnipUser();
            if(!empty($user->authenticate_id)) {
                $this->token = OAuthToken::load($user->authenticate_id);
            }
            if(empty($this->token)) {
                $this->token = $this->createSnipToken($user);
            }
        }
        return $this->token;
    }

    /**
     * Create oauth token for the SNIP user
     * @param User $user
     */
    protected function createSnipToken($user)
    {
        $consumer = $this->getSnipConsumer();
        $token = OAuthToken::createAuthorized($consumer, $user);
        $user->authenticate_id = $token->token;
        $user->save();
        return $token;
    }

    /**
     * Create oauth token for the SNIP user
     * @param User $user
     */
    protected function deleteSnipTokens($user)
    {
        $consumer = $this->getSnipConsumer();
        if(!empty($consumer)) {
            OAuthToken::deleteByConsumer($consumer->id);
        }
        OAuthToken::deleteByUser($user->id);
    }

    /**
     * Create user to use for SNIP imports
     * @return User
     */
    protected function createSnipUser()
    {
        $user = BeanFactory::newBean('Users');
        $user->user_name = self::SNIP_USER;
        $user->title = translate('LBL_SNIP_USER_DESC', 'SNIP');
        $user->description = $user->title;
        $user->first_name = "";
        $user->last_name = $user->title;
        $user->status='Reserved';
        $user->receive_notifications = 0;
        $user->is_admin = 0;
        $random = CSPRNG::getInstance()->generate(32, true);
        $user->authenicate_id = md5($random);
        $user->user_hash = User::getPasswordHash($random);
        $user->default_team = '1';
        $user->created_by = '1';
        $user->external_auth_only = 1;
        $user->save();
        // create oauth token
        $this->createSnipToken($user);
        return $user;
    }

    /**
     * Get user used for SNIP imports
     * @return User
     */
    public function getSnipUser()
    {
        if($this->user) {
            return $this->user;
        }

        /** @var User $user */
        $user = BeanFactory::newBean('Users');
        $id = $user->retrieve_user_id(self::SNIP_USER);

        if (!$id) {
            return $this->createSnipUser();
        }

        if ($user->retrieve($id)) {
            $user->rehashPassword(CSPRNG::getInstance()->generate(32, true));
            $this->user = $user;
        }

        return $user;
    }

    /**
     * Assign the email to proper user
     * @param Email $email
     * @param string $username
     */
    protected function assignUser($email, $username = null)
    {
        $user = BeanFactory::newBean('Users');
        // if sugar_config['snip']['assign_ignore_email'] is set, assign everything to one user
        // which will be specified below
        if(empty($GLOBALS['sugar_config']['snip']['assign_ignore_email'])) {
	        foreach($email->all_addrs as $addr) {
	        	$iusr = $user->retrieve_by_email_address($addr);
				if(!empty($iusr) && !empty($user->id)) {
					$email->assigned_user_id = $user->id;
					return;
				}
	        }
        }
    }

    /**
     * Imports an email from the SNIP serice
     *
     * @param array $email
     */
    public function importEmail($email)
    {
        global $current_user;

        if(!$email['message']['message_id']) {
            // messages should have IDs
            $GLOBALS['log']->error("SNIP: message has no ID, can't import");
            return;
        }
        $e = BeanFactory::newBean('Emails');
        $e->retrieve_by_string_fields(array("message_id" => $email['message']['message_id']));
        if(!empty($e->id)) {
            $GLOBALS['log']->debug("SNIP: Duplicate ID {$email['message']['message_id']} - not importing");
            return;
        }

        $e->id = create_guid();
        $e->new_with_id = true;

        // Don't assign the email to the current user by default.
        $e->assigned_user_id = null;

        //Can't use sugar_bean field definition to determine which fields to import.
        $copyFields = array('from_name','description','description_html','to_addrs','cc_addrs','bcc_addrs','date_sent', 'message_id', 'subject');
        foreach ($copyFields as $field)
        {
            if(isset($email['message'][$field])) {
                $e->$field = $email['message'][$field];
            } else {
                $e->$field = '';
            }
        }
        // preserve name because bean cleanup can strip <>
        $from_name = $e->from_addr_name = $e->from_name;
        $from = $this->splitEmailAddress($e, $e->from_name);
        $e->from_addr = $from["email"];
        $e->from_name = $from["name"];
        $e->name = $e->subject;
        $e->date_sent = gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime($e->date_sent));
        $e->type = 'inbound';
        $e->status = 'unread';
        $e->state = Email::STATE_ARCHIVED;
        $e->to_addrs_names = $e->to_addrs;
        $e->cc_addrs_names = $e->cc_addrs;
        $e->bcc_addrs_names = $e->bcc_addrs;
        $e->state = Email::STATE_ARCHIVED;

        $addrs = explode(',',$e->to_addrs.",".$e->cc_addrs.",".$e->bcc_addrs);
        $e->all_addrs = array();
    	foreach($addrs as $addr) {
    		if(empty($addr)) continue;
    		$addr = $this->splitEmailAddress($e, $addr);
    		if(!empty($addr["email"])) {
        		$e->all_addrs[] = $addr["email"];
    		}
    	}
        if(!empty($e->from_addr)) {
        	array_unshift($e->all_addrs, $e->from_addr);
        }

        if(empty($e->description) && !empty($e->description_html)) {
            // html-only mail - provide plaintext if possible
            $e->description = strip_tags($e->description_html);
        }

        // assign to proper user
        if(!empty($e->all_addrs)) {
        	$this->assignUser($e, $email['user']);
        }
        // For snipLite, use Global team
        $e->team_id = $e->default_team = '1';
        self::assignUserTeam($e, $e->assigned_user_id);

        $e->call_custom_logic("before_email_import");
        // If custom logic cleared the object, skip it
        if(empty($e->id)) return;
        $e->save(FALSE);
        $e->from_addr_name = $from_name;
        // Object creation hook
        if(!empty($e->all_addrs)) {
        	$this->createObject($e);
        }

        //Process attachments
        if(isset($email['message']['attachments']) && count($email['message']['attachments'])) {
            foreach ($email['message']['attachments'] as $attach)
            {
                $this->processEmailAttachment($attach,$e);
            }
        }

        // Relate records
        if(!empty($e->subject)) {
            $this->relateRecords($e);
        }
    }

    /**
     * Split email address into name & address part
     * @param Email $email
     * @param string $addr
     * @return array
     */
    protected function splitEmailAddress($email, $addr)
    {
    	$email = $email->emailAddress->_cleanAddress($addr);
		$name = trim(str_replace(array($email, '<', '>', '"', "'"), '', $addr));
		return array("name" => $name, "email" => strtolower($email));
    }

    /**
     * Create objects from createdef definitions
     * Example definition:
     * <code>
     * $createdef['email@host.com']['Contacts'] = array(
     * 		'fields' => array(
     * 			'email1' => '{from_addr}',
     * 			'last_name' => '{from_name}',
     * 			'description' => 'created from {subject}',
     * 			'lead_source' => 'Email',
     * 		),
     * );
     * </code>
     * Supported variables:
     * - from
     * - from_addr
     * - from_name
     * - subject
     * - date
     * - description
     * - description_html
     * - message_id
     * - email_id
     * @param Email $email
     */
    protected function createObject($email)
    {
    	if(!SugarAutoLoader::existing('custom/modules/SNIP/createdefs.php')) {
    		return false;
    	}
    	$createdef = array();
		include 'custom/modules/SNIP/createdefs.php';
		$emaildata = array();
		foreach(array("subject", "description", "description_html", "message_id", "from_addr", "from_name") as $prop) {
			$emaildata["{".$prop."}"] = $email->$prop;
		}
		$emaildata["{from}"] = to_html($email->from_addr_name);
		$emaildata["{date}"] = $email->date_sent;
		$emaildata["{email_id}"] = $email->id;


    	foreach($email->all_addrs as $cleanaddr) {
			if(!isset($createdef[$cleanaddr])) {
				continue;
			}
			foreach($createdef[$cleanaddr] as $module => $data) {
				//
				$obj = BeanFactory::newBean($module);
				if(!$obj) {
					$GLOBALS['log']->error("Unable to create bean for module $module");
					continue;
				}
				// instantiate the data
				foreach($data["fields"] as $key => $value) {
					$obj->$key = str_replace(array_keys($emaildata), array_values($emaildata), $value);
				}
                // special case for Opportunity
                if ( $obj instanceof Opportunity && empty($obj->date_closed) )
                {
                    $obj->date_closed = TimeDate::getInstance()->getNow()->asDbDate();
                }
				// save
				$obj->save();
				// associate email to new object
				if(empty($obj->id)) continue; // save failed

                $linkName = $email->findEmailsLink($obj);

                if ($obj->load_relationship($linkName)) {
                    $obj->$linkName->add($email);
	            }
			}
    	}
    	return true;
    }

    /**
    * Assign user's private team to an email
    * @param SugarBean $email Email object
    * @param string $userid User ID
    */
    static function assignUserTeam($email, $userid)
    {
        if(empty($userid)) return null;

        $teamid = User::staticGetPrivateTeamID($userid);
        if(empty($teamid)) return null;

        if(empty($email->teams)){
            $email->load_relationship('teams');
        }
        $GLOBALS['log']->debug("Assigning {$email->id} to user $userid team $teamid");
        $email->teams->add($teamid, array(), false);
        return $teamid;
    }

    /**
    * Save a snip email attachment and associated it to a parent email.  Content is base64 encoded.
    *
    */
    protected function processEmailAttachment($data, $email)
    {
        if (substr($data['filename'], - 4) === '.ics') {
            $ic = new iCalendar();
            try {
                $ic->parse(base64_decode($data['content']));
                $ic->createSugarEvents($email);
            } catch(Exception $e) {
                $GLOBALS['log']->info("Could not process calendar attachment: ".$e->getMessage());
            }
        } else {
            $this->createNote($data, $email);
        }
    }

    /**
    * Create a new Note object
    * @param array $data Note data
    * @param Email $email parent email
    */
    protected function createNote($data, $email)
    {
        $upload_file = new UploadFile('uploadfile');
        $decodedFile = base64_decode($data['content']);
        $upload_file->set_for_soap($data['filename'], $decodedFile);
        $ext_pos = strrpos($upload_file->stored_file_name, ".");
        $upload_file->file_ext = substr($upload_file->stored_file_name, $ext_pos + 1);

        $note = BeanFactory::newBean('Notes');
        $note->id = create_guid();
        $note->new_with_id = true;
        if (in_array($upload_file->file_ext, $this->config['upload_badext'])) {
            $upload_file->stored_file_name .= ".txt";
            $upload_file->file_ext = "txt";
        }

        $note->filename = $upload_file->get_stored_file_name();
        if(isset($data['type'])) {
            $note->file_mime_type = $data['type'];
        } else {
            $note->file_mime_type = $upload_file->getMimeSoap($note->filename);
        }

        $note->team_id = $email->team_id;
        $note->team_set_id = $email->team_set_id;
        $note->assigned_user_id = $email->assigned_user_id;
        $note->email_type = 'Emails';
        $note->email_id = $email->id;
        $note->name = $note->filename;

        // Move the file before saving so that the file size is captured during save.
        $upload_file->final_move($note->id);
        $note->save();
    }

    /**
     * Relate records to this email
     * @param Email $e
     */
    protected function relateRecords($e)
    {
        // relate a case
        $case = BeanFactory::newBean('Cases');
        $subj = str_replace("%1", '(\d+)', preg_quote($case->getEmailSubjectMacro(), "#"));
        if(preg_match("#$subj#", $e->subject, $match) && !empty($match[1])) {
            $caseid = $match[1];
            $GLOBALS['log']->info("Trying to link to case $caseid");
            $case->retrieve_by_string_fields(array("case_number" => $caseid));
            if(!empty($case->id)) {
                $case->load_relationship("emails");
                $case->emails->add($e);
            }
        }
        // allow custom stuff
        $e->call_custom_logic("after_email_import");
    }
}
