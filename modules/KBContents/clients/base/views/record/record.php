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
$viewdefs['KBContents']['base']['view']['record'] = array(
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
                    'type' => 'rowaction',
                    'event' => 'button:edit_button:click',
                    'name' => 'edit_button',
                    'label' => 'LBL_EDIT_BUTTON_LABEL',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:create_localization_button:click',
                    'name' => 'create_localization_button',
                    'label' => 'LBL_CREATE_LOCALIZATION_BUTTON_LABEL',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:create_revision_button:click',
                    'name' => 'create_revision_button',
                    'label' => 'LBL_CREATE_REVISION_BUTTON_LABEL',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'divider',
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
                    'acl_module' => 'KBContents',
                    'acl_action' => 'create',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:audit_button:click',
                    'name' => 'audit_button',
                    'label' => 'LNK_VIEW_CHANGE_LOG',
                    'acl_action' => 'view',
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
            'label' => 'LBL_PANEL_HEADER',
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
                    'related_fields' => array(
                        'useful',
                        'notuseful',
                        'usefulness_user_vote',
                        'kbdocument_id',
                    ),
                ),
                array(
                    'name' => 'favorite',
                    'label' => 'LBL_FAVORITE',
                    'type' => 'favorite',
                    'dismiss_label' => true,
                ),
                array(
                    'name' => 'follow',
                    'label' => 'LBL_FOLLOW',
                    'type' => 'follow',
                    'readonly' => true,
                    'dismiss_label' => true,
                ),
                'status' => array(
                    'name' => 'status',
                    'type' => 'status',
                    'enum_width' => 'auto',
                    'dropdown_width' => 'auto',
                    'dropdown_class' => 'select2-menu-only',
                    'container_class' => 'select2-menu-only',
                    'related_fields' => array(
                        'active_date',
                        'exp_date',
                    ),
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
                    'name' => 'kbdocument_body_set',
                    'type' => 'fieldset',
                    'label' => 'LBL_TEXT_BODY',
                    'span' => 12,
                    'fields' => array(
                        array(
                            'name' => 'template',
                            'type' => 'template-button',
                            'icon' => 'fa-file-o',
                            'css_class' => 'pull-right load-template',
                            'label' => 'LBL_TEMPLATES',
                        ),
                        array(
                            'name' => 'kbdocument_body',
                            'type' => 'htmleditable_tinymce',
                            'dismiss_label' => false,
                            'fieldSelector' => 'kbdocument_body',
                        ),
                    ),
                ),
                array(
                    'name' => 'attachment_list',
                    'label' => 'LBL_ATTACHMENTS',
                    'type' => 'attachments',
                    'link' => 'attachments',
                    'module' => 'Notes',
                    'modulefield' => 'filename',
                    'bLabel' => 'LBL_ADD_ATTACHMENT',
                    'span' => 12,
                ),
                array(
                    'name' => 'tag',
                    'span' => 12,
                ),
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
                'language' => array(
                    'name' => 'language',
                    'type' => 'enum-config',
                    'key' => 'languages',
                    'readonly' => false,
                ),
                'revision' => array(
                    'name' => 'revision',
                    'readonly' => true,
                ),
                'category_name' => array(
                    'name' => 'category_name',
                    'label' => 'LBL_CATEGORY_NAME',
                    'initial_filter' => 'by_category',
                    'initial_filter_label' => 'LBL_FILTER_CREATE_NEW',
                    'filter_relate' => array(
                        'category_id' => 'category_id',
                    ),
                ),
                'active_rev' => array(
                    'name' => 'active_rev',
                    'type' => 'bool',
                ),
                'viewcount' => array(
                    'name' => 'viewcount',
                ),
                'team_name' => array(
                    'name' => 'team_name',
                ),
                'assigned_user_name' => array(
                    'name' => 'assigned_user_name',
                ),
                'is_external' => array(
                    'name' => 'is_external',
                    'type' => 'bool',
                ),
                'date_entered' => array(
                    'name' => 'date_entered',
                ),
                'created_by_name' => array(
                    'name' => 'created_by_name',
                ),
                'date_modified' => array(
                    'name' => 'date_modified',
                ),
                'kbsapprover_name' => array(
                    'name' => 'kbsapprover_name',
                ),
                'active_date' => array(
                    'name' => 'active_date',
                ),
                'kbscase_name' => array(
                    'name' => 'kbscase_name',
                ),
                'exp_date' => array(
                    'name' => 'exp_date',
                ),
            ),
        ),
    ),
    'moreLessInlineFields' => array(
        'usefulness' => array(
            'name' => 'usefulness',
            'type' => 'usefulness',
            'span' => 6,
            'cell_css_class' => 'pull-right usefulness',
            'readonly' => true,
            'fields' => array(
                'usefulness_user_vote',
                'useful',
                'notuseful',
            ),
        ),
    )
);
