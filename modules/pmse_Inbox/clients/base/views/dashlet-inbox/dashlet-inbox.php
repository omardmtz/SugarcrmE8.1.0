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
$viewdefs[$module_name]['base']['view']['dashlet-inbox'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_PMSE_PROCESSES_DASHLET',
            'description' => 'LBL_PMSE_PROCESSES_DASHLET_DESCRIPTION',
            'config' => array(
                'limit' => 10,
                'date' => 'true',
                'visibility' => 'user',
            ),
            'preview' => array(
                'limit' => 10,
                'date' => 'true',
                'visibility' => 'user',
            ),
            'filter' => array(
                'module' => array(
                    'Home',
                ),
                'view' => 'record',
            ),
        ),
    ),
    'custom_toolbar' => array(
        'buttons' => array(
            array(
                'dropdown_buttons' => array(
                    array(
                        'type' => 'dashletaction',
                        'action' => 'editClicked',
                        'label' => 'LBL_DASHLET_CONFIG_EDIT_LABEL',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'refreshClicked',
                        'label' => 'LBL_DASHLET_REFRESH_LABEL',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'removeClicked',
                        'label' => 'LBL_DASHLET_REMOVE_LABEL',
                    ),
                ),
            ),
        ),
    ),

    'panels' => array(
        array(
            'name' => 'panel_body',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'visibility',
                    'label' => 'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY',
                    'type' => 'enum',
                    'options' => 'tasks_visibility_options',
                ),
                array(
                    'name' => 'limit',
                    'label' => 'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS',
                    'type' => 'enum',
                    'options' => 'tasks_limit_options',
                ),
            ),
        ),
    ),
    'tabs' => array(
        array(
            'active' => true,
            'filter_applied_to' => 'in_time',
            'filters' => array(
                'assignment_method' => 'static',
                'visibility' => 'regular_user',
            ),
            'label' => 'LBL_PMSE_MY_PROCESSES',
            'link' => 'pmse_Inbox',
            'module' => 'pmse_Inbox',
            'order_by' => 'date_entered:asc',
            'record_date' => 'cas_due_date',
            'include_child_items' => true,
            'overdue_badge' => array(
                'name' => 'cas_due_date',
                'type' => 'overdue-badge',
                'css_class' => 'pull-right',
            ),
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_entered',
                'cas_due_date',
            ),
        ),
        array(
            'filter_applied_to' => 'in_time',
            'filters' => array(
                'assignment_method' => 'selfservice',
                'visibility' => 'selfservice',
            ),
            'label' => 'LBL_PMSE_SELF_SERVICE_PROCESSES',
            'link' => 'pmse_Inbox',
            'module' => 'pmse_Inbox',
            'order_by' => 'date_entered:asc',
            'record_date' => 'date_entered',
            'include_child_items' => true,
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_entered',
                'cas_due_date',
                'cas_assignment_method',
            ),
        ),
    ),
);
