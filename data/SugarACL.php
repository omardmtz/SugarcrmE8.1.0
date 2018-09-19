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
 * Generic ACL implementation
 * @api
 */
class SugarACL
{
    static $acls = array();

    // Access levels for field
    // matches ACLField::hasAccess returns for compatibility
    const ACL_NO_ACCESS = 0;
    const ACL_READ_ONLY = 1;
    const ACL_READ_WRITE = 4;

    /**
     * Load bean from context
     * @static
     * @param string $module
     * @param array $context
     * @return SugarBean
     */
    public static function loadBean($module, $context = array())
    {
        if(isset($context['bean']) && $context['bean'] instanceof SugarBean && $context['bean']->module_dir == $module) {
            $bean = $context['bean'];
        } else {
            $bean = BeanFactory::newBean($module);
        }
        return $bean;
    }


    /**
     * Reset ACL cache
     * To be used when
     * @param string $module If empty, all ACL module caches are reset
     */
    public static function resetACLs($module = null)
    {
        if($module) {
            unset(self::$acls[$module]);
        } else {
            self::$acls = array();
        }
    }

    /**
     * Set ACLs for specific module
     *
     * For use mainly in unit tests, for overriding ACL functions
     *
     * @param string $module
     * @param array $acldata Array of SugarACLStrategy objects that implement needed ACL
     */
    public static function setACL($module, $acldata)
    {
        self::$acls[$module] = $acldata;
    }

    /**
     * Load ACLs for module
     * @param string $module
     * @param array $context
     * @return array ACLs list
     */
    public static function loadACLs($module, $context = array())
    {
        if(!isset(self::$acls[$module])) {
            self::$acls[$module] = array();

            $name = BeanFactory::getObjectName($module);
            if(!isset($GLOBALS['dictionary'][$name])) {
                if(empty($name) || empty($GLOBALS['beanList'][$module]) || empty($GLOBALS['beanFiles'][$GLOBALS['beanList'][$module]]) || in_array($module, array('Empty'))) {
                    // try to weed out non-bean modules - these can't have ACLs as they don't have vardefs to keep them
                    $GLOBALS['log']->debug("Non-bean $module - no ACL for you!");
                    return array();
                }
                VardefManager::loadVardef($module, $name);
            }
            $acl_list = isset($GLOBALS['dictionary'][$name]['acls'])?$GLOBALS['dictionary'][$name]['acls']:array();
            $acl_list = array_merge($acl_list, SugarBean::getDefaultACL($name));

            $GLOBALS['log']->debug("ACLS for $module: ".var_export($acl_list, true));

            foreach($acl_list as $klass => $args) {
                if($args === false) continue;
                self::$acls[$module][] = new $klass($args);
            }
        }

        return self::$acls[$module];
    }

    /**
     * Check if module has any ACLs defined
     * @param string $module
     * @return bool
     */
    public static function moduleSupportsACL($module)
    {
        $acls = self::loadACLs($module);
        return !empty($acls);
    }

    /**
     * Check ACLs for field
     * @param string $module
     * @param string $field
     * @param string $action
     * @param array $context
     * @return bool Access allowed?
     */
    public static function checkField($module, $field, $action,  $context = array())
    {
        $context['field'] = strtolower($field);
        $context['action'] = $action;
        return self::checkAccess($module, "field", $context);
    }

    /**
     * Get ACL access level
     * @param string $module
     * @param string $field
     * @param array $context
     * @return int Access level - one of ACL_* constants
     */
    public static function getFieldAccess($module, $field, $context = array())
    {
        $read = self::checkField($module, $field, "detail", $context);
        if(!$read) return self::ACL_NO_ACCESS;
        $write = self::checkField($module, $field, "edit", $context);
        if($write) return self::ACL_READ_WRITE;
        return self::ACL_READ_ONLY;
    }

