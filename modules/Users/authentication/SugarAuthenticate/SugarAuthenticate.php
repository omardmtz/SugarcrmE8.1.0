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

/**
 * This file is used to control the authentication process.
 * It will call on the user authenticate and controll redirection
 * based on the users validation
 * @deprecated Will be removed in 7.11. IDM-46
 * @deprecated Please use new idM Mango library Glue \IdMSugarAuthenticate
 */
class SugarAuthenticate{
	var $userAuthenticateClass = 'SugarAuthenticateUser';
	var $authenticationDir = 'SugarAuthenticate';
	/**
	 * Constructs SugarAuthenticate
	 * This will load the user authentication class
	 *
	 * @return SugarAuthenticate
	 */
    public function __construct()
	{
	    // check in custom dir first, in case someone want's to override an auth controller
	    SugarAutoLoader::requireWithCustom('modules/Users/authentication/'.$this->authenticationDir.'/' . $this->userAuthenticateClass . '.php');

        $this->userAuthenticate = new $this->userAuthenticateClass();
	}
	/**
	 * Authenticates a user based on the username and password
	 * returns true if the user was authenticated false otherwise
	 * it also will load the user into current user if he was authenticated
	 *
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	function loginAuthenticate($username, $password, $fallback=false, $PARAMS = array ()){
        global $app_strings, $log;
        $log->deprecated(sprintf(
            'The %s in %s was called. Please see \IdMSugarAuthenticate',
            __METHOD__,
            __CLASS__
        ));

		unset($_SESSION['login_error']);
		$res = $GLOBALS['sugar_config']['passwordsetting'];
        $usr = BeanFactory::newBean('Users');
        $usr->retrieve_by_string_fields(array('user_name'=>$username));
		$_SESSION['login_error']='';
		$_SESSION['waiting_error']='';
		$_SESSION['hasExpiredPassword']='0';
		$usr->reloadPreferences();
		// if there is too many login attempts
		if (!empty($usr->id) && $res['lockoutexpiration'] > 0 && $usr->getPreference('loginfailed')>=($res['lockoutexpirationlogin']) && !($usr->portal_only)){
		    // if there is a lockout time set
		    if ($res['lockoutexpiration'] == '2'){
		    	// lockout date is now if not set
		    	if (($logout_time=$usr->getPreference('logout_time'))==''){
			        $usr->setPreference('logout_time',TimeDate::getInstance()->nowDb());
			        $logout_time=$usr->getPreference('logout_time');
			        }

                // Bug # 45922 - calculating the expiretime properly
                $stim = strtotime($logout_time);
                $mins = $res['lockoutexpirationtime']*$res['lockoutexpirationtype'];
                $expiretime = TimeDate::getInstance()->fromDb($logout_time)->modify("+$mins minutes")->asDb();

				// Test if the user is still locked out and return a error message
			    if (TimeDate::getInstance()->nowDb() < $expiretime){
			    	$usr->setPreference('lockout','1');
			        $_SESSION['login_error'] = $app_strings['LBL_LOGIN_ATTEMPTS_OVERRUN'] . ' ';
			        $_SESSION['login_error'] .= $app_strings['LBL_LOGIN_LOGIN_TIME_ALLOWED'] . ' ';
			        $lol= strtotime($expiretime)-strtotime(TimeDate::getInstance()->nowDb());
					        switch (true) {
				    case (floor($lol/86400) !=0):
				        $_SESSION['login_error'] .= floor($lol/86400).$app_strings['LBL_LOGIN_LOGIN_TIME_DAYS'];
				        break;
				    case (floor($lol/3600)!=0):
				        $_SESSION['login_error'] .= floor($lol/3600).$app_strings['LBL_LOGIN_LOGIN_TIME_HOURS'];
				        break;
				    case (floor($lol/60)!=0):
				        $_SESSION['login_error'] .= floor($lol/60).$app_strings['LBL_LOGIN_LOGIN_TIME_MINUTES'];
				        break;
			        case (floor($lol)!=0):
				        $_SESSION['login_error'] .= floor($lol).$app_strings['LBL_LOGIN_LOGIN_TIME_SECONDS'];
				        break;
					}
					$usr->savePreferencesToDB();
					return false;
			    }
			    else{
			    	$usr->setPreference('lockout','');
			        $usr->setPreference('loginfailed','0');
			        $usr->setPreference('logout_time','');
			        $usr->savePreferencesToDB();
			    }
		    }
		    else{
		    	$usr->setPreference('lockout','1');
			    $_SESSION['login_error']=$app_strings['LBL_LOGIN_ATTEMPTS_OVERRUN'];
		        $_SESSION['waiting_error']=$app_strings['LBL_LOGIN_ADMIN_CALL'];
		        $usr->savePreferencesToDB();
		        return false;
			}
		}
		if ($this->userAuthenticate->loadUserOnLogin($username, $password, $fallback, $PARAMS)) {
			require_once('modules/Users/password_utils.php');
            if (hasPasswordExpired($username, true)) {
				$_SESSION['hasExpiredPassword'] = '1';
			}
			// now that user is authenticated, reset loginfailed
			if ($usr->getPreference('loginfailed') != '' && $usr->getPreference('loginfailed') != 0) {
				$usr->setPreference('loginfailed','0');
				$usr->savePreferencesToDB();
			}
            $this->updateUserLastLogin($usr);
			return $this->postLoginAuthenticate();

		}
		else
		{
			if(!empty($usr->id) && isset($res['lockoutexpiration']) && $res['lockoutexpiration'] > 0){
				if (($logout=$usr->getPreference('loginfailed'))=='')
	        		$usr->setPreference('loginfailed','1');
	    		else
	        		$usr->setPreference('loginfailed',$logout+1);
	    		$usr->savePreferencesToDB();
    		}
		}
		if(strtolower(get_class($this)) != 'sugarauthenticate'){
			$sa = new SugarAuthenticate();
			$error = (!empty($_SESSION['login_error']))?$_SESSION['login_error']:'';
			if($sa->loginAuthenticate($username, $password, true, $PARAMS)){
				return true;
			}
			$_SESSION['login_error'] = $error;
		}


		$_SESSION['login_user_name'] = $username;
		$_SESSION['login_password'] = $password;
		if(empty($_SESSION['login_error'])){
			$_SESSION['login_error'] = translate('ERR_INVALID_PASSWORD', 'Users');
		}

		return false;

	}

	/**
	 * Once a user is authenticated on login this function will be called. Populate the session with what is needed and log anything that needs to be logged
	 *
	 */
	function postLoginAuthenticate(){

		global $reset_theme_on_default_user, $reset_language_on_default_user, $sugar_config;

		//just do a little house cleaning here
		unset($_SESSION['login_password']);
		unset($_SESSION['login_error']);
		unset($_SESSION['login_user_name']);
		unset($_SESSION['ACL']);

		//set the server unique key
		if (isset ($sugar_config['unique_key']))$_SESSION['unique_key'] = $sugar_config['unique_key'];

		//set user language
		if (isset ($reset_language_on_default_user) && $reset_language_on_default_user && $GLOBALS['current_user']->user_name == $sugar_config['default_user_name']) {
			$authenticated_user_language = $sugar_config['default_language'];
		} else {
			$authenticated_user_language = InputValidation::getService()->getValidInputRequest('login_language', 'Assert\Language', $sugar_config['default_language']);
		}

		$_SESSION['authenticated_user_language'] = $authenticated_user_language;

		$GLOBALS['log']->debug("authenticated_user_language is $authenticated_user_language");

		// Clear all uploaded import files for this user if it exists
        $tmp_file_name = ImportCacheFiles::getImportDir()."/IMPORT_" . $GLOBALS['current_user']->id;

		if (file_exists($tmp_file_name)) {
			unlink($tmp_file_name);
		}

		return true;
	}

