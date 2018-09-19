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

$viewdefs['Quotes']['base']['view']['preview'] = array(
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
                    'name' => 'quote_stage',
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
                'opportunity_name',
                'quote_num',
                'purchase_order_num',
                'date_quote_expected_closed',
                'payment_terms',
                'original_po_date',
                'billing_account_name',
                'billing_contact_name',
                array(
                    'inline' => false,
                    'type' => 'fieldset',
                    'label' => 'LBL_BILLING_ADDRESS_STREET',
                    'fields' => array(
                        array(
                            'name' => 'billing_address_street',
                            'placeholder' => 'LBL_STREET',
                        ),
                        array(
                            'name' => 'billing_address_city',
                            'placeholder' => 'LBL_CITY',
                        ),
                        array(
                            'name' => 'billing_address_state',
                            'placeholder' => 'LBL_STATE',
                        ),
                        array(
                            'name' => 'billing_address_postalcode',
                            'placeholder' => 'LBL_POSTAL_CODE',
                        ),
                        array(
                            'name' => 'billing_address_country',
                            'placeholder' => 'LBL_COUNTRY',
                        ),
                    ),
                ),
                'shipping_account_name',
                'shipping_contact_name',
                array(
                    'inline' => false,
                    'type' => 'fieldset',
                    'label' => 'LBL_SHIPPING_ADDRESS_STREET',
                    'fields' => array(
                        array(
                            'name' => 'shipping_address_street',
                            'placeholder' => 'LBL_STREET',
                        ),
                        array(
                            'name' => 'shipping_address_city',
                            'placeholder' => 'LBL_CITY',
                        ),
                        array(
                            'name' => 'shipping_address_state',
                            'placeholder' => 'LBL_STATE',
                        ),
                        array(
                            'name' => 'shipping_address_postalcode',
                            'placeholder' => 'LBL_POSTAL_CODE',
                        ),
                        array(
                            'name' => 'shipping_address_country',
                            'placeholder' => 'LBL_COUNTRY',
                        ),
                    ),
                ),
                'description',
                'tag',
            ),
        ),
        array(
            'name' => 'panel_hidden',
            'hide' => true,
            'fields' => array(
                'assigned_user_name',
                'team_name',
                array(
                    'name' => 'date_entered_by',
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
                    'name' => 'date_modified_by',
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
