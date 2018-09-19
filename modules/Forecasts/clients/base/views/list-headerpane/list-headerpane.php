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

$viewdefs['Forecasts']['base']['view']['list-headerpane'] = array(
    'tree' => array(
        array(
            'type' => 'reportingUsers',
            'acl_action' => 'is_manager'
        )
    ),
    'buttons' => array(
        array(
            'name' => 'save_draft_button',
            'events' => array(
                'click' => 'button:save_draft_button:click',
            ),
            'type' => 'button',
            'label' => 'LBL_SAVE_DRAFT',
            'css_class' => 'btn-group save-draft-button',
            'acl_action' => 'current_user',
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'buttons' => array(
                array(
                    'name' => 'commit_button',
                    'type' => 'button',
                    'label' => 'LBL_QC_COMMIT_BUTTON',
                    'events' => array(
                        'click' => 'button:commit_button:click',
                    ),
                    'tooltip' => 'LBL_COMMIT_TOOLTIP',
                    'css_class' => 'btn-primary disabled commit-button',
                    'icon' => 'fa-arrow-circle-o-up',
                    'acl_action' => 'current_user',
                    'primary' => true
                ),
                array(
                    'name' => 'assign_quota',
                    'type' => 'assignquota',
                    'label' => 'LBL_ASSIGN_QUOTA_BUTTON',
                    'events' => array(
                        'click' => 'button:assign_quota:click',
                    ),
                    'acl_action' => 'manager_current_user',
                ),
                array(
                    'name' => 'export_button',
                    'type' => 'rowaction',
                    'label' => 'LBL_EXPORT_CSV',
                    'event' => 'button:export_button:click',
                ),
                array(
                    'name' => 'settings_button',
                    'type' => 'rowaction',
                    'label' => 'LBL_FORECAST_SETTINGS',
                    'acl_action' => 'developer',
                    'route' => array(
                        'action'=>'config'
                    ),
                    'events' => array(
                        'click' => 'button:settings_button:click',
                    ),
                ),
            ),
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
);
