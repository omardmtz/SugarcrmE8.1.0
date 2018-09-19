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

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Query\Expression\CompositeExpression;

class Administration extends SugarBean {
    var $settings;
    var $table_name = "config";
    var $object_name = "Administration";
    var $new_schema = true;
    var $module_dir = 'Administration';
    var $config_categories = array(
        // 'mail', // cn: moved to include/OutboundEmail
        'disclosure', // appended to all outbound emails
        'notify',
        'system',
        'portal',
        'proxy',
        'massemailer',
        'ldap',
        'captcha',
        'honeypot',
        'sugarpdf',
        'base',


        'license',

    );
    var $disable_custom_fields = true;
    public $checkbox_fields = array(
        'notify_send_by_default',
        'mail_smtpauth_req',
        'notify_on',
        'tweettocase_on',
        'skypeout_on',
        'system_mailmerge_on',
        'proxy_auth',
        'proxy_on',
        'system_ldap_enabled',
        'captcha_on',
        'honeypot_on',
        );
    public $disable_row_level_security = true;
    public static $passwordPlaceholder = "::PASSWORD::";


    public function __construct() {
        parent::__construct();

        $this->setupCustomFields('Administration');
        $this->disable_row_level_security =true;
    }

    function retrieveSettings($category = false, $clean=false) {
        // declare a cache for all settings
        $settings_cache = sugar_cache_retrieve('admin_settings_cache');

        if($clean) {
            $settings_cache = array();
        }

        // Check for a cache hit
        if(!empty($settings_cache)) {
            $this->settings = $settings_cache;
            if (!empty($this->settings[$category]))
            {
                return $this;
            }
        }

        $conn = $this->db->getConnection();
        $builder = $conn->createQueryBuilder();
        $query = $builder
            ->select('category', 'name', 'value', 'platform')
            ->from($this->table_name);
        if ($category) {
            $query->where('category = ' . $builder->createPositionalParameter($category));
        }

        $stmt = $query->execute();
        while ($row = $stmt->fetch()) {
            $key = $row['category'] . '_' . $row['name'];
            // There can be settings that have the same `category`, the same
            // `name` but a different platform. We are going to prevent the
            // settings from non `base` platforms (ie `mobile` or `portal`) from
            // overriding `base` settings.

            // TODO: deprecate this method for a method that can select settings
            // per platform
            if (empty($row['platform'])) {
                $row['platform'] = 'base';
            }
            if (isset($this->settings[$key]) && $row['platform'] !== 'base') {
                // Don't hold this setting because it's already set
                continue;
            }
            if ($key == 'ldap_admin_password' || $key == 'proxy_password') {
                $this->settings[$key] = $this->decrypt_after_retrieve($row['value']);
            } else {
                $this->settings[$key] = $this->decodeConfigVar($row['value']);
            }
        }
        $this->settings[$category] = true;

        // outbound email settings
        $oe = new OutboundEmail();

        if ($oe->getSystemMailerSettings(false)) {
            foreach ($oe->field_defs as $name => $value) {
                // Only set the value if the key starts with "mail_".
                if (strpos($name, 'mail_') === 0) {
                    $this->settings[$name] = $oe->$name;
                }
            }
        }

        // At this point, we have built a new array that should be cached.
        sugar_cache_put('admin_settings_cache',$this->settings);
        return $this;
    }

    function saveConfig() {
        // outbound email settings
        $oe = new OutboundEmail();
        $oe = $oe->getSystemMailerSettings();

        foreach($_POST as $key => $val) {
            $prefix = $this->get_config_prefix($key);
            if(in_array($prefix[0], $this->config_categories)) {
                if(is_array($val)){
                    $val=implode(",",$val);
                }
                $this->saveSetting($prefix[0], $prefix[1], $val);
            }

            // Only store the value if the key starts with "mail_".
            if (strpos($key, 'mail_') === 0 && array_key_exists($key, $oe->field_defs)) {
                $oe->$key = $val;
            }

            // Keep the name and email address of the system account in sync with the configs notify_fromname and
            // notify_fromaddress.
            if ($key === 'notify_fromname') {
                $oe->name = $val;
            } elseif ($key === 'notify_fromaddress') {
                $sea = new SugarEmailAddress();
                $oe->email_address_id = $sea->getEmailGUID($val);
                $oe->email_address = $val;
            }
        }

        //saving outbound email from here is probably redundant, adding a check to make sure
        //smtpserver name is set.
        if (!empty($oe->mail_smtpserver)) {
            $oe->saveSystem();
        }

        $this->retrieveSettings(false, true);
    }

