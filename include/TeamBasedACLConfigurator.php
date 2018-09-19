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

class TeamBasedACLConfigurator
{
    const CONFIG_KEY = 'team_based_acl';

    /**
     * @var boolean $stateCache TBA availability.
     */
    protected static $stateCache;

    /**
     * @var array $stateCache Module state..
     */
    protected static $moduleCache = array();

    /**
     * @var array $implementationCache Implementation state of modules.
     */
    protected static $implementationCache = array();

    /**
     * @var array
     */
    protected static $defaultConfig = array(
        'enabled' => false,
        'enabled_modules' => array(),
    );

    /**
     * Hidden modules.
     * These modules can't be enabled/disabled on Team-based Permissions admin page.
     * @var array
     */
    protected static $hiddenModules = array(
        'pmse_Inbox', // see RS-1275
        'Users', // see RS-1347
    );

    /**
     * Permanently enabled modules.
     * Useful in pair with self::$hiddenModules.
     * @var array
     */
    private static $alwaysEnabledModules = array(
        'pmse_Inbox',
        'Users',
    );

    /**
     * @var array List of TBA field constants.
     */
    protected $fieldOptions = array(
        'ACL_READ_SELECTED_TEAMS_WRITE' => 65,
        'ACL_SELECTED_TEAMS_READ_OWNER_WRITE' => 68,
        'ACL_SELECTED_TEAMS_READ_WRITE' => 71,
    );

    /**
     * @var array List of TBA module constants.
     */
    protected $moduleOptions = array(
        // 78 to be suppressed by owner's 75.
        'ACL_ALLOW_SELECTED_TEAMS' => 78,
    );

    /**
     * @var string Fields fallback keys [from => to].
     */
    protected $fieldFallbackOption = array(
        'ACL_READ_SELECTED_TEAMS_WRITE' => 'ACL_READ_OWNER_WRITE',
        'ACL_SELECTED_TEAMS_READ_OWNER_WRITE' => 'ACL_OWNER_READ_WRITE',
        'ACL_SELECTED_TEAMS_READ_WRITE' => 'ACL_OWNER_READ_WRITE',
    );

    /**
     * @var string Modules fallback [from => to].
     */
    protected $moduleFallbackOption = array(
        'ACL_ALLOW_SELECTED_TEAMS' => 'ACL_ALLOW_OWNER',
    );

    /**
     * @var array Affected during fallback actions.
     */
    protected $affectedRows = array();

    /**
     * Get TBA field options.
     * @return array
     */
    public function getFieldOptions()
    {
        return $this->fieldOptions;
    }

    /**
     * Get TBA module options.
     * @return array
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     * Get field fallback options.
     * @return array
     */
    public function getFieldFallbackOption()
    {
        return $this->fieldFallbackOption;
    }

    /**
     * Get module fallback options.
     * @return array
     */
    public function getModuleFallbackOption()
    {
        return $this->moduleFallbackOption;
    }

    /**
     * Convert fallback arrays to access.
     * @return array Of field and module accesses.
     */
    protected function fallbackAsAccess()
    {
        $res = array();
        foreach ($this->fieldFallbackOption + $this->moduleFallbackOption as $k => $v) {
            $res[constant($k)] = constant($v);
        }
        return $res;
    }

    /**
     * Get access to fallback for by access number. Searches in field and module constants.
     * @param int $access TBA access number.
     * @return int|false Access number to fallback.
     */
    public function getFallbackByAccess($access)
    {
        $combArray = $this->fallbackAsAccess();
        return isset($combArray[$access]) ? $combArray[$access] : false;
    }

    /**
     * Get access by fallback access number. Searches in field and module constants.
     * @param int $access Access number.
     * @return int|false TBA access.
     */
    public function getAccessByFallback($access)
    {
        $combArray = $this->fallbackAsAccess();
        $key = array_search($access, $combArray);
        return $key !== false ? $combArray[$key] : false;
    }

