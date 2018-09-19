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
$viewdefs['Calls']['mobile']['view']['edit'] = array(
    'templateMeta' => array(
        'maxColumns' => '1', 
        'widths' => array(
            array(
                'label' => '10',
                'field' => '30',
            ),
        ),
    ),
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                'name',
                array(
                    'name' => 'date',
                    'type' => 'fieldset',
                    'related_fields' => array('date_start', 'date_end'),
                    'label' => "LBL_START_AND_END_DATE_DETAIL_VIEW",
                    'fields' => array(
                        array(
                            'name' => 'date_start',
                        ),
                        array(
                            'name' => 'date_end',
                            'required' => true,
                            'readonly' => false,  
                        ),
                    ),
                ),
                'direction',
                'status',
                array(
                    'name' => 'reminder',
                    'type' => 'fieldset',
                    'orientation' => 'horizontal',
                    'related_fields' => array('reminder_checked', 'reminder_time'),
                    'label' => "LBL_REMINDER",
                    'fields' => array(
                        array(
                            'name' => 'reminder_checked',
                        ),
                        array(
                            'name' => 'reminder_time',
                            'type' => 'enum',
                            'options' => 'reminder_time_options'
                        ),
                    ),
                ),
                array(
                    'name' => 'email_reminder',
                    'type' => 'fieldset',
                    'orientation' => 'horizontal',
                    'related_fields' => array('email_reminder_checked', 'email_reminder_time'),
                    'label' => "LBL_EMAIL_REMINDER",
                    'fields' => array(
                        array(
                            'name' => 'email_reminder_checked',
                        ),
                        array(
                            'name' => 'email_reminder_time',
                            'type' => 'enum',
                            'options' => 'reminder_time_options',
                        ),
                    ),
                ),
                'description',
                'parent_name',
                'assigned_user_name',
                'team_name',
            ),
        ),
    ),
);
