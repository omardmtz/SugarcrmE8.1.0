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
$viewdefs[$module_name]['base']['view']['record'] = array(
    'buttons' => array(
        array(
            'type' => 'button',
            'name' => 'cancel_button',
            'label' => 'Cancel Edit',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'edit',
            'events' => array(
                'click' => 'button:cancel_button:click',
            ),
        ),
        array(
            'type' => 'rowaction',
            'event' => 'approve:case',
            'name' => 'approve_button',
            'label' => 'LBL_PMSE_LABEL_APPROVE',
            'css_class' => 'btn btn-primary',
        ),
        array(
            'type' => 'rowaction',
            'event' => 'reject:case',
            'name' => 'reject_button',
            'label' => 'LBL_PMSE_LABEL_REJECT',
            'css_class' => 'btn btn-primary',
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
//            'showOn' => 'edit',
            'buttons' => array(
                array(
                    'type' => 'rowaction',
                    'event' => 'cancel:case',
                    'name' => 'Cancel',
                    'label' => 'LBL_PMSE_LABEL_CANCEL',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'name' => 'history',
                    'label' => 'LBL_PMSE_LABEL_HISTORY',
                    'acl_action' => 'create',
                    'route' => array(
                        'action'=>'create'
                    )
                ),
                array(
                    'type' => 'pdfaction',
                    'name' => 'download-pdf',
                    'label' => 'LBL_PMSE_LABEL_STATUS',
                    'acl_action' => 'view',
                ),
//                array(
//                    'type' => 'pdfaction',
//                    'name' => 'email-pdf',
//                    'label' => 'Add notes',
//                    'acl_action' => 'view',
//                ),
                array(
                    'type' => 'divider',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => '',
                    'name' => 'find_duplicates_button',
                    'label' => 'LBL_PMSE_LABEL_CHANGE_OWNER',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => '',
                    'name' => 'duplicate_button',
                    'label' => 'LBL_PMSE_LABEL_REASSIGN',
                    'acl_action' => 'create',
                ),
            ),
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_RECORD_HEADER',
            'header' => true,
            'fields' => array(
                array(
                    'name'          => 'picture',
                    'type'          => 'avatar',
                    'width'         => 42,
                    'height'        => 42,
                    'dismiss_label' => true,
                    'readonly'      => true,
                ),
                'name',
                array(
                    'name' => 'favorite',
                    'label' => 'LBL_FAVORITE',
                    'type' => 'favorite',
                    'readonly' => true,
                    'dismiss_label' => true,
                ),
                array(
                    'name' => 'follow',
                    'label'=> 'LBL_FOLLOW',
                    'type' => 'follow',
                    'readonly' => true,
                    'dismiss_label' => true,
                ),
            )
        ),
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                'assigned_user_name',
                'team_name',
            ),
        ),
        array(
            'name' => 'panel_hidden',
            'label' => 'LBL_SHOW_MORE',
            'hide' => true,
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'description',
                    'span' => 12,
                ),
                'date_modified',
                'date_entered',
            ),
        ),
    ),
);
