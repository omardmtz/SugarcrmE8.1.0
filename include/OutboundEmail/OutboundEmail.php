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

use Sugarcrm\Sugarcrm\Security\Crypto\Blowfish;

/**
 * Outbound email management
 * @api
 */
class OutboundEmail extends SugarBean
{
    const TYPE_USER = 'user';
    const TYPE_SYSTEM = 'system';
    const TYPE_SYSTEM_OVERRIDE = 'system-override';

    /**
     * @var bool
     */
    public $importable = false;

    /**
     * @var string
     */
    public $module_dir = 'OutboundEmail';

    /**
     * @var string
     */
    public $module_name = 'OutboundEmail';

    /**
     * @var bool
     */
    public $new_schema = true;

    /**
     * @var string
     */
    public $object_name = 'OutboundEmail';

    /**
     * @var string
     */
    public $table_name = 'outbound_email';

    protected $adminSystemFields = array(
        'mail_smtptype',
        'mail_sendtype',
        'mail_smtpserver',
        'mail_smtpport',
        'mail_smtpauth_req',
        'mail_smtpssl',
        'mail_smtpuser',
        'mail_smtppass',
    );

    protected $userSystemFields = array(
        'mail_smtptype',
        'mail_sendtype',
        'mail_smtpserver',
        'mail_smtpport',
        'mail_smtpauth_req',
        'mail_smtpssl',
    );

    public $name;
    public $type; // system, system-override, or user
    public $user_id; // owner
    public $mail_sendtype;
    public $mail_smtptype;
    public $mail_smtpserver;
    public $mail_smtpport = 465;
    public $mail_smtpuser;
    public $mail_smtppass;
    public $mail_smtpauth_req;
    public $mail_smtpssl = 1;
    public $mail_smtpdisplay; // calculated value, not in DB

    /**
     * @var null|OutboundEmail
     */
    protected static $sysMailerCache = null;

    protected $module_key = 'OutBoundEmail';

