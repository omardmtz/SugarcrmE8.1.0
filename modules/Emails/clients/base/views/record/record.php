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
$viewdefs['Emails']['base']['view']['record'] = array(
    'buttons' => array(
        array(
            'type' => 'button',
            'name' => 'cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'edit',
            'events' => array(
                'click' => 'button:cancel_button:click',
            ),
        ),
        array(
            'type' => 'rowaction',
            'event' => 'button:save_button:click',
            'name' => 'save_button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'css_class' => 'btn btn-primary',
            'showOn' => 'edit',
            'acl_action' => 'edit',
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'showOn' => 'view',
            'buttons' => array(
                array(
                    'name' => 'reply_button',
                    'type' => 'reply-action',
                    'event' => 'button:reply_button:click',
                    'label' => 'LBL_BUTTON_REPLY',
                    'acl_module' => 'Emails',
                    'acl_action' => 'create',
                ),
                array(
                    'name' => 'reply_all_button',
                    'type' => 'reply-all-action',
                    'event' => 'button:reply_all_button:click',
                    'label' => 'LBL_BUTTON_REPLY_ALL',
                    'acl_module' => 'Emails',
                    'acl_action' => 'create',
                ),
                array(
                    'name' => 'forward_button',
                    'type' => 'forward-action',
                    'event' => 'button:forward_button:click',
                    'label' => 'LBL_BUTTON_FORWARD',
                    'acl_module' => 'Emails',
                    'acl_action' => 'create',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:edit_button:click',
                    'name' => 'edit_button',
                    'label' => 'LBL_EDIT_BUTTON_LABEL',
                    'acl_action' => 'edit',
                ),
                array(
                    'name' => 'delete_button',
                    'type' => 'rowaction',
                    'event' => 'button:delete_button:click',
                    'label' => 'LBL_DELETE_BUTTON',
                    'acl_action' => 'delete',
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
            'header' => true,
            'fields' => array(
                array(
                    'name' => 'picture',
                    'type' => 'avatar',
                    'size' => 'large',
                    'dismiss_label' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'name',
                    'readonly' => true,
                    'related_fields' => array(
                        'state',
                    ),
                ),
                array(
                    'name' => 'favorite',
                    'label' => 'LBL_FAVORITE',
                    'type' => 'favorite',
                    'dismiss_label' => true,
                ),
            ),
        ),
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'from_collection',
                    'type' => 'from',
                    'label' => 'LBL_FROM',
                    'readonly' => true,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                ),
                array(
                    'name' => 'date_sent',
                    'label' => 'LBL_DATE',
                    'readonly' => true,
                ),
                array(
                    'name' => 'to_collection',
                    'type' => 'email-recipients',
                    'label' => 'LBL_TO_ADDRS',
                    'readonly' => true,
                    'max_num' => -1,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                    'span' => 12,
                ),
                array(
                    'name' => 'description_html',
                    'dismiss_label' => true,
                    'readonly' => true,
                    'span' => 12,
                    'related_fields' => array(
                        'description',
                    ),
                ),
                array(
                    'name' => 'attachments_collection',
                    'type' => 'email-attachments',
                    'label' => 'LBL_ATTACHMENTS',
                    'readonly' => true,
                    'span' => 12,
                    'max_num' => -1,
                    'fields' => array(
                        'name',
                        'filename',
                        'file_size',
                        'file_source',
                        'file_mime_type',
                        'file_ext',
                        'upload_id',
                    ),
                ),
            ),
        ),
        array(
            'name' => 'panel_hidden',
            'label' => 'LBL_RECORD_SHOWMORE',
            'hide' => true,
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'cc_collection',
                    'type' => 'email-recipients',
                    'label' => 'LBL_CC',
                    'readonly' => true,
                    'max_num' => -1,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                ),
                array(
                    'name' => 'bcc_collection',
                    'type' => 'email-recipients',
                    'label' => 'LBL_BCC',
                    'readonly' => true,
                    'max_num' => -1,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                ),
                'assigned_user_name',
                'parent_name',
                'team_name',
                array(
                    'name' => 'tag',
                    'span' => 12,
                ),
            ),
        ),
    ),
);
