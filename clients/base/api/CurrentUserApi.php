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

require_once('modules/Users/password_utils.php');

class CurrentUserApi extends SugarApi
{
    /**
     * Hash of user preference indexes and their corresponding metadata index 
     * name. This is used in both the user pref setting in this class and in 
     * user preference setting in BWC mode. The list of preference indexes will
     * be used by the BWC implementation to determine whether the state of the 
     * user has changed so as to notify clients that they need to rerequest user
     * data.
     * 
     * @var array
     */
    protected $userPrefMeta = array(
        'timezone' => 'timezone',
        'datef' => 'datepref',
        'timef' => 'timepref',
        'currency' => 'currency',
        'signature_default' => 'signature_default',
        'email_link_type' => 'email_link_type',
        'default_locale_name_format' => 'default_locale_name_format',
        'fdow' => 'first_day_of_week',
        'sweetspot' => 'sweetspot',
        'shortcuts' => 'shortcuts',
        'reminder_time' => 'reminder_time',
        'email_reminder_time' => 'email_reminder_time',
    );

    const TYPE_ADMIN = "admin";
    const TYPE_USER = "user";

    public function registerApiRest()
    {
        return array(
            'retrieve' => array(
                'reqType' => 'GET',
                'path' => array('me',),
                'pathVars' => array(),
                'method' => 'retrieveCurrentUser',
                'shortHelp' => 'Returns current user',
                'longHelp' => 'include/api/help/me_get_help.html',
                'ignoreMetaHash' => true,
                'ignoreSystemStatusError' => true,
                'noEtag' => true,
            ),
            'update' => array(
                'reqType' => 'PUT',
                'path' => array('me',),
                'pathVars' => array(),
                'method' => 'updateCurrentUser',
                'shortHelp' => 'Updates current user',
                'longHelp' => 'include/api/help/me_put_help.html',
                'ignoreMetaHash' => true,
            ),
            'updatePassword' =>  array(
                'reqType' => 'PUT',
                'path' => array('me','password'),
                'pathVars'=> array(''),
                'method' => 'updatePassword',
                'shortHelp' => "Updates current user's password",
                'longHelp' => 'include/api/help/me_password_put_help.html',
            ),
            'verifyPassword' =>  array(
                'reqType' => 'POST',
                'path' => array('me','password'),
                'pathVars'=> array(''),
                'method' => 'verifyPassword',
                'shortHelp' => "Verifies current user's password",
                'longHelp' => 'include/api/help/me_password_post_help.html',
            ),

            'userPreferences' =>  array(
                'reqType' => 'GET',
                'path' => array('me','preferences'),
                'pathVars'=> array(),
                'method' => 'userPreferences',
                'shortHelp' => "Returns all the current user's stored preferences",
                'longHelp' => 'include/api/help/me_preferences_get_help.html',
                'ignoreMetaHash' => true,
            ),

            'userPreferencesSave' =>  array(
                'reqType' => 'PUT',
                'path' => array('me','preferences'),
                'pathVars'=> array(),
                'method' => 'userPreferencesSave',
                'shortHelp' => "Mass Save Updated Preferences For a User",
                'longHelp' => 'include/api/help/me_preferences_put_help.html',
            ),

            'userPreference' =>  array(
                'reqType' => 'GET',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreference',
                'shortHelp' => "Returns a specific preference for the current user",
                'longHelp' => 'include/api/help/me_preference_preference_name_get_help.html',
            ),

            'userPreferenceCreate' =>  array(
                'reqType' => 'POST',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreferenceSave',
                'shortHelp' => "Create a preference for the current user",
                'longHelp' => 'include/api/help/me_preference_preference_name_post_help.html',
            ),
            'userPreferenceUpdate' =>  array(
                'reqType' => 'PUT',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreferenceSave',
                'shortHelp' => "Update a specific preference for the current user",
                'longHelp' => 'include/api/help/me_preference_preference_name_put_help.html',
            ),
            'userPreferenceDelete' =>  array(
                'reqType' => 'DELETE',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreferenceDelete',
                'shortHelp' => "Delete a specific preference for the current user",
                'longHelp' => 'include/api/help/me_preference_preference_name_delete_help.html',
            ),
            'getMyFollowedRecords' => array(
                'reqType' => 'GET',
                'path' => array('me','following'),
                'pathVars' => array('',''),
                'method' => 'getMyFollowedRecords',
                'shortHelp' => 'This method retrieves all followed methods for the user.',
                'longHelp' => 'include/api/help/me_getfollowed_help.html',
            ),
        );
    }

