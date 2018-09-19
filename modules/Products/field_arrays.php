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
$fields_array['Product'] = array ('column_fields' => Array("id"
		,"product_template_id"
		,"name"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		, "created_by"
		,"date_purchased"
		,"manufacturer_id"
		,"type_id"
		,"quote_id"
		,"tax_class"
		,"vendor_part_num"
		,"category_id"
		,'cost_usdollar'
		,'list_usdollar'
		,'discount_usdollar'
		,'deal_calc_usdollar'
		,'currency_id'
		,"status"
		,"cost_price"
		,"discount_price"
		,"discount_amount"
		,"deal_calc"
		,"discount_select"
		,"list_price"
		,"mft_part_num"
		,"weight"
		,"quantity"
		,"website"
		,"support_name"
		,"support_description"
		,"support_contact"
		,"support_term"
		,"date_support_expires"
		,"date_support_starts"
		,"pricing_formula"
		,"pricing_factor"
		,"description"
		,"account_id"
		,"contact_id"
		,"team_id"
		,"serial_number"
		,"asset_number"
		,"book_value"
		,"book_value_date"
		),
        'list_fields' =>  array('id', 'name', 'status', 'quantity', 'date_purchased', 'cost_price',
			'cost_usdollar', 'discount_amount', 'discount_select', 'discount_price','discount_usdollar', 'list_price','list_usdollar','deal_calc','deal_calc_usdollar',
			'mft_part_num', 'manufacturer_name', 'account_name', 'account_id', 'contact_id',
			'contact_name', 'date_support_expires'),
    'required_fields' =>  array("name"=>1,  ),
);
?>