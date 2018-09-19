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
$viewdefs['Accounts']['base']['filter']['default'] = array(
    'default_filter' => 'all_records',
    'fields' => array(
        'name' => array(),
        'account_type' => array(),
        'industry' => array(),
        'annual_revenue' => array(),
        'address_street' => array(
            'dbFields' => array(
                'billing_address_street',
                'shipping_address_street',
            ),
            'vname' => 'LBL_STREET',
            'type' => 'text',
        ),
        'address_city' => array(
            'dbFields' => array(
                'billing_address_city',
                'shipping_address_city',
            ),
            'vname' => 'LBL_CITY',
            'type' => 'text',
        ),
        'address_state' => array(
            'dbFields' => array(
                'billing_address_state',
                'shipping_address_state',
            ),
            'vname' => 'LBL_STATE',
            'type' => 'text',
        ),
        'address_postalcode' => array(
            'dbFields' => array(
                'billing_address_postalcode',
                'shipping_address_postalcode',
            ),
            'vname' => 'LBL_POSTAL_CODE',
            'type' => 'text',
        ),
        'address_country' => array(
            'dbFields' => array(
                'billing_address_country',
                'shipping_address_country',
            ),
            'vname' => 'LBL_COUNTRY',
            'type' => 'text',
        ),
        'rating' => array(),
        'phone_office' => array(),
        'website' => array(),
        'ownership' => array(),
        'employees' => array(),
        'sic_code' => array(),
        'ticker_symbol' => array(),
        'date_entered' => array(),
        'date_modified' => array(),
        'tag' => array(),
        '$owner' => array(
            'predefined_filter' => true,
            'vname' => 'LBL_CURRENT_USER_FILTER',
        ),
        'assigned_user_name' => array(),
        '$favorite' => array(
            'predefined_filter' => true,
            'vname' => 'LBL_FAVORITES_FILTER',
        ),
    ),
);
