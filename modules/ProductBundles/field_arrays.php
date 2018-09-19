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
$fields_array['ProductBundle'] = array ('column_fields' => Array("id"
		,"name"
		,"tax"
		,'tax_usdollar'
		,"shipping"
		,'shipping_usdollar'
		,"subtotal"
		,'deal_tot'
		,'deal_tot_usdollar'
		,'new_sub'
		,'new_sub_usdollar'
		,'subtotal_usdollar'
		,"total"
		,'total_usdollar'
		,'currency_id'
		,'bundle_stage'
		,"is_template"
		,"is_editable"
		,"description"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		, "created_by"
		, 'team_id'
		),
        'list_fields' =>  array('id', 'name', 'tax', 'shipping', 'subtotal', 'deal_tot', 'new_sub', 'new_sub_usdollar', 'total',
			'tax_usdollar', 'shipping_usdollar', 'subtotal_usdollar', 'deal_tot_usdollar','total_usdollar','bundle_stage','team_id'),

);
?>