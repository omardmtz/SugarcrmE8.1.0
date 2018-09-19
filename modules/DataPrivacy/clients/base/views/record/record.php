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
$viewdefs['DataPrivacy']['base']['view']['record'] = array(
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
            'type' => 'rowaction',
            'event' => 'button:complete_button:click',
            'name' => 'complete_button',
            'label' => 'LBL_COMPLETE_BUTTON_LABEL',
            'css_class' => 'btn btn-success',
            'showOn' => 'view',
            'acl_action' => 'admin',
        ),
        array(
            'type' => 'rowaction',
            'event' => 'button:erase_complete_button:click',
            'name' => 'erase_complete_button',
            'label' => 'LBL_ERASE_COMPLETE_BUTTON_LABEL',
            'css_class' => 'btn btn-success',
            'showOn' => 'view',
            'acl_action' => 'admin',
            'tooltip' => 'LBL_ERASE_SUBPANEL_FIELDS_LABEL',
        ),
        array(
            'type' => 'rowaction',
            'event' => 'button:reject_button:click',
            'name' => 'reject_button',
            'label' => 'LBL_REJECT_BUTTON_LABEL',
            'css_class' => 'btn btn-danger',
            'showOn' => 'view',
            'acl_action' => 'admin',
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'showOn' => 'view',
            'buttons' => array(
                array(
                    'type' => 'rowaction',
                    'event' => 'button:edit_button:click',
                    'name' => 'edit_button',
                    'label' => 'LBL_EDIT_BUTTON_LABEL',
                    'primary' => true,
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'shareaction',
                    'name' => 'share',
                    'label' => 'LBL_RECORD_SHARE_BUTTON',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'divider',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:audit_button:click',
                    'name' => 'audit_button',
                    'label' => 'LNK_VIEW_CHANGE_LOG',
                    'acl_action' => 'view',
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
                    'related_fields' => array(
                        // Need fields_to_erase for Mark for Erasure view
                        // put in header because this is not customizable in Studio
                        'fields_to_erase',
                    ),
                ),
                'name',
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
                    'name' => 'dataprivacy_number',
                    'readonly' => true,
                ),
                array(
                    'name' => 'status',
                    'readonly' => true,
                ),
                'type',
                'priority',
                array(
                    'name' => 'source',
                    'nl2br' => true,
                ),
                array(
                    'name' => 'requested_by',
                    'nl2br' => true,
                ),
                'assigned_user_name',
                array(
                    'name' => 'date_due',
                    'readonly' => false,
                ),
                array(
                    'name' => 'date_opened',
                    'readonly' => false,
                ),
                array(
                    'name' => 'date_closed',
                    'readonly' => false,
                ),
                array(
                    'name' => 'business_purpose',
                    'isMultiSelect' => true,
                ),
                array(
                    'name' => 'description',
                    'nl2br' => true,
                    'span' => 12,
                ),
                array(
                    'name' => 'resolution',
                    'nl2br' => true,
                    'span' => 12,
                ),
                array(
                    'name' => 'work_log',
                    'nl2br' => true,
                    'span' => 12,
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
                'team_name',
                'tag',
                array(
                    'name' => 'date_entered_by',
                    'readonly' => true,
                    'inline' => true,
                    'type' => 'fieldset',
                    'label' => 'LBL_DATE_ENTERED',
                    'fields' => array(
                        array(
                            'name' => 'date_entered',
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_BY',
                        ),
                        array(
                            'name' => 'created_by_name',
                        ),
                    ),
                ),
                array(
                    'name' => 'date_modified_by',
                    'readonly' => true,
                    'inline' => true,
                    'type' => 'fieldset',
                    'label' => 'LBL_DATE_MODIFIED',
                    'fields' => array(
                        array(
                            'name' => 'date_modified',
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_BY',
                        ),
                        array(
                            'name' => 'modified_by_name',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
