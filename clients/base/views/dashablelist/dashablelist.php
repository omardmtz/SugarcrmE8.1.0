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

$viewdefs['base']['view']['dashablelist'] = array(
    'template' => 'list',
    'dashlets' => array(
        array(
            'label' => 'LBL_DASHLET_LISTVIEW_NAME',
            'description' => 'LBL_DASHLET_LISTVIEW_DESCRIPTION',
            'config' => array(),
            'preview' => array(
                'module' => 'Accounts',
                'label' => 'LBL_MODULE_NAME',
                'display_columns' => array(
                    'name',
                    'phone_office',
                    'billing_address_country',
                ),
            ),
        ),
    ),
    'panels' => array(
        array(
            'name' => 'dashlet_settings',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'module',
                    'label' => 'LBL_MODULE',
                    'type' => 'enum',
                    'span' => 12,
                    'sort_alpha' => true,
                ),
                array(
                    'name' => 'display_columns',
                    'label' => 'LBL_COLUMNS',
                    'type' => 'enum',
                    'isMultiSelect' => true,
                    'ordered' => true,
                    'span' => 12,
                    'hasBlank' => true,
                    'options' => array('' => ''),
                ),
                array(
                    'name' => 'limit',
                    'label' => 'LBL_DASHLET_CONFIGURE_DISPLAY_ROWS',
                    'type' => 'enum',
                    'options' => 'dashlet_limit_options',
                ),
                array(
                    'name' => 'auto_refresh',
                    'label' => 'Auto Refresh',
                    'type' => 'enum',
                    'options' => 'sugar7_dashlet_auto_refresh_options',
                ),
                array(
                    'name' => 'intelligent',
                    'label' => 'LBL_DASHLET_CONFIGURE_INTELLIGENT',
                    'type' => 'bool',
                ),
                array(
                    'name' => 'linked_fields',
                    'label' => 'LBL_DASHLET_CONFIGURE_LINKED',
                    'type' => 'enum',
                    'required' => true
                ),
            ),
        ),
    ),
);
