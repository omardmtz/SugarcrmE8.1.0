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
$subpanel_layout = array(
    'top_buttons' => array(
       array('widget_class' => 'SubPanelTopCreateButton'),
       array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Emails'),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_LIST_SUBJECT',
            'width' => '20%',
        ),
        'status'=> array(
            'name' => 'status',
            'vname' => 'LBL_LIST_STATUS',
            'width' => '20%',
        ),
        'date_entered'=>array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_CREATED',
            'width' => '20%',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'width' => '20%',
        ),
        'assigned_user_name' => array (
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_USER',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'assigned_user_id',
            'target_module' => 'Employees',
        ),
    ),
);
