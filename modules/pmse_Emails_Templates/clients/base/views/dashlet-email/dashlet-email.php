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


$viewdefs['pmse_Emails_Templates']['base']['view']['dashlet-email'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_PMSE_EMAIL_TEMPLATES_DASHLET',
            'description' => 'LBL_PMSE_EMAIL_TEMPLATES_DASHLET_DESCRIPTION',
            'config' => array(
                'limit' => 10,
                'visibility' => 'user',
            ),
            'preview' => array(
                'limit' => 10,
                'visibility' => 'user',
            ),
            'filter' => array(
                'module' => array(
                    'Home',
                    'pmse_Emails_Templates',
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
                            'module' => 'pmse_Emails_Templates',
                            'link' => '#pmse_Emails_Templates',
                        ),
                        'label' => 'LNK_PMSE_EMAIL_TEMPLATES_NEW_RECORD',
                        'acl_action' => 'create',
                        'acl_module' => 'pmse_Emails_Templates',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'action' => 'importRecord',
                        'params' => array(
                            'module' => 'pmse_Emails_Templates',
                            'link' => '#pmse_Emails_Templates/layout/emailtemplates-import'
                        ),
                        'label' => 'LNK_PMSE_EMAIL_TEMPLATES_IMPORT_RECORD',
                        'acl_action' => 'importRecord',
                        'acl_module' => 'pmse_Emails_Templates',
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
            'filters' => array(),
            'label' => 'LBL_PMSE_EMAIL_TEMPLATES_DASHLET',
            'link' => 'LBL_PMSE_EMAIL_TEMPLATES_DASHLET',
            'module' => 'pmse_Emails_Templates',
            'order_by' => 'date_entered:desc',
            'record_date' => 'date_entered',
            'row_actions' => array(
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-pencil',
                    'css_class' => 'btn btn-mini',
                    'event' => 'dashlet-email:edit:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_EDIT_BUTTON',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-times',
                    'css_class' => 'btn btn-mini',
                    'event' => 'dashlet-email:delete-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_PMSE_LABEL_DELETE',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa fa-download',
                    'css_class' => 'btn btn-mini',
                    'event' => 'dashlet-email:download:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_PMSE_LABEL_EXPORT',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'icon' => 'fa-info-circle',
                    'css_class' => 'btn btn-mini',
                    'event' => 'dashlet-email:description-record:fire',
                    'target' => 'view',
                    'tooltip' => 'LBL_DESCRIPTION',
                    'acl_action' => 'edit',
                ),
            ),
            'fields' => array(
                'name',
                'base_module',
                'assigned_user_name',
                'assigned_user_id',
                'date_entered',
                'description'
            ),
        ),
    ),
);