    /**
     * Save a setting
     *
     * @param string $category      Category for the config value
     * @param string $key           Key for the config value
     * @param string|array $value   Value of the config param
     * @param string $platform      Which platform this belongs to (API use only, If platform is empty it will not be returned in the API calls)
     * @return int                  Number of records Returned
     */
    public function saveSetting($category, $key, $value, $platform = '') {
        // platform is always lower case
        $platform = strtolower($platform);
        $conn = $this->db->getConnection();

        $builder = $conn->createQueryBuilder();
        $query = $builder
            ->select('COUNT(*) AS the_count')
            ->from($this->table_name)
            ->where($this->getConfigWhere($builder, $category, $key, $platform));

        $stmt = $query->execute();

        $row = $stmt->fetch();
        $row_count = $row['the_count'];

        if (is_array($value)) {
            $value = json_encode($value);
        }

        if($category."_".$key == 'ldap_admin_password' || $category."_".$key == 'proxy_password')
            $value = $this->encrpyt_before_save($value);

        $builder = $conn->createQueryBuilder();
        if ($row_count == 0) {
            $query = $builder
                ->insert($this->table_name)
                ->values(array(
                    'category' => $builder->createPositionalParameter($category),
                    'name' => $builder->createPositionalParameter($key),
                    'platform' => $builder->createPositionalParameter($platform),
                    'value' => $builder->createPositionalParameter($value),
                ));
        }
        else{
            $query = $builder
                ->update($this->table_name)
                ->set('value', $builder->createPositionalParameter($value))
                ->where($this->getConfigWhere($builder, $category, $key, $platform));
        }
        $result = $query->execute();

        sugar_cache_clear('admin_settings_cache');

        // check to see if category is a module
        if (!empty($platform)) {
            // we have an api call so lets clear out the cache for the module + platform
            global $moduleList;
            // FIXME TY-839 'portal' should be the platform, not category
            if (in_array($category, $moduleList) || $category == 'portal') {
                $cache_key = "ModuleConfig-" . $category;
                if($platform != "base")  {
                    $cache_key .= $platform;
                }
                sugar_cache_clear($cache_key);
            }
        }

        return $result;
    }

    /**
     * Builds WHERE for the given configuration parameter
     *
     * @param QueryBuilder $builder Query builder
     * @param string $category Config parameter category
     * @param string $name Config parameter name
     * @param string $platform Platform name
     * @return CompositeExpression
     */
    protected function getConfigWhere(QueryBuilder $builder, $category, $name, $platform)
    {
        $where = $builder->expr()->andX(
            $builder->expr()->eq('category', $builder->createPositionalParameter($category)),
            $builder->expr()->eq('name', $builder->createPositionalParameter($name))
        );

        $wherePlatform = $builder->expr()->eq('platform', $builder->createPositionalParameter($platform));
        if (empty($platform)) {
            $wherePlatform = $builder->expr()->orX(
                $wherePlatform,
                $builder->expr()->isNull('platform')
            );
        }

        return $where->add($wherePlatform);
    }

    /**
     * Return the config for a specific module.
     *
     * @param string $module        The module we are wanting to get the config for
     * @param string $platform      The platform we want to get the data back for
     * @param boolean $clean        Get clean copy of module config
     * @return array
     */
    public function getConfigForModule($module, $platform = 'base', $clean = false) {
        // platform is always lower case
        $platform = strtolower($platform);

        $cache_key = "ModuleConfig-" . $module;
        if($platform != "base")  {
            $cache_key .= $platform;
        }

        if($clean){
            sugar_cache_clear($cache_key);
        } else {
            // try and see if there is a cache for this
            $moduleConfig = sugar_cache_retrieve($cache_key);

            if(!empty($moduleConfig)) {
                return $moduleConfig;
            }
        }

        $sql = "SELECT name, value FROM config WHERE category = ?";
        if($platform != "base") {
            // if the platform is not base, we need to order it so the platform we are looking for overrides any base values
            $sql .= " AND platform IN ('base', ?) ORDER BY CASE WHEN platform = 'base' THEN 0 ELSE 1 END";
        } else {
            $sql .= " AND platform = ?";
        }

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($module, $platform));

        $moduleConfig = array();
        while ($row = $stmt->fetch()) {
            $moduleConfig[$row['name']] = $this->decodeConfigVar($row['value']);
        }

        if(!empty($moduleConfig)) {
            sugar_cache_put($cache_key, $moduleConfig);
        }

        return $moduleConfig;
    }

    function get_config_prefix($str) {
        return Array(substr($str, 0, strpos($str, "_")), substr($str, strpos($str, "_")+1));
    }

    /**
     * Get the full config table as an associative array
     *
     * @return array
     */
    public function getAllSettings()
    {
        $conn = $this->db->getConnection();
        $builder = $conn->createQueryBuilder();
        $query = $builder
            ->select('category', 'name', 'value', 'platform')
            ->from($this->table_name);
        $stmt = $query->execute();

        $return = array();
        while ($row = $stmt->fetch()) {
            $row['value'] = $this->decodeConfigVar($row['value']);
            $return[] = $row;
        }

        return $return;
    }

    /**
     * @param string $var
     * @return string
     * decode the config var
     */
    protected function decodeConfigVar($var)
    {
        // make sure the value is not null and the length is greater than 0
        if (!is_null($var) && strlen($var) > 0) {
            // if it looks like a JSON string then lets run the json_decode on it
            if ($var[0] == '{' || $var[0] == '[') {
                $decoded = json_decode($var, true);
                // if we didn't get a json error, then put the decoded value as the value we want to return
                if(json_last_error() == JSON_ERROR_NONE) {
                    $var = $decoded;
                }
            } elseif (is_numeric($var) && ctype_digit($var)) {
                // if it's a numeric value and all the string only contains digits, the convert it to an integer
                $var = intval($var);
            }
        }
        return $var;
    }

    /**
     * Return Administration object with filled in settings
     * @param string $category
     * @param bool $clean
     * @return Administration
     */
    public static function getSettings($category = false, $clean=false)
    {
        $admin = BeanFactory::newBean('Administration');
        $admin->retrieveSettings($category, $clean);
        return $admin;
    }

    /**
     * Check if the Bean Implements anything special
     * @param $interface 
     * @return bool
     */
    public function bean_implements($interface)
    {
        switch($interface){
            case 'ACL':return true;
        }
        return false;
    }    
}