    /**
     * Check access
     * @param string $module
     * @param string $action
     * @param array $context
     * @return bool Access allowed?
     */
    public static function checkAccess($module, $action, $context = array())
    {
        if(!isset(self::$acls[$module])) {
            self::loadACLs($module, $context);
        }
        foreach(self::$acls[$module] as $acl) {
            if(!$acl->checkAccess($module, $action, $context)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get list of disabled modules
     * @param array $list Module list
     * @param string $action
     * @param bool $use_value Use value or key as module name?
     * @return array Disabled modules
     */
    public static function disabledModuleList($list, $action = 'access', $use_value = false)
    {
        $result = array();
        if(empty($list)) {
            return $result;
        }
        foreach($list as $key => $module) {
            $checkmodule = $use_value?$module:$key;
            if(!self::checkAccess($checkmodule, $action)) {
                $result[$checkmodule] = $checkmodule;
            }
        }
        return $result;
    }

    /**
     * Remove disabled modules from list
     * @param array $list Module list
     * @param string $action
     * @param bool $use_value Use value or key as module name?
     * @return array Filtered list
     */
    public static function filterModuleList($list, $action = 'access', $use_value = false)
    {
        $result = array();
        if(empty($list)) {
            return $list;
        }

        foreach($list as $key => $module) {
            if(self::checkAccess($use_value?$module:$key, $action, array("owner_override" => true))) {
                $result[$key] = $module;
            }
        }
        return $result;
    }

    /**
     * Filter list of fields and remove/blank fields that we can not access.
     * Modifies the list directly.
     * @param string $module
     * @param array $list list of fields, keys are field names
     * @param array $context
     * @param array $options Filtering options:
     * - blank_value (bool) - instead of removing inaccessible field put '' there
     * - add_acl (bool) - instead of removing fields add 'acl' value with access level
     * - suffix (string) - strip suffix from field names
     * - min_access (int) - require this level of access for field
     * - use_value (bool) - look for field name in value, not in key of the list
     */
    public static function listFilter($module, &$list, $context = array(), $options = array())
    {
        if(empty($list)) {
            return;
        }

        if(empty($options['min_access'])) {
            $min_access = 'access';
        } else {
            if($options['min_access'] >= SugarACL::ACL_READ_WRITE) {
                $min_access = "edit";
            }
        }

        $check_fields = array();

        foreach($list as $key=>$value) {
            if(!empty($options['use_value'])) {
                if(is_array($value)) {
                    if(!empty($value['group'])){
                        $value = $value['group'];
                    } elseif(!empty($value['name'])) {
                        $value = $value['name'];
                    } else {
                        // we don't know what to do with this one, skip it
                        continue;
                    }
                }
                $field = $value;
            } else {
                $field = $key;
                if(is_array($value) && !empty($value['group'])){
                        $field = $value['group'];
                }
            }
            if(!empty($options['suffix'])) {
                // remove suffix like _advanced
                $field = str_replace($options['suffix'], '', $field);
            }
            if(!empty($options['add_acl'])) {
                $check_fields[$key] = $check_fields[$field] = strtolower($field);
            } else {
                if(!empty($list[$key])) {
                    $check_fields[$key] = strtolower($field);
                }
            }
        }

        if(!isset(self::$acls[$module])) {
            self::loadACLs($module, $context);
        }

        if(!empty($options['add_acl'])) {
            // initialize the access details
            foreach($check_fields as $key => $value) {
                $list[$key]['acl'] = self::ACL_READ_WRITE;
            }
            foreach(self::$acls[$module] as $acl) {
                foreach($acl->getFieldListAccess($module, $check_fields, $context) as $key => $acl) {
                    if($acl < $list[$key]['acl']) {
                        $list[$key]['acl'] = $acl;
                    }
                }
            }
        } else {
            foreach(self::$acls[$module] as $acl) {
                foreach($acl->checkFieldList($module, $check_fields, $min_access, $context) as $key => $access) {
                    if(!$access) {
                        // if have no access, blank or remove field value
                        if(empty($options['blank_value'])) {
                        	unset($list[$key]);
                        } else {
                        	$list[$key] = '';
                        }
                        // no need to check it again
                        unset($check_fields[$key]);
                    }
                }
                if(empty($check_fields)) break;
            }
        }
    }

    public static $all_access = array('access' => true,'view' => true,'list' => true,'edit' => true,
        'delete' => true,'import' => true,'export' => true,'massupdate' => true);

    /**
     * Get user access for the list of actions
     * @param string $module
     * @param array $access_list List of actions
     * @returns array - List of access levels. Access levels not returned are assumed to be "all allowed".
     */
    public static function getUserAccess($module, $access_list = array(), $context = array())
    {
        if(!isset(self::$acls[$module])) {
        	self::loadACLs($module, $context);
        }
        if(empty($access_list)) {
            $access_list = self::$all_access;
        }
        $access = $access_list;
        foreach(self::$acls[$module] as $acl) {
            $acl_access = $acl->getUserAccess($module, $access_list, $context);
            foreach($acl_access as $name => $value) {
                if($value == false) {
                    $access[$name] = false;
                    // don't check already rejected ones
                    unset($access_list[$name]);
                }
            }
            // if we did not have any actions left, we're done
            if(empty($access_list)) {
                break;
            }
        }

        // Mass Update action should be accessible only if Edit is accessible
        if (isset($access['edit']) && !$access['edit']) {
            $access['massupdate'] = false;
        }

        return $access;
    }
}
