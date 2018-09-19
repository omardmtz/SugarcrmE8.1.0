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
$fields_array['Contact'] = array ('column_fields' => Array("id"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		, "created_by"
		,"team_id"
		,"salutation"
		,"first_name"
		,"last_name"
		,"lead_source"
		,"title"
		,"department"
		,"birthdate"
		,"reports_to_id"
		,"do_not_call"
		,"phone_home"
		,"phone_mobile"
		, "portal_name"
		, "portal_app"
		,"portal_active"
        ,"portal_password"
		,"phone_work"
		,"phone_other"
		,"phone_fax"
		,"assistant"
		,"assistant_phone"
		,"email_opt_out"
		,"primary_address_street"
		,"primary_address_city"
		,"primary_address_state"
		,"primary_address_postalcode"
		,"primary_address_country"
		,"alt_address_street"
		,"alt_address_city"
		,"alt_address_state"
		,"alt_address_postalcode"
		,"alt_address_country"
		,"description"
		,'invalid_email'
		,"campaign_id"
		),
        'list_fields' => Array('id', 'first_name', 'last_name', 'account_name', 'account_id', 'title', 'phone_work', 'assigned_user_name', 'assigned_user_id', "case_role", 'case_rel_id', 'opportunity_role', 'opportunity_rel_id'
	, 'quote_role'
	, 'quote_rel_id'
	, "team_id"
	, "team_name"
    ,'invalid_email'
		),
        'required_fields' => array("last_name"=>1),
);
?>