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

$viewdefs['Calls']['base']['view']['preview'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'fields' => array(
                array(
                    'name' => 'picture',
                    'type' => 'avatar',
                    'size' => 'large',
                    'dismiss_label' => true,
                    'readonly' => true,
                ),
                'name',
                array(
                    'name' => 'status',
                    'type' => 'event-status',
                    'enum_width' => 'auto',
                    'dropdown_width' => 'auto',
                    'dropdown_class' => 'select2-menu-only',
                    'container_class' => 'select2-menu-only',
                ),
            ),
        ),
        array(
            'name' => 'panel_body',
            'fields' => array(
                array(
                    'name' => 'duration',
                    'type' => 'duration',
                    'label' => 'LBL_START_AND_END_DATE_DETAIL_VIEW',
                    'dismiss_label' => true,
                    'inline' => false,
                    'show_child_labels' => true,
                    'fields' => array(
                        array(
                            'name' => 'date_start',
                            'time' => array(
                                'step' => 15,
                            ),
                            'readonly' => false,
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_START_AND_END_DATE_TO',
                        ),
                        array(
                            'name' => 'date_end',
                            'time' => array(
                                'step' => 15,
                                'duration' => array(
                                    'relative_to' => 'date_start'
                                )
                            ),
                            'readonly' => false,
                        ),
                    ),
                    'span' => 9,
                    'related_fields' => array(
                        'duration_hours',
                        'duration_minutes',
                    ),
                ),
                array(
                    'name' => 'repeat_type',
                    'span' => 3,
                    'related_fields' => array(
                        'repeat_parent_id',
                    ),
                    'readonly' => true,
                ),
                'direction',
                array(
                    'name' => 'description',
                    'span' => 12,
                    'rows' => 3,
                ),
                'parent_name',
                array(
                    'name' => 'invitees',
                    'type' => 'participants',
                    'label' => 'LBL_INVITEES',
                    'span' => 12,
                    'fields' => array('name', 'accept_status_calls', 'picture'),
                ),
                'assigned_user_name',
                'team_name',
                array(
                    'name' => 'tag',
                    'span' => 12,
                ),
            ),
        ),
        array(
            'name' => 'panel_hidden',
            'hide' => true,
            'fields' => array(
                array(
                    'name' => 'date_entered_by',
                    'readonly' => true,
                    'inline' => true,
                    'type' => 'fieldset',
                    'label' => 'LBL_DATE_ENTERED',
                    'fields' => array(
                        array(
                            'name' => 'date_entered',
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_BY'
                        ),
                        array(
                            'name' => 'created_by_name',
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
                        ),
                        array(
                            'type' => 'label',
                            'default_value' => 'LBL_BY'
                        ),
                        array(
                            'name' => 'modified_by_name',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
