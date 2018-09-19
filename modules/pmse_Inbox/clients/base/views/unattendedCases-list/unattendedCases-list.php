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

$module_name = 'pmse_Inbox';
$viewdefs[$module_name]['base']['view']['unattendedCases-list'] = array(
    'template' => 'flex-list',
    'favorite' => false,
    'following' => false,
    'selection' => array(
    ),
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'rowaction',
                'css_class' => 'btn',
                'tooltip' => 'LBL_PREVIEW',
                'event' => 'list:preview:fire',
                'icon' => 'fa-eye',
                'acl_action' => 'view',
            ),
            array(
                'type' => 'rowaction',
                'name' => 'edit_button',
                'label' => 'LBL_PMSE_LABEL_REASSIGN',
                'event' => 'list:reassign:fire',
                'acl_action' => 'view',
            ),
        ),
    ),
    'last_state' => array(
        'id' => 'record-list',
    ),
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'cas_id',
                    'label' => 'LBL_CAS_ID',
                    'default' => true,
                    'enabled' => true,
                    'link' => false,
                ),
                array(
                    'name' => 'pro_title',
                    'label' => 'LBL_PROCESS_DEFINITION_NAME',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                    'type' => 'pmse-link',
                ),
                array(
                    'name' => 'cas_title',
                    'label' => 'LBL_RECORD_NAME',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                    'type' => 'pmse-link',
                    'sortable' => false,
                ),
                array(
                    'name' => 'cas_status',
                    'label' => 'LBL_STATUS',
                    'default' => false,
                    'enabled' => false,
                    'link' => false,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_OWNER',
                    'default' => true,
                    'enabled' => true,
                    'link' => false,
                ),
                array(
                    'name' => 'cas_user_full_name',
                    'label' => 'LBL_ACTIVITY_OWNER',
                    'default' => true,
                    'enabled' => true,
                    'link' => false,
                ),
                array(
                    'name' => 'prj_user_id_full_name',
                    'label' => 'LBL_PROCESS_OWNER',
                    'default' => true,
                    'enabled' => true,
                    'link' => false,
                ),
                array(
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'date_entered',
                    'readonly' => true,
                ),
            ),
        ),
    ),
    'orderBy' => array(
        'field' => 'cas_id',
        'direction' => 'desc',
    ),
);
