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
$fields_array['Note'] = array ('column_fields' => Array("id"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "created_by"
		, "description"
		, "name"
		, "filename"
		, "file_mime_type"
		, "parent_type"
		, "parent_id"
		, "contact_id"
		, "portal_flag"
		,"team_id"
		),
        'list_fields' =>  Array('id', 'name', 'parent_type', 'parent_name', 'parent_id','date_modified', 'contact_id', 'contact_name','filename','file_mime_type'
	, "team_id"
	),
    'required_fields' =>  array("name"=>1),
);
?>