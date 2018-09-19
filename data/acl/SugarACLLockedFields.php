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

use Sugarcrm\Sugarcrm\ProcessManager\Registry;

/**
 * Class SugarACLLockedFields
 *
 * To check the edit access for a field based on whether it's a locked field
 */
class SugarACLLockedFields extends SugarACLStrategy
{
    /**
     * Check access to the locked field
     * @param string $module
     * @param string $view
     * @param array $context
     * @return bool true if not a locked field, false otherwise
     */
    public function checkAccess($module, $view, $context)
    {
        // only need to check field acl
        if ($view != 'field') {
            return true;
        }

        // nothing to check
        if (empty($context) || empty($context['field']) || empty($context['action'])) {
            return true;
        }

        // only need to check write type action, but not delete
        $action = self::fixUpActionName($context['action']);
        $writes = array('edit', 'import', 'massupdate');
        if (!in_array($action, $writes)) {
            return true;
        }

        // If the skip flag is set, it will be true, so check if it is not true
        // to determine if we need to enforce locked field checking
        if (Registry\Registry::getInstance()->get('skip_locked_field_checks') === true) {
            return true;
        }

        // to get bean object
        $bean = static::loadBean($module, $context);
        if (empty($bean)) {
            return true;
        }

        // Get our locked field array for checking
        $lockedFields = $bean->getLockedFields();

        // If there are locked fields...
        if (!empty($lockedFields)) {
            // See if the requested field is in the locked list. If it is, return false.
            if (in_array($context['field'], $lockedFields)) {
                return false;
            }

            // Handle group fields, since individual fields of a group lock the group
            if ($this->isGroupLocked($context['field'], $lockedFields, $bean->field_defs)) {
                return false;
            }
        }

        // Default is true
        return true;
    }

    /**
     * Load bean from context
     * @static
     * @param string $module
     * @param array $context
     * @return SugarBean
     */
    public static function loadBean($module, $context = array())
    {
        $bean = null;
        if (isset($context['bean']) && $context['bean'] instanceof SugarBean
            && $context['bean']->module_dir == $module) {
            $bean = $context['bean'];
        }
        return $bean;
    }

    /**
     * Checks to see if a field is in a group field that might also be locked
     * @param string $field The field to check
     * @param array $lockedFields The locked fields array
     * @param array $defs The beans field def array
     * @return boolean true if the field is grouped and locked
     */
    protected function isGroupLocked($field, array $lockedFields, array $defs)
    {
        // Loop over each field to see if it is a group field
        foreach ($defs as $def) {
            // And check to see if the group is the same as the requested field
            if (isset($def['group']) && $def['group'] === $field) {
                $groupSet = array();
                // Now find all fields in the group
                foreach ($defs as $d) {
                    if ((isset($d['group']) && $d['group'] === $def['group']) || $d['name'] === $def['group']) {
                        $groupSet[] = $d['name'];
                    }
                }

                // Only compare if there is a need to
                if (!empty($groupSet)) {
                    $check = array_intersect($groupSet, $lockedFields);
                    // If any of the fields in the group are also in the lockedFields array...
                    if (!empty($check)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
