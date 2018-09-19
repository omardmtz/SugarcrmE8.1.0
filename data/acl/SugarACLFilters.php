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

class SugarACLFilters extends SugarACLStrategy
{
    /**
     * Check access a current user has on Users and Employees
     * @param string $module
     * @param string $view
     * @param array $context
     * @return bool|void
     */
    public function checkAccess($module, $view, $context) {
        
        if($module != 'Filters') {
            // how'd you get here...
            return false;
        }


        $current_user = $this->getCurrentUser($context);

        $bean = self::loadBean($module, $context);

        // non-admin users cannot edit a default filter
        if(!is_admin($current_user)) {
            if(isset($bean) && $bean instanceof SugarBean && !empty($bean->id) && isset($bean->default_filter) && $bean->default_filter == 1){
                if($view == 'save') {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Load bean from context
     * @static
     * @param string $module
     * @param array $context
     * @return SugarBean
     */
    protected static function loadBean($module, $context = array()) {
        if(isset($context['bean']) && $context['bean'] instanceof SugarBean && $context['bean']->module_dir == $module) {
            $bean = $context['bean'];
        } else {
            $bean = BeanFactory::newBean($module);
        }
        return $bean;
    }

}
