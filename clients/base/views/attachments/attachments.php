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
$viewdefs['base']['view']['attachments'] = array(
	'dashlets' => array(
		array(
            'label' => 'LBL_DASHLET_ATTACHMENTS_NAME',
            'description' => 'LBL_DASHLET_ATTACHMENTS_DESCRIPTION',
            'config' => array(
                'auto_refresh' => '0',
                'module' => 'Notes',
                'link' => 'notes',
            ),
            'preview' => array(
                'module' => 'Notes',
                'link' => 'notes',
            ),
            'filter' => array(
                'module' => array(
                    'Accounts',
                    'Contacts',
                    'Opportunities',
                    'Leads',
                    'Bugs',
                    'Cases',
                    'RevenueLineItems',
                    'KBContents',
                ),
                'view' => 'record',
            ),
            'fields' => array(
                'name',
                'date_entered',
                'filename',
                'file_mime_type',
                'assigned_user_id',
                'assigned_user_name',
            ),
        ),
 	),
    'custom_toolbar' => array(
        'buttons' => array(
            array(
                'type' => 'actiondropdown',
                'icon' => 'fa-plus',
                'no_default_action' => true,
                'buttons' => array(
                    array(
                        'type' => 'dashletaction',
                        'css_class' => '',
                        'label' => 'LBL_CREATE_RELATED_RECORD',
                        'action' => 'openCreateDrawer',
                    ),
                    array(
                        'type' => 'dashletaction',
                        'css_class' => '',
                        'label' => 'LBL_ASSOC_RELATED_RECORD',
                        'action' => 'openSelectDrawer',
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
                )
            )
        )
    ),
    'rowactions' => array(
        array(
            'type' => 'rowaction',
            'icon' => 'fa-chain-broken',
            'css_class' => 'btn btn-mini',
            'event' => 'attachment:unlinkrow:fire',
            'target' => 'view',
            'tooltip' => 'LBL_UNLINK_BUTTON',
            'acl_action' => 'edit',
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
                    'name' => 'limit',
                    'label' => 'Display Rows',
                    'type' => 'enum',
                    'options' => array(
                        5 => 5,
                        10 => 10,
                        15 => 15,
                        20 => 20
                    )
                ),
                array(
                    'name' => 'auto_refresh',
                    'label' => 'Auto Refresh',
                    'type' => 'enum',
                    'options' => 'sugar7_dashlet_auto_refresh_options',
                ),
            ),
        ),
    ),
	'supportedImageExtensions' => array(
        'image/jpeg' => 'JPG',
        'image/gif' => 'GIF',
        'image/png' => 'PNG',
	),
	'defaultType' => 'txt',
);
