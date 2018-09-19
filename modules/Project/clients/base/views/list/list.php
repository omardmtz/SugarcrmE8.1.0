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
$viewdefs['Project']['base']['view']['list'] = array(
    'panels' =>
    array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(
                array(
                    'label' => 'LBL_LIST_NAME',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'name',
                    'link' => true,
                ),
                array(
                    'label' => 'LBL_STATUS',
                    'name' => 'status',
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'target_record_key' => 'assigned_user_id',
                    'target_module' => 'Users',
                    'label' => 'LBL_LIST_ASSIGNED_USER_ID',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'assigned_user_name',
                    'sortable' => false,
                ),
                array(
                    'label' => 'LBL_DATE_START',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'estimated_start_date',
                ),
                array(
                    'label' => 'LBL_DATE_END',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'estimated_end_date',
                ),
            ),
        ),
    ),
);
