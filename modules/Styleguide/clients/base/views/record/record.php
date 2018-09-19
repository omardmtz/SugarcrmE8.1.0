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
$viewdefs['Styleguide']['base']['view']['record'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'header' => true,
            'fields' => array(
                array(
                    'name' => 'picture',
                    'type' => 'avatar',
                    'size' => 'large',
                    'dismiss_label' => true,
                ),
                array(
                    'name' => 'name',
                    'label' => 'LBL_NAME',
                    'dismiss_label' => true,
                    'type' => 'fullname',
                    'fields' => array('salutation', 'first_name', 'last_name'),
                ),
                array(
                    'name' => 'favorite',
                    'label' => 'LBL_FAVORITE',
                    'type' => 'favorite',
                    'dismiss_label' => true,
                ),
                array(
                    'name' => 'follow',
                    'label'=> 'LBL_FOLLOW',
                    'type' => 'follow',
                    'readonly' => true,
                    'dismiss_label' => true,
                ),
            ),
        ),
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'title',
                    'label' => 'Base',
                    'type' => 'text',
                ),
                array(
                    'name' => 'do_not_call',
                    'label' => 'Boolean',
                    'type' => 'bool',
                    'text' => 'Do not call',
                ),
                array(
                    'name' => 'parent_name',
                    'label' => 'Parent',
                    'type' => 'parent',
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'Relate',
                    'type' => 'relate',
                    'id' => 'ASSIGNED_USER_ID',
                    'default' => true,
                    'sortable' => false,
                    'help' => 'This is the user that will be responsible for this record.',
                ),
                array(
                    'name' => 'user_email',
                    'label' => 'Email',
                    'type' => 'email',
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'Teamset',
                    'type' => 'teamset',
                    'module' => 'Teams',
                    'help' => 'Teamset fields provide a way for records to be assigned to a group of users.',
                ),
            ),
        ),
        array(
            'columns' => 2,
            'name' => 'panel_hidden',
            'label' => 'LBL_RECORD_SHOWMORE',
            'hide' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'primary_address',
                    'type' => 'fieldset',
                    'css_class' => 'address',
                    'label' => 'LBL_PRIMARY_ADDRESS',
                    'fields' => array(
                        array(
                            'name' => 'primary_address_street',
                            'css_class' => 'address_street',
                            'placeholder' => 'LBL_PRIMARY_ADDRESS_STREET',
                        ),
                        array(
                            'name' => 'primary_address_city',
                            'css_class' => 'address_city',
                            'placeholder' => 'LBL_PRIMARY_ADDRESS_CITY',
                        ),
                        array(
                            'name' => 'primary_address_state',
                            'css_class' => 'address_state',
                            'placeholder' => 'LBL_PRIMARY_ADDRESS_STATE',
                        ),
                        array(
                            'name' => 'primary_address_postalcode',
                            'css_class' => 'address_zip',
                            'placeholder' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
                        ),
                        array(
                            'name' => 'primary_address_country',
                            'css_class' => 'address_country',
                            'placeholder' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
                        ),
                    ),
                ),
                array(
                    'name' => 'alt_address',
                    'type' => 'fieldset',
                    'css_class' => 'address',
                    'label' => 'LBL_ALT_ADDRESS',
                    'fields' => array(
                        array(
                            'name' => 'alt_address_street',
                            'css_class' => 'address_street',
                            'placeholder' => 'LBL_ALT_ADDRESS_STREET',
                        ),
                        array(
                            'name' => 'alt_address_city',
                            'css_class' => 'address_city',
                            'placeholder' => 'LBL_ALT_ADDRESS_CITY',
                        ),
                        array(
                            'name' => 'alt_address_state',
                            'css_class' => 'address_state',
                            'placeholder' => 'LBL_ALT_ADDRESS_STATE',
                        ),
                        array(
                            'name' => 'alt_address_postalcode',
                            'css_class' => 'address_zip',
                            'placeholder' => 'LBL_ALT_ADDRESS_POSTALCODE',
                        ),
                        array(
                            'name' => 'alt_address_country',
                            'css_class' => 'address_country',
                            'placeholder' => 'LBL_ALT_ADDRESS_COUNTRY',
                        ),
                        array(
                            'name' => 'copy',
                            'label' => 'NTC_COPY_PRIMARY_ADDRESS',
                            'type' => 'copy',
                            'mapping' => array(
                                'primary_address_street' => 'alt_address_street',
                                'primary_address_city' => 'alt_address_city',
                                'primary_address_state' => 'alt_address_state',
                                'primary_address_postalcode' => 'alt_address_postalcode',
                                'primary_address_country' => 'alt_address_country',
                            ),
                        ),
                    ),
                ),
                array(
                    'name' => 'birthdate',
                    'label' => 'Date',
                    'type' => 'date',
                ),
                array(
                    'name' => 'date_start',
                    'label' => 'Datetimecombo',
                    'type' => 'datetimecombo',
                ),
                array(
                    'name' => 'filename',
                    'label' => 'File',
                    'type' => 'file',
                ),
                array(
                    'name' => 'list_price',
                    'label' => 'Currency',
                    'type' => 'currency',
                ),
                array(
                    'name' => 'website',
                    'label' => 'URL',
                    'type' => 'url',
                ),
                array(
                    'name' => 'phone_home',
                    'label' => 'Phone',
                    'type' => 'phone',
                ),
                array(
                    'name' => 'description',
                    'label' => 'Textarea',
                    'type' => 'textarea',
                ),
                array(
                    'name' => 'radio_button_group',
                    'type' => 'radioenum',
                    'label' => 'Radioenum',
                    'view' => 'edit',
                    'options' => array(
                        'option_one' => 'Option One',
                        'option_two' => 'Option Two',
                    ),
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'secret_password',
                    'label' => 'Password',
                    'type' => 'password',
                ),
                array(
                    'name' => 'empty_text',
                    'label' => 'Label',
                    'type' => 'label',
                    'default_value' => 'Static text string.',
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
                            'default_value' => 'LBL_BY',
                        ),
                        array(
                            'name' => 'modified_by_name',
                        ),
                    ),
                ),
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
                            'default_value' => 'LBL_BY',
                        ),
                        array(
                            'name' => 'created_by_name',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
