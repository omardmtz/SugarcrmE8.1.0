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
use Sugarcrm\Sugarcrm\Security\Password\Hash;


class SugarOAuth2StoragePortal extends SugarOAuth2StoragePlatform {
    /**
     * The user type for this client
     *
     * @var string
     */
    protected $userType = 'support_portal';

    /**
     * The client type that this client is associated with
     *
     * @var string
     */
    protected $clientType = 'support_portal';

    /**
     * The Portal API user used as a stand-in user for all portal logins
     *
     * @var User
     */
    protected $portalApiUser;

    /**
     * The Contact record for the portal login
     *
     * @var Contact
     */
    protected $contactBean;

    /**
     * Gets a user bean. Also sets the contact id for this portal user.
     *
     * @return User
     */
    public function getUserBean($user_id) {
        $userBean = $this->findPortalApiUser();
        if ( $userBean == null ) {
            return false;
        }

        if ( isset($this->contactBean) && $this->contactBean->id == $user_id ) {
            if (!isset($this->userBean)) {
                $this->userBean = $userBean;
            }

            return $userBean;
        } else {
            $contactBean = BeanFactory::newBean('Contacts');
            // Need to disable the row-level security because this user probably doesn't have access to much of anything
            $contactBean->disable_row_level_security = true;
            $contactBean->retrieve($user_id);
            if ( empty($contactBean->id) ) {
                return false;
            }

            $this->contactBean = $contactBean;
            if (!isset($this->userBean)) {
                $this->userBean = $userBean;
            }
        }

        return $userBean;
    }

    /**
     * Small validator for child classes to use to determine whether a session can
     * be written to
     */
    public function canStartSession() {
        return !empty($this->contactBean) && !empty($this->userBean);
    }

    /**
     * Fills in any added session data needed by this client type
     */
    public function fillInAddedSessionData() {
        if ($this->canStartSession()) {
            $_SESSION['type'] = $this->userType;
            $_SESSION['contact_id'] = $this->contactBean->id;
            $_SESSION['portal_user_id'] = $this->userBean->id;
            // This is to make sure the licensing is handled correctly for portal logins
            $sessionManager = new SessionManager();
            $sessionManager->session_type = 'contact';
            $sessionManager->last_request_time = TimeDate::getInstance()->nowDb();
            $sessionManager->session_id = session_id();
            $sessionManager->save();
        }
    }

    /**
     * Gets the authentication bean for a given client
     * @param OAuthToken
     * @return mixed
     */
    public function getAuthBean(OAuthToken $token) {
        $portalApiUser = $this->findPortalApiUser($token->consumer_obj->c_key);
        if ( $portalApiUser == null ) {
            return false;
        }
        $contact = BeanFactory::newBean('Contacts');
        $contact->disable_row_level_security = true;
        $authBean = $contact->retrieve($token->contact_id);
        if ( $authBean->portal_active != 1 ) {
            $authBean = null;
        } else if ( empty($authBean->portal_name) ) {
            $authBean = null;
        }

        return $authBean;
    }

    /**
     * Gets contact and user ids for a user id. Most commonly different for clients
     * like portal
     *
     * @param string $client_id The client id for this check
     * @return array An array of contact_id and user_id
     */
    public function getIdsForUser($user_id, $client_id) {
        $return = array('contact_id' => '', 'user_id' => '');
        $portalApiUser = $this->findPortalApiUser($client_id);
        if ( $portalApiUser == null ) {
            return $return;
        }

        $return['contact_id'] = $user_id;
        $return['user_id'] = $portalApiUser->id;

        return $return;
    }

    /**
     * Sets up necessary visibility for a client. Not all clients will set this
     *
     * @return void
     */
    public function setupVisibility() {
        // Add the necessary visibility and acl classes to the default bean list
        $default_acls = SugarBean::getDefaultACL();
        // This one overrides the Static ACL's, so disable that
        unset($default_acls['SugarACLStatic']);
        $default_acls['SugarACLStatic'] = false;
        $default_acls['SugarACLSupportPortal'] = true;
        SugarBean::setDefaultACL($default_acls);
        SugarACL::resetACLs();

        $default_visibility = SugarBean::getDefaultVisibility();
        $default_visibility['SupportPortalVisibility'] = true;
        SugarBean::setDefaultVisibility($default_visibility);
        $GLOBALS['log']->debug("Added SupportPortalVisibility to session.");
    }

    /**
     * This method locates the portal API user for the specified client_id
     * Currently there is no way to associate a specific user with a specific client_id, so that parameter is ignored for now
     * @param $client_id string The client identifier of the portal account, should be used to identifiy different portal types
     * @return User Returs the user bean of the portal user that it found.
     */
    protected function findPortalApiUser()
    {
        if (isset($this->portalApiUser)) {
            return $this->portalApiUser;
        }

        // Find the Portal API user
        $admin = new Administration();
        $admin->retrieveSettings(false, true);
        if (isset($admin->settings['supportPortal_RegCreatedBy'])) {
            $portalApiUser = BeanFactory::getBean('Users', $admin->settings['supportPortal_RegCreatedBy']);
        }
        if (!empty($portalApiUser->id)) {
            $this->portalApiUser = $portalApiUser;
            $this->rehashPortalApiUser();
            return $this->portalApiUser;
        } else {
            return null;
        }
    }

