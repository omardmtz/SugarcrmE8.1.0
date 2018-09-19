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

/**
 * Sugar OAuth2.0 Storage system, allows the OAuth2 library we are using to 
 * store and retrieve data.
 * This class should only be used by the OAuth2 library and cannot be relied
 * on as a stable API for any other sources. 
 */
abstract class SugarOAuth2StoragePlatform  {
    /**
     * The name of the platform. Does not have to be set but if it is will be used
     * to identify the platform for this storage mechanism.
     * 
     * @var string
     */
    protected $platformName = null;
    
    /**
     * The client type that this client is associated with
     * 
     * @var string
     */
    protected $clientType = null;
    
    // When we authenticate these beans, store them here so if the user id's match (which it will), we just use these instead
    
    /**
     * The SugarCRM User record for this user
     * @var User
     */
    protected $userBean;
    
    /**
     * The record of the OAuth Key based off of the user's supplide client_id
     * @var OAuthKeys
     */
    protected $oauthKeyRecord;

    /**
     * The user type for this client
     * 
     * @var string
     */
    protected $userType;

    /**
     * How many simultaneous sessions allowed for this platform
     *
     * @var int
     */
    public $numSessions = 1;

    // BEGIN METHODS FROM IOAuth2GrantUser
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
	abstract public function checkUserCredentials(IOAuth2GrantUser $storage, $client_id, $username, $password);
    // END METHODS FROM IOAuth2GrantUser
    
    /**
     * Allows setting of the platform name from the server
     * 
     * @param string $name The name of the platform to set to
     */
    public function setPlatformName($name) {
        $this->platformName = $name;
    }

    /**
     * Gets the platform name of the given storage mechanism
     * 
     * @return string
     */
    public function getPlatformName() {
        // If the class sets the name of its platform, use it
        if (!empty($this->platformName)) {
            return $this->platformName;
        }
        
        // Send back the name of the platform from the class name
        return strtolower(str_replace('SugarOAuth2Storage', '', get_class($this)));
    }

    /**
     * Gets the client type associated with this storage
     * 
     * @return string
     */
    public function getClientType() {
        return $this->clientType;
    }
    
    /**
     * Get the user type for this user
     * 
     * @return string
     */
    public function getUserType() {
        return $this->userType;
    }
}