    /**
     * Check is passed ACL option is handled by TBA.
     * @param mixed $access
     * @return boolean
     */
    public function isValidAccess($access)
    {
        return in_array($access, $this->fieldOptions) || in_array($access, $this->moduleOptions);
    }

    /**
     * Set Team Based ACL for a particular module.
     * @param $module
     * @param boolean $enable
     */
    public function setForModule($module, $enable)
    {
        $enabledGlobally = self::isEnabledForModule($module);
        if (($enable && $enabledGlobally) || (!$enable && !$enabledGlobally)) {
            return;
        }
        $cfg = new Configurator();
        $actualList = $cfg->config[self::CONFIG_KEY]['enabled_modules'];

        if ($enable) {
            $actualList[] = $module;
        } else {
            $actualList = array_values(array_diff($actualList, array($module)));
        }
        $actualList = array_unique($actualList);
        // Configurator doesn't handle lists, to remove an element overriding needed.
        $cfg->config[self::CONFIG_KEY]['enabled_modules'] = false;
        $this->saveConfig($cfg);
        $cfg->config[self::CONFIG_KEY]['enabled_modules'] = $actualList;
        $this->saveConfig($cfg);

        if ($enable) {
            $this->restoreTBA(array($module));
        } else {
            $this->fallbackTBA(array($module));
        }
        self::$moduleCache[$module] = $enable;
        $this->applyTBA($module);
        $cfg->clearCache();
    }

    /**
     * Set Team Based ACL for a set of modules.
     * @param array $modules List of modules.
     * @param boolean $enable
     */
    public function setForModulesList(array $modules, $enable)
    {
        if (empty($modules)) {
            return;
        }
        $cfg = new Configurator();
        $actualList = $cfg->config[self::CONFIG_KEY]['enabled_modules'];
        $newList = $actualList;

        foreach ($modules as $module) {
            $enabledGlobally = self::isEnabledForModule($module);
            if (($enable && $enabledGlobally) || (!$enable && !$enabledGlobally)) {
                continue;
            }
            if ($enable) {
                $newList[] = $module;
            } else {
                $newList = array_values(array_diff($newList, array($module)));
            }
            self::$moduleCache[$module] = $enable;
        }
        if ($newList == $actualList) {
            return;
        }
        $newList = array_unique($newList);
        $cfg->config[self::CONFIG_KEY]['enabled_modules'] = false;
        $this->saveConfig($cfg);
        $cfg->config[self::CONFIG_KEY]['enabled_modules'] = $newList;
        $this->saveConfig($cfg);

        if ($enable) {
            $this->restoreTBA($modules);
        } else {
            $this->fallbackTBA($modules);
        }
        $this->applyTBA();
        $cfg->clearCache();
    }

    /**
     * Is Team Based ACL applied for module, if not set - uses global value.
     * Does not check implementation and configuration page.
     * @param $module
     * @return bool
     */
    public static function isEnabledForModule($module)
    {
        if (!self::isEnabledGlobally()) {
            return false;
        }
        if (!isset(self::$moduleCache[$module])) {
            $config = self::getConfig();
            $hiddenAndEnabled = in_array($module, self::$hiddenModules)
                && in_array($module, self::$alwaysEnabledModules);
            $enabled = !empty($config['enabled_modules'])
                && is_array($config['enabled_modules'])
                && in_array($module, $config['enabled_modules']);
            self::$moduleCache[$module] = $hiddenAndEnabled || $enabled;
        }
        return self::$moduleCache[$module];
    }

    /**
     * Set global state of the Team Based ACL.
     * @param boolean $enable
     */
    public function setGlobal($enable)
    {
        $enabledGlobally = self::isEnabledGlobally();
        if (($enable && $enabledGlobally) || (!$enable && !$enabledGlobally)) {
            return;
        }
        $cfg = new Configurator();
        $cfg->config[self::CONFIG_KEY]['enabled'] = $enable;
        $this->saveConfig($cfg);

        $notDisabledModules = $cfg->config[self::CONFIG_KEY]['enabled_modules'];
        if ($enable) {
            $this->restoreTBA($notDisabledModules);
        } else {
            $this->fallbackTBA($notDisabledModules);
        }
        self::$stateCache = $enable;
        $this->applyTBA();
        $cfg->clearCache();
    }

