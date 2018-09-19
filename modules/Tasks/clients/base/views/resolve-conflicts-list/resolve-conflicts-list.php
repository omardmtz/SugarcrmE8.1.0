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
$viewdefs['Tasks']['base']['view']['resolve-conflicts-list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'link' => true,
                    'label' => 'LBL_LIST_SUBJECT',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'contact_name',
                    'label' => 'LBL_LIST_CONTACT',
                    'link' => true,
                    'id' => 'CONTACT_ID',
                    'module' => 'Contacts',
                    'enabled' => true,
                    'default' => true,
                    'ACLTag' => 'CONTACT',
                    'related_fields' => array('contact_id'),
                ),
                array(
                    'name' => 'parent_name',
                    'label' => 'LBL_LIST_RELATED_TO',
                    'dynamic_module' => 'PARENT_TYPE',
                    'id' => 'PARENT_ID',
                    'link' => true,
                    'enabled' => true,
                    'default' => true,
                    'sortable' => false,
                    'ACLTag' => 'PARENT',
                    'related_fields' => array('parent_id', 'parent_type'),
                ),
                array(
                    'name' => 'date_due',
                    'label' => 'LBL_LIST_DUE_DATE',
                    'link' => false,
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_LIST_TEAM',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
                    'id' => 'ASSIGNED_USER_ID',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'date_start',
                    'label' => 'LBL_LIST_START_DATE',
                    'link' => false,
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'status',
                    'label' => 'LBL_LIST_STATUS',
                    'link' => false,
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                ),
            ),
        ),
    ),
);
