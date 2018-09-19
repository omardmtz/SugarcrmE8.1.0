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

// $Id: layout_defs.php 14232 2006-06-23 00:33:16 +0000 (Fri, 23 Jun 2006) wayne $


global $modules_exempt_from_availability_check;
$modules_exempt_from_availability_check = array('Holidays'=>'Holidays',
												'Tasks'=>'Tasks',
												'Calls'=>'Calls',
												'Meetings'=>'Meetings',
												'History'=>'History',
												'Notes'=>'Notes',
												'Emails'=>'Emails',
												'Users'=>'Users',
											   );

$layout_defs['Project'] = array(
	// list of what Subpanels to show in the DetailView
	'subpanel_setup' => array(

		'projectresources' => array(
			'order' => 10,
			'sort_order' => 'desc',
			'sort_by' => 'id',
			'title_key' => 'LBL_RESOURCES_SUBPANEL_TITLE',
			'type' => 'collection',
			'subpanel_name' => 'project',   //this values is not associated with a physical file.
			'module'=>'Project',

			// The buttons are defined at the bottom of this file
			'top_buttons' => array(
				//array('widget_class' => 'SubPanelTopSelectUsersButton', ),
				//array('widget_class' => 'SubPanelTopSelectContactsButton', ),
			),

			'collection_list' => array(
				'users' => array(
					'module' => 'Users',
					'subpanel_name' => 'ForProject',
					'get_subpanel_data' => 'user_resources',
				),
				'contacts' => array(
					'module' => 'Contacts',
					'subpanel_name' => 'ForProject',
					'get_subpanel_data' => 'contact_resources',
				),
			)
		),

       'projecttask' => array(
			'order' => 20,
			'sort_order' => 'asc',
			'sort_by' => 'project_task_id',
			'module' => 'ProjectTask',
			'top_buttons' => array(

				array('widget_class' => 'SubPanelEditProjectTasksButton', ),
			),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_PROJECT_TASKS_SUBPANEL_TITLE',
			'get_subpanel_data' => 'projecttask',
		),

		'activities' => array(
			'order' => 40,
			'sort_order' => 'desc',
			'sort_by' => 'date_start',
			'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE',
			'type' => 'collection',
			'subpanel_name' => 'activities',   //this values is not associated with a physical file.
			'module'=>'Activities',

			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateTaskButton'),
				array('widget_class' => 'SubPanelTopScheduleMeetingButton'),
				array('widget_class' => 'SubPanelTopScheduleCallButton'),
				array('widget_class' => 'SubPanelTopComposeEmailButton'),
			),

			'collection_list' => array(
				'meetings' => array(
					'module' => 'Meetings',
					'subpanel_name' => 'ForActivities',
					'get_subpanel_data' => 'meetings',
				),
				'tasks' => array(
					'module' => 'Tasks',
					'subpanel_name' => 'ForActivities',
					'get_subpanel_data' => 'tasks',
				),
				'calls' => array(
					'module' => 'Calls',
					'subpanel_name' => 'ForActivities',
					'get_subpanel_data' => 'calls',
				),
			),
		),

		'history' => array(
			'order' => 50,
			'sort_order' => 'desc',
			'sort_by' => 'date_entered',
			'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
			'type' => 'collection',
			'subpanel_name' => 'history',   //this values is not associated with a physical file.
			'module'=>'History',

			'top_buttons' => array(
			array('widget_class' => 'SubPanelTopCreateNoteButton'),
			array('widget_class' => 'SubPanelTopArchiveEmailButton'),
            array('widget_class' => 'SubPanelTopSummaryButton'),
			),

			'collection_list' => array(
				'meetings' => array(
					'module' => 'Meetings',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'meetings',
				),
				'calls' => array(
					'module' => 'Calls',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'calls',
				),
				'tasks' => array(
					'module' => 'Tasks',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'tasks',
				),
				'notes' => array(
					'module' => 'Notes',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'notes',
				),
				'emails' => array(
					'module' => 'Emails',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'emails',
				),
			)
		),
        'contacts' => array(
            'top_buttons' => array(
			    array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Contacts'),
	         ),
			'order' => 60,
			'module' => 'Contacts',
			'sort_order' => 'asc',
			'sort_by' => 'last_name, first_name',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'contacts',
			'add_subpanel_data' => 'contact_id',
			'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
		),

      'accounts' => array(
            'top_buttons' => array(
			    array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
	         ),
			'order' => 70,
			'module' => 'Accounts',
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'accounts',
			'add_subpanel_data' => 'account_id',
			'title_key' => 'LBL_ACCOUNTS_SUBPANEL_TITLE',
		),
		'opportunities' => array(
            'top_buttons' => array(
			    array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Opportunities'),
	         ),
			'order' => 80,
			'module' => 'Opportunities',
			'sort_order' => 'desc',
			'sort_by' => 'date_closed',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'opportunities',
			'add_subpanel_data' => 'opportunity_id',
			'title_key' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
		),

        'quotes' => array(
            'top_buttons' => array(
			    array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Quotes'),
	         ),
			'order' => 90,
			'module' => 'Quotes',
			'sort_order' => 'desc',
			'sort_by' => 'date_quote_expected_closed',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'quotes',
			'add_subpanel_data' => 'quote_id',
			'title_key' => 'LBL_QUOTES_SUBPANEL_TITLE',
		),
        'cases' => array(
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Cases'),
             ),
            'order' => 110,
            'module' => 'Cases',
            'sort_order' => 'desc',
            'sort_by' => 'case_number',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'cases',
            'add_subpanel_data' => 'case_id',
            'title_key' => 'LBL_CASES_SUBPANEL_TITLE',
        ),
        'bugs' => array(
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Bugs'),
             ),
            'order' => 120,
            'module' => 'Bugs',
            'sort_order' => 'desc',
            'sort_by' => 'bug_number',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'bugs',
            'add_subpanel_data' => 'bug_id',
            'title_key' => 'LBL_BUGS_SUBPANEL_TITLE',
        ),

        'products' => array(
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Products'),
             ),
            'order' => 100,
            'module' => 'Products',
            'sort_order' => 'desc',
            'sort_by' => 'name',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'products',
            'add_subpanel_data' => 'product_id',
            'title_key' => 'LBL_PRODUCTS_SUBPANEL_TITLE',
        ),

		'holidays' => array(
			'order' => 30,
			'sort_by' => 'holiday_date',
			'sort_order' => 'asc',
			'module' => 'Holidays',
			'subpanel_name' => 'ForProject',
			'get_subpanel_data' => 'function:getProjectHolidays',
			'set_subpanel_data' => 'project_holidays',
			'refresh_page'=>1,
             'top_buttons' => array(
               // array('widget_class' => 'SubPanelTopButtonQuickCreate'),
              ),
			'title_key' => 'LBL_PROJECT_HOLIDAYS_TITLE',
		),
   ),
);

