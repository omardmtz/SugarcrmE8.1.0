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
 * This class is here to provide functions to easily call in to the individual module api helpers
 */
class ApiHelper
{
    /**
     * A list of all the loaded helpers so we don't have to reload them all again
     *
     * @var array
     */
    public static $moduleHelpers = array();

    /**
     * This method looks up the helper class for the bean and will provide the default helper
     * if there is not one defined for that particular bean
     *
     * @param $api ServiceBase The API that will be associated to this helper class
     *                         This is used so the formatting functions can handle different
     *                         API's with varying formatting requirements.
     * @param $bean SugarBean Grab the helper module for this bean
     * @return SugarBeanApiHelper API helper
     */
    public static function getHelper(ServiceBase $api, SugarBean $bean)
    {
        $modulePath = $bean->module_dir;
        $moduleName = $bean->module_name;

        if (!isset(self::$moduleHelpers[$moduleName])) {
            if (SugarAutoLoader::requireWithCustom('modules/' . $modulePath . '/' . $moduleName . 'ApiHelper.php')) {
                $moduleHelperClass = SugarAutoLoader::customClass($moduleName . 'ApiHelper');
            } elseif (file_exists('custom/data/SugarBeanApiHelper.php')) {
                require_once('custom/data/SugarBeanApiHelper.php');
                $moduleHelperClass = 'CustomSugarBeanApiHelper';
            } else {
                $moduleHelperClass = 'SugarBeanApiHelper';
            }

            self::$moduleHelpers[$moduleName] = new $moduleHelperClass($api);
        }

        return self::$moduleHelpers[$moduleName];
    }

    /**
     * Override module's api helper class
     *
     * For use mainly in unit tests
     *
     * @param $module
     * @param SugarBeanApiHelper|null $helper What class to set the helper as, if null, the helper will be unset
     */
    public static function setHelper($module, SugarBeanApiHelper $helper = null)
    {
        if (is_null($helper) && isset(self::$moduleHelpers[$module])) {
            unset(self::$moduleHelpers[$module]);
        } elseif (is_object($helper)) {
            self::$moduleHelpers[$module] = $helper;
        }
    }
}
