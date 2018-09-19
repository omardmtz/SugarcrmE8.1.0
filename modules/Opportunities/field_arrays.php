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
$fields_array['Opportunity'] = array ('column_fields' => Array("id"
		, "name"
		, "opportunity_type"
		, "lead_source"
		, "amount"
		, "currency_id"
		, "amount_usdollar"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "assigned_user_id"
		, "created_by"
		,"team_id"
		, "date_closed"
		, "next_step"
		, "sales_stage"
		, "probability"
		, "description"
		,"campaign_id"
		),
        'list_fields' => Array('id', 'name', 'account_id', 'sales_stage', 'account_name', 'date_closed', 'amount', 'assigned_user_name', 'assigned_user_id','sales_stage','probability','lead_source','opportunity_type'
	, "team_id"
	, "team_name"
	, "amount_usdollar"
	),
        'required_fields' => Array('name'=>1, 'date_closed'=>2, 'amount'=>3, 'sales_stage'=>4, 'account_name'=>5),
);
?>