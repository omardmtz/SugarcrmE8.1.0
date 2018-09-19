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
 * $Id: field_arrays.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Holiday'] = array ('column_fields' => array (
		'id', 
		'date_entered', 
		'date_modified',
		'modified_user_id', 
		'created_by', 
		'holiday_date', 
		'description',
		'person_id',
		'person_type',
		'related_module_id',
		'related_module',
	),
        'list_fields' =>  array (
		'id',
		'holiday_date',
		'description',
		'person_id',
		'person_type',
		'related_module_id',
		'related_module',
	),
    'required_fields' =>   array (
		'holiday_date'=>1,
	),
);
?>