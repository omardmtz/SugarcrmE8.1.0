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
 * Static ACL implementation - ACLs defined per-module
 * Uses ACLController and ACLAction
 */
class SugarACLStatic extends SugarACLStrategy
{
    /**
     * (non-PHPdoc)
     * @see SugarACLStrategy::checkAccess()
     */
    public function checkAccess($module, $action, $context)
    {
        // Check if we have to apply team security based on ACLs
        // If user had admin rights then team security is disabled
        if($action == "team_security") {
            if(isset($context['bean']) && $context['bean']->bean_implements('ACL')) {
                $user_id = $this->getUserID($context);
                if(ACLAction::getUserAccessLevel($user_id, $module, 'access') != ACL_ALLOW_ENABLED) {
                    return true;
                }
                if(ACLAction::getUserAccessLevel($user_id, $module, 'admin') == ACL_ALLOW_ADMIN
                    || ACLAction::getUserAccessLevel($user_id, $module, 'admin') == ACL_ALLOW_ADMIN_DEV) {
                        // disable team security for admins
                        return false;
                    }
                return true;
            } else {
                // True means team security is enabled and it's the default
                return true;
            }
        }
        $user = $this->getCurrentUser($context);
        if($user && $user->isAdmin()) {
            return true;
        }

        // make sure we have the correct action name
        $action = !empty($_REQUEST['bwcFrame']) ? strtolower($action) : $this->fixUpActionName($action);
        if($action == "field") {
            return $this->fieldACL($module, $context['action'], $context);
        }
        if(!empty($context['bean'])) {
            return $this->beanACL($module, $action, $context);
        }

        if(empty($action)) {
            return true;
        }

        if($module == 'Trackers') {
            return ACLController::checkAccessInternal($module, $action, true, 'Tracker');
        }

        // if we're editing and we do not have the bean, if owner is allowed then action is allowed
        if(empty($context['bean']) && !empty(self::$edit_actions[$action]) && !isset($context['owner_override'])) {
            $context['owner_override'] = true;
        }

        if (isset(self::$non_module_acls[$module])) {
            $type = self::$non_module_acls[$module];
        } else {
            $type = 'module';
        }
        return ACLController::checkAccessInternal($module, $action, !empty($context['owner_override']), $type);
    }

    static $edit_actions = array(
        'popupeditview' => 1,
        'editview' => 1,
        'save' => 1,
        'edit' => 1,
        'delete' => 1,
    );

    static $action_translate = array(
        'listview' => 'list',
        'index' => 'list',
        'detail' => 'view',
        'detailview' => 'view',
        'save' => 'edit',
    );
    /**
     * Check access to fields
     * @param string $module
     * @param string $action
     * @param array $context
     */
    protected function fieldACL($module, $action, $context)
    {
        $bean = isset($context['bean'])?$context['bean']:null;
        $is_owner = false;
        if(!empty($context['owner_override'])) {
            $is_owner = $context['owner_override'];
        } else {
            if($bean) {
                // non-ACL bean - access granted
                if(!$bean->bean_implements('ACL')) return true;
                $is_owner = $bean->isOwner($this->getUserID($context));
            }
        }

        if(!empty($context["user"])) {
            $user = $context["user"];
        } else {
            $user = $this->getUserID($context);
        }
        if(!$user) {
            return true;
        }

        $field_access = ACLField::hasAccess($context['field'], $module, $user,  $is_owner);

        switch($action) {
            case 'access':
                return $field_access > 0;
            case 'read':
            case 'detail':
            case 'list':
                $access = 1;
                break;
            case 'write':
            case 'edit':
                $access = 3;
                break;
            default:
                $access = 4;
        }

        return ($field_access == 4 || $field_access == $access);
    }
    /**
     * Check bean ACLs
     * @param string $module
     * @param string $action
     * @param array $context
     */
    protected function beanACL($module, $action, $context)
    {
        $bean = $context['bean'];
        //if we don't implent acls return true
        if(!$bean->bean_implements('ACL')) return true;

        if(!empty($context['owner_override'])) {
            $is_owner = $context['owner_override'];
        } else {
            $is_owner = $bean->isOwner($this->getUserID($context));
        }

        if(isset(self::$action_translate[$action])) {
            $action = self::$action_translate[$action];
        }

        // Some modules (Trackers, TrackerSessions, TrackerPerfs, TrackerQueries) use special acltype
        $aclType = 'module';
        if (!empty($bean->acltype)) {
            $aclType = $bean->acltype;
        }

        switch ($action)
        {
            case 'import':
            case 'list':
                return ACLController::checkAccessInternal($module, $action, true, $aclType);
            case 'delete':
            case 'view':
            case 'export':
            case 'massupdate':
                return ACLController::checkAccessInternal($module, $action, $is_owner, $aclType);
            case 'edit':
                if(!isset($context['owner_override']) && !empty($bean->id)) {
                    if (!empty($bean->fetched_row['id']) && (
                        !empty($bean->fetched_row['assigned_user_id']) || !empty($bean->fetched_row['created_by'])
                    )) {
                        $temp = BeanFactory::newBean($bean->module_dir);
                        $temp->createLocaleFormattedName = false;
                        $temp->populateFromRow($bean->fetched_row);
                    }else{
                        if($bean->new_with_id) {
                            $is_owner = true;
                        } else {
                            $GLOBALS['log']->warn('The bean does not have owner fields populated. Re-retrieving');
                            $temp = BeanFactory::getBean($bean->module_dir, $bean->id);
                        }
                    }
                    if(!empty($temp)) {
                        $is_owner = $temp->isOwner($this->getUserID($context));
                        unset($temp);
                    }
                }
            case 'popupeditview':
            case 'editview':
                return ACLController::checkAccessInternal($module,'edit', $is_owner, $aclType);
        }
        //if it is not one of the above views then it should be implemented on the page level
        return true;

    }

