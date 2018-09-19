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

$viewdefs['Reports']['base']['view']['list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array (
                    'name'  => 'name',
                    'label' => 'LBL_LIST_REPORT_NAME',
                    'link' => true,
                    'type' => 'name',
                    'enabled' => true,
                    'default' => true,
                    'bwcLink' => true,
                ),
                array (
                    'name'  => 'module',
                    'label' => 'LBL_MODULE',
                    'default' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'report_type',
                    'label' => 'LBL_REPORT_TYPE',
                    'type' => 'enum',
                    'options' => 'dom_report_types',
                    'default' => true,
                    'readonly' => true,
                ),
                array (
                    'name'  => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_USER',
                    'default' => true,
                    'sortable' => false,
                    'readonly' => true,
                ),
                array(
                    'name' => 'last_run_date',
                    'default' => true,
                    'type' => 'datetimecombo',
                    'link' => false,
                    'readonly' => true,
                ),
                array (
                    'name'  => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'default' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'date_modified',
                    'enabled' => true,
                    'default' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'next_run',
                    'label' => 'LBL_SCHEDULE_REPORT',
                    'type' => 'next-run',
                    'default' => false,
                    'link' => true,
                    'readonly' => true,
                ),
                array(
                    'name' => 'tag',
                    'label' => 'LBL_TAGS',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_LIST_TEAM',
                    'default' => false,
                ),
                array(
                    'name' => 'chart_type',
                    'default' => false,
                    'type' => 'chart-type',
                    'readonly' => true,
                ),
            ),
        ),
    ),
);
