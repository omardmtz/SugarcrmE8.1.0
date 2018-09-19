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

$viewdefs['base']['view']['planned-activities'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_PLANNED_ACTIVITIES_DASHLET',
            'description' => 'LBL_PLANNED_ACTIVITIES_DASHLET_DESCRIPTION',
            'config' => array(
                'limit' => '10',
                'date' => 'today',
                'visibility' => 'user',
            ),
            'preview' => array(
                'limit' => '10',
                'date' => 'today',
                'visibility' => 'user',
            ),
            'filter' => array(
                'module' => array(
                    'Accounts',
                    'Bugs',
                    'Cases',
                    'Contacts',
                    'Home',
                    'Leads',
                    'Opportunities',
                    'Prospects',
                    'RevenueLineItems',
                ),
                'view' => 'record',
            ),
        ),
    ),
    'custom_toolbar' => array(
        'buttons' => array(
            array(
                'type' => 'actiondropdown',
                'no_default_action' => true,
                'icon' => 'fa-plus',
                'buttons' => array(
                    array(
                        'type' => 'dashletaction',
                        'action' => 'createRecord',
                        'params' => array(
                            'link' => 'meetings',
                            'module' => 'Meetings',
                        ),
                        'label' => 'LBL_SCHEDULE_MEETING',
                        'acl_action' => 'create',
                        'acl_module' => 'Meetings',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'createRecord',
                        'params' => array(
                            'link' => 'calls',
                            'module' => 'Calls',
                        ),
                        'label' => 'LBL_SCHEDULE_CALL',
                        'acl_action' => 'create',
                        'acl_module' => 'Calls',
                    ),
                ),
            ),
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
                        'action' => 'toggleClicked',
                        'label' => 'LBL_DASHLET_MINIMIZE',
                        'event' => 'minimize',
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
                    'name' => 'date',
                    'label' => 'LBL_DASHLET_CONFIGURE_FILTERS',
                    'type' => 'enum',
                    'options' => 'planned_activities_filter_options',
                ),
                array(
                    'name' => 'visibility',
                    'label' => 'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY',
                    'type' => 'enum',
                    'options' => 'planned_activities_visibility_options',
                ),
                array(
                    'name' => 'limit',
                    'label' => 'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS',
                    'type' => 'enum',
                    'options' => 'planned_activities_limit_options',
                )
            ),
        ),
    ),
    'tabs' => array(
        array(
            'active' => true,
            'filter_applied_to' => 'date_start',
            'filters' => array(
                'status' => array('$not_in' => array('Held', 'Not Held')),
            ),
            'link' => 'meetings',
            'module' => 'Meetings',
            'order_by' => 'date_start:asc',
            'record_date' => 'date_start',
            'row_actions' => array(
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-times-circle',
                    'css_class' => 'btn btn-mini',
                    'event' => 'planned-activities:close-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_PLANNED_ACTIVITIES_DASHLET_HELD_ACTIVITY',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'unlink-action',
                    'icon' => 'fa-chain-broken',
                    'css_class' => 'btn btn-mini',
                    'event' => 'tabbed-dashlet:unlink-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_UNLINK_BUTTON',
                    'acl_action' => 'edit',
                ),
            ),
            'include_child_items' => true,
            'invitation_actions' => array(
                'name' => 'accept_status_users',
                'type' => 'invitation-actions',
            ),
            'overdue_badge' => array(
                'name' => 'date_start',
                'type' => 'overdue-badge',
                'css_class' => 'pull-right',
            ),
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_start',
            ),
        ),
        array(
            'filter_applied_to' => 'date_start',
            'filters' => array(
                'status' => array('$not_in' => array('Held', 'Not Held')),
            ),
            'link' => 'calls',
            'module' => 'Calls',
            'order_by' => 'date_start:asc',
            'record_date' => 'date_start',
            'row_actions' => array(
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-times-circle',
                    'css_class' => 'btn btn-mini',
                    'event' => 'planned-activities:close-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_PLANNED_ACTIVITIES_DASHLET_HELD_ACTIVITY',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'unlink-action',
                    'icon' => 'fa-chain-broken',
                    'css_class' => 'btn btn-mini',
                    'event' => 'tabbed-dashlet:unlink-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_UNLINK_BUTTON',
                    'acl_action' => 'edit',
                ),
            ),
            'include_child_items' => true,
            'invitation_actions' => array(
                'name' => 'accept_status_users',
                'type' => 'invitation-actions',
            ),
            'overdue_badge' => array(
                'name' => 'date_start',
                'type' => 'overdue-badge',
            ),
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_start',
            ),
        ),
    ),
);
