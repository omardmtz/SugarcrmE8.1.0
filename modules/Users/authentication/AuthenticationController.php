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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\TemporaryLockedUserException;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\PermanentLockedUserException;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\InactiveUserException;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\InvalidUserException;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\ExternalAuthUserException;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;

use Sugarcrm\Sugarcrm\Logger\Factory as LoggerFactory;

use Sugarcrm\IdentityProvider\Authentication\Exception\SAMLRequestException;
use Sugarcrm\IdentityProvider\Authentication\Exception\SAMLResponseException;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidIdentifier\InvalidIdentifierException;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class AuthenticationController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

	public $loggedIn = false; //if a user has attempted to login
	public $authenticated = false;
	public $loginSuccess = false;// if a user has successfully logged in

	protected static $authcontrollerinstance = null;

    /**
     * @var SugarAuthenticate
     */
    public $authController;

	/**
	 * Creates an instance of the authentication controller and loads it
	 *
	 * @param STRING $type - the authentication Controller - default to SugarAuthenticate
	 * @return AuthenticationController -
	 */
	public function __construct($type = 'SugarAuthenticate')
	{
	    if ($type == 'SugarAuthenticate' && !empty($GLOBALS['system_config']->settings['system_ldap_enabled']) && empty($_SESSION['sugar_user'])){
			$type = 'LDAPAuthenticate';
        }

        // check in custom dir first, in case someone want's to override an auth controller
        $customFile = SugarAutoLoader::requireWithCustom('modules/Users/authentication/' . $type . '/' . $type . '.php');
        if (!$customFile) {
            $type = 'SugarAuthenticate';
        }

        $authUserPath = sprintf('custom/modules/Users/authentication/%1$s/%1$sUser.php', $type);
        if (!preg_match('|^custom/|', $customFile) && !file_exists($authUserPath)) {
            // if there's no customization we can safely use IdM glue
            $idmGlueClass = 'IdM' . $type;
            $idmGluePath = 'modules/Users/authentication/' . $idmGlueClass . '/' . $idmGlueClass . '.php';
            if (file_exists($idmGluePath)) {
                $type = $idmGlueClass;
            }
        }

        $this->setLogger(LoggerFactory::getLogger('authentication'));

        $this->authController = new $type();
	}


    /**
     * Returns an instance of the authentication controller
     *
     * @param string $type this is the type of authentication you want to use default is SugarAuthenticate
     * @return AuthenticationController An instance of the authentication controller
     */
    public static function getInstance($type = null)
    {
        if (empty($type)) {
            $idpConfig = new Config(\SugarConfig::getInstance());
            if ($idpConfig->isIDMModeEnabled()) {
                $type = 'OAuth2Authenticate';
            } else {
                $type = $idpConfig->get('authenticationClass', 'SugarAuthenticate');
            }
        }
        if (empty(static::$authcontrollerinstance)) {
            SugarAutoLoader::requireWithCustom('modules/Users/authentication/AuthenticationController.php');
            $controllerClass = SugarAutoLoader::customClass('AuthenticationController');
            static::$authcontrollerinstance = new $controllerClass($type);
        }

        return static::$authcontrollerinstance;
    }

	/**
	 * Set currect instance (for testing)
	 * @param AuthenticationController $instance
	 */
    public static function setInstance($instance)
	{
	    self::$authcontrollerinstance = $instance;
	}


	/**
	 * This function is called when a user initially tries to login.
	 *
	 * @param string $username
	 * @param string $password
	 * @param array $params Login parameters:
	 * - noHooks - don't run logic hooks
	 * - noRedirect - don't redirect if not logged in
	 * - passwordEncrypted - is password plaintext (false) or md5 (true)?
	 * @return boolean true if the user successfully logs in or false otherwise.
	 */
	public function login($username, $password, $params = array())
	{
		//kbrill bug #13225
		unset($GLOBALS['login_error']);

		if($this->loggedIn)return $this->loginSuccess;
		if(empty($params['noHooks'])) {
		    LogicHook::initialize()->call_custom_logic('Users', 'before_login');
		}
        $_SESSION['externalLogin'] = false;
        $this->loggedIn = false;
        $this->loginSuccess = false;
        try {
            $this->loginSuccess = $this->authController->loginAuthenticate($username, $password, false, $params);
            $this->loggedIn = true;
        } catch (TemporaryLockedUserException $e) {
            $_SESSION['login_error'] = $e->getMessage();
        } catch (PermanentLockedUserException $e) {
            $_SESSION['login_error'] = $e->getMessage();
            $_SESSION['waiting_error'] = $e->getWaitingErrorMessage();
        } catch (BadCredentialsException $e) {
            $_SESSION['login_error'] = translate('ERR_INVALID_PASSWORD', 'Users');
        } catch (InvalidIdentifierException $e) {
            $_SESSION['login_error'] = translate('EXCEPTION_FATAL_ERROR', 'Users');
            $this->logger->error($e->getMessage());
        } catch (SAMLRequestException $e) {
            $this->logger->error($e->getMessage());
            $_SESSION['login_error'] = translate('ERR_INVALID_PASSWORD', 'Users');
        } catch (SAMLResponseException $e) {
            $this->logger->error($e->getMessage());
            $_SESSION['login_error'] = translate('ERR_INVALID_PASSWORD', 'Users');
        } catch (AuthenticationServiceException $e) {
            $this->logger->error($e->getMessage());
            $_SESSION['login_error'] = $this->getMessageForProviderException($e->getPrevious());
        } catch (InactiveUserException $e) {
            $this->logger->error($e->getMessage());
            $_SESSION['login_error'] = $this->getMessageForProviderException($e);
        } catch (InvalidUserException $e) {
            $this->logger->error($e->getMessage());
            $_SESSION['login_error'] = $this->getMessageForProviderException($e);
        } catch (ExternalAuthUserException $e) {
            $this->logger->error($e->getMessage());
            $_SESSION['login_error'] = $this->getMessageForProviderException($e);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $_SESSION['login_error'] = translate('ERR_INVALID_PASSWORD', 'Users');
        }

		if($this->loginSuccess){
			loginLicense();
			if(!empty($GLOBALS['login_error'])){
				unset($_SESSION['authenticated_user_id']);
                $this->logger->fatal('FAILED LOGIN: potential hack attempt:' . $GLOBALS['login_error']);
				$this->loginSuccess = false;
				return false;
			}

			//call business logic hook
			if(isset($GLOBALS['current_user']) && empty($params['noHooks']))
				$GLOBALS['current_user']->call_custom_logic('after_login');

			// Check for running Admin Wizard
            $config = Administration::getSettings();
		    if ( is_admin($GLOBALS['current_user']) && empty($config->settings['system_adminwizard']) && isset($_REQUEST['action']) && $_REQUEST['action'] != 'AdminWizard' ) {

                if ( isset($params['noRedirect']) && $params['noRedirect'] == true ) {
                    $this->nextStep = array('module'=>'Configurator','action'=>'AdminWizard');
                } else {
                    ob_clean();
                    $GLOBALS['module'] = 'Configurator';
                    $GLOBALS['action'] = 'AdminWizard';
                    header("Location: index.php?module=Configurator&action=AdminWizard");
                    sugar_cleanup(true);
                }
			}

			$ut = $GLOBALS['current_user']->getPreference('ut');
			$checkTimeZone = true;
			if (is_array($params) && !empty($params) && isset($params['passwordEncrypted'])) {
				$checkTimeZone = false;
			} // if
			if(empty($ut) && $checkTimeZone && isset($_REQUEST['action']) && $_REQUEST['action'] != 'SetTimezone' && $_REQUEST['action'] != 'SaveTimezone' ) {
			    if ( isset($params['noRedirect']) && $params['noRedirect'] == true && empty($this->nextStep) ) {
                    $this->nextStep = array('module'=>'Users','action'=>'Wizard');
                } else {
                    $GLOBALS['module'] = 'Users';
                    $GLOBALS['action'] = 'Wizard';
                    ob_clean();
                    header("Location: index.php?module=Users&action=Wizard");
                    sugar_cleanup(true);
                }
			}
		}else{
			//kbrill bug #13225
			if(empty($params['noHooks'])) {
			    LogicHook::initialize();
			    $GLOBALS['logic_hook']->call_custom_logic('Users', 'login_failed');
			}
		}
		// if password has expired, set a session variable

		return $this->loginSuccess;
	}

	/**
	 * This is called on every page hit.
	 * It returns true if the current session is authenticated or false otherwise
	 *
	 * @return bool
	 */
	public function sessionAuthenticate()
	{
		if(!$this->authenticated){
			$this->authenticated = $this->authController->sessionAuthenticate();
		}
		if($this->authenticated){
			if(!isset($_SESSION['userStats']['pages'])){
			    $_SESSION['userStats']['loginTime'] = time();
			    $_SESSION['userStats']['pages'] = 0;
			}
			$_SESSION['userStats']['lastTime'] = time();
			$_SESSION['userStats']['pages'] += 1;

		}
		return $this->authenticated;
	}

	/**
	 * This is called on every page hit.
	 * It returns true if the current session is authenticated or false otherwise
	 *
	 * @return bool
	 */
	public function apiSessionAuthenticate()
	{
		if (!$this->authenticated) {
			$this->authenticated = $this->authController->postSessionAuthenticate();
		}
		if (!$this->authenticated) {
			if (session_id()) {
				session_destroy();
			}
			$_SESSION = array();
		} else {
			if(!isset($_SESSION['userStats']['pages'])){
			    $_SESSION['userStats']['loginTime'] = time();
			    $_SESSION['userStats']['pages'] = 0;
			}
			$_SESSION['userStats']['lastTime'] = time();
			$_SESSION['userStats']['pages'] += 1;

		}
		return $this->authenticated;
	}

	/**
	 * Called when a user requests to logout. Should invalidate the session and redirect
	 * to the login page.
	 */
	public function logout()
	{
		$GLOBALS['current_user']->call_custom_logic('before_logout');
        try {
            $this->authController->logout();
        } catch (SAMLResponseException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
		LogicHook::initialize();
		$GLOBALS['logic_hook']->call_custom_logic('Users', 'after_logout');
	}

	/**
	 * Does this controller require external authentication?
	 * @return boolean
	 */
	public function isExternal()
	{
        return $this->authController instanceof SugarAuthenticateExternal
            || $this->authController instanceof ExternalLoginInterface;
	}

	/**
	 * Get URL for external login
     *
     * @param array $returnQueryVars Query variables that should be added to the return URL
	 * @return string
	 */
    public function getLoginUrl(array $returnQueryVars = array())
	{
	    if($this->isExternal()) {
            return $this->authController->getLoginUrl($returnQueryVars);
	    }
	    return false;
	}

	/**
	 * Get URL for external login
     * @return string|array
	 */
	public function getLogoutUrl()
	{
	    if($this->isExternal()) {
	        return $this->authController->getLogoutUrl();
	    }
	    return false;
	}

    /**
     * return translated error message
     * @param InvalidUserException|InactiveUserException $exception
     * @return string return translated error message
     */
    protected function getMessageForProviderException($exception)
    {
        if ($exception instanceof InvalidUserException) {
            return translate('LBL_LOGIN_PORTAL_GROUP_CANT_LOGIN');
        } elseif ($exception instanceof ExternalAuthUserException) {
            return translate('ERR_INVALID_PASSWORD', 'Users') . ' ' .
                translate('LBL_EXTERNAL_USER_CANT_LOGIN', 'Users');
        } elseif ($exception instanceof InactiveUserException) {
            return translate('LBL_LOGIN_INACTIVE_USER');
        } else {
            return translate('ERR_INVALID_PASSWORD', 'Users');
        }
    }
}
