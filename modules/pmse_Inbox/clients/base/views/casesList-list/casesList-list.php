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
$viewdefs[$module_name]['base']['view']['casesList-list'] = array(
    'template' => 'flex-list',
    'favorite' => false,
    'following' => false,
    'selection' => array(
    ),
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'rowaction',
                'icon' => 'fa-eye',
                'event' => 'list:preview:fire',
                'css_class'=>'overflow-visible',
                'tooltip'=> 'Status',
            ),
            array(
                'type' => 'rowaction',
                'name' => 'History',
                'label' => 'LBL_PMSE_LABEL_HISTORY',
                'event' => 'case:history',
                'css_class'=>'overflow-visible',
            ),
            array(
                'type' => 'rowaction',
                'name' => 'viewNotes',
                'label' => 'LBL_PMSE_LABEL_NOTES',
                'event' => 'case:notes',
                'css_class'=>'overflow-visible',
            ),
            array(
                'type' => 'reassignbutton',
                'name' => 'reassignButton',
                'label' => 'LBL_PMSE_LABEL_REASSIGN',
                'event' => 'case:reassign',
                'css_class'=>'overflow-visible',
            ),
            array(
                'type' => 'executebutton',
                'name' => 'executeButton',
                'label' => 'LBL_PMSE_LABEL_EXECUTE',
                'event' => 'case:execute',
            ),
            array(
                'type' => 'cancelcasebutton',
                'name' => 'cancelButton',
                'label' => 'LBL_PMSE_LABEL_CANCEL',
                'event' => 'list:cancelCase:fire',
            ),
        ),
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
                    'type' => 'pmse-link',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                ),
                array(
                    'name' => 'cas_title',
                    'label' => 'LBL_RECORD_NAME',
                    'default' => true,
                    'enabled' => true,
                    'type' => 'pmse-link',
                    'link' => true,
                    'sortable' => false,
                ),
                array(
                    'name' => 'cas_status',
                    'label' => 'LBL_STATUS',
                    'type' => 'event-status-pmse',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'cas_create_date',
                    'readonly' => true,
                ),
                array(
                    'label' => 'LBL_OWNER',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'assigned_user_full_name',
                    'readonly' => true,
                    'link' => false,
                ),
                array(
                    'name' => 'cas_user_id_full_name',
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
            ),
        ),
    ),
    'orderBy' => array(
        // Default sort for cases list view
        'field' => 'cas_id',
        'direction' => 'desc',
    ),
);
