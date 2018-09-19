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
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\AuthProviderManagerBuilder;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Token\UsernamePasswordTokenFactory;

/**
 * Used as a base class for various Auth flows. It should be extended by classes that implement
 * that particular kind of Auth flow.
 * Holds basic underlying actions for dealing with Sugar Users, e.g. session management. etc.
 */
class BaseAuthenticate
{
    /**
     * Called after a session is authenticated - if this returns false the sessionAuthenticate
     * will return false and destroy the session
     * and it will load the  current user
     *
     * @return boolean
     */
    public function postSessionAuthenticate()
    {
        $_SESSION['userTime']['last'] = time();
        $user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
        $server_unique_key = \SugarConfig::getInstance()->get('unique_key', '');
        $authenticated = true;

        // CHECK IF USER IS CROSSING SITES
        if (($user_unique_key != $server_unique_key) && (!isset($_SESSION['login_error']))) {
            $GLOBALS['log']->security('Destroying Session User has crossed Sites');
            $authenticated = false;
        }
        if (!$this->loadUserOnSession($_SESSION['authenticated_user_id'])) {
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
     * On every page hit this will be called to ensure a user is authenticated
     *
     * @return boolean
     */
    public function sessionAuthenticate()
    {
        global $module, $action, $allowed_actions;
        $authenticated = false;
        $allowed_actions = array ("Authenticate", "Login"); // these are actions where the user/server keys aren't compared
        if (isset($_SESSION['authenticated_user_id'])) {
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
        } else {
            if (isset($action) && isset($module) && $action == "Authenticate" && $module == "Users") {
                $GLOBALS['log']->debug("We are authenticating user now");
            } else {
                $GLOBALS['log']->debug("The current user does not have a session.  Going to the login page");
                $action = "Login";
                $module = "Users";
                $_REQUEST['action'] = $action;
                $_REQUEST['module'] = $module;
            }
        }
        if (empty($GLOBALS['current_user']->id) && !in_array($action, $allowed_actions)) {
            $GLOBALS['log']->debug("The current user is not logged in going to login page");
            $action = "Login";
            $module = "Users";
            $_REQUEST['action'] = $action;
            $_REQUEST['module'] = $module;
        }

        return $authenticated;
    }

    /**
     * Loads the current user based on the given user_id.
     *
     * @param string $user_id
     * @return boolean
     */
    public function loadUserOnSession($user_id = '')
    {
        if (!empty($user_id)) {
            $_SESSION['authenticated_user_id'] = $user_id;
        }

        if (!empty($_SESSION['authenticated_user_id']) || !empty($user_id)) {
            $GLOBALS['current_user'] = BeanFactory::newBean('Users');
            if ($GLOBALS['current_user']->retrieve($_SESSION['authenticated_user_id'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Make sure a user isn't stealing sessions so check the IP to ensure
     * that the ip address has not dramatically changed.
     *
     * @return bool
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
     * @param Config $config
     *
     * @return AuthProviderManagerBuilder
     */
    protected function getAuthProviderBuilder(Config $config)
    {
        return new AuthProviderManagerBuilder($config);
    }

    /**
     * @param $username
     * @param $password
     * @param $params
     * @return UsernamePasswordTokenFactory
     */
    protected function getUsernamePasswordTokenFactory($username, $password, $params)
    {
        return new UsernamePasswordTokenFactory($username, $password, $params);
    }
}
