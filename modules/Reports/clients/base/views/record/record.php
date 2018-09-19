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
$viewdefs['Reports']['base']['view']['record'] = array(
    'buttons' => array(
    ),
    'panels' => array(
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name'          => 'picture',
                    'type'          => 'avatar',
                    'size'          => 'large',
                    'dismiss_label' => true,
                    'readonly'      => true,
                ),
                array(
                    'name' => 'name',
                    'bwcLink' => true,
                ),
                'description',
                array(
                    'name' => 'module',
                    'readonly' => true,
                ),
                array(
                    'name' => 'report_type',
                    'readonly' => true,
                ),
                array(
                    'name' => 'chart_type',
                    'type' => 'chart-type',
                    'readonly' => true,
                ),
                'assigned_user_name',
                array(
                    'name' => 'next_run',
                    'readonly' => true,
                ),
                array(
                    'name' => 'last_run_date',
                    'readonly' => true,
                ),
                'name' => 'tag',
                array(
                    'name' => 'date_entered_by',
                    'readonly' => true,
                    'inline' => true,
                    'type' => 'fieldset',
                    'label' => 'LBL_DATE_ENTERED',
                    'fields' => array(
                        array(
                            'name' => 'date_entered',
                            'readonly' => true,
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_BY',
                        ),
                        array(
                            'name' => 'created_by_name',
                            'readonly' => true,
                        ),
                    ),
                ),
                array(
                    'name' => 'date_modified_by',
                    'readonly' => true,
                    'inline' => true,
                    'type' => 'fieldset',
                    'label' => 'LBL_DATE_MODIFIED',
                    'fields' => array(
                        array(
                            'name' => 'date_modified',
                            'readonly' => true,
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_BY',
                        ),
                        array(
                            'name' => 'modified_by_name',
                            'readonly' => true,
                        ),
                    ),
                ),
                'team_name',
            ),
        ),
    ),
);
