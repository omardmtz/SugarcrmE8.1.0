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

class DashboardApi extends ModuleApi
{
    /**
     * Rest Api Registration Method
     *
     * @return array
     */
    public function registerApiRest()
    {
        $dashboardApi = array(
            'createDashboardForModule' => array(
                'reqType' => 'POST',
                'minVersion' => '10',
                'maxVersion' => '10',
                'path' => array('Dashboards', '<module>'),
                'pathVars' => array('', 'module'),
                'method' => 'createDashboard',
                'shortHelp' => 'Create a new dashboard for a module',
                'longHelp' => 'include/api/help/create_dashboard.html',
            ),
            'createDashboardForHome' => array(
                'reqType' => 'POST',
                'minVersion' => '10',
                'maxVersion' => '10',
                'path' => array('Dashboards'),
                'pathVars' => array(''),
                'method' => 'createDashboard',
                'shortHelp' => 'Create a new dashboard for home',
                'longHelp' => 'include/api/help/create_home_dashboard.html',
            ),
        );
        return $dashboardApi;
    }

    /**
     * Create a new dashboard
     *
     * @param ServiceBase $api      The Api Class
     * @param array $args           Service Call Arguments
     * @return mixed
     */
    public function createDashboard(ServiceBase $api, array $args)
    {
        $args['dashboard_module'] = empty($args['module']) ? 'Home' : $args['module'];
        $bean = BeanFactory::newBean('Dashboards');
        
        if (!$bean->ACLAccess('save')) {
            // No create access so we construct an error message and throw the exception
            $failed_module_strings = return_module_language($GLOBALS['current_language'], 'Dashboards');
            $moduleName = $failed_module_strings['LBL_MODULE_NAME'];
            $args = null;
            if(!empty($moduleName)){
                $args = array('moduleName' => $moduleName);
            }
            throw new SugarApiExceptionNotAuthorized('EXCEPTION_CREATE_MODULE_NOT_AUTHORIZED', $args);
        }

        $id = $this->updateBean($bean, $api, $args);
        $args['record'] = $id;
        $args['module'] = 'Dashboards';
        $bean = $this->loadBean($api, $args, 'view');
        $data = $this->formatBean($api, $args, $bean);
        return $data;
    }
}