    /**
     * Retrieves the current user info
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function retrieveCurrentUser(ServiceBase $api, array $args)
    {
        $current_user = $this->getUserBean();
        //If the users password is expired, don't generate an etag.
        if (!hasPasswordExpired($current_user)) {
            $hash = $this->getUserHash($current_user);
            if ($api->generateETagHeader($hash, 3)) {
                return;
            }
        }

        $data = $this->getUserData($api, $args);

        if (!empty($data['current_user']['preferences'])) {
            $this->htmlDecodeReturn($data['current_user']['preferences']);
        }

        return $data;
    }

    protected function getUserHash(User $user)
    {
        return $user->getUserMDHash();
    }

    /**
     * Returns TRUE if a user needs to run through the setup wizard after install
     * Used when building $user_data['show_wizard']
     * @return bool TRUE if client should run wizard
     */
    public function shouldShowWizard($category = 'global')
    {
        $current_user = $this->getUserBean();
        $isInstanceConfigured = $current_user->getPreference('ut', $category);
        return !filter_var($isInstanceConfigured, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * If user has exceeded time or number of attempts with a generated password,
     * this sets user data `is_password_expired` to true (otherwise false). Also,
     * if password has expired, than `password_expired_message` is set.
     */
    public function setExpiredPassword($user_data)
    {
        $user_data['is_password_expired'] = false;
        $user_data['password_expired_message'] = "";
        require_once('modules/Users/password_utils.php');
        if (hasPasswordExpired($user_data['user_name'])) {
            $messageLabel = $_SESSION['expiration_label'];
            $message = translate($messageLabel, 'Users');
            $user_data['is_password_expired'] = true;
            $user_data['password_expired_message'] = $message;
            $passwordSettings = $GLOBALS['sugar_config']['passwordsetting'];
            $user_data['password_requirements'] = $this->getPasswordRequirements($passwordSettings);
        }
        return $user_data;
    }

    //Essentially 7.X version of legacy smarty_function_sugar_password_requirements_box
    public function getPasswordRequirements($passwordSettings)
    {
        global $current_language;
        $settings = array();
        $administrationModStrings = return_module_language($current_language, 'Administration');

        //simple password settings keys
        $keys = array(
            'oneupper' => 'LBL_PASSWORD_ONE_UPPER_CASE',
            'onelower' => 'LBL_PASSWORD_ONE_LOWER_CASE',
            'onenumber' => 'LBL_PASSWORD_ONE_NUMBER',
            'onespecial' => 'LBL_PASSWORD_ONE_SPECIAL_CHAR'
        );
        foreach ($keys as $key => $labelKey) {
            if (!empty($passwordSettings[$key])) {
                $settings[$key] = isset($administrationModStrings[$labelKey]) ? $administrationModStrings[$labelKey] : '';
            }
        }
        //custom regex
        if (!empty($passwordSettings['customregex'])) {
            $settings['regex'] = isset($passwordSettings['regexcomment']) ? $passwordSettings['regexcomment'] : '';
        }

        //Handles min/max password length messages
        $min = isset($passwordSettings['minpwdlength']) && $passwordSettings['minpwdlength'] > 0;
        $max = isset($passwordSettings['maxpwdlength']) && $passwordSettings['maxpwdlength'] > 0;
        if ($min && $max) {
            $settings['lengths'] = $administrationModStrings['LBL_PASSWORD_MINIMUM_LENGTH'].' = '.$passwordSettings['minpwdlength'].' '.$administrationModStrings['LBL_PASSWORD_AND_MAXIMUM_LENGTH'].' = '.$passwordSettings['maxpwdlength'];
        } else if ($min) {
            $settings['lengths'] = $administrationModStrings['LBL_PASSWORD_MINIMUM_LENGTH'].' = '.$passwordSettings['minpwdlength'];
        } else if ($max) {
            $settings['lengths'] = $administrationModStrings['LBL_PASSWORD_MAXIMUM_LENGTH'].' = '.$passwordSettings['maxpwdlength'];
        }

        return $settings;
    }

    /**
     * Updates current user info
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function updateCurrentUser(ServiceBase $api, array $args)
    {
        $bean = $this->getUserBean();

        // setting these for the loadBean
        $args['module'] = $bean->module_name;
        $args['record'] = $bean->id;

        $this->updateBean($bean, $api, $args);

        return $this->getUserData($api, $args);
    }

    /**
     * Updates the current user's password
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionMissingParameter|SugarApiExceptionNotFound
     */
    public function updatePassword(ServiceBase $api, array $args)
    {
        $user_data['valid'] = false;

        // Deals with missing required args else assigns oldpass and new paswords
        if (empty($args['old_password']) || empty($args['new_password'])) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter('Error: Missing argument.');
        } else {
            $oldpass = $args['old_password'];
            $newpass = $args['new_password'];
        }

        $bean = $this->getUserIfPassword($oldpass);
        if (null !== $bean) {
            $change = $this->changePassword($bean, $oldpass, $newpass);
            $user_data = array_merge($user_data, $change);
        } else {
            $user_data['message'] = $GLOBALS['app_strings']['LBL_INCORRECT_PASSWORD'];
        }

        return $user_data;
    }

    /**
     * Verifies against the current user's password
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function verifyPassword(ServiceBase $api, array $args)
    {
        $user_data['valid'] = false;

        // Deals with missing required args else assigns oldpass and new paswords
        if (empty($args['password_to_verify'])) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter('Error: Missing argument.');
        }

        // If the user password is good, send that messaging back
        if (!is_null($this->getUserIfPassword($args['password_to_verify']))) {
            $user_data['valid'] = true;
            $user_data['message'] = 'Password verified.';
            $user_data['expiration'] = $this->getUserLoginExpirationPreference();
        }

        return $user_data;
    }

    /**
     * Gets acls given full module list passed in.
     * @param string The platform e.g. portal, mobile, base, etc.
     * @return array
     */
    public function getAcls($platform)
    {
        // in this case we should always have current_user be the user
        global $current_user;
        $mm = $this->getMetaDataManager($platform);
        $fullModuleList = array_keys($GLOBALS['app_list_strings']['moduleList']);
        $acls = array();
        foreach ($fullModuleList as $modName) {
            $bean = BeanFactory::newBean($modName);
            if (!$bean || !is_a($bean,'SugarBean') ) {
                // There is no bean, we can't get data on this
                continue;
            }


            $acls[$modName] = $mm->getAclForModule($modName,$current_user);
            $acls[$modName] = $this->verifyACLs($acls[$modName]);
        }
        // Handle enforcement of acls for clients that override this (e.g. portal)
        $acls = $this->enforceModuleACLs($acls);

        return $acls;
    }

    /**
     * Manipulates the ACLs as needed, per client
     *
     * @param  array $acls
     * @return array
     */
    protected function verifyACLs(Array $acls)
    {
        // No manipulation for base acls
        return $acls;
    }

    /**
     * Enforces module specific ACLs for users without accounts, as needed
     *
     * @param  array $acls
     * @return array
     */
    protected function enforceModuleACLs(Array $acls)
    {
        // No manipulation for base acls
        return $acls;
    }

    /**
     * Checks a given password and sends back the user bean if the password matches
     *
     * @param  string $passwordToVerify
     * @return User
     */
    protected function getUserIfPassword($passwordToVerify)
    {
        $user = BeanFactory::getBean('Users', $GLOBALS['current_user']->id);
        $currentPassword = $user->user_hash;
        if (User::checkPassword($passwordToVerify, $currentPassword)) {
            return $user;
        }

        return null;
    }
    
    /**
     * Gets the list of fields that should trigger a user metadata change reauth
     *
     * @return array
     */
    public function getUserPrefsToCache()
    {
        return $this->userPrefMeta;
    }

    /**
     * Gets a single preference for a user by name
     * 
     * @param User $user Current User object
     * @param string $pref The name of the pref to get
     * @param string $metaName The metadata property name, usually the same as $pref
     * @param string $category The category for the preference
     * @return array 
     */
    protected function getUserPref(User $user, $pref, $metaName, $category = 'global')
    {
        $method = 'getUserPref' . ucfirst($pref);
        if (method_exists($this, $method)) {
            return $this->$method($user, $category);
        }

        // Get the val so we can check for null
        $val = $user->getPreference($pref, $category);

        // Set nulls to empty string
        if (is_null($val)) {
            $val = '';
        }

        return array($metaName => $val);
    }

    /**
     * Gets the user preference name by meta name.
     *
     * @param string $metaName
     * @return string
     */
    protected function getUserPreferenceName($metaName)
    {
        if(false !== $preferenceName = array_search($metaName, $this->userPrefMeta)) {
            return $preferenceName;
        }
        return $metaName;
    }

    /**
     * Gets the user's timezone setting
     * 
     * @param User $user The current user
     * @return string
     */
    protected function getUserPrefTimezone(User $user, $category = 'global')
    {
        // Grab the user's timezone preference if it's set
        $val = $user->getPreference('timezone', $category);

        $timeDate = TimeDate::getInstance();

        // If there is no setting for the user, fall back to the system setting
        if (!$val) {
            $val = $timeDate->guessTimezone();
        }

        // If there is still no timezone, fallback to UTC
        if (!$val) {
            $val = 'UTC';
        }

        $dateTime = new SugarDateTime();
        $timeDate->tzUser($dateTime, $user);
        $offset = $timeDate->getIsoOffset($dateTime,array('stripTZColon' => true));
        $offsetSec = $dateTime->getOffset();

        return array('timezone' => $val, 'tz_offset' => $offset, 'tz_offset_sec' => $offsetSec);
    }
    
    protected function getUserPrefCurrency(User $user, $category = 'global')
    {
        global $locale;
        
        $currency = BeanFactory::newBean('Currencies');
        $currency_id = $user->getPreference('currency', $category);
        $currency->retrieve($currency_id);
        $return['currency_id'] = $currency->id;
        $return['currency_name'] = $currency->name;
        $return['currency_symbol'] = $currency->symbol;
        $return['currency_iso'] = $currency->iso4217;
        $return['currency_rate'] = $currency->conversion_rate;
        $return['currency_show_preferred'] = $user->getPreference('currency_show_preferred');
        
        // user number formatting prefs
        $return['decimal_precision'] = $locale->getPrecision();
        $return['decimal_separator'] = $locale->getDecimalSeparator();
        $return['number_grouping_separator'] = $locale->getNumberGroupingSeparator();
        
        return $return;
    }
    
    /**
     * Helper function that gets a default signature user pref
     * 
     * @param User $user Current User
     * @return array
     */
    protected function getUserPrefSignature_default(User $user)
    {
        // email signature preferences
        return array('signature_default' => $user->getDefaultSignature());
    }

    /**
     * Helper function to get the email link type user pref
     * @param User $user Current User object
     * @return array
     */
    protected function getUserPrefEmail_link_type(User $user)
    {
        $emailClientPreference = $user->getEmailClientPreference();
        $preferences = array ('type' => $emailClientPreference);

        if ($emailClientPreference === 'sugar') {
            $statusCode = OutboundEmailConfigurationPeer::getMailConfigurationStatusForUser($user);
            if($statusCode != OutboundEmailConfigurationPeer::STATUS_VALID_CONFIG) {
                $preferences['error'] = array (
                    'code' => $statusCode,
                    'message' => OutboundEmailConfigurationPeer::$configurationStatusMessageMappings[$statusCode],
                );
            }
        }

        return array(
            'email_client_preference' => $preferences,
        );
    }

    /**
     * Utility function to get the users preferred language
     * 
     * @param User $user Current User object
     * @return array
     */
    protected function getUserPrefLanguage(User $user)
    {
        // use their current auth language if it exists
        if (!empty($_SESSION['authenticated_user_language'])) {
            $language = $_SESSION['authenticated_user_language'];
        } elseif (!empty($user->preferred_language)) {
            // if current auth language doesn't exist get their preferred lang from the user obj
            $language = $user->preferred_language;
        } else {
            // if nothing exists, get the sugar_config default language
            $language = $GLOBALS['sugar_config']['default_language'];
        }
        
        return array('language' => $language);
    }

    /**
     * Returns all the user data to be sent in the REST API call for a normal
     * `/me` call.
     *
     * This data is dependent on the platform used. Each own platform has a
     * different data set to be sent in the response.
     *
     * @param ServiceBase $api
     * @param array $options A list of options like `category` to retrieve the
     *   basic user info. Will use `global` if no `category` is supplied.
     * @return array The user's data to be used in a `/me` request.
     */
    protected function getUserData(ServiceBase $api, array $options)
    {
        $platform = $api->platform;
        $current_user = $this->getUserBean();

        // Get the basics
        $category = isset($options['category']) ? $options['category'] : 'global';
        $user_data = $this->getBasicUserInfo($platform, $category);

        // Fill in the rest
        $user_data['type'] = self::TYPE_USER;
        if ($current_user->isAdmin()) {
            $user_data['type'] = self::TYPE_ADMIN;
        }
        $user_data['show_wizard'] = $this->shouldShowWizard($category);
        $user_data['id'] = $current_user->id;
        $current_user->_create_proper_name_field();
        $user_data['full_name'] = $current_user->full_name;
        $user_data['user_name'] = $current_user->user_name;
        $user_data['roles'] = ACLRole::getUserRoles($current_user->id);
        $user_data = $this->setExpiredPassword($user_data);
        $user_data['picture'] = $current_user->picture;
        $user_data['acl'] = $this->getAcls($platform);
        $user_data['is_manager'] = User::isManager($current_user->id);
        $user_data['is_top_level_manager'] = false;
        $user_data['reports_to_id'] = $current_user->reports_to_id;
        $user_data['reports_to_name'] = $current_user->reports_to_name;
        if($user_data['is_manager']) {
            $user_data['is_top_level_manager'] = User::isTopLevelManager($current_user->id);
        }

        // Email addresses
        $fieldDef = $current_user->getFieldDefinition('email');

        if (!$fieldDef) {
            $fieldDef = [];
        }

        $sf = SugarFieldHandler::getSugarField('email');
        $sf->apiFormatField($user_data, $current_user, $options, 'email', $fieldDef, ['email'], $api);

        // Address information
        $user_data['address_street'] = $current_user->address_street;
        $user_data['address_city'] = $current_user->address_city;
        $user_data['address_state'] = $current_user->address_state;
        $user_data['address_country'] = $current_user->address_country;
        $user_data['address_postalcode'] = $current_user->address_postalcode;

        require_once 'modules/Teams/TeamSetManager.php';

        $teams = $current_user->get_my_teams();
        $my_teams = array();
        foreach ($teams as $id => $name) {
            $my_teams[] = array('id' => $id, 'name' => $name,);
        }
        $user_data['my_teams'] = $my_teams;

        $defaultTeams = TeamSetManager::getTeamsFromSet($current_user->team_set_id);
        $defaultSelectedTeamIds = array();
        foreach (TeamSetManager::getTeamsFromSet($current_user->acl_team_set_id) as $selectedTeam) {
            $defaultSelectedTeamIds[] = $selectedTeam['id'];
        }
        foreach ($defaultTeams as $id => $team) {
            $defaultTeams[$id]['primary'] = false;
            if ($team['id'] == $current_user->team_id) {
                $defaultTeams[$id]['primary'] = true;
            }
            $defaultTeams[$id]['selected'] = in_array($team['id'], $defaultSelectedTeamIds);
        }
        $user_data['preferences']['default_teams'] = $defaultTeams;

        // Send back a hash of this data for use by the client
        $user_data['_hash'] = $current_user->getUserMDHash();

        return array('current_user' => $user_data);
    }

    /**
     * Gets the basic user data that all users that are logged in will need. Client
     * specific user information will be filled in within the client API class.
     *
     * @param string $platform The platform for this request
     * @return array
     */
    protected function getBasicUserInfo($platform, $category = 'global')
    {
        global $current_user;
        
        $this->forceUserPreferenceReload($current_user);
        
        $user_data['preferences'] = array();
        foreach ($this->userPrefMeta as $pref => $metaName) {
            // Twitterate this, since long lines are the devil
            $val = $this->getUserPref($current_user, $pref, $metaName, $category);
            $user_data['preferences'] = array_merge($user_data['preferences'], $val);
        }
        
        // Handle language on its own for now
        $lang = $this->getUserPrefLanguage($current_user);
        $user_data['preferences'] = array_merge($user_data['preferences'], $lang);
        
        // Set the user module list
        $user_data['module_list'] = $this->getModuleList($platform);
        
        return $user_data;
    }

    /**
     * Gets the user bean for the user of the api
     *
     * @return User
     */
    protected function getUserBean()
    {
        global $current_user;

        return $current_user;
    }

    /**
     * Changes a password for a user from old to new
     *
     * @param  User   $bean User bean
     * @param  string $old  Old password
     * @param  string $new  New password
     * @return array
     */
    protected function changePassword(SugarBean $bean, $old, $new)
    {
        if ($bean->change_password($old, $new)) {
            return array(
                'valid' => true,
                'message' => 'Password updated.',
                'expiration' => $bean->getPreference('loginexpiration'),
            );
        }
        //Legacy change_password populates user bean with an error_string on error
        $errorMessage = isset($bean->error_string) ? $bean->error_string : $GLOBALS['app_strings']['LBL_PASSWORD_UPDATE_GENERIC_ISSUE'];
        return array(
            'valid' => false,
            'message' => $errorMessage,
        );
    }

    /**
     * Gets the preference for user login expiration
     *
     * @return string
     */
    protected function getUserLoginExpirationPreference()
    {
        global $current_user;

        return $current_user->getPreference('loginexpiration');
    }

    /**
     * Return all the current users preferences
     *
     * @param  ServiceBase $api  Api Service
     * @param  array       $args Array of arguments from the rest call
     * @return mixed       User Preferences, if the category exists.  If it doesn't then return an empty array
     */
    public function userPreferences(ServiceBase $api, array $args)
    {
        $current_user = $this->getUserBean();

        // For filtering results back
        $pref_filter = array();
        if (isset($args['pref_filter'])) {
            $pref_filter = explode(',', $args['pref_filter']);
        }

        $category = 'global';
        if (isset($args['category'])) {
            $category = $args['category'];
        }
        $this->forceUserPreferenceReload($current_user);

        $prefs = (isset($current_user->user_preferences[$category])) ?
                        $current_user->user_preferences[$category] :
                        array();
        
        // Handle filtration of requested preferences
        $data = $this->filterResults($prefs, $pref_filter);
        $this->htmlDecodeReturn($data);
        return $data;
    }
    
    /**
     * Filters results from a preferences request against a list of prefs
     * 
     * @param array $prefs Preferences collection for a user
     * @param array $prefFilter Filter definition to filter against
     * @return array
     */
    protected function filterResults($prefs, $prefFilter) 
    {
        if (empty($prefFilter) || !is_array($prefFilter)) {
            return $prefs;
        }

        $return = array();
        foreach ($prefFilter as $key) {
            if (isset($prefs[$key])) {
                $return[$key] = $prefs[$key];
            }
        }
        return $return;
    }
    /**
     * Update multiple user preferences at once
     *
     * @param  ServiceBase $api  Api Service
     * @param  array       $args Array of arguments from the rest call
     * @return mixed       Return the updated keys with their values
     */
    public function userPreferencesSave(ServiceBase $api, array $args)
    {
        $current_user = $this->getUserBean();

        $category = 'global';
        if (isset($args['category'])) {
            $category = $args['category'];
            unset($args['category']);
        }

        // set each of the args in the array
        foreach ($args as $key => $value) {
            $preferenceName = $this->getUserPreferenceName($key);
            $current_user->setPreference($preferenceName, $value, 0, $category);
        }

        // save the preferences to the db
        $current_user->save();
        $args['_hash'] = $current_user->getUserMDHash();
        return $args;
    }

    /**
     * Return a specific preference for the key that was passed in.
     *
     * @param  ServiceBase $api
     * @param  array       $args
     * @return mixed
     * @return mixed
     */
    public function userPreference(ServiceBase $api, array $args)
    {
        // Get the pref so we can find out if it needs special handling
        $pref = $args['preference_name'];
        $current_user = $this->getUserBean();

        $category = 'global';
        if (isset($args['category'])) {
            $category = $args['category'];
        }
        $this->forceUserPreferenceReload($current_user);

        // Handle special cases if there are any
        $prefKey = array_search($pref, $this->userPrefMeta);
        $alias   = $prefKey ? $prefKey : $pref;
        $data = $this->getUserPref($current_user, $alias, $pref, $category);

        // If the value of the user pref is not an array, or is an array but does
        // not contain an index with the same name as our pref, send the response 
        // back an array keyed on the pref. This turns prefs like "m/d/Y" or ""
        // into {"datef": "m/d/Y"} on the client.
        if (!is_array($data) || !isset($data[$pref])) {
            $data = array($pref => $data);
        }

        $this->htmlDecodeReturn($data);
        return $data;
    }

    /**
     * Update a preference.  The key is part of the url and the value comes from the value $args variable
     *
     * @param  ServiceBase $api
     * @param  array       $args
     * @return array
     */
    public function userPreferenceSave(ServiceBase $api, array $args)
    {
        $current_user = $this->getUserBean();

        $category = 'global';
        if (isset($args['category'])) {
            $category = $args['category'];
        }

        $preferenceName = $this->getUserPreferenceName($args['preference_name']);

        $current_user->setPreference($preferenceName, $args['value'], 0, $category);
        $current_user->save();

        return array($preferenceName => $args['value']);
    }

    /**
     * Delete a preference.  Since there is no way to actually delete with out resetting the whole category, we just
     * set the value of the key = null.
     *
     * @param  ServiceBase $api
     * @param  array       $args
     * @return array
     */
    public function userPreferenceDelete(ServiceBase $api, array $args)
    {
        $current_user = $this->getUserBean();

        $category = 'global';
        if (isset($args['category'])) {
            $category = $args['category'];
        }

        $preferenceName = $this->getUserPreferenceName($args['preference_name']);

        $current_user->setPreference($preferenceName, null, 0, $category);
        $current_user->save();

        return array($preferenceName => "");
    }

    /**
     * Gets the module list for the current user and platform
     * 
     * @param string $platform The platform for this request
     * @return array
     */
    public function getModuleList($platform = '')
    {
        return $this->getMetaDataManager($platform)->getUserModuleList();
    }
    
    /**
     * Forces a fresh fetching of user preferences.
     * 
     * User preferences are written to the users session, so when an admin changes
     * a preference for a user, that user won't get the change until they logout.
     * This forces a fresh fetching of a users preferences from the DB when called.
     * This shouldn't be too expensive of a hit since user preferences need only
     * be fetched once and can be stored on the client.
     * 
     * @param User $current_user A User bean
     */
    public function forceUserPreferenceReload($current_user)
    {
        $current_user->reloadPreferences();
    }

    /**
     * Get all of the records a user follows.
     * @param ServiceBase $api
     * @param array $args
     * @return array - records user follows
     */
    public function getMyFollowedRecords(ServiceBase $api, array $args)
    {
        $current_user = $this->getUserBean();

        $options = array();
        $options['limit'] = !empty($args['limit']) ? $args['limit'] : 20;
        $options['offset'] = 0;

        if (!empty($args['offset'])) {
            if ($args['offset'] == 'end') {
                $options['offset'] = 'end';
            } else {
                $options['offset'] = (int) $args['offset'];
            }
        }
        $records = Subscription::getSubscribedRecords($current_user, 'array', $options);
        $beans = array();

        $data = array();
        $data['next_offset'] = -1;
        foreach ($records as $i => $record) {
            if ($i == $options['limit']) {
                $data['next_offset'] = (int) ($options['limit'] + $options['offset']);
                continue;
            }
            $beans[] = BeanFactory::getBean($record['parent_type'], $record['parent_id']);
        }

        $data['records'] = $this->formatBeans($api, $args, $beans);
        return $data;
    }
}
