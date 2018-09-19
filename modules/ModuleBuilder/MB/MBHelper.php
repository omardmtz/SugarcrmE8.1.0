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
 * Class for common ModuleBuilder utilities
 */
class MBHelper
{
    /**
     * Brittle list of roles that should not be used with RBV.
     * We need a better way of identifing these roles in the future.
     * @var array
     */
    protected static $hiddenRoles = array(
        "Tracker",
        "Customer Self-Service Portal Role"
    );

    /**
     * Returns list of roles with marker indicating whether role specific metadata exists
     *
     * @param callable $callback Callback that checks if there is role specific metadata
     * @return array
     */
    public static function getAvailableRoleList($callback)
    {
        $roles = self::getRoles($callback);

        $result = array('' => translate('LBL_DEFAULT'));
        foreach ($roles as $role) {
            $hasMetadata = $roles->offsetGet($role);
            $prefix = $hasMetadata ? '* ' : '';
            $result[$role->id] = $prefix . $role->name;
        }

        return $result;
    }

    /**
     * Returns list of roles which have role specific metadata
     *
     * @param callable $callback
     * @param $currentRole
     * @return array
     */
    public function getRoleListWithMetadata($callback, $currentRole)
    {
        $roles = self::getRoles($callback);

        $result = array();
        foreach ($roles as $role) {
            $hasMetadata = $roles->offsetGet($role);
            if ($hasMetadata && $role->id != $currentRole) {
                $result[$role->id] = $role->name;
            }
        }

        return $result;
    }

    /**
     * Returns object storage containing available roles as keys
     * and flags indicating if there is role specific metadata as value
     *
     * @param callable $callback Callback that checks if there is role specific metadata
     * @return SplObjectStorage
     */
    public static function getRoles($callback = null)
    {
        global $current_user;

        $roles = new SplObjectStorage();
        //Only super user should have access to all roles
        $allRoles = $current_user->isAdmin() ? ACLRole::getAllRoles() : ACLRole::getUserRoles($current_user->id, false);
        foreach ($allRoles as $role) {
            if (in_array($role->name, static::$hiddenRoles)) {
                continue;
            }
            $roles[$role] = $callback ? $callback(array(
                'role' => $role->id,
            )) : null;
        }

        return $roles;
    }
}
