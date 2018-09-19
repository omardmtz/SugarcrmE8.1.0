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
$viewdefs['OutboundEmail']['base']['view']['recordlist'] = array(
    'favorite' => false,
    'following' => false,
    'sticky_resizable_columns' => false,
    'selection' => array(),
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'rowaction',
                'name' => 'edit_button',
                'dismiss_label' => true,
                'icon' => 'fa-pencil',
                'tooltip' => 'LBL_EDIT_BUTTON',
                'acl_action' => 'edit',
                'route' => array(
                    'action' => 'edit',
                    'module' => 'OutboundEmail',
                ),
            ),
            array(
                'type' => 'rowaction',
                'name' => 'delete_button',
                'event' => 'list:deleterow:fire',
                'label' => 'LBL_DELETE_BUTTON',
                'acl_action' => 'delete',
            ),
        ),
    ),
    'last_state' => array(
        'id' => 'record-list',
    ),
);
