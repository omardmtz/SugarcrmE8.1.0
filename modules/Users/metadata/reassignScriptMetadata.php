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
$moduleFilters = array(
	'Accounts' => array(
		'display_default' => false,
		'fields' => array(
			'account_type' => array(
				'display_name' => 'Account Type',
				'name' => 'account_type',
				'vname' => 'LBL_TYPE',
				'dbname' => 'account_type',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '4',
				'dropdown' => $app_list_strings['account_type_dom'],
			),
		),
	),
	'Bugs' => array(
		'display_default' => false,
		'fields' => array(
			'status' => array(
				'display_name' => 'Status',
				'name' => 'status',
				'vname' => 'LBL_STATUS',
				'dbname' => 'status',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '5',
				'dropdown' => $app_list_strings['bug_status_dom'],
			),
		),
	),
	'Calls' => array(
		'display_default' => false,
		'fields' => array(
			'status' => array(
				'display_name' => 'Status',
				'name' => 'status',
				'vname' => 'LBL_STATUS',
				'dbname' => 'status',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '3',
				'dropdown' => $app_list_strings['call_status_dom'],
			),
		),
	),
	
	'Cases' => array(
		'display_default' => false,
		'fields' => array(
			'priority' => array(
				'display_name' => 'Priority',
				'name' => 'priority',
				'vname' => 'LBL_PRIORITY',
				'dbname' => 'priority',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '3',
				'dropdown' => $app_list_strings['case_priority_dom'],
			),
			'status' => array(
				'display_name' => 'Status',
				'name' => 'status',
				'vname' => 'LBL_STATUS',
				'dbname' => 'status',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '3',
				'dropdown' => $app_list_strings['case_status_dom'],
			),
		),
	),
    'Dashboards' => array(
        'display_default' => false,
        'fields' => array(
            'default_dashboard' => array(
                'display_name' => 'Default Dashboard',
                'name' => 'default_dashboard',
                'vname' => 'LBL_DEFAULT_DASHBOARD',
                'dbname' => 'default_dashboard',
                'custom_table' => false,
                'type' => 'multiselect',
                'size' => '2',
                'dropdown' => $app_list_strings['filter_checkbox_dom'],
            ),
        ),
    ),
	'Opportunities' => array(
		'display_default' => false,
		'fields' => array(
			'sales_stage' => array(
				'display_name' => 'Sales Stage',
				'name' => 'sales_stage',
				'vname' => 'LBL_SALES_STAGE',
				'dbname' => 'sales_stage',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '4',
				'dropdown' => $app_list_strings['sales_stage_dom'],
			),
			'opportunity_type' => array(
				'display_name' => 'Opportunity Type',
				'name' => 'opportunity_type',
				'vname' => 'LBL_TYPE',
				'dbname' => 'opportunity_type',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '4',
				'dropdown' => $app_list_strings['opportunity_type_dom'],
			),
		),
	),
	'Tasks' => array(
		'display_default' => false,
		'fields' => array(
			'status' => array(
				'display_name' => 'Status',
				'name' => 'status',
				'vname' => 'LBL_STATUS',
				'dbname' => 'status',
				'custom_table' => false,
				'type' => 'multiselect',
				'size' => '5',
				'dropdown' => $app_list_strings['task_status_dom'],
			),
		),
	),
);

?>
