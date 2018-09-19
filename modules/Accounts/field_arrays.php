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
$fields_array['Account'] = array ('column_fields' => Array(
		"annual_revenue"
		,"billing_address_street"
		,"billing_address_city"
		,"billing_address_state"
		,"billing_address_postalcode"
		,"billing_address_country"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		,"description"
		,"email1"
		,"email2"
		,"employees"
		,"id"
		,"industry"
		,"name"
		,"ownership"
		,"parent_id"
		,"phone_alternate"
		,"phone_fax"
		,"phone_office"
		,"rating"
		,"shipping_address_street"
		,"shipping_address_city"
		,"shipping_address_state"
		,"shipping_address_postalcode"
		,"shipping_address_country"
		,"sic_code"
		,"ticker_symbol"
		,"account_type"
		,"website"
		, "created_by"
		,"team_id"
		),
        'list_fields' => Array('id', 'name', 'website', 'phone_office', 'assigned_user_name', 'assigned_user_id'
	, 'billing_address_street'
	, 'billing_address_city'
	, 'billing_address_state'
	, 'billing_address_postalcode'
	, 'billing_address_country'
	, 'shipping_address_street'
	, 'shipping_address_city'
	, 'shipping_address_state'
	, 'shipping_address_postalcode'
	, 'shipping_address_country'
	, "team_id"
	, "team_name"
		),
        'required_fields' => array("name"=>1),
);
?>