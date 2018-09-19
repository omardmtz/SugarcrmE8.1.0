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
 * $Id: field_arrays.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Case'] = array ('column_fields' => Array("id"
        , "name"
        , "case_number"
        , "account_name"
        , "account_id"
        , "date_entered"
        , "date_modified"
        , "modified_user_id"
        , "assigned_user_id"
        , "created_by"
        ,"team_id"
        , "status"
        , "priority"
        , "description"
        , "resolution"
        ),
        'list_fields' => Array('id', 'priority', 'status', 'name', 'account_name', 'case_number', 'account_id', 'assigned_user_name', 'assigned_user_id'
    , "team_id"
    , "team_name"
        ),
        'required_fields' => array('name'=>1, 'account_name'=>2),
);
?>