    /**
     * The password of the portal user is random and is not used to login
     * directly. Nevertheless we want to keep the password hash in compliance
     * with the current hash settings.
     * @param User $user The portal user object
     */
    protected function rehashPortalApiUser()
    {
        // Don't do anything in case portal user object not set yet
        if (empty($this->portalApiUser)) {
            return;
        }

        // This check already happens in User::rehashPassword but we want
        // to do it earlier to avoid calling CSPRNG every time.
        if (Hash::getInstance()->needsRehash($this->portalApiUser->user_hash)) {
            $this->portalApiUser->rehashPassword(CSPRNG::getInstance()->generate(32, true));
        }
    }

    /**
   	 * Grant access tokens for basic user credentials.
   	 *
   	 * Check the supplied username and password for validity.
   	 *
   	 * You can also use the $client_id param to do any checks required based
   	 * on a client, if you need that.
   	 *
   	 * Required for OAuth2::GRANT_TYPE_USER_CREDENTIALS.
   	 *
   	 * @param $client_id
   	 * Client identifier to be check with.
   	 * @param $username
   	 * Username to be check with.
   	 * @param $password
   	 * Password to be check with.
   	 *
   	 * @return
   	 * TRUE if the username and password are valid, and FALSE if it isn't.
   	 * Moreover, if the username and password are valid, and you want to
   	 * verify the scope of a user's access, return an associative array
   	 * with the scope values as below. We'll check the scope you provide
   	 * against the requested scope before providing an access token:
   	 * @code
   	 * return array(
   	 * 'scope' => <stored scope values (space-separated string)>,
   	 * );
   	 * @endcode
   	 *
   	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-4.3
   	 *
   	 * @ingroup oauth2_section_4
   	 */
   	public function checkUserCredentials(IOAuth2GrantUser $storage, $client_id, $username, $password)
   	{
   	    if(empty($username)) {
   	        return false;
   	    }
        $clientInfo = $storage->getClientDetails($client_id);
        if ( $clientInfo === false ) {
            return false;
        }

        $portalApiUser = $this->findPortalApiUser($client_id);
        if ( $portalApiUser == null ) {
           // Can't login as a portal user if there is no API user
            throw new SugarApiExceptionPortalNotConfigured();
        }

        $contact = $this->loadUserFromName($username);
        if ( !empty($contact) && !User::checkPassword($password, $contact->portal_password) ) {
           $contact = null;
        }

        if ( !empty($contact) ) {
            $sessionManager = new SessionManager();
            if(!$sessionManager->canAddSession()) {
                //not able to add another session right now
                $GLOBALS['log']->error("Unable to add new session");
                throw new SugarApiExceptionNeedLogin('too_many_concurrent_connections',array('Too many concurrent sessions'));
            }

            $this->contactBean = $contact;
            if (empty($this->userBean)) {
                $this->userBean = $portalApiUser;
            }

            $contact->rehashPortalPassword($password);

            return array('user_id'=>$contact->id);
        } else {
            throw new SugarApiExceptionNeedLogin(translate('ERR_INVALID_PASSWORD', 'Users'));
        }
    }

    /**
     * Loads the current user from the user name
     * split out so that portal can load users properly
     *
     * @param string $username The name of the user you want to load
     *
     * @return SugarBean The user from the name
     */
    public function loadUserFromName($username)
    {
        // It's a portal user, log them in against the Contacts table
        $contact = BeanFactory::newBean('Contacts');
        $contact->disable_row_level_security = true;
        $contact = $contact->retrieve_by_string_fields(
            array(
                'portal_name'=>$username,
                'portal_active'=>'1',
                'deleted'=>0,
            ));

        return $contact;
    }

    /**
     * Sets any additional data as needed when we load a download token
     *
     * @param array $tokenData The token data generated in the base app
     * @param OAuthToken $token The token bean that the download token is attached to
     * @param OAuthKey $clientBean The oauth key associated with the download token
     *
     * @return array The completely filled in token data
     */
    public function getDownloadTokenData(array $tokenData, OAuthToken $tokenBean, OAuthKey $clientBean)
    {
        if (empty($tokenBean->contact_id)) {
            return false;
        } 

        $tokenData['user_id'] = $tokenBean->assigned_user_id;
        $_SESSION['type'] = 'support_portal';
        $_SESSION['contact_id'] = $tokenBean->contact_id;
        $_SESSION['portal_user_id'] = $tokenBean->assigned_user_id;

        return $tokenData;

    }

}
