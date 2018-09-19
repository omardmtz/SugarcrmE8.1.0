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
$fields_array['Relationship'] = array ('column_fields' => Array(
		'id',
		'relationship_name',
		'lhs_module',
		'lhs_table',
		'lhs_key',
		'rhs_module',
		'rhs_table',
		'rhs_key',
		'join_table',
		'join_key_lhs',
		'join_key_rhs',
		'relationship_type',
		'relationship_role_column',
		'relationship_role_column_value',
		'reverse',
	),
        'list_fields' =>  Array(
		'id',
		'relationship_name',
		'lhs_module',
		'lhs_table',
		'lhs_key',
		'rhs_module',
		'rhs_table',
		'rhs_key',
		'join_table',
		'join_key_lhs',
		'join_key_rhs',
		'relationship_type',
		'relationship_role_column',
		'relationship_role_column_value',
		'reverse',
	),
    'required_fields' =>   array("relationship_name"=>1),
);
?>