	/**
	 * On every page hit this will be called to ensure a user is authenticated
	 *
	 * @return boolean
	 */
	function sessionAuthenticate(){

		global $module, $action, $allowed_actions;
		$authenticated = false;
		$allowed_actions = array ("Authenticate", "Login"); // these are actions where the user/server keys aren't compared
		if (isset ($_SESSION['authenticated_user_id'])) {

			$GLOBALS['log']->debug("We have an authenticated user id: ".$_SESSION["authenticated_user_id"]);

			$authenticated = $this->postSessionAuthenticate();
			
			if (!$authenticated) {
				// postSessionAuthenticate failed, nuke the session
				if (session_id()) {
					session_destroy();
				}
				header("Location: index.php?action=Login&module=Users&loginErrorMessage=LBL_SESSION_EXPIRED");
				sugar_cleanup(true);				
			}

		} else
		if (isset ($action) && isset ($module) && $action == "Authenticate" && $module == "Users") {
			$GLOBALS['log']->debug("We are authenticating user now");
		} else {
			$GLOBALS['log']->debug("The current user does not have a session.  Going to the login page");
			$action = "Login";
			$module = "Users";
			$_REQUEST['action'] = $action;
			$_REQUEST['module'] = $module;
		}
		if (empty ($GLOBALS['current_user']->id) && !in_array($action, $allowed_actions)) {

			$GLOBALS['log']->debug("The current user is not logged in going to login page");
			$action = "Login";
			$module = "Users";
			$_REQUEST['action'] = $action;
			$_REQUEST['module'] = $module;

		}

		return $authenticated;
	}




