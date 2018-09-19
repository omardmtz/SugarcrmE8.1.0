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
$viewdefs['Campaigns']['base']['view']['dupecheck-list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'link' => true,
                    'label' => 'LBL_LIST_NAME',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'status',
                    'label' => 'LBL_LIST_STATUS',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'campaign_type',
                    'label' => 'LBL_LIST_TYPE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'end_date',
                    'label' => 'LBL_LIST_END_DATE',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_TEAM',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'module' => 'Users',
                    'label' => 'LBL_LIST_ASSIGNED_USER',
                    'id' => 'ASSIGNED_USER_ID',
                    'sortable' => false,
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'date_entered',
                    'type' => 'datetime',
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                ),
            ),
        ),
    ),
);
