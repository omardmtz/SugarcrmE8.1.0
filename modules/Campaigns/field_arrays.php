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
$fields_array['Campaign'] = array ('column_fields' => array(
				"id", "date_entered",
				"date_modified", "modified_user_id",
				"assigned_user_id", "created_by",
				"team_id",
				"name", "start_date",
				"end_date", "status",
				"budget", "expected_cost",
				"actual_cost", "expected_revenue",
				"campaign_type", "objective",
				"content", "tracker_key","refer_url","tracker_text",
				"tracker_count","currency_id","impressions",
                "frequency",
	),
        'list_fields' => array(
				'id', 'name', 'status',
				'campaign_type','assigned_user_id','assigned_user_name','end_date',
				'team_id',
				'team_name',
				'refer_url',"currency_id",
	),
        'required_fields' => array(
				'name'=>1, 'end_date'=>2,
				'status'=>3, 'campaign_type'=>4
	),
);
?>