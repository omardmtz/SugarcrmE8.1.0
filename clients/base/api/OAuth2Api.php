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

class OAuth2Api extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'token' => array(
                'reqType' => 'POST',
                'path' => array('oauth2','token'),
                'pathVars' => array('',''),
                'method' => 'token',
                'shortHelp' => 'OAuth2 token requests.',
                'longHelp' => 'include/api/help/oauth2_token_post_help.html',
                'noLoginRequired' => true,
                'keepSession' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'oauth_logout' => array(
                'reqType' => 'POST',
                'path' => array('oauth2','logout'),
                'pathVars' => array('',''),
                'method' => 'logout',
                'shortHelp' => 'OAuth2 logout.',
                'longHelp' => 'include/api/help/oauth2_logout_post_help.html',
                'keepSession' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'oauth_bwc_login' => array(
                'reqType' => 'POST',
                'path' => array('oauth2','bwc', 'login'),
                'pathVars' => array('','',''),
                'method' => 'bwcLogin',
                'shortHelp' => 'Bwc login for bwc modules. Internal usage only.',
                'longHelp' => 'include/api/help/oauth2_bwc_login_post_help.html',
                'keepSession' => true,
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
            ),
            'oauth_sudo' => array(
                'reqType' => 'POST',
                'path' => array('oauth2','sudo','?'),
                'pathVars' => array('','','user_name'),
                'method' => 'sudo',
                'shortHelp' => 'Get an access token for another user',
                'longHelp' => 'include/api/help/oauth2_sudo_post_help.html',
                'ignoreMetaHash' => true,
            ),
        );
    }

    protected function getOAuth2Server(array $args)
    {
        $platform = empty($args['platform']) ? 'base' : $args['platform'];
        $oauth2Server = SugarOAuth2Server::getOAuth2Server($platform);
        $oauth2Server->setPlatform($platform);

        return $oauth2Server;
    }

    public function token(ServiceBase $api, array $args)
    {
        //The token API supports setting a language for error messages as the user is not yet logged in.
        global $current_language;

        if (!empty($args['current_language'])) {
            $current_language = $args['current_language'];
        }

        $validVersion = $this->isSupportedClientVersion($api, $args);

        if ( !$validVersion ) {
            throw new SugarApiExceptionClientOutdated();
        }

        $platform = empty($args['platform']) ? 'base' : $args['platform'];
        $api->validatePlatform($platform);

        $oauth2Server = $this->getOAuth2Server($args);
        try {
            $GLOBALS['logic_hook']->call_custom_logic('Users', 'before_login');
            $authData = $oauth2Server->grantAccessToken($args);
            // if we're here, the login was OK

            if (!empty($GLOBALS['current_user'])) {
                //Update password expired since user's essentially logged in at this point
                require_once('modules/Users/password_utils.php');

                $GLOBALS['current_user']->call_custom_logic('after_login');
            }
            $cleanupChance = isset($GLOBALS['sugar_config']['token_cleanup_probability'])?(int)$GLOBALS['sugar_config']['token_cleanup_probability']:10;
            if (mt_rand() % $cleanupChance == 0) {
                // cleanup based on probability
                OAuthToken::cleanup();
            }
        } catch (OAuth2ServerException $e) {
            // failed to get token - something went wrong - list as failed login
            $GLOBALS['logic_hook']->call_custom_logic('Users', 'login_failed');
            throw $e;
        } catch(SugarApiExceptionNeedLogin $e) {
            $GLOBALS['logic_hook']->call_custom_logic('Users', 'login_failed');
            // have API throw login exception wil full data
            $api->needLogin($e);
        }

        $loginStatus = apiCheckLoginStatus();
        if ($loginStatus !== true && $loginStatus['level'] != 'warning') {
            if (($loginStatus['level'] == 'admin_only' || $loginStatus['level'] == 'maintenance')
                 && $GLOBALS['current_user']->isAdmin() ) {
                // Let them through
            } else {
                // This is no good, they shouldn't be allowed in.
                $e = new SugarApiExceptionMaintenance($loginStatus['message'], null, null, 0, $loginStatus['level']);
                if (!empty($loginStatus['url'])) {
                    $e->setExtraData("url", $loginStatus['url']);
                }
                $api->needLogin($e);
                return;
            }
        }

        // Adding the setcookie() here instead of calling $api->setHeader() because
        // manually adding a cookie header will break 3rd party apps that use cookies
        setcookie(RestService::DOWNLOAD_COOKIE.'_'.$platform, $authData['download_token'], time()+$authData['refresh_expires_in'], ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure'), true);

        return $authData;
    }

    public function logout(ServiceBase $api, array $args)
    {
        $externalLogin = !empty($_SESSION['externalLogin']) ? $_SESSION['externalLogin'] : false;
        $oauth2Server = $this->getOAuth2Server($args);
        if(!empty($api->user)) {
            $api->user->call_custom_logic('before_logout');
        }

        if ( isset($args['refresh_token']) ) {
            // Nuke the refresh token as well.
            // No security checks needed here to make sure the refresh token is theirs,
            // because if someone else has your refresh token logging out is the nicest possible thing they could do.
            $oauth2Server->unsetRefreshToken($args['refresh_token']);
        }

        setcookie(RestService::DOWNLOAD_COOKIE.'_'.$api->platform, false, -1, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure'), true);

        SugarApplication::endSession();

        // The OAuth access token is actually just a session, so we can nuke that here.
        $_SESSION = array();

        // Whack the cookie that was set in BWC mode
        $this->killSessionCookie();
        $GLOBALS['logic_hook']->call_custom_logic('Users', 'after_logout');

        $auth = AuthenticationController::getInstance();
        $res = array('success'=>true);
        if ($externalLogin && $auth->isExternal()) {
            $logout = $auth->getLogoutUrl();
            if ($logout) {
                $res['url'] = $logout;
            }
        }

        return $res;
    }

    /**
     * By default OAuth is not using cookies. For bwc we need cookies.
     *
     * Use the information supplied by oauth2 on $_SESSION.
     *
     * @param ServiceBase $api
     * @param array $args
     */
    public function bwcLogin(ServiceBase $api, array $args)
    {
        $sendCookie = true;
        $sessionName = session_name();

        // At this point we are authenticated using the current access token
        // and have the session for it. When we have already a BWC session
        // cookie lets check if the user matches the current user. If so we
        // do not need to send the BWC session cookie again.
        if (isset($_COOKIE[$sessionName]) && !empty($_COOKIE[$sessionName])) {

            // close current session
            $tokenSession = session_id();
            session_write_close();

            // grab BWC session
            ini_set('session.use_cookies', false);
            session_id($_COOKIE[$sessionName]);
            session_start();

            if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] === $GLOBALS['current_user']->id) {
                $sendCookie = false;
            }

            // restore token session
            session_write_close();
            session_id($tokenSession);
            session_start();
        }

        // Only send the cookie if there isn't one yet or if the current one
        // does not match the current user.
        if ($sendCookie) {
            $this->sendSessionCookie();
        }

        // Send back session_name so the client can use it for other bwc functions,
        // like studio, module builder, etc when sessions expire outside of the
        // ajax calls
        return array('name' => $sessionName);
    }

    /**
     * This API allows a user to impersonate another user
     * restricting their security to the level of the impersonated user
     *
     * @param ServiceBase $api
     * @param array $args
     */
    public function sudo(ServiceBase $api, array $args)
    {
        if (!$api->user->isAdmin() || !empty($_SESSION['sudo_for'])) {
            // Don't let non-admins sudo
            // Also don't let a sudo user sudo again (so they can't hide their tracks)
            throw new SugarApiExceptionNotAuthorized();
        }

        if (!empty($args['client_id'])) {
            $clientId = $args['client_id'];
        } else {
            $clientId = 'sugar';
        }

        if (!empty($args['platform'])) {
            $platform = $args['platform'];
        } else {
            $platform = 'base';
        }

        $api->validatePlatform($platform);

        $oauth2Server = $this->getOAuth2Server($args);

        $token = $oauth2Server->getSudoToken($args['user_name'], $clientId, $platform);

        if (!$token) {
            throw new SugarApiExceptionRequestMethodFailure("Could not setup a token for the requested user");
        }

        return $token;
    }

    /**
     * This function checks to make sure that if a client version is supplied it is up to date.
     *
     * @param ServiceBase $api The service api
     * @param array $args The arguments passed in to the function
     * @return bool True if the version was good, false if it wasn't
     */
    public function isSupportedClientVersion(ServiceBase $api, array $args)
    {
        if (!empty($args['client_info']['app']['name'])
            && !empty($args['client_info']['app']['version'])) {

            $name = $args['client_info']['app']['name'];
            $version = $args['client_info']['app']['version'];

            if (isset($api->api_settings['minClientVersions'][$name])
                && version_compare($api->api_settings['minClientVersions'][$name], $args['client_info']['app']['version'],'>') ) {
                // Version is too old, force them to upgrade.
                return false;
            }
        }

        return true;
    }

    /**
     * Sends the session cookie. This is needed when moving into and out of BWC mode
     * and the auth token changes.
     *
     * @return string The session name
     */
    protected function sendSessionCookie()
    {
        $sessionName = session_name();
        $sessionId = Uuid::uuid4();

        // Grab current session information which is supplied through the
        // access token authentication and close it so we can start a new one.
        $sessionData = $_SESSION;
        session_write_close();

        // Start new BWC session and populate it from token session
        ini_set('session.use_cookies', false);
        session_id($sessionId);
        session_start();
        $_SESSION = $sessionData;
        session_write_close();

        // Use the refresh token lifetime for bwc session cookies as the "session lifetime"
        // for the end user is tied to this. This means that once we have a bwc session
        // there is no need to refresh/replace it anymore in the future until after the
        // user logs back in.
        $expire = time() + SugarConfig::getInstance()->get(
            'oauth2.max_session_lifetime',
            OAuth2::DEFAULT_REFRESH_TOKEN_LIFETIME
        );

        setcookie(
            $sessionName,
            $sessionId,
            $expire,
            ini_get('session.cookie_path'),
            ini_get('session.cookie_domain'),
            ini_get('session.cookie_secure'),
            ini_get('session.cookie_httponly')
        );

        return $sessionName;
    }

    /**
     * Kills a session cookie. Called from both logout and token on refresh
     * requests in which there is an existing session cookie. This will force new
     * BWC logins so that BWC sessions stay in sync with sidecar sessions.
     */
    protected function killSessionCookie()
    {
        setcookie(
            session_name(),
            '',
            time() - 3600,
            ini_get('session.cookie_path'),
            ini_get('session.cookie_domain'),
            ini_get('session.cookie_secure'),
            ini_get('session.cookie_httponly')
        );
    }
}