    /**
     * Global state of the Team Based ACL.
     * @return boolean
     */
    public static function isEnabledGlobally()
    {
        if (self::$stateCache === null) {
            $config = self::getConfig();
            self::$stateCache = $config['enabled'];
        }
        return self::$stateCache;
    }

    /**
     * Update modules vardefs to apply the Team Based visibility.
     * @param string $module Module name.
     */
    protected function applyTBA($module = null)
    {
        if ($module) {
            $bean = BeanFactory::newBean($module);
            VardefManager::clearVardef($bean->module_dir, $bean->object_name);
        } else {
            VardefManager::clearVardef();
        }
    }

    /**
     * Fallback all roles options in case of TBA disabling.
     * @param array $modules
     */
    protected function fallbackTBA($modules)
    {
        $aclField = new ACLField();
        $fieldOptions = $this->getFieldOptions();

        $allRoles = ACLRole::getAllRoles();
        foreach ($allRoles as $role) {
            $actions = ACLRole::getRoleActions($role->id);
            $fields = $aclField->getACLFieldsByRole($role->id);

            foreach ($actions as $aclKey => $aclModule) {
                if (!in_array($aclKey, $modules)) {
                    continue;
                }
                $aclType = BeanFactory::newBean($aclKey)->acltype;
                foreach ($aclModule[$aclType] as $action) {
                    if (in_array($action['aclaccess'], $this->getModuleOptions())) {
                        $this->fallbackModule($aclKey, $role->id, $action['id'], $action['aclaccess']);
                    }
                }
            }
            if ($fields) {
                $tbaRecords = array_filter($fields, function ($val) use ($modules, $fieldOptions) {
                    if (!in_array($val['category'], $modules)) {
                        return false;
                    }
                    return in_array($val['aclaccess'], $fieldOptions);
                });
                foreach ($tbaRecords as $fieldToReset) {
                    $this->fallbackField(
                        $fieldToReset['category'],
                        $role->id,
                        $fieldToReset['name'],
                        $fieldToReset['aclaccess']
                    );
                }
            }
        }
        $this->applyFallback();
    }

    /**
     * Restore previously enabled TBA actions if they were not changed after fallback.
     * @param array $modules
     */
    protected function restoreTBA($modules)
    {
        $savedActions = $this->getSavedAffectedRows();
        if (!$savedActions) {
            return;
        }
        $aclRole = new ACLRole();
        $aclField = new ACLField();

        foreach ($savedActions as $moduleName => $moduleActions) {
            if (!in_array($moduleName, $modules)) {
                continue;
            }
            $aclType = BeanFactory::newBean($moduleName)->acltype;
            if (isset($moduleActions[$aclType])) {
                foreach ($moduleActions[$aclType] as $moduleRow) {
                    $accessOverride = $aclRole->retrieve_relationships(
                        'acl_roles_actions',
                        array('role_id' => $moduleRow['role'], 'action_id' => $moduleRow['action']),
                        'access_override'
                    );
                    if (!empty($accessOverride[0]['access_override']) &&
                        $this->getAccessByFallback($accessOverride[0]['access_override']) !== false
                    ) {
                        $aclRole->setAction(
                            $moduleRow['role'],
                            $moduleRow['action'],
                            $moduleRow['access']
                        );
                    }
                }
            }
            if (isset($moduleActions['field'])) {
                foreach ($moduleActions['field'] as $fieldRow) {
                    $roleFields = $aclField->getFields($moduleName, '', $fieldRow['role']);
                    if (!empty($roleFields[$fieldRow['field']]) &&
                        $this->getAccessByFallback($roleFields[$fieldRow['field']]['aclaccess']) !== false
                    ) {
                        $aclField->setAccessControl(
                            $moduleName,
                            $fieldRow['role'],
                            $fieldRow['field'],
                            $fieldRow['access']
                        );
                    }
                }
            }
            unset($savedActions[$moduleName]);
        }
        $admin = BeanFactory::newBean('Administration');
        $admin->saveSetting(self::CONFIG_KEY, 'fallback', json_encode($savedActions), 'base');
        // Calls ACLAction::clearACLCache.
        $aclField->clearACLCache();
    }

