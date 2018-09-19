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

$dictionary['Dashboard'] = array(
    'table' => 'dashboards',
    'fields' => array(
        'dashboard_module' => array(
            'required' => false,
            'name' => 'dashboard_module',
            'vname' => 'LBL_DASHBOARD_MODULE',
            'type' => 'enum',
            'dbType' => 'varchar',
            'len' => 100,
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => true,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'function' => array(
                'name' => 'DashboardHelper::getDashboardsModulesDropdown',
                'include' => 'modules/Dashboards/DashboardHelper.php',
            ),
        ),
        'view_name' => array(
            'required' => false,
            'name' => 'view_name',
            'vname' => 'LBL_VIEW',
            'type' => 'enum',
            'dbType' => 'varchar',
            'len' => 100,
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => true,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'options' => 'dashboard_view_name_list',
        ),
        'metadata' => array(
            'required' => false,
            'name' => 'metadata',
            'vname' => 'LBL_METADATA',
            'type' => 'json',
            'dbType' => 'text',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => true,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
        ),
        'default_dashboard' => array(
            'name' => 'default_dashboard',
            'vname' => 'LBL_DEFAULT_DASHBOARD',
            'type' => 'bool',
            'default' => '0',
            'reportable' => false,
            'duplicate_on_record_copy' => 'no',
            'merge_filter' => 'disabled',
            'comments' => '',
            'massupdate' => 0,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'user_module_view',
            'type' => 'index',
            'fields' => array('assigned_user_id', 'dashboard_module', 'view_name')
        ),
    ),
    'relationships' =>
        array(),
    'uses' => array(
        'team_security',
    ),
    'acls' => array(
        'SugarACLOwnerWrite' => true,
        'SugarACLAdminOnlyFields' => array(
            'non_writable_fields' => array(
                'default_dashboard',
            ),
        ),
    ),
    // FIXME TY-1675 Fix the Default and Basic SugarObject templates so that
    // Basic implements Default. This would allow the application of various
    // implementations on Basic without forcing Default to have those so that
    // situations like this - implementing taggable - doesn't have to apply to
    // EVERYTHING. Since there is no distinction between basic and default for
    // sugar objects templates yet, we need to forecefully remove the taggable
    // implementation fields. Once there is a separation of default and basic
    // templates we can safely remove these as this module will implement
    // default instead of basic.
    'ignore_templates' => array(
        'taggable',
    ),
);

if (!class_exists('VardefManager')) {
}
VardefManager::createVardef('Dashboards', 'Dashboard', array('basic', 'assignable'));