global $current_user, $app;
// check if $app present - if in Studio/MB then loading a subpanel definition through the SubpanelDefinitions class 'requires' this file without an $app
if (isset($app) && isset($app->controller))
{
$projectId = $app->controller->record;
$focus = BeanFactory::getBean('Project', $projectId);
	if (isset($focus) && $focus->object_name == 'Project')
	{
		// make this security check ONLY in the Project detail view
	    $layout_defs['Project']['subpanel_setup']['holidays']['top_buttons'] =
	    array(array('widget_class' => 'SubPanelTopCreateButton'),array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect' ));
	
		$layout_defs['Project']['subpanel_setup']['projectresources']['top_buttons'] =
		array(array('widget_class' => 'SubPanelTopSelectUsersButton', 'mode'=>'MultiSelect' ),
		array('widget_class' => 'SubPanelTopSelectContactsButton', 'mode'=>'MultiSelect' ));
		
	}
}
$layout_defs['ProjectTemplates'] = array(
	// list of what Subpanels to show in the DetailView
	'subpanel_setup' => array(
       'projecttask' => array(
			'top_buttons' => array(
				array('widget_class' => 'SubPanelEditProjectTasksButton', ),
			),
			'order' => 10,
			'sort_order' => 'desc',
			'sort_by' => 'id',
			'module' => 'ProjectTask',
			'subpanel_name' => 'default',
			'title_key' => 'LBL_PROJECT_TASKS_SUBPANEL_TITLE',
			'get_subpanel_data' => 'projecttask',
		),

	),
);


?>