    /**
     * Get affected by fallback module and field actions.
     * @return array [ModuleName => ['module' => [[role, action, access]], 'field' => [[role, field, access]]]]|null
     */
    protected function getSavedAffectedRows()
    {
        $admin = BeanFactory::newBean('Administration');
        // Uses json_decode().
        $settings = $admin->getConfigForModule(self::CONFIG_KEY, 'base', true);
        return isset($settings['fallback']) ? $settings['fallback'] : null;
    }

    /**
     * Save field's data in internal property to change its ACL option in future.
     * @param string $module Module name.
     * @param string $role Role id.
     * @param string $field Field name.
     * @param mixed $access Access value.
     */
    protected function fallbackField($module, $role, $field, $access)
    {
        $arrObj = new ArrayObject($this->affectedRows);
        $arrObj[$module]['field'][] = array('role' => $role, 'field' => $field, 'access' => $access);
        $this->affectedRows = $arrObj->getArrayCopy();
    }

    /**
     * Save module's data in internal property.
     * @param string $module Module name.
     * @param string $role Role id.
     * @param string $action Action id.
     * @param mixed $access Access value.
     */
    protected function fallbackModule($module, $role, $action, $access)
    {
        $aclType = BeanFactory::newBean($module)->acltype;
        $arrObj = new ArrayObject($this->affectedRows);
        $arrObj[$module][$aclType][] = array('role' => $role, 'action' => $action, 'access' => $access);
        $this->affectedRows = $arrObj->getArrayCopy();
    }

    /**
     * Fallback TBA options to default and save affected actions in module's settings.
     */
    protected function applyFallback()
    {
        $aclRole = new ACLRole();
        $aclField = new ACLField();

        foreach ($this->affectedRows as $moduleName => $data) {
            $aclType = BeanFactory::newBean($moduleName)->acltype;
            if (isset($data[$aclType])) {
                foreach ($data[$aclType] as $moduleRow) {
                    $fallbackAccess = $this->getFallbackByAccess($moduleRow['access']);
                    if ($fallbackAccess === false) {
                        continue;
                    }
                    $aclRole->setAction(
                        $moduleRow['role'],
                        $moduleRow['action'],
                        $fallbackAccess
                    );
                }
            }
            if (isset($data['field'])) {
                foreach ($data['field'] as $fieldRow) {
                    $fallbackAccess = $this->getFallbackByAccess($fieldRow['access']);
                    if ($fallbackAccess === false) {
                        continue;
                    }
                    $aclField->setAccessControl(
                        $moduleName,
                        $fieldRow['role'],
                        $fieldRow['field'],
                        $fallbackAccess
                    );
                }
            }
        }
        $actions = $this->getSavedAffectedRows();
        if ($actions) {
            $this->affectedRows = array_merge($actions, $this->affectedRows);
        }
        $admin = BeanFactory::newBean('Administration');
        $admin->saveSetting(self::CONFIG_KEY, 'fallback', json_encode($this->affectedRows), 'base');
        $this->affectedRows = array();
        // Calls ACLAction::clearACLCache.
        $aclField->clearACLCache();
    }

    /**
     * Return default config.
     * @return array
     */
    public static function getDefaultConfig()
    {
        return self::$defaultConfig;
    }

    /**
     * Returns permanently hidden modules.
     * @return array
     */
    public static function getHiddenModules()
    {
        return self::$hiddenModules;
    }

    /**
     * Return config.
     * @return array
     */
    public static function getConfig()
    {
        return SugarConfig::getInstance()->get(self::CONFIG_KEY, self::getDefaultConfig());
    }

