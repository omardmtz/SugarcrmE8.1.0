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
/*********************************************************************************

 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['ProjectTask'] = array ('column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'assigned_user_id',
		'modified_user_id',
		'created_by',
		'team_id',
		'name',
		'date_start',
		'date_finish',
		'project_id',
		'duration',
		'duration_unit',
		'priority',
		'status',
		'description',
        'project_task_id',
        'actual_duration',
		'milestone_flag',
		'percent_complete',
		'estimated_effort',
		'utilization', 
		'order_number',
		'task_number',
		'deleted',
	),
        'list_fields' =>  array(
		'id',
		'parent_id',
		'parent_name',
		'priority',
		'name',
		'date_start',
		'date_finish',
		'percent_complete',
		'status',
		'assigned_user_id',
		'assigned_user_name',
	),
    'required_fields' =>  array('name'=>1, 'project_id'=>2, 'project_task_id'=>3, 'duration'=>4, 'duration_unit'=>5),
);
?>