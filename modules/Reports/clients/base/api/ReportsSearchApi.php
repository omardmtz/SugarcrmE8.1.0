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

require_once('modules/Reports/SavedReport.php');

class ReportsSearchApi extends PersonFilterApi
{
    public function registerApiRest()
    {
        return array(
            'ReportSearch' => array(
                'reqType' => 'GET',
                'path' => array('Reports'),
                'pathVars' => array('module_list'),
                'method' => 'filterList',
                'shortHelp' => 'Search Reports',
                'longHelp' => 'include/api/help/getListModule.html',
            ),
        );
    }

    /**
     * Gets the proper query where clause to use to prevent special user types from
     * being returned in the result
     *
     * @param string $module The name of the module we are looking for
     * @param SugarQuery|null
     * @return string
     */
    protected function getCustomWhereForModule($module, $query = null)
    {
        $ACLUnAllowedModules = getACLDisAllowedModules();

        if ($query instanceof SugarQuery) {
            foreach ($ACLUnAllowedModules as $module => $class_name) {
                $query->where()->notEquals('saved_reports.module', $module);
            }
            return;
        }

        $where_clauses = array();
        foreach ($ACLUnAllowedModules as $module => $class_name) {
            array_push($where_clauses, "saved_reports.module != '$module'");
        }

        return implode(' AND ', $where_clauses);
    }
}
