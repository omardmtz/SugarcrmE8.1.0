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
$fields_array['Project'] = array ('column_fields' => array(
        'id',
        'date_entered',
        'date_modified',
        'assigned_user_id',
        'modified_user_id',
        'created_by',
        'team_id',
        'name',
        'description',
        'deleted',
        'priority',
        'status',
        'estimated_start_date',
        'estimated_end_date',
        
    ),
        'list_fields' =>  array(
        'id',
        'assigned_user_id',
        'assigned_user_name',
        'team_id',
        'team_name',
        'name',
        'relation_id',
        'relation_name',
        'relation_type',
        'total_estimated_effort',
        'total_actual_effort',
        'status',
        'priority',     
        
    ),
    'required_fields' =>  array('name'=>1, 'estimated_start_date'=>2, 'estimated_end_date'=>3),
);
?>