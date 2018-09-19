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

$viewdefs['Calls']['base']['view']['record'] = array(
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
            'type' => 'actiondropdown',
            'name' => 'save_dropdown',
            'primary' => true,
            'switch_on_click' => true,
            'showOn' => 'edit',
            'buttons' => array(
                array(
                    'type' => 'rowaction',
                    'event' => 'button:save_button:click',
                    'name' => 'save_button',
                    'label' => 'LBL_SAVE_BUTTON_LABEL',
                    'css_class' => 'btn btn-primary',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'save-and-send-invites-button',
                    'event' => 'button:save_button:click',
                    'name' => 'save_invite_button',
                    'label' => 'LBL_SAVE_AND_SEND_INVITES_BUTTON',
                    'acl_action' => 'edit',
                ),
            ),
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
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'editrecurrencesbutton',
                    'event' => 'button:edit_recurrence_button:click',
                    'name' => 'edit_recurrence_button',
                    'label' => 'LBL_EDIT_ALL_RECURRENCES',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'shareaction',
                    'name' => 'share',
                    'label' => 'LBL_RECORD_SHARE_BUTTON',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'pdfaction',
                    'name' => 'download-pdf',
                    'label' => 'LBL_PDF_VIEW',
                    'action' => 'download',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'pdfaction',
                    'name' => 'email-pdf',
                    'label' => 'LBL_PDF_EMAIL',
                    'action' => 'email',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'divider',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:duplicate_button:click',
                    'name' => 'duplicate_button',
                    'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                    'acl_module' => 'Calls',
                    'acl_action' => 'create',
                ),
                array(
                    'type' => 'divider',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:delete_button:click',
                    'name' => 'delete_button',
                    'label' => 'LBL_DELETE_BUTTON_LABEL',
                    'acl_action' => 'delete',
                ),
                array(
                    'type' => 'deleterecurrencesbutton',
                    'name' => 'delete_recurrence_button',
                    'label' => 'LBL_REMOVE_ALL_RECURRENCES',
                    'acl_action' => 'delete',
                ),
                array(
                    'type' => 'closebutton',
                    'name' => 'record-close-new',
                    'label' => 'LBL_CLOSE_AND_CREATE_BUTTON_LABEL',
                    'closed_status' => 'Held',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'closebutton',
                    'name' => 'record-close',
                    'label' => 'LBL_CLOSE_BUTTON_LABEL',
                    'closed_status' => 'Held',
                    'acl_action' => 'edit',
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
                    'label' => 'LBL_FOLLOW',
                    'type' => 'follow',
                    'readonly' => true,
                    'dismiss_label' => true,
                ),
                array(
                    'name' => 'status',
                    'type' => 'event-status',
                    'enum_width' => 'auto',
                    'dropdown_width' => 'auto',
                    'dropdown_class' => 'select2-menu-only',
                    'container_class' => 'select2-menu-only',
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
                    'name' => 'duration',
                    'type' => 'duration',
                    'label' => 'LBL_START_AND_END_DATE_DETAIL_VIEW',
                    'dismiss_label' => true,
                    'inline' => true,
                    'show_child_labels' => true,
                    'fields' => array(
                        array(
                            'name' => 'date_start',
                            'time' => array(
                                'step' => 15,
                            ),
                            'readonly' => false,
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_START_AND_END_DATE_TO',
                        ),
                        array(
                            'name' => 'date_end',
                            'time' => array(
                                'step' => 15,
                                'duration' => array(
                                    'relative_to' => 'date_start'
                                )
                            ),
                            'readonly' => false,
                        ),
                    ),
                    'span' => 9,
                    'related_fields' => array(
                        'duration_hours',
                        'duration_minutes',
                    ),
                ),
                array(
                    'name' => 'repeat_type',
                    'span' => 3,
                    'related_fields' => array(
                        'repeat_parent_id',
                    ),
                ),
                array(
                    'name' => 'recurrence',
                    'type' => 'recurrence',
                    'span' => 12,
                    'inline' => true,
                    'show_child_labels' => true,
                    'fields' => array(
                        array(
                            'label' => 'LBL_CALENDAR_REPEAT_INTERVAL',
                            'name' => 'repeat_interval',
                            'type' => 'enum',
                            'options' => 'repeat_interval_number',
                            'required' => true,
                            'default' => 1,
                        ),
                        array(
                            'label' => 'LBL_CALENDAR_REPEAT_DOW',
                            'name' => 'repeat_dow',
                            'type' => 'repeat-dow',
                            'options' => 'dom_cal_day_of_week',
                            'isMultiSelect' => true,
                        ),
                        array(
                            'label' => 'LBL_CALENDAR_CUSTOM_DATE',
                            'name' => 'repeat_selector',
                            'type' => 'enum',
                            'options' => 'repeat_selector_dom',
                            'default' => 'None',
                        ),
                        array(
                            'name' => 'repeat_days',
                            'type' => 'repeat-days',
                            'options' => array('' => ''),
                            'isMultiSelect' => true,
                            'dropdown_class' => 'recurring-date-dropdown',
                            'container_class' => 'recurring-date-container select2-choices-pills-close',
                        ),
                        array(
                            'label' => ' ',
                            'name' => 'repeat_ordinal',
                            'type' => 'enum',
                            'options' => 'repeat_ordinal_dom',
                        ),
                        array(
                            'label' => ' ',
                            'name' => 'repeat_unit',
                            'type' => 'enum',
                            'options' => 'repeat_unit_dom',
                        ),
                        array(
                            'label' => 'LBL_CALENDAR_REPEAT',
                            'name' => 'repeat_end_type',
                            'type' => 'enum',
                            'options' => 'repeat_end_types',
                            'default' => 'Until',
                        ),
                        array(
                            'label' => 'LBL_CALENDAR_REPEAT_UNTIL_DATE',
                            'name' => 'repeat_until',
                            'type' => 'repeat-until',
                        ),
                        array(
                            'label' => 'LBL_CALENDAR_REPEAT_COUNT',
                            'name' => 'repeat_count',
                            'type' => 'repeat-count',
                        ),
                    ),
                ),
                'direction',
                array(
                    'name' => 'reminders',
                    'type' => 'fieldset',
                    'inline' => true,
                    'equal_spacing' => true,
                    'show_child_labels' => true,
                    'fields' => array(
                        'reminder_time',
                        'email_reminder_time',
                    ),
                ),
                array(
                    'name' => 'description',
                    'span' => 12,
                    'rows' => 3,
                ),
                'parent_name',
                array(
                    'name' => 'invitees',
                    'type' => 'participants',
                    'label' => 'LBL_INVITEES',
                    'span' => 12,
                    'fields' => array(
                        'name',
                        'accept_status_calls',
                        'picture',
                        'email',
                    ),
                ),
                'assigned_user_name',
                'team_name',
                array(
                    'name' => 'tag',
                    'span' => 12,
                ),
            ),
        ),
        array(
            'name' => 'panel_hidden',
            'label' => 'LBL_RECORD_SHOWMORE',
            'columns' => 2,
            'hide' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
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
                            'default_value' => 'LBL_BY'
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
                            'default_value' => 'LBL_BY'
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
