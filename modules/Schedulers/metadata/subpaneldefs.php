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

$layout_defs['Schedulers'] = array(
	// list of what Subpanels to show in the DetailView 
	'subpanel_setup' => array( 
        'times' => array(
			'order' => 20,
			'module' => 'SchedulersJobs',
			'sort_by' => 'execute_time',
			'sort_order' => 'desc',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'schedulers_times',
			'add_subpanel_data' => 'scheduler_id',
			'title_key' => 'LBL_JOBS_SUBPANEL_TITLE',
			'top_buttons' => array(
			),
		),
	),
);
 
?>