	/**
	 * Called after a session is authenticated - if this returns false the sessionAuthenticate will return false and destroy the session
	 * and it will load the  current user
	 * @return boolean
	 */

	function postSessionAuthenticate(){
		global $action, $allowed_actions, $sugar_config;

		$_SESSION['userTime']['last'] = time();
		$user_unique_key = (isset ($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
		$server_unique_key = (isset ($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';
		$authenticated = true;

		//CHECK IF USER IS CROSSING SITES
		if (($user_unique_key != $server_unique_key) && (!isset($_SESSION['login_error']))) {

			$GLOBALS['log']->security('Destroying Session User has crossed Sites');
			$authenticated = false;
		}
		if (!$this->userAuthenticate->loadUserOnSession($_SESSION['authenticated_user_id'])) {
			$GLOBALS['log']->error('Current user session does not exist redirecting to login');
			$authenticated = false;
		}
		if ($authenticated) {
			$authenticated = $this->validateIP();
		}
		if ($authenticated) {
			$GLOBALS['log']->debug('Current user is: '.$GLOBALS['current_user']->user_name);
		}
		return $authenticated;
	}

	/**
	 * Make sure a user isn't stealing sessions so check the ip to ensure that the ip address hasn't dramatically changed
	 */
    public function validateIP()
    {
        $clientIp = query_client_ip();
        if (isset($_SESSION['ipaddress'])) {
            if (!validate_ip($clientIp, $_SESSION['ipaddress'])) {
                $GLOBALS['log']->fatal(sprintf(
                    'IP address mismatch: SESSION IP: %s, CLIENT IP: %s',
                    $_SESSION['ipaddress'],
                    $clientIp
                ));
                return false;
            }
            return true;
        }

        $_SESSION['ipaddress'] = $clientIp;
        return true;
    }

	/**
	 * Called when a user requests to logout
	 *
	 */
	function logout()
	{
	    if(session_id() != '') {
			session_destroy();
	    }
		ob_clean();
		header('Location: index.php?module=Users&action=Login');
		sugar_cleanup(true);
	}


	/**
	 * Encodes a users password. This is a static function and can be called at any time.
	 *
	 * @param STRING $password
	 * @return STRING $encoded_password
	 */
    public static function encodePassword($password)
    {
		return strtolower(md5($password));
	}

	/**
	 * If a user may change there password through the Sugar UI
	 *
	 */
	function canChangePassword(){
		return true;
	}
	/**
	 * If a user may change there user name through the Sugar UI
	 *
	 */
	function canChangeUserName(){
		return true;
	}


    /**
     * pre_login
     *
     * This function allows the SugarAuthenticate subclasses to perform some pre login initialization as needed
     */
    function pre_login()
    {
        if (isset($_SESSION['authenticated_user_id']))
        {
            ob_clean();
            // fixing bug #46837: Previosly links/URLs to records in Sugar from MSO Excel/Word were referred to the home screen and not the record
            // It used to appear when default browser was not MS IE
            header("Location: ".$GLOBALS['app']->getLoginRedirect());
            sugar_cleanup(true);
        }
    }

    /**
     * Updates user's last_login field with current datetime
     *
     * @param User $user
     */
    protected function updateUserLastLogin(User $user)
    {
        $user->updateLastLogin();
    }
}
