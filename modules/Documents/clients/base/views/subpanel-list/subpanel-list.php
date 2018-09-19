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
$viewdefs['Documents']['base']['view']['subpanel-list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'document_name',
                    'label' => 'LBL_LIST_DOCUMENT_NAME',
                    'enabled' => true,
                    'default' => true,
                    'link' => true,
                ),
                array(
                    'name' => 'filename',
                    'label' => 'LBL_LIST_FILENAME',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true
                ),
                array(
                    'name' => 'category_id',
                    'label' => 'LBL_LIST_CATEGORY',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'doc_type',
                    'label' => 'LBL_LIST_DOC_TYPE',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'status_id',
                    'label' => 'LBL_LIST_STATUS',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'active_date',
                    'label' => 'LBL_LIST_ACTIVE_DATE',
                    'enabled' => true,
                    'default' => true,
                ),
            ),
        ),
    ),
    'rowactions' => array(
        'actions' => array(
            array(
                'type' => 'rowaction',
                'name' => 'edit_button',
                'icon' => 'fa-pencil',
                'label' => 'LBL_EDIT_BUTTON',
                'event' => 'list:editrow:fire',
                'acl_action' => 'edit',
            ),
            array(
                'type' => 'unlink-action',
                'icon' => 'fa-chain-broken',
                'label' => 'LBL_UNLINK_BUTTON',
            ),
        ),
    ),
);
