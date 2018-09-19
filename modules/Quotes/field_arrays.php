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
$fields_array['Quote'] = array ('column_fields' => Array("id"
		, "name"
		, "quote_type"
		, "subtotal"
		, "deal_tot"
		, "deal_tot_usdollar"
		, "new_sub"
		, "new_sub_usdollar"
		, "subtotal_usdollar"
		, "shipping"
		, "shipping_usdollar"
		, "tax"
		, "tax_usdollar"
		, "total"
		, "total_usdollar"
		,'show_line_nums'
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "assigned_user_id"
		, "created_by"
		, "team_id"
		, "shipper_id"
		, "currency_id"
		, "taxrate_id"
		, "date_quote_expected_closed"
		, "date_quote_closed"
		, "quote_stage"
		, "description"
		, "purchase_order_num"
		, "quote_num"
		, "billing_address_street"
		, "billing_address_city"
		, "billing_address_state"
		, "billing_address_postalcode"
		, "billing_address_country"
		, "shipping_address_street"
		, "shipping_address_city"
		, "shipping_address_state"
		, "shipping_address_postalcode"
		, "shipping_address_country"
		, "payment_terms"
		, "original_po_date"
		),
        'list_fields' =>  Array('id', 'quote_type', 'name', 'billing_account_name', 'billing_account_id'
	,'date_quote_expected_closed', 'total', 'assigned_user_name', 'assigned_user_id'
	,'quote_stage', 'shipping_account_name', 'shipping_account_id'
	, "team_id", "team_name", 'total_usdollar','new_sub'
	, "quote_num"
	),
    'required_fields' =>   Array('name'=>1, 'date_quote_expected_closed'=>1,  'quote_stage'=>1, 'billing_account_name'=>1),
);
?>