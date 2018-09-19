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
$fields_array['EmailMan'] = array ('column_fields' => Array(
		"id"
		, "date_entered"
		, "date_modified"
		, 'user_id'
		, 'module'
		, 'module_id'
		, 'marketing_id'
		, 'campaign_id'
		, 'list_id'
		, 'template_id'
		, 'from_email'
		, 'from_name'
		, 'invalid_email'
		, 'send_date_time'
		, 'in_queue'
		, 'in_queue_date'
		,'send_attempts'
		),
        'list_fields' =>  Array(
		"id"
		, 'user_id'
		, 'module'
		, 'module_id'
		, 'campaign_id'
		, 'marketing_id'
		, 'list_id'
		, 'invalid_email'
		, 'from_name'
		, 'from_email'
		, 'template_id'
		, 'send_date_time'
		, 'in_queue'
		, 'in_queue_date'
		,'send_attempts'
		,'user_name'
		,'to_email'
		,'from_email'
		,'campaign_name'
		,'to_contact'
		,'to_lead'
		,'to_prospect'
		,'contact_email'
		, 'lead_email'
		, 'prospect_email'
        ),
);
?>