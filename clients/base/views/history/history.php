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

$viewdefs['base']['view']['history'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_HISTORY_DASHLET',
            'description' => 'LBL_HISTORY_DASHLET_DESCRIPTION',
            'config' => array(
                'limit' => '10',
                'filter' => '7',
                'visibility' => 'user',
            ),
            'preview' => array(
                'limit' => '10',
                'filter' => '7',
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
                        'action' => 'archiveEmail',
                        'params' => array(
                            'link' => 'emails',
                            'module' => 'Emails',
                        ),
                        'label' => 'LBL_CREATE_ARCHIVED_EMAIL',
                        'acl_action' => 'create',
                        'acl_module' => 'Emails',
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
                    'name' => 'filter',
                    'label' => 'LBL_DASHLET_CONFIGURE_FILTERS',
                    'type' => 'enum',
                    'options' => 'history_filter_options',
                ),
                array(
                    'name' => 'visibility',
                    'label' => 'LBL_DASHLET_CONFIGURE_MY_ITEMS_ONLY',
                    'type' => 'enum',
                    'options' => 'history_visibility_options',
                ),
                array(
                    'name' => 'limit',
                    'label' => 'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS',
                    'type' => 'enum',
                    'options' => 'history_limit_options',
                )
            ),
        ),
    ),
    'filter' => array(
        array(
            'name' => 'filter',
            'label' => 'LBL_FILTER',
            'type' => 'enum',
            'options' => 'history_filter_options'
        ),
    ),
    'tabs' => array(
        array(
            'active' => true,
            'filter_applied_to' => 'date_start',
            'filters' => array(
                'status' => array('$in' => array('Held', 'Not Held')),
            ),
            'link' => 'meetings',
            'module' => 'Meetings',
            'order_by' => 'date_start:desc',
            'record_date' => 'date_start',
            'row_actions' => array(
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
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_start',
            ),
        ),
        array(
            'filter_applied_to' => 'date_entered',
            'filters' => array(
                'state' => array(
                    '$in' => array('Archived'),
                ),
            ),
            'labels' => array(
                'singular' => 'LBL_HISTORY_DASHLET_EMAIL_SINGULAR',
                'plural' => 'LBL_HISTORY_DASHLET_EMAIL_PLURAL',
            ),
            'link' => 'archived_emails',
            'module' => 'Emails',
            'order_by' => 'date_entered:desc',
            'row_actions' => array(
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
            'fields' => array(
                'name',
                'assigned_user_name',
                'assigned_user_id',
                'date_entered',
            ),
        ),
        array(
            'filter_applied_to' => 'date_start',
            'filters' => array(
                'status' => array('$in' => array('Held', 'Not Held')),
            ),
            'link' => 'calls',
            'module' => 'Calls',
            'order_by' => 'date_start:desc',
            'record_date' => 'date_start',
            'row_actions' => array(
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
            'fields' => array(
                'name',
                'assigned_user_id',
                'assigned_user_name',
                'date_start',
            ),
        ),
    ),
    'visibility_labels' => array(
        'user' => 'LBL_HISTORY_DASHLET_USER_BUTTON_LABEL',
        'group' => 'LBL_HISTORY_DASHLET_GROUP_BUTTON_LABEL',
    ),
);
