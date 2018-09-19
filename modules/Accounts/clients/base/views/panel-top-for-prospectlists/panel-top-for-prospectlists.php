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

$viewdefs['Accounts']['base']['view']['panel-top-for-prospectlists'] = array(
    'type' => 'panel-top',
    'template' => 'panel-top',
    'buttons' => array(
        array(
            'type' => 'actiondropdown',
            'name' => 'panel_dropdown',
            'css_class' => 'pull-right',
            'buttons' => array(
                array(
                    'type' => 'sticky-rowaction',
                    'icon' => 'fa-plus',
                    'name' => 'create_button',
                    'label' => ' ',
                    'acl_action' => 'create',
                    'tooltip' => 'LBL_CREATE_BUTTON_LABEL',
                ),
                array(
                    'type' => 'link-action',
                    'name' => 'select_button',
                    'label' => 'LBL_ASSOC_RELATED_RECORD',
                ),
                array(
                    'type' => 'linkfromreportbutton',
                    'name' => 'select_button',
                    'label' => 'LBL_SELECT_REPORTS_BUTTON_LABEL',
                    'initial_filter' => 'by_module',
                    'initial_filter_label' => 'LBL_FILTER_ACCOUNTS_REPORTS',
                    'filter_populate' => array(
                        'module' => array('Accounts'),
                    ),
                ),
            ),
        ),
    ),
);
