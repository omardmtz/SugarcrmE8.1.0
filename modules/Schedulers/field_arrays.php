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
$fields_array['Scheduler'] = array (
	'column_fields' => array(
		'id',
		'deleted',
		'date_entered',
		'date_modified',
		'modified_user_id',
		'created_by',
		'name',
		'job',
		'date_time_start',
		'date_time_end',
		'job_interval',
		'time_from',
		'time_to',
		'last_run',
		'status',
		'catch_up',
	),
	'list_fields' => array(
		'id',
		'name',
		'list_order',
		'status'
	),
	'required_fields' => array(
		'name' => 1,
		'list_order' => 1,
		'status' => 1
	),
);

$fields_array['Job'] = array (
	'column_fields' => array (
		'id',
		'deleted',
		'date_entered',
		'date_modified',
		'job_id',
		'execute_time',
		'status',
	),
	'list_fields' => array (
		'id',
		'job_id',
		'execute_time'
	),
	'required_fields' => array (
		'job_id' => 1,
		'execute_time' => 1
	)
);
?>