    /**
     * Adds the {@link OutboundEmailVisibility} visibility strategy.
     *
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->addVisibilityStrategy('OutboundEmailVisibility');
    }

    /**
     * {@inheritdoc}
     */
    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
            default:
                return false;
        }
    }

    /**
     * The owner field is {@link OutboundEmail::$user_id}.
     *
     * {@inheritdoc}
     */
    public function getOwnerField()
    {
        return 'user_id';
    }

    /**
     * {@inheritdoc}
     */
    public function isOwner($user_id)
    {
        if (!$this->isUpdate() && $GLOBALS['current_user']->id === $user_id) {
            return true;
        }

        $ownerField = $this->getOwnerField();

        return isset($this->$ownerField) && $this->$ownerField === $user_id;
    }

    /**
     * Returns `true` if all fields, which are necessary to connect to the server, are completed.
     *
     * @return bool
     */
    public function isConfigured()
    {
        if (empty($this->mail_smtpserver)) {
            return false;
        } elseif ($this->mail_smtpauth_req) {
            return !empty($this->mail_smtpuser) && !empty($this->mail_smtppass);
        }

        return true;
    }

    /**
	 * Retrieves the mailer for a user if they have overriden the username
	 * and password for the default system account.
	 *
	 * @param String $user_id
     * @return OutboundEmail|null
	 */
    public function getUsersMailerForSystemOverride($user_id)
	{
        $email = new self();
        $email->disable_row_level_security = true;
        $email->retrieveByCriteria(
            array('user_id' => $user_id, 'type' => static::TYPE_SYSTEM_OVERRIDE),
            array('name' => 'ASC')
        );

        return $email->id ? $email : null;
	}

    /**
     * Duplicate the system account for a user, setting new parameters specific to the user. Simply update and return
     * the user's system override account if it already exists.
     *
     * @param string $user_id
     * @param string $user_name
     * @param string $user_pass
     * @return OutboundEmail
     */
    public function createUserSystemOverrideAccount($user_id, $user_name = '', $user_pass = '')
    {
        $ob = $this->getUsersMailerForSystemOverride($user_id);
        $saveIt = false;

        if (empty($ob)) {
            // Create only if this user's system-override account does not already exist.
            $saveIt = true;
            $user = BeanFactory::retrieveBean('Users', $user_id, ['disable_row_level_security' => true]);

            // Get 'system' Record, clone it and create new user 'system-override' record
            $obSystem = $this->getSystemMailerSettings();
            $ob = clone $obSystem;

            $ob->id = create_guid();
            $ob->new_with_id = true;
            $ob->user_id = $user_id;
            $ob->type = static::TYPE_SYSTEM_OVERRIDE;
            $ob->mail_smtpuser = $user_name;
            $ob->mail_smtppass = $user_pass;

            if ($user) {
                $ob->populateFromUser($user);
            }
        } else {
            // Update the user's existing system-override account.
            if (!empty($user_name) && $user_name !== $ob->mail_smtpuser) {
                $ob->mail_smtpuser = $user_name;
                $saveIt = true;
            }

            if (!empty($user_pass) && $user_pass !== $ob->mail_smtppass) {
                $ob->mail_smtppass = $user_pass;
                $saveIt = true;
            }
        }

        if ($saveIt) {
            $ob->save();
        }

        return $ob;
    }

	/**
	 * Determines if a user needs to set their user name/password for their system
	 * override account.
	 *
     * @param string $user_id
     * @return bool
	 */
	function doesUserOverrideAccountRequireCredentials($user_id)
	{
	    $userCredentialsReq = FALSE;
	    $sys = new OutboundEmail();
	    $ob = $sys->getSystemMailerSettings(); //Dirties '$this'

	    //If auth for system account is disabled or user can use system outbound account return false.
	    if($ob->mail_smtpauth_req == 0 || $this->isAllowUserAccessToSystemDefaultOutbound() || $this->mail_sendtype == 'sendmail')
	       return $userCredentialsReq;

	    $userOverideAccount = $this->getUsersMailerForSystemOverride($user_id);
	    if( $userOverideAccount == null || empty($userOverideAccount->mail_smtpuser) || empty($userOverideAccount->mail_smtppass) )
	       $userCredentialsReq = TRUE;

        return $userCredentialsReq;

	}

	/**
	 * Retrieves name value pairs for opts lists
	 */
	function getUserMailers($user) {
		global $app_strings;

        $stmt = $this->db->getConnection()->executeQuery(
            sprintf('SELECT * FROM %s WHERE user_id = ? AND type = ? ORDER BY name', $this->table_name),
            array($user->id, static::TYPE_USER)
        );

		$ret = array();

		$system = $this->getSystemMailerSettings();

		//Now add the system default or user override default to the response.
		if(!empty($system->id) )
		{
			if ($system->mail_sendtype == 'SMTP')
			{
			    $systemErrors = "";
                $userSystemOverride = $this->getUsersMailerForSystemOverride($user->id);

                //If the user is required to to provide a username and password but they have not done so yet,
        	    //create the account for them.
        	     $autoCreateUserSystemOverride = FALSE;
        		 if( $this->doesUserOverrideAccountRequireCredentials($user->id) )
        		 {
        		      $systemErrors = $app_strings['LBL_EMAIL_WARNING_MISSING_USER_CREDS'];
        		      $autoCreateUserSystemOverride = TRUE;
        		 }

                //Substitute in the users system override if its available.
                if($userSystemOverride != null)
        		   $system = $userSystemOverride;
        		else if ($autoCreateUserSystemOverride)
        	       $system = $this->createUserSystemOverrideAccount($user->id,"","");

                // User overrides can be edited.
                $isEditable = $system->type !== static::TYPE_SYSTEM;

                if( !empty($system->mail_smtpserver) )
				    $ret[] = array('id' =>$system->id, 'name' => "$system->name", 'mail_smtpserver' => $system->mail_smtpdisplay,
								   'is_editable' => $isEditable, 'type' => $system->type, 'errors' => $systemErrors);
			}
			else //Sendmail
			{
				$ret[] = array('id' =>$system->id, 'name' => "{$system->name} - sendmail", 'mail_smtpserver' => 'sendmail',
								'is_editable' => false, 'type' => $system->type, 'errors' => '');
			}
		}

        while ($a = $stmt->fetch()) {
			$oe = array();
			if($a['mail_sendtype'] != 'SMTP')
				continue;

			$oe['id'] =$a['id'];
			$oe['name'] = $a['name'];
			$oe['type'] = $a['type'];
			$oe['is_editable'] = true;
			$oe['errors'] = '';
			if ( !empty($a['mail_smtptype']) )
			    $oe['mail_smtpserver'] = $this->_getOutboundServerDisplay($a['mail_smtptype'],$a['mail_smtpserver']);
			else
			    $oe['mail_smtpserver'] = $a['mail_smtpserver'];

			$ret[] = $oe;
		}

		return $ret;
	}

	/**
	 * Retrieves a cascading mailer set
	 * @param object user
	 * @param string mailer_id
	 * @return object
	 */
	function getUserMailerSettings(&$user, $mailer_id='', $ieId='') {
        $conn = $this->db->getConnection();

        $criteria = array('user_id' => $user->id);

        if (!empty($mailer_id)) {
            $criteria['id'] = $mailer_id;
        } elseif (!empty($ieId)) {
            $stmt = $conn->executeQuery("SELECT stored_options FROM inbound_email WHERE id = ?", array($ieId));
            $options = $stmt->fetchColumn();

            if ($options) {
                $options = unserialize(base64_decode($options));
                if (!empty($options['outbound_email'])) {
                    $criteria['id'] = $options['outbound_email'];
				}
			}
		}

        $this->retrieveByCriteria($criteria);
        return empty($this->id) ? $this->getSystemMailerSettings() : $this;
	}

	/**
	 * Retrieve an array containing inbound emails ids for all inbound email accounts which have
	 * their outbound account set to this object.
	 *
	 * @param SugarBean $user
	 * @return array
	 */
    public function getAssociatedInboundAccounts($user)
    {
        $stmt = $this->db->getConnection()->executeQuery(
            'SELECT id, stored_options FROM inbound_email WHERE is_personal = ? AND deleted = ? AND created_by = ?',
            array(1, 0, $user->id)
        );

        $results = array();
        while ($row = $stmt->fetch()) {
            $opts = unserialize(base64_decode($row['stored_options']));
            if( isset($opts['outbound_email']) && $opts['outbound_email'] == $this->id)
            {
                $results[] = $row['id'];
            }
		}

		return $results;
	}

	/**
	 * Retrieves a cascading mailer set
     * @param object $user
     * @param string $mailer_id
     * @param string $ieId
	 * @return object
	 */
    public function getInboundMailerSettings($user, $mailer_id = '', $ieId = '')
    {
        $emailId = null;
		if(!empty($mailer_id)) {
            $emailId = $mailer_id;
		} elseif(!empty($ieId)) {
            $stmt = $this->db->getConnection()->executeQuery(
                'SELECT stored_options FROM inbound_email WHERE id = ?',
                array($ieId)
            );
            $options = $stmt->fetchColumn();
            // its possible that its an system account
            $emailId = $ieId;
            if (!empty($options)) {
                $options = unserialize(base64_decode($options));
                if (!empty($options['outbound_email'])) {
                    $emailId = array('id' => $options['outbound_email']);
				}
			}
		}

        if (empty($emailId)) {
            $criteria = array('type' => static::TYPE_SYSTEM);
        } else {
            $criteria = array('id' => $emailId);
        }

        $this->disable_row_level_security = true;
        $this->retrieveByCriteria($criteria);

        if (empty($this->id)) {
            return $this->getSystemMailerSettings();
		}
        return $this;
	}

	/**
	 *  Determine if the user is allowed to use the current system outbound connection.
	 */
	function isAllowUserAccessToSystemDefaultOutbound()
	{
	    $allowAccess = FALSE;

	    // first check that a system default exists
        $a = $this->getSystemMailData();
		if (!empty($a)) {
		    // next see if the admin preference for using the system outbound is set
            $admin = Administration::getSettings('',TRUE);
            if (isset($admin->settings['notify_allow_default_outbound'])
                &&  $admin->settings['notify_allow_default_outbound'] == 2 )
                $allowAccess = TRUE;
        }

        return $allowAccess;
	}

    /**
     * Determine whether a user is allowed to create new 'user' Outbound Email records.
     *
     * @param User $user
     * @return bool
     */
    public function isUserAllowedToConfigureEmailAccounts(User $user)
    {
        $config = SugarConfig::getInstance();

        return $user->isAdminForModule('Emails') || !$config->get('disable_user_email_config', false);
    }

    /**
     * Retrieves the system's Outbound options
     *
     * @param bool $create Lazy create the system account when true.
     * @return null|OutboundEmail
     */
    public function getSystemMailerSettings($create = true)
    {
        if (is_null(static::$sysMailerCache)) {
            // result puts in static cache to avoid per-request repeating calls
            $a = $this->getSystemMailData();

            if(empty($a)) {
                if ($create) {
                    $admin = Administration::getSettings();
                    $name = isset($admin->settings['notify_fromname']) ? $admin->settings['notify_fromname'] : 'system';
                    $email = isset($admin->settings['notify_fromaddress']) ? $admin->settings['notify_fromaddress'] : '';
                    $emailId = '';

                    if (!empty($email)) {
                        $sea = new SugarEmailAddress();
                        $emailId = $sea->getEmailGUID($email);
                    }

                    $this->id = '';
                    $this->name = $name;
                    $this->email_address = $email;
                    $this->email_address_id = $emailId;
                    $this->type = static::TYPE_SYSTEM;
                    $this->user_id = '1';
                    $this->mail_sendtype = 'SMTP';
                    $this->mail_smtptype = 'other';
                    $this->mail_smtpserver = '';
                    $this->mail_smtpport = 25;
                    $this->mail_smtpuser = '';
                    $this->mail_smtppass = '';
                    $this->mail_smtpauth_req = 1;
                    $this->mail_smtpssl = 0;
                    $this->mail_smtpdisplay = $this->_getOutboundServerDisplay(
                        $this->mail_smtptype,
                        $this->mail_smtpserver
                    );
                    $this->save();
                    static::$sysMailerCache = $this;
                }
            } else {
                $this->disable_row_level_security = true;
                static::$sysMailerCache = $this->retrieve($a['id']);
            }
        }

        if (is_object(static::$sysMailerCache)) {
            foreach(static::$sysMailerCache as $k => $v) {
                $this->$k = $v;
            }
        }

        return static::$sysMailerCache;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($id = '-1', $encode = true, $deleted = true)
    {
        $result = parent::retrieve($id, $encode, $deleted);

        if ($result) {
            $this->mail_smtppass = htmlspecialchars_decode($this->mail_smtppass, ENT_QUOTES);

            if (empty($this->mail_smtptype)) {
                $this->mail_smtpdisplay = $this->mail_smtpserver;
            } else {
                $this->mail_smtpdisplay = $this->_getOutboundServerDisplay($this->mail_smtptype, $this->mail_smtpserver);
            }
        }

        return $result;
    }

    /**
     * populate current object with data from DB by criteria
     * @param array $criteria
     * @param array $order
     * @return $this
     */
    public function retrieveByCriteria(array $criteria, array $order = [])
    {
        $data = $this->getDataByCriteria($criteria, $order);
        if (!empty($data) && !empty($data['id'])) {
            return $this->retrieve($data['id']);
        }
        return $this;
    }

    /**
     * Retrieve data from DB by criteria
     * @param array $criteria
     * @param array $order
     * @throws \Doctrine\DBAL\DBALException
     * @return array|null
     */
    protected function getDataByCriteria(array $criteria, array $order = [])
    {
        $builder = $this->db->getConnection()->createQueryBuilder();
        $query = $builder->select('*')
            ->from($this->table_name);

        if (!empty($criteria)) {
            $where = $builder->expr()->andX();
            foreach ($criteria as $name => $value) {
                $where->add($builder->expr()->eq($name, $builder->createPositionalParameter($value)));
            }
            $query->where($where);
        }

        foreach ($order as $field => $direction) {
            $query->addOrderBy($field, $direction);
        }

        return $query->execute()->fetch();
    }

    /**
     * return system type data from DB
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getSystemMailData()
    {
        return $this->getDataByCriteria(array('type' => static::TYPE_SYSTEM));
    }

    /**
     *  populate email from $_POST
     */
    public function populateFromPost()
    {
        foreach ($this->field_defs as $name => $def) {
            if (array_key_exists($name, $_POST)) {
                $this->$name = $_POST[$name];
            } elseif ($name !== 'mail_smtppass') {
                $this->$name = '';
            }
        }
    }

    /**
     * {@inheritdoc}
     * @uses OutboundEmail::populateFromUser() to set `name`, `email_address`, and `email_address_id` from the current
     * user's data when populating the system configuration and the user is allowed to use the system configuration.
     */
    public function populateFromRow(array $row, $convert = false)
    {
        $row = parent::populateFromRow($row, $convert);

        if ($this->type === static::TYPE_SYSTEM) {
            static::$sysMailerCache = $this;

            if (isset($GLOBALS['current_user']) && $this->isAllowUserAccessToSystemDefaultOutbound()) {
                $this->populateFromUser($GLOBALS['current_user']);
            }
        }

        return $row;
    }

    /**
     * {@inheritdoc}
     * @uses OutboundEmail::populateFromUser() to default `name`, `email_address`, and `email_address_id` from the
     * current user's data.
     */
    public function populateDefaultValues($force = false)
    {
        parent::populateDefaultValues($force);

        if (isset($GLOBALS['current_user'])) {
            $this->populateFromUser($GLOBALS['current_user']);
        }
    }

    /**
     * Sets `name` to the user's full name and `email_address` and `email_address_id` to the requisite values
     * representing the user's primary email address.
     *
     * @param User $user
     */
    public function populateFromUser(User $user)
    {
        $userData = $user->getUsersNameAndEmail();
        $this->name = $userData['name'];

        if (!empty($userData['email'])) {
            $this->email_address = $userData['email'];
            $this->email_address_id = $user->emailAddress->getEmailGUID($this->email_address);
        }
    }

	/**
	 * Generate values for saving into outbound_emails table
     * @param array $fieldDefs
	 * @return array
	 */
    protected function getValues($fieldDefs)
	{
        global $sugar_config;
	    $values = array();

        // Ignore non-db fields.
        $ignoreFields = array_filter($this->field_defs, function ($def) {
            return isset($def['source']) && $def['source'] === 'non-db';
        });
        $ignoreFields = array_map(function ($def) {
            return $def['name'];
        }, $ignoreFields);

        foreach ($fieldDefs as $field => $def) {
            // Skip fields that should be ignored.
            if (in_array($field, $ignoreFields)) {
                continue;
            }

            if (isset($this->$field)) {
                if ($field == 'mail_smtppass') {
                    if (!empty($this->mail_smtppass)) {
                        $this->mail_smtppass = htmlspecialchars_decode($this->mail_smtppass, ENT_QUOTES);
                    }

                    $this->mail_smtppass = Blowfish::encode(Blowfish::getKey($this->module_key), $this->mail_smtppass);
                }
                if ($field == 'mail_smtpserver'
                    && !empty($sugar_config['bad_smtpservers'])
                    && in_array($this->mail_smtpserver, $sugar_config['bad_smtpservers'])
                ) {
                    $this->mail_smtpserver = '';
                }
                $values[$field] = $this->$field;
            }
	    }
	    return $values;
	}

    /**
     * {@inheritdoc}
     *
     * The record is owned by the current user, by default. The system mailer cache is reset after save.
     */
    public function save($check_notify = false)
    {
        $ownerField = $this->getOwnerField();

        if (empty($this->$ownerField) && isset($GLOBALS['current_user'])) {
            $this->$ownerField = $GLOBALS['current_user']->id;
        }

        if (!empty($this->mail_smtppass)) {
            $this->mail_smtppass = htmlspecialchars_decode($this->mail_smtppass, ENT_QUOTES);
        }

        $id = parent::save($check_notify);
        $this->resetSystemMailerCache();

        return $id;
    }

    /**
     * Saves system mailer. Presumes all values are filled.
     *
     * @param bool $saveConfig Save the notify_fromname and notify_fromaddress configuration settings when true.
     */
    public function saveSystem($saveConfig = false)
    {
        $a = $this->getSystemMailData();

		if(empty($a)) {
			$a['id'] = ''; // trigger insert
		}

		$this->id = $a['id'];
        $this->type = static::TYPE_SYSTEM;
		$this->user_id = '1';
		$this->save();

        if ($saveConfig) {
            $ea = BeanFactory::retrieveBean('EmailAddresses', $this->email_address_id);
            $admin = BeanFactory::newBean('Administration');
            $admin->saveSetting('notify', 'fromname', $this->name);
            $admin->saveSetting('notify', 'fromaddress', $ea ? $ea->email_address : '');
        }

        // If there is no system-override record for the System User - Create One using the System Mailer Configuration
        // If there already is one, update the smtpuser and smtppass
        //      If User Access To System Default Outbound is enabled
        //   Or If SMTP Auth is required And Either the smtpuser or smtppass is empty
        $oe_system = $this->getSystemMailerSettings();
        $oe_override = $this->getUsersMailerForSystemOverride($this->user_id);
        if ($oe_override == null) {
            $this->createUserSystemOverrideAccount($this->user_id, $oe_system->mail_smtpuser, $oe_system->mail_smtppass);
        }
        else if ($this->doesUserOverrideAccountRequireCredentials($this->user_id) ||
                 $this->isAllowUserAccessToSystemDefaultOutbound() ||
                   ( $oe_override->mail_smtpauth_req &&
                     $oe_override->mail_smtpserver == $oe_system->mail_smtpserver &&
                     ( empty($oe_override->mail_smtpuser) || ($oe_system->mail_smtpuser==$oe_override->mail_smtpuser) || empty($oe_override->mail_smtppass))) ) {
            $this->updateAdminSystemOverrideAccount();
        }

        $this->updateUserSystemOverrideAccounts();
        $this->resetSystemMailerCache();
	}

    /**
     * Update the Admin's user system override account with the system information if anything has changed.
     */
    function updateAdminSystemOverrideAccount()
    {
        $this->updateSystemOverride($this->adminSystemFields, array('user_id' => 1));
    }

    /**
	 * Update the user system override accounts with the system information if anything has changed.
	 */
	function updateUserSystemOverrideAccounts()
	{
        $this->updateSystemOverride($this->userSystemFields);
    }

    /**
     * update system override settings
     * @param array $fields
     * @param array $where
     * @return bool
     */
    protected function updateSystemOverride(array $fields, array $where = array())
    {
        $where['type'] = static::TYPE_SYSTEM_OVERRIDE;
        return $this->db->updateParams(
            $this->table_name,
            $this->field_defs,
            $this->getValues(array_flip($fields)),
            $where
        );
    }

    /**
     * Hard deletes the instance.
     *
     * @uses OutboundEmail::delete()
     *
     * {@inheritdoc}
     */
    public function mark_deleted($id)
    {
        if ($this->id !== $id) {
            return false;
        }

        return $this->delete();
    }

    /**
	 * Deletes an instance
     *
     * @return bool
	 */
	function delete() {
		if(empty($this->id)) {
			return false;
		}

        $this->db->getConnection()->delete($this->table_name, array('id' => $this->id));

        return true;
	}

    /**
     * OutboundEmail records are hard deleted. This function is a noop.
     *
     * {@inheritdoc}
     */
    public function mark_undeleted($id)
    {
    }

    private function _getOutboundServerDisplay(
	    $smtptype,
	    $smtpserver
	    )
	{
	    global $app_strings;

	    switch ($smtptype) {
        case "yahoomail":
            return $app_strings['LBL_SMTPTYPE_YAHOO']; break;
        case "gmail":
            return $app_strings['LBL_SMTPTYPE_GMAIL']; break;
        case "exchange":
            return $smtpserver . ' - ' . $app_strings['LBL_SMTPTYPE_EXCHANGE']; break;
        default:
            return $smtpserver; break;
        }
	}

	/**
	 * Get mailer for current user by name
	 * @param User $user
	 * @param string $name
	 * @return OutboundEmail|false
	 */
	public function getMailerByName($user, $name)
	{
	    if($name == "system" && !$this->isAllowUserAccessToSystemDefaultOutbound()) {
	        $oe = $this->getUsersMailerForSystemOverride($user->id);
	        if(!empty($oe) && !empty($oe->id)) {
	            return $oe;
	        }
            else  {
                return $this->getSystemMailerSettings();
            }
	    }
        $this->retrieveByCriteria(array('user_id' => $user->id, 'name' => $name));
        return $this->id ? $this : false;
	}

    /**
     * Reset system mailer settings cache
     */
    public function resetSystemMailerCache()
    {
        static::$sysMailerCache = null;
    }
}
