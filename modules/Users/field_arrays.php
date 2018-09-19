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
$fields_array['User'] = array (
	'column_fields' => array(
		'id',
		'full_name',
		'user_name'
		,'user_hash'
		,'first_name'
		,'last_name'
		,'description'
		,'date_entered'
		,'date_modified'
		,'modified_user_id'
		, 'created_by'
		,'title'
		,'department'
		,'is_admin'
		,'phone_home'
		,'phone_mobile'
		,'phone_work'
		,'phone_other'
		,'phone_fax'
		,'address_street'
		,'address_city'
		,'address_state'
		,'address_postalcode'
		,'address_country'
		,'reports_to_id'
		,'portal_only'
		,'status'
		,'receive_notifications'
		,'employee_status'
		,'messenger_id'
		,'messenger_type'
		,'is_group'


		,'default_team'
	),
    'list_fields' => array(
    	'full_name',
		'id', 
		'first_name', 
		'last_name', 
		'user_name', 
		'status', 
		'department', 
		'is_admin', 
		'email1', 
		'phone_work', 
		'title', 
		'reports_to_name', 
		'reports_to_id', 
		'is_group'

	),
	'export_fields' => array(
		'id',
		'user_name'
		,'first_name'
		,'last_name'
		,'description'
		,'date_entered'
		,'date_modified'
		,'modified_user_id'
		,'created_by'
		,'title'
		,'department'
		,'is_admin'
		,'phone_home'
		,'phone_mobile'
		,'phone_work'
		,'phone_other'
		,'phone_fax'
		,'address_street'
		,'address_city'
		,'address_state'
		,'address_postalcode'
		,'address_country'
		,'reports_to_id'
		,'portal_only'
		,'status'
		,'receive_notifications'
		,'employee_status'
		,'messenger_id'
		,'messenger_type'
		,'is_group'


		,'default_team'
	),
    'required_fields' =>   array("last_name"=>1,'user_name'=>2,'status'=>3),
);

$fields_array['UserSignature'] = array(
	'column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'deleted',
		'user_id',
		'name',
		'signature',
	),
	'list_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'deleted',
		'user_id',
		'name',
		'signature',
	),
);
?>