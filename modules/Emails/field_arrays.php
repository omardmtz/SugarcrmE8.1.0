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
$fields_array['Email'] = array(
	'column_fields' => array(
		"id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"
		,"team_id"
		, "description"
		, "description_html"
		, "name"
		, "date_start"
		, "time_start"
		, "parent_type"
		, "parent_id"
		, "from_addr"
		, "from_name"
		, "to_addrs"
		, "cc_addrs"
		, "bcc_addrs"
		, "to_addrs_ids"
		, "to_addrs_names"
		, "to_addrs_emails"
		, "cc_addrs_ids"
		, "cc_addrs_names"
		, "cc_addrs_emails"
		, "bcc_addrs_ids"
		, "bcc_addrs_names"
		, "bcc_addrs_emails"
		, "type"
		, "status"
		, "intent"
		),
	'list_fields' => array(
		'id', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id', 'first_name','last_name','to_addrs','from_addr','date_sent','type_name','type','status','link_action','date_entered','attachment_image','intent','date_sent'
	, "team_id"
	, "team_name"
		),
);
?>