    public function checkFieldList($module, $field_list, $action, $context)
    {
        $user = $this->getCurrentUser($context);
        if(empty($user) || empty($user->id) || is_admin($user)) {
            return array();
        }
        if(!ACLField::hasACLs($user->id, $module)) {
            return array();
        }
        return parent::checkFieldList($module, $field_list, $action, $context);
    }

    public function getFieldListAccess($module, $field_list, $context)
    {
        $user = $this->getCurrentUser($context);
        if(empty($user) || empty($user->id) || is_admin($user)) {
        	return array();
        }
        if(!ACLField::hasACLs($user->id, $module)) {
            return array();
        }
        return parent::getFieldListAccess($module, $field_list, $context);
    }

    /**
     * For some mysterious reasons Tracker ACLs are "special" and do not follow the rules.
     * @var array
     */
    protected static $non_module_acls = array(
        'Trackers' => 'Tracker',
        'TrackerQueries' => 'TrackerQuery',
        'TrackerPerfs' => 'TrackerPerf',
        'TrackerSessions' => 'TrackerSession',

    );

    /**
     * Get user access for the list of actions
     * @param string $module
     * @param array $access_list List of actions
     * @returns array - List of access levels. Access levels not returned are assumed to be "all allowed".
     */
    public function getUserAccess($module, $access_list, $context)
    {
        $user = $this->getCurrentUser($context);
        if(empty($user) || empty($user->id) || is_admin($user)) {
            // no user or admin - do nothing
            return $access_list;
        }
        $is_owner = !(isset($context['owner_override']) && $context['owner_override'] == false);
        if(isset(self::$non_module_acls[$module])) {
            $level = self::$non_module_acls[$module];
        } else {
            $level = 'module';
        }
        $actions = ACLAction::getUserActions($user->id, false, $module, $level);
        if(empty($actions)) {
            return $access_list;
        }
        // default implementation, specific ACLs can override
        $access = $access_list;
        // check 'access' first - if it's false all others will be false
        if(isset($access_list['access'])) {
        	if(!ACLAction::userHasAccess($user->id, $module, 'access', $level, true)) {
        		foreach($access_list as $action => $value) {
        			$access[$action] = false;
        		}
        		return $access;
        	}
        	// no need to check it second time
        	unset($access_list['access']);
        }
        foreach($access_list as $action => $value) {
            // may have the bean, so we need to use checkAccess
        	if(!$this->checkAccess($module, $action, $context) || (isset($actions[$action]['aclaccess']) && !ACLAction::hasAccess($is_owner, $actions[$action]['aclaccess']))) {
        		$access[$action] = false;
        	}
        }
        return $access;
    }
}
