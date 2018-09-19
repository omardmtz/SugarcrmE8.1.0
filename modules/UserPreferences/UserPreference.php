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
/*********************************************************************************

 * Description: Handles the User Preferences and stores them in a separate table.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class UserPreference extends SugarBean
{
    public $db;

    // Stored fields
    public $id;
    public $date_entered;
    public $date_modified;
    public $assigned_user_id;
    public $assigned_user_name;
    public $name;
    public $category;
    public $contents;
    public $deleted;

    public $object_name = 'UserPreference';
    public $table_name = 'user_preferences';

    public $disable_row_level_security = true;
    public $module_dir = 'UserPreferences';
    public $field_defs = array();
    public $field_defs_map = array();
    public $new_schema = true;

    protected $cache;

    protected $_userFocus;

    // Do not actually declare, use the functions statically
    public function __construct(User $user = null)
    {
        parent::__construct();

        $this->_userFocus = $user;
        $this->tracker_visibility = false;
        $this->cache = SugarCache::instance();
    }

    /**
     * Get preference by name and category. Lazy loads preferences from the database per category
     *
     * @param string $name name of the preference to retreive
     * @param string $category name of the category to retreive, defaults to global scope
     * @return mixed the value of the preference (string, array, int etc)
     */
    public function getPreference($name, $category = 'global')
    {
        global $sugar_config;

        $cachedPrefs = $this->getFromCache($category);
        // if the unique key in session doesn't match the app or prefereces are empty
        if (!isset($cachedPrefs) || (!empty($_SESSION['unique_key']) && $_SESSION['unique_key'] != $sugar_config['unique_key'])) {
            $this->loadPreferences($category);
            $cachedPrefs = $this->getFromCache($category);
        }
        if (isset($cachedPrefs[$name])) {
            return $cachedPrefs[$name];
        }

        // check to see if a default preference ( i.e. $sugar_config setting ) exists for this value )
        // if so, return it
        $value = $this->getDefaultPreference($name, $category);
        if (!is_null($value)) {
            return $value;
        }

        return null;
    }

    /**
     * Get preference by name and category from the system settings.
     *
     * @param string $name name of the preference to retreive
     * @param string $category name of the category to retreive, defaults to global scope
     * @return mixed the value of the preference (string, array, int etc)
     */
    public function getDefaultPreference($name, $category = 'global')
    {
        global $sugar_config;

        // Doesn't support any prefs but global ones
        if ( $category != 'global' )
            return null;

        if ( $name == 'datef' ) // Use default date format from Admin panel (Bug57252)
            return $sugar_config['default_date_format'];
        if ( $name == 'timef' )  // Use default time format from Admin panel (Bug57252)
            return $sugar_config['default_time_format'];

        // First check for name matching $sugar_config variable
        if ( isset($sugar_config[$name]) )
            return $sugar_config[$name];

        // Next, check to see if it's one of the common problem ones
        if ( isset($sugar_config['default_'.$name]) )
            return $sugar_config['default_'.$name];

        if ( $name == 'email_link_type' )
            return $sugar_config['email_default_client'];

    }

    public function removePreference($name, $category = 'global')
    {
        $cached = $this->getFromCache($category);
        if (isset($cached[$name])) {
            unset($cached[$name]);
            $this->storeToCache($cached, $category);
        }

        $this->savePreferencesToDB(true);
    }

    /**
     * Set preference by name and category. Saving will be done in utils.php -> sugar_cleanup
     *
     * @param string $name name of the preference to retreive
     * @param mixed $value value of the preference to set
     * @param string $category name of the category to retreive, defaults to global scope
     */
    public function setPreference($name, $value, $category = 'global')
    {
        if (empty($this->_userFocus->id)) {
            return;
        }

        $cachedPrefs = $this->getFromCache($category);

        if (!is_array($cachedPrefs)) {
            $this->loadPreferences($category);
            $cachedPrefs = $this->getFromCache($category);
        }
        if (!is_array($cachedPrefs)) {
            $cachedPrefs = array();
        }

        // preferences changed or a new preference, save it to DB
        if (!isset($cachedPrefs[$name]) || (isset($cachedPrefs[$name]) && $cachedPrefs[$name] != $value)) {
            $GLOBALS['savePreferencesToDB'] = true;
            if (!isset($GLOBALS['savePreferencesToDBCats'])) {
                $GLOBALS['savePreferencesToDBCats'] = array();
            }
            $GLOBALS['savePreferencesToDBCats'][$category] = true;
        }

        $cachedPrefs[$name] = $value;
        $this->storeToCache($cachedPrefs, $category);
    }

    /**
     * Loads preference by category from database. Saving will be done in utils.php -> sugar_cleanup
     *
     * @param string $category name of the category to retreive, defaults to global scope
     * @return bool successful?
     */
    public function loadPreferences($category = 'global')
    {
        global $sugar_config;
        $user = $this->_userFocus;

        if ($user->object_name != 'User') {
            return null;
        }

        $cachedPrefs = $this->getFromCache($category);

        if (!empty($user->id) && (!isset($cachedPrefs)
                || (!empty($_SESSION['unique_key']) && $_SESSION['unique_key'] != $sugar_config['unique_key']))
        ) {
            // cn: moving this to only log when valid - throwing errors on install
            return $this->reloadPreferences($category);
        }

        return null;
    }

    /**
     * Unconditionally reloads user preferences from the DB and updates the session
     * @param string $category name of the category to retreive, defaults to global scope
     * @return bool successful?
     */
    public function reloadPreferences($category = 'global')
    {
        $user = $this->_userFocus;

        if ($user->object_name != 'User' || empty($user->id)) {
            return false;
        }
        $GLOBALS['log']->debug('Loading Preferences DB ' . $user->id);

        if (!isset($user->user_preferences) || !is_array($user->user_preferences)) {
            $user->user_preferences = array();
        }
        $conn = DBManagerFactory::getInstance()->getConnection();
        $stmt = $conn->executeQuery(
            'SELECT contents FROM user_preferences WHERE assigned_user_id = ? AND category = ? AND deleted = 0',
            array(
                $user->id,
                $category,
            )
        );
        $contents = $stmt->fetchColumn();

        $value = array();
        if ($contents) {
            $value = unserialize(base64_decode($contents));
        }

        $this->storeToCache($value, $category);
        $user->user_preferences[$category] = $value;

        return !empty($contents);
    }

    protected function storeToCache($value, $category = null)
    {
        $cacheKey = $this->_userFocus->id . '_PREFERENCES';
        if (!empty($category)) {
            $stored = $this->getFromCache();
            if (!is_array($stored)) {
                $stored = array();
            }
            if (is_null($value)) {
                unset($stored[$category]);
            } else {
                $stored[$category] = $value;
            }
            $value = $stored;
        }
        $ttl = SugarConfig::getInstance()->get('oauth2.max_session_lifetime', ini_get('session.gc_maxlifetime'));
        $this->cache->set($cacheKey, $value, $ttl);
    }

    protected function getFromCache($category = null)
    {
        $cacheKey = $this->_userFocus->id . '_PREFERENCES';
        //SugarCache has a mem layer that ensures we only actually load from external cache once per request,
        //even if grabbing multiple categories.
        $prefs = $this->cache->$cacheKey;
        if (empty($category)) {
            return $prefs;
        }
        if (isset($prefs[$category])) {
            return $prefs[$category];
        }

        return null;
    }

    /**
     * Loads users timedate preferences
     *
     * @return array 'date' - date format for user ; 'time' - time format for user
     */
    public function getUserDateTimePreferences()
    {
        global $sugar_config, $db, $timedate, $current_user;

        $user = $this->_userFocus;

        $prefDate = array();

        if(!empty($user) && $this->loadPreferences('global')) {
            // forced to set this to a variable to compare b/c empty() wasn't working
            $timeZone = TimeDate::userTimezone($user);
            $timeFormat = $user->getPreference("timef");
            $dateFormat = $user->getPreference("datef");

            // cn: bug xxxx cron.php fails because of missing preference when admin hasn't logged in yet
            $timeZone = empty($timeZone) ? 'America/Los_Angeles' : $timeZone;

            if(empty($timeFormat)) $timeFormat = $sugar_config['default_time_format'];
            if(empty($dateFormat)) $dateFormat = $sugar_config['default_date_format'];

            $prefDate['date'] = $dateFormat;
            $prefDate['time'] = $timeFormat;
            $prefDate['userGmt'] = TimeDate::tzName($timeZone);
            $prefDate['userGmtOffset'] = $timedate->getUserUTCOffset($user);

            return $prefDate;
        } else {
            $prefDate['date'] = $timedate->get_date_format();
            $prefDate['time'] = $timedate->get_time_format();

            if(!empty($user) && $user->object_name == 'User') {
                $timeZone = TimeDate::userTimezone($user);
                // cn: bug 9171 - if user has no time zone, cron.php fails for InboundEmail
                if(!empty($timeZone)) {
                    $prefDate['userGmt'] = TimeDate::tzName($timeZone);
                    $prefDate['userGmtOffset'] = $timedate->getUserUTCOffset($user);
                }
            } else {
                $timeZone = TimeDate::userTimezone($current_user);
                if(!empty($timeZone)) {
                    $prefDate['userGmt'] = TimeDate::tzName($timeZone);
                    $prefDate['userGmtOffset'] = $timedate->getUserUTCOffset($current_user);
                }
            }

            return $prefDate;
        }
    }

    /**
     * Saves all preferences into the database that are in the session. Expensive, this is called by default in
     * sugar_cleanup if a setPreference has been called during one round trip.
     *
     * @global user will use current_user if no user specificed in $user param
     * @param user $user User object to retrieve, otherwise user current_user
     * @param bool $all save all of the preferences? (Dangerous)
     *
     */
    public function savePreferencesToDB($all = false)
    {
        global $sugar_config;
        $GLOBALS['savePreferencesToDB'] = false;

        $user = $this->_userFocus;

        // these are not the preferences you are looking for [ hand waving ]
        if (empty($GLOBALS['installing']) && !empty($_SESSION['unique_key'])
            && $_SESSION['unique_key'] != $sugar_config['unique_key']
        ) {
            return;
        }

        $cached = $this->getFromCache();

        $GLOBALS['log']->debug('Saving Preferences to DB ' . $user->id);
        if (isset($cached)) {
            $GLOBALS['log']->debug("Saving Preferences to DB: {$user->id}");
            // only save the categories that have been modified or all?
            if (!$all && isset($GLOBALS['savePreferencesToDBCats']) && is_array($GLOBALS['savePreferencesToDBCats'])) {
                $catsToSave = array();
                foreach ($GLOBALS['savePreferencesToDBCats'] as $category => $value) {
                    if (isset($cached[$category])) {
                        $catsToSave[$category] = $cached[$category];
                    }
                }
            } else {
                $catsToSave = $cached;
            }

            foreach ($catsToSave as $category => $contents) {
                $focus = new UserPreference($this->_userFocus);
                $result = $focus->retrieve_by_string_fields(array(
                    'assigned_user_id' => $user->id,
                    'category' => $category,
                ));
                $focus->assigned_user_id = $user->id; // MFH Bug #13862
                $focus->deleted = 0;
                $focus->contents = base64_encode(serialize($contents));
                $focus->category = $category;
                $focus->save();
            }
        }
    }

    /**
     * Resets preferences for a particular user. If $category is null all user preferences will be reset
     *
     * @param string $category category to reset
     */
    public function resetPreferences($category = null)
    {
        $user = $this->_userFocus;

        $GLOBALS['log']->debug('Reseting Preferences for user ' . $user->id);

        $remove_tabs = $this->getPreference('remove_tabs');
        $favorite_reports = $this->getPreference('favorites', 'Reports');
        $home_pages = $this->getPreference('pages', 'home');
        $home_dashlets = $this->getPreference('dashlets', 'home');
        $ut = $this->getPreference('ut');
        $timezone = $this->getPreference('timezone');
        $hideTabs = $this->canEditTabs() ? array() : $this->getPreference('hide_tabs');

        $query = "UPDATE user_preferences SET deleted = 1 WHERE assigned_user_id = '" . $user->id . "'";
        if ($category) {
            $query .= " AND category = '" . $category . "'";
        }
        $this->db->query($query);

        $this->storeToCache(null, $category);

        if (!$category) {
            if (!empty($_COOKIE['sugar_user_theme']) && !headers_sent()) {
                setcookie('sugar_user_theme', '', time() - 3600); // expire the sugar_user_theme cookie
            }

            // only call session_destroy() when we have a valid session_id
            if ($user->id == $GLOBALS['current_user']->id && session_id() != "") {
                session_destroy();
            }
            $this->setPreference('remove_tabs', $remove_tabs);
            $this->setPreference('favorites', $favorite_reports, 'Reports');
            $this->setPreference('pages', $home_pages, 'home');
            $this->setPreference('dashlets', $home_dashlets, 'home');
            $this->setPreference('ut', $ut);
            $this->setPreference('timezone', $timezone);
            $this->setPreference('reminder_time', 1800);
            $this->setPreference('hide_tabs', $hideTabs);
            $this->savePreferencesToDB();
        }
    }

    /**
     * Check if current user can edit displayed modules
     *
     * @return bool
     */
    protected function canEditTabs()
    {
        if ($GLOBALS['current_user']->isAdminForModule('Users')) {
            return true;
        }
        $tabs = new TabController();
        return $tabs->get_users_can_edit();
    }

    /**
     * Updates every user pref with a new key value supports 2 levels deep, use append to
     * array if you want to append the value to an array
     */
    public static function updateAllUserPrefs(
        $key,
        $new_value,
        $sub_key = '',
        $is_value_array = false,
        $unset_value = false )
    {
        global $current_user, $db;

        // Admin-only function; die if calling as a non-admin
        if(!is_admin($current_user)){
            sugar_die('only admins may call this function');
        }

        // we can skip this if we've already upgraded to the user_preferences format.
        if (!array_key_exists('user_preferences', $db->get_columns('users'))) {
            return;
        }

        $result = $db->query("SELECT id, user_preferences FROM users");
        while ($row = $db->fetchByAssoc($result)) {
            $prefs = array();
            $newprefs = array();

            $prefs = unserialize(base64_decode($row['user_preferences']));

            if(!empty($sub_key)){
                if($is_value_array ){
                    if(!isset($prefs[$key][$sub_key])){
                        continue;
                    }

                    if(empty($prefs[$key][$sub_key])){
                        $prefs[$key][$sub_key] = array();
                    }
                    $already_exists = false;
                    foreach($prefs[$key][$sub_key] as $k=>$value){
                        if($value == $new_value){

                            $already_exists = true;
                            if($unset_value){
                                unset($prefs[$key][$sub_key][$k]);
                            }
                        }
                    }
                    if(!$already_exists && !$unset_value){
                        $prefs[$key][$sub_key][] = $new_value;
                    }
                }
                else{
                    if(!$unset_value)$prefs[$key][$sub_key] = $new_value;
                }
            }
            else{
                if($is_value_array ){
                    if(!isset($prefs[$key])){
                        continue;
                    }

                    if(empty($prefs[$key])){
                        $prefs[$key] = array();
                    }
                    $already_exists = false;
                    foreach($prefs[$key] as $k=>$value){
                        if($value == $new_value){
                            $already_exists = true;

                            if($unset_value){
                                unset($prefs[$key][$k]);
                            }
                        }
                    }
                    if(!$already_exists && !$unset_value){

                        $prefs[$key][] = $new_value;
                    }
                }else{
                    if(!$unset_value)$prefs[$key] = $new_value;
                }
            }

            $newstr = $GLOBALS['db']->quote(base64_encode(serialize($prefs)));
            $db->query("UPDATE users SET user_preferences = '{$newstr}' WHERE id = '{$row['id']}'");
        }

        unset($prefs);
        unset($newprefs);
        unset($newstr);
    }

}
