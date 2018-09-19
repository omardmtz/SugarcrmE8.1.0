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
 * Provides single point of reference to some system info.
 * Implemented for sending info to heartbeat server however can be used as general purpose class.
 */
class SugarSystemInfo
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $sugarConfig = array();

    /**
     * @var DBManager
     */
    protected $db;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var SugarSystemInfo
     */
    private static $instance;

    private function __construct()
    {
        $this->db = DBManagerFactory::getInstance();
        $this->sugarConfig = $GLOBALS['sugar_config'];
        $this->settings = $this->getSettings();
    }

    /**
     * @return SugarSystemInfo
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Returns all system info dictionary
     *
     * @return array
     */
    public function getInfo()
    {
        $info = array_merge(
            $this->getBaseInfo(),
            $this->getApplicationKeyInfo(),
            $this->getEnvInfo(),
            $this->getUsersInfo(),
            $this->getSystemNameInfo(),
            $this->getLatestTrackerIdInfo(),
            $this->getLicensePortalInfo(),
            $this->getDistroInfo()
        );
        return $info;
    }

    /**
     * Returns distro info dictionary
     *
     * @return array
     */
    public function getDistroInfo()
    {
        $info = array();
        if (file_exists('distro.php')) {
            include('distro.php');
            if (!empty($distro_name)) {
                $info['distro_name'] = $distro_name;
            }
        }
        return $info;
    }


    /**
     * Returns base system info dictionary
     *
     * @return array
     */
    public function getBaseInfo()
    {
        $info = $this->getAppInfo();
        $info = array_merge($info, $this->getLicenseInfo());
        return $info;
    }

    /**
     * Returns users info dictionary
     *
     * @return array
     */
    public function getUsersInfo()
    {
        return array(
            'users' => $this->getActiveUsersCount(),
            'registered_users' => $this->getUsersCount(),
            'admin_users' => $this->getAdminCount(),
            'users_active_30_days' => $this->getActiveUsersXDaysCount(30)
        );
    }

    /**
     * Returns number of active (logged in at least once) users since $days
     *
     * @param int $days
     * @return int
     */
    public function getActiveUsersXDaysCount($days)
    {
        $info = $this->getAppInfo();
        if(version_compare($info['sugar_version'], '7.2', '>')) {
            $query = "SELECT COUNT(users.id) AS user_count FROM users WHERE users.last_login >= %s AND %s";
        } else {
            $query = "SELECT COUNT(DISTINCT users.id) AS user_count FROM tracker, users WHERE users.id = tracker.user_id AND tracker.date_modified >= %s AND %s";
        }
        $query = sprintf($query, $this->getLastXDays($days), $this->getActiveUsersWhere('users'));
        return $this->db->getOne($query, false, 'fetching last 30 users count');
    }

    /**
     * Returns number of admins in the system
     *
     * @return int
     */
    public function getAdminCount()
    {
        $query = "SELECT COUNT(id) AS count FROM users WHERE is_admin = 1 AND " . $this->getActiveUsersWhere();
        return $this->db->getOne($query, false, 'fetching admin count');
    }

    /**
     * Returns number of all users in the system
     *
     * @return int
     */
    public function getUsersCount()
    {
        $query = "SELECT COUNT(id) AS count FROM users WHERE " . $this->getExcludeSystemUsersWhere();
        return $this->db->getOne($query, false, 'fetching all users count');
    }

    /**
     * Returns number of all active users in the system
     *
     * @return int
     */
    public function getActiveUsersCount()
    {
        $query = "SELECT COUNT(id) AS total FROM users WHERE " . $this->getActiveUsersWhere();
        return $this->db->getOne($query, false, 'fetching active users count');
    }

    /**
     * Returns system name
     *
     * @return string
     */
    public function getSystemName()
    {
        return (!empty($this->settings['system_name'])) ? substr(
            $this->settings['system_name'],
            0,
            255
        ) : '';
    }

    /**
     * Returns system name dictionary
     *
     * @return array
     */
    public function getSystemNameInfo()
    {
        return array('system_name' => $this->getSystemName());
    }

    /**
     * Returns License info dictionary
     *
     * @return array
     */
    public function getLicenseInfo()
    {
        $info = array();
        if (!empty($this->settings)) {
            $info['license_users'] = $this->settings['license_users'];
            $info['license_expire_date'] = $this->settings['license_expire_date'];
            $info['license_key'] = $this->settings['license_key'];
            if (!empty($this->settings['license_num_portal_users'])) {
                $info['license_num_portal_users'] = $this->settings['license_num_portal_users'];
            } else {
                $info['license_num_portal_users'] = '';
            }
        }
        $info['license_portal_ex'] = 0;
        $info['license_portal_max'] = 0;
        return $info;
    }

    /**
     * Returns license portal info dictionary
     *
     * @return array
     */
    public function getLicensePortalInfo()
    {
        $info = array();
        $query = sprintf(
            "SELECT count(*) AS record_count FROM session_history WHERE is_violation = 1 AND date_entered >= %s",
            $this->getLastXDays(30)
        );
        $result = $this->db->getOne($query);
        if ($result) {
            $info['license_portal_ex'] = $result;
        }

        $query = sprintf(
            "SELECT MAX(num_active_sessions) AS record_max FROM session_history WHERE date_entered >= %s",
            $this->getLastXDays(30)
        );
        $result = $this->db->getOne($query);
        $info['license_portal_max'] = 0;
        if (is_numeric($result)) {
            $info['license_portal_max'] = $result;
        }
        return $info;
    }


    /**
     * Returns env info dictionary
     *
     * @return array
     */
    public function getEnvInfo()
    {
        $info = array();
        $info['php_version'] = phpversion();
        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            $info['server_software'] = $_SERVER['SERVER_SOFTWARE'];
        }
        $info['db_type'] = $this->sugarConfig['dbconfig']['db_type'];
        $info['db_version'] = $this->db->version();
        $info['os'] = php_uname('s');
        $info['os_version'] = php_uname('r');
        $info['timezone_u'] = $GLOBALS['current_user']->getPreference('timezone');
        $info['timezone'] = date('e');
        if ($info['timezone'] == 'e') {
            $info['timezone'] = date('T');
        }
        return $info;
    }

    /**
     * Returns app info dictionary
     *
     * @return array
     */
    public function getAppInfo()
    {
        $info = array();
        $sugar_db_version = $sugar_version = $sugar_flavor = '';
        require 'sugar_version.php';
        $info['sugar_db_version'] = $sugar_db_version;
        $info['sugar_version'] = $sugar_version;
        $info['sugar_flavor'] = $sugar_flavor;
        $info['auth_level'] = 0;
        return $info;
    }

    /**
     * Returns app key dictionary
     *
     * @return array
     */
    public function getApplicationKeyInfo()
    {
        return array('application_key' => $this->sugarConfig['unique_key']);
    }

    /**
     * Returns Latest tracker id dictionary
     *
     * @return array
     */
    public function getLatestTrackerIdInfo()
    {
        $info = array();
        $query = "SELECT id FROM tracker ORDER BY date_modified desc";
        $id = $this->db->getOne($query, false, 'fetching most recent tracker entry');
        $info['latest_tracker_id'] = (int)$id;
        return $info;
    }

    /**
     * @return string
     */
    public function getLicenseKey()
    {
        return $this->settings['license_key'];
    }

    /**
     * Returns db-formatted 30-days-ago date
     *
     * @param $days
     * @return string
     */
    protected function getLastXDays($days)
    {
        $days = (int)$days;
        $timedate = TimeDate::getInstance();
        return $this->db->convert($this->db->quoted($timedate->getNow()->modify("-$days days")->asDb(false)), 'datetime');
    }

    /**
     * Returns settings array
     *
     * @return array
     */
    protected function getSettings()
    {
        return BeanFactory::newBean('Administration')->retrieveSettings()->settings;
    }
    
    /**
     * Returns where clause
     * This is a copy-paste from User::getSystemUsersWhere because that method is not available in 6.5
     *
     * @param string $alias for user table
     * @return string
     */
    protected function getExcludeSystemUsersWhere($alias = 'users')
    {
        $aliasQuery = '';
        if (!empty($alias)) {
            $aliasQuery = $alias . '.';
        }
        return ' ' . $aliasQuery . 'deleted = 0 AND ' . $aliasQuery . 'user_name NOT IN(\'SugarCRMSupport\', \'SugarCRMUpgradeUser\')';
    }
    
    /**
     * Returns where clause for active users
     *
     * @param string $alias for user table
     * @return string
     */
    protected function getActiveUsersWhere($alias = 'users')
    {
        $aliasQuery = '';
        if (!empty($alias)) {
            $aliasQuery = $alias . '.';
        }
        $query = sprintf(
            ' ' . $aliasQuery . 'status = %s AND ' . $aliasQuery . 'is_group = 0 AND ' . $aliasQuery . 'portal_only = 0 AND %s',
            $this->db->quoted('Active'),
            $this->getExcludeSystemUsersWhere($alias)
        );

        return $query;
    }
}
