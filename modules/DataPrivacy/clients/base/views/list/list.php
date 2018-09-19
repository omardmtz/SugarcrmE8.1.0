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
$viewdefs['DataPrivacy']['base']['view']['list'] = array(
    'panels' =>
    array(
        array(
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(
                array(
                    'name' => 'dataprivacy_number',
                    'label' => 'LBL_LIST_NUMBER',
                    'default' => true,
                    'enabled' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'name',
                    'label' => 'LBL_LIST_SUBJECT',
                    'link' => true,
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'type',
                    'label' => 'LBL_LIST_TYPE',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'status',
                    'label' => 'LBL_LIST_STATUS',
                    'default' => true,
                    'enabled' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'priority',
                    'label' => 'LBL_LIST_PRIORITY',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'source',
                    'label' => 'LBL_LIST_SOURCE',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'requested_by',
                    'label' => 'LBL_LIST_REQUESTED_BY',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'date_due',
                    'label' => 'LBL_LIST_DATE_DUE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'date_closed',
                    'label' => 'LBL_LIST_DATE_CLOSED',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'date_modified',
                    'label' => 'LBL_LIST_DATE_MODIFIED',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'modified_by_name',
                    'label' => 'LBL_LIST_MODIFIED_BY_NAME',
                    'id' => 'MODIFIED_USER_ID',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
                    'id' => 'ASSIGNED_USER_ID',
                    'default' => true,
                    'enabled' => true,
                ),
            ),
        ),
    ),
);