    /**
     * Check if the module implements TBA.
     * Implementation includes defs and ACL.
     * @param string $module Module name.
     * @return bool
     */
    public static function implementsTBA($module)
    {
        if (!isset(self::$implementationCache[$module])) {
            $bean = BeanFactory::newBean($module);
            // need to check if $bean is instance of SugarBean
            // because of DynamicFields module and DynamicField class which is not a SugarBean
            self::$implementationCache[$module] =
                $bean
                && $bean instanceof SugarBean
                && $bean->getFieldDefinition('acl_team_set_id')
                && $bean->bean_implements('ACL');
        }
        return self::$implementationCache[$module];
    }

    /**
     * Check if the module implements TBA and enabled on admin page.
     * @param string $module Module name.
     * @return bool
     */
    public static function isAccessibleForModule($module)
    {
        return self::isEnabledForModule($module) && self::implementsTBA($module);
    }

    /**
     * Save new config and clear cache.
     * @param Configurator $cfg
     */
    protected function saveConfig(\Configurator $cfg)
    {
        $cfg->handleOverride();
        SugarConfig::getInstance()->clearCache();
        // PHP 5.5+. Because of the default value for "opcache.revalidate_freq" is 2 seconds and for modules
        // the config_override.php is being overridden frequently.
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate('config_override.php', true);
        }
    }

    /**
     * Sets acl_team_set_id to NULL for ALL records of db table.
     * @param string $tableName
     */
    protected function removeAllTBAValuesFromTable($tableName)
    {
        DBManagerFactory::getInstance()->query("UPDATE {$tableName} SET acl_team_set_id = null");
    }

    /**
     * Sets acl_team_set_id to NULL for ALL records of bean's db table.
     * @param SugarBean $bean
     */
    public function removeAllTBAValuesFromBean(SugarBean $bean)
    {
        if (!self::implementsTBA($bean->getModuleName())
            || in_array($bean->getModuleName(), self::$alwaysEnabledModules)) {
            return;
        }
        $this->removeAllTBAValuesFromTable($bean->getTableName());
    }

    /**
     * Make sure that we remove team based acl settings from all existing tables.
     * Might be useful if module is disabled.
     * @param array $exclude_tables
     */
    public function removeTBAValuesFromAllTables(array $exclude_tables)
    {
        $db = DBManagerFactory::getInstance();

        //Get all tables schemas
        $all_tables = $db->getTablesArray();

        foreach ($all_tables as $table_name) {
            // Do nothing If $table_name is in exclude list
            if (!in_array($table_name, $exclude_tables)) {
                // Get table's columns
                $columns = $db->get_columns($table_name);
                if (!empty($columns['acl_team_set_id'])) {
                    $this->removeAllTBAValuesFromTable($table_name);
                }
            }
        }
    }

    /**
     * Get modules list which are implement TBA and which are not hidden.
     */
    public function getListOfPublicTBAModules()
    {
        $actionsList = ACLAction::getUserActions($GLOBALS['current_user']->id);

        // Skipping modules that have 'hidden_to_role_assignment' property or not implement TBA
        foreach ($actionsList as $name => $category) {
            $buf = reset($category);
            if (isset($buf['access']['aclaccess']) && $buf['access']['aclaccess'] == ACL_ALLOW_DISABLED) {
                unset($actionsList[$name]);
                continue;
            }
            $objName = BeanFactory::getObjectName($name);
            VardefManager::loadVardef($name, $objName);
            // $objName might be false, e.g. custom module is disabled.
            $dictionary = isset($GLOBALS['dictionary'][$objName]) ? $GLOBALS['dictionary'][$objName] : null;
            if ($dictionary && ((!empty($dictionary['hidden_to_role_assignment'])
                        && $dictionary['hidden_to_role_assignment']) || !self::implementsTBA($name))) {
                unset($actionsList[$name]);
            }
        }

        // remove hidden modules
        $actionsList = array_diff(array_keys($actionsList), self::getHiddenModules());

        return $actionsList;
    }
}
