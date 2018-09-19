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
$fields_array['ReportMaker'] = array ('column_fields' => Array("id"
		,"name"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"created_by"
		,"description"
		,"title"
		,"team_id"
		,"report_align"
		),
        'list_fields' =>  array('id', 'name', 'type', 'module_title', 'schedule_id', 'active', 'next_run'),
    'required_fields' =>  array("name"=>1),
);
?>