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
$viewdefs['Opportunities']['mobile']['view']['list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_LIST_OPPORTUNITY_NAME',
                    'link' => true,
                    'default' => true,
                    'enabled' => true
                ),
                array(
                    'name' => 'account_name',
                    'label' => 'LBL_LIST_ACCOUNT_NAME',
                    'default' => true,
                    'enabled' => true
                ),
                array(
                    'name' => 'opportunity_type',
                    'label' => 'LBL_TYPE',
                    'default' => false,
                ),
                array(
                    'name' => 'lead_source',
                    'label' => 'LBL_LEAD_SOURCE',
                    'default' => false,
                ),
                array(
                    'name' => 'next_step',
                    'label' => 'LBL_NEXT_STEP',
                    'default' => false,
                ),
                array(
                    'name' => 'sales_stage',
                    'width' => '10',
                    'label' => 'LBL_LIST_SALES_STAGE',
                    'default' => false,
                ),
                array(
                    'name' => 'probability',
                    'label' => 'LBL_PROBABILITY',
                    'default' => false,
                ),
                array(
                    'name' => 'date_closed',
                    'label' => 'LBL_DATE_CLOSED',
                    'default' => false,
                    'enabled' => true
                ),
                array(
                    'name' => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'default' => false,
                    'readonly' => true,
                ),
                array(
                    'name' => 'created_by_name',
                    'label' => 'LBL_CREATED',
                    'default' => false,
                    'readonly' => true,
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_LIST_TEAM',
                    'default' => false,
                    'enabled' => true
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_USER',
                    'default' => false,
                    'enabled' => true
                ),
                array(
                    'name' => 'modified_by_name',
                    'label' => 'LBL_MODIFIED',
                    'default' => false,
                    'readonly' => true,
                )
            )
        )
    )
);

