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
 * Class DashboardHelper
 *
 * Helper methods for Dashboard manipulation and retrieval.
 */
class DashboardHelper
{
    private static $instance;

    /**
     * Statically gets a list of allowed dashboard modules in the current language.
     *
     * @return array List of translated module names.
     */
    public static function getDashboardsModulesDropdown()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }

        global $moduleList, $app_list_strings;
        return self::$instance->prepareDashboardModules($moduleList, $app_list_strings['moduleList']);
    }

    /**
     * Prepares the passed in dashboard modules in the following ways:
     * 1. Removes modules that the user does not have access to.
     * 2. Map between the canonical module name and its local translation.
     * 3. Sort the modules alphabetically.
     *
     * @param array $moduleList Complete list of modules in the app.
     * @param array $translations A mapping between module and  its translation.
     *
     * @return array Mapping between canonical module name and translated name.
     */
    public function prepareDashboardModules(array $moduleList, array $translations)
    {
        $allowedModules = array();

        foreach ($moduleList as $module) {
            if (!$this->checkModuleAccess($module)) {
                continue;
            }
            $allowedModules[$module] = $translations[$module];
        }

        asort($allowedModules);

        return $allowedModules;
    }

    /**
     * Wrapper around SugarACL checkAccess.
     *
     * @param $module string The module to check.
     *
     * @return bool True if access is allowed, otherwise false.
     */
    public function checkModuleAccess($module)
    {
        return SugarACL::checkAccess($module, 'access');
    }
}
