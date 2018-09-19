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
$fields_array['Team'] = array (
	'column_fields' => array(
        'id',
		'date_entered',
		'date_modified',
		'modified_user_id',
		'created_by',
		'name',
		'description',
		'private',
	),
    'list_fields' =>  array(
    	'id', 
    	'name', 
    	'description', 
    	'description_head'
	),
	'export_fields' => array(
		'id',
		'name',
	    'name_2',
	    'associated_user_id',
	    'date_entered',
	    'date_modified',
	    'modified_user_id',
	    'created_by',
	    'private',
	    'description',
	    'deleted'
	),	
);
$fields_array['TeamMembership'] = array ('column_fields' => Array("id"
		,"team_id"
		,"user_id"
		,"explicit_assign"
		,"implicit_assign"
		,'date_modified'
		,"deleted"
		),
        'list_fields' =>  Array('id', "team_id", "user_id", "explicit_assign", "implicit_assign", 'date_modified'),
);
?>