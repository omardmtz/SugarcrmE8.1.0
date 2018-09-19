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
$viewdefs['base']['view']['history-summary'] = array(
    'template' => 'flex-list',
    'sticky_resizable_columns' => true,
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'preview-button',
                'css_class' => 'btn',
                'tooltip' => 'LBL_PREVIEW',
                'event' => 'list:preview:fire',
                'icon' => 'fa-eye',
                'acl_action' => 'view'
            ),
        ),
    ),
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'name' => 'picture',
                    'type' => 'avatar',
                    'align' => 'center',
                    'label' => 'LBL_MODULE_TYPE',
                    'dismiss_label' => true,
                    'readonly' => true,
                    'enabled' => true,
                    'default' => true,
                    'isSortable' => true,
                    'width' => 'small',
                ),
                array(
                    'name' => 'name',
                    'label' => 'LBL_SUBJECT',
                    'type' => 'name',
                    'enabled' => true,
                    'default' => true,
                    'link' => true,
                    'isSortable' => true,
                    'width' => 'large',
                ),
                array(
                    'name' => 'status',
                    'label' => 'LBL_STATUS',
                    'type' => 'status',
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'name' => 'description',
                    'type' => 'textarea',
                    'label' => 'LBL_DESCRIPTION',
                    'enabled' => true,
                    'default' => true,
                    'width' => 'xlarge'
                ),
                array(
                    'name' => 'to_addrs',
                    'type' => 'email',
                    'label' => 'LBL_HISTORICAL_SUMMARY_EMAIL_TO',
                    'enabled' => true,
                    'default' => true,
                ),

                array(
                    'name' => 'from_addr',
                    'type' => 'email',
                    'label' => 'LBL_HISTORICAL_SUMMARY_EMAIL_FROM',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'related_contact',
                    'label' => 'LBL_RELATED_CONTACT',
                    'enabled' => true,
                    'default' => true,
                    'type' => 'related-contact',
                    'link' => true
                ),
                array(
                    'name' => 'date_entered',
                    'label' => 'LBL_LIST_DATE_ENTERED',
                    'type' => 'datetimecombo',
                    'enabled' => true,
                    'default' => false,
                    'isSortable' => true,
                ),
                array(
                    'name' => 'date_modified',
                    'label' => 'LBL_DATE_MODIFIED',
                    'type' => 'datetimecombo',
                    'enabled' => true,
                    'default' => true,
                    'isSortable' => true,
                ),
                array (
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_USER',
                    'id_name' => 'assigned_user_id',
                    'link' => 'assigned_user_link',
                    'module' => 'Users',
                    'rname'=> 'full_name',
                    'type' => 'relate',
                    'isSortable' => false,
                    'enabled' => true,
                    'default' => false,
                ),
            )
        )
    ),
    'last_state' => array(
        'id' => 'history-summary',
    ),
);
