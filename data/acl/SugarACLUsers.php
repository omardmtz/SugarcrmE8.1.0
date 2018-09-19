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

class SugarACLUsers extends SugarACLStrategy
{
    /**
     * Fields non admin cannot edit
     */
    public $no_edit_fields = array(
            'title' => true,
            'department' => true,
            'reports_to_id' => true,
            'reports_to_name' => true,
            'reports_to_link' => true,
            'user_name' => true,
            'status' => true,
            'employee_status' => true,
            'is_admin' => true,
            'UserType' => true,
        );

    public $no_access_fields = array(
            'show_on_employees' => true,
            'portal_only' => true,
            'is_group' => true,
            'system_generated_password' => true,
            'external_auth_only' => true,
            'sugar_login' => true,
            'authenticate_id' => true,
            'pwd_last_changed' => true,
            'user_hash' => true,
            'password' => true,
            'last_login' => true
        );

    public $view_checks = array(
            'access' => true,
            'view' => true,
            'ListView' => true,
            'list' => true,
            'export' => true,
            'Export' => true,
            'field' => true,
            'DetailView' => true,
            'detail' => true,
            'team_security' => true,
        );

    /**
     * Check access a current user has on Users and Employees
     * @param string $module
     * @param string $view
     * @param array $context
     * @return bool
     */
    public function checkAccess($module, $view, $context)
    {
        global $sugar_config;

        if($module != 'Users' && $module != 'Employees') {
            // how'd you get here...
            return false;
        }

        if ( $view == 'team_security' ) {
            // Let the other modules decide
            return true;
        }

        $current_user = $this->getCurrentUser($context);

        if (!$current_user) {
            return false;
        }

        // Deny access to export if it disabled globally or user is not an admin
        if ($view == 'export' && (!empty($sugar_config['disable_export'])
                || !$current_user->isAdminForModule($module))) {
            return false;
        }

        $bean = self::loadBean($module, $context);

        $myself = $this->myselfCheck($bean, $current_user);


        // Let's make it a little easier on ourselves and fix up the actions nice and quickly
        $view = SugarACLStrategy::fixUpActionName($view);
        if ( $view == 'field' ) {
            $context['action'] = SugarACLStrategy::fixUpActionName($context['action']);
        }

        // even an admin can't delete themselves
        if( $myself ) {
            if ( $view == 'delete') {
                // Here's the obvious way to disable yourself
                return false;
            }
            if ( $view == 'field'
                 && ( $context['action'] == 'edit' || $context['action'] == 'massupdate' || $context['action'] == 'delete' )
                 && ( $context['field'] == 'employee_status' || $context['field'] == 'status' ) ) {
                // This is another way to disable yourself
                return false;
            }
        }


        if($current_user->isAdminForModule($module)) {
            return true;
        }

        if(empty($view) || empty($current_user->id)) {
            return true;
        }

        // We can edit ourself
        if( $myself && $view == 'edit' ) {
            return true;
        }

        if ( !$myself && $view == 'field' && !empty($this->no_access_fields[$context['field']])) {
            // This isn't us, these aren't fields we should be poking around in.
            return false;
        }

        if(!empty($this->view_checks[$view])) {
            if( $view == 'field'
                && ($context['action'] == 'edit' || $context['action'] == 'massupdate' || $context['action'] == 'delete')
                && !empty($this->no_edit_fields[$context['field']])) {

                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Load bean from context
     * @static
     * @param string $module
     * @param array $context
     * @return SugarBean
     */
    protected static function loadBean($module, $context = array())
    {
        $bean = false;

        if(isset($context['bean']) && $context['bean'] instanceof SugarBean && $context['bean']->module_dir == $module) {
            $bean = $context['bean'];
        }
        return $bean;
    }


    /**
     * Check access for the list of fields
     * @param string $module
     * @param array $field_list key=>value list of fields
     * @param string $action Action to check
     * @param array $context
     * @return array[boolean] Access for each field, array() means all allowed
     */
    public function checkFieldList($module, $field_list, $action, $context)
    {
        // we need the user
        $current_user = $this->getCurrentUser($context);
        $is_admin = false;
        if(!empty($current_user) && $current_user->isAdminForModule($module)) {
            $is_admin = true;
        }
        $bean = self::loadBean($module, $context);

        $myself = $this->myselfCheck($bean, $current_user);
        $result = array();
        foreach($field_list as $key => $field) {
            // you can't set your own status
            if($myself == true && ($field == 'status' || $field == 'employee_status') && ($action == 'edit' || $action == 'massupdate' || $action == 'delete')) {
                $result[$key] = false;
                // admins can have access to every field
            } elseif($is_admin) {
                $result[$key] = true;
                // everything else should go through checks
            } else {
                if($action == 'field' && ($field == 'user_hash' || $field == 'password') && !$myself) {
                    $result[$key] = false;
                } elseif(!$myself && !empty($this->no_access_fields[$field])) {
                    $result[$key] = false;
                } elseif(($action == 'edit' || $action == 'massupdate' || $action == 'delete') && !empty($this->no_edit_fields[$field])) {
                    $result[$key] = false;
                } else {
                    $result[$key] = $this->checkAccess($module, "field", $context + array("field" => $field, "action" => $action));
                }
            }
        }
        return $result;
    }

    /**
     * Get access for the list of fields
     * @param string $module
     * @param array $field_list key=>value list of fields
     * @param array $context
     * @return array[int] Access for each field, array() means all allowed
     */
    public function getFieldListAccess($module, $field_list, $context)
    {
        // we need the user
        $current_user = $this->getCurrentUser($context);
        $is_admin = false;
        if(!empty($current_user) && $current_user->isAdminForModule($module)) {
            $is_admin = true;
        }
        $bean = self::loadBean($module, $context);

        $myself = $this->myselfCheck($bean, $current_user);

        $result = array();
        foreach($field_list as $key => $field) {
            if($myself == true) {
                if($field == 'status' || $field == 'employee_status') {
                    $result[$key] = SugarACL::ACL_READ_ONLY;
                }
            }

            if($is_admin === true) {
                $result[$key] = SugarACL::ACL_READ_WRITE;
            } else {
                if(($field == 'user_hash' || $field == 'password') && !$myself) {
                    $result[$key] = SugarACL::ACL_NO_ACCESS;
                } elseif(!empty($this->no_edit_fields[$field])) {
                    $result[$key] = SugarACL::ACL_READ_ONLY;
                } elseif(!empty($this->no_access_fields[$field])) {
                    $result[$key] = SugarACL::ACL_NO_ACCESS;
                } else {
                    $result[$key] = SugarACL::ACL_READ_WRITE;
                }
            }

            if ($myself == true) {
                if ($field == 'pwd_last_changed' || $field == 'last_login') {
                    $result[$key] = SugarACL::ACL_READ_ONLY;
                }
            }
        }
        return $result;
    }

    /**
     * Check if the user is the current user
     *
     * @param User|null $user
     * @param User|null $current_user
     *
     * @return bool
     */
    public function myselfCheck($user, $current_user)
    {
        return $user && $current_user && $user->id == $current_user->id;
    }
}
