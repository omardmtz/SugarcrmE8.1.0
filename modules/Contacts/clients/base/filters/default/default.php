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

$viewdefs['Contacts']['base']['filter']['default'] = array(
    'default_filter' => 'all_records',
    'fields' => array(
        'first_name' => array(),
        'last_name' => array(),
        'title' => array(),
        'account_name'=> array(),
        'lead_source' => array(),
        'do_not_call' => array(),
        'phone' => array(
            'dbFields' => array(
                'phone_mobile',
                'phone_work',
                'phone_other',
                'phone_fax',
                'assistant_phone',
            ),
            'type' => 'phone',
            'vname' => 'LBL_PHONE'
        ),
        'assistant' => array(),
        'address_street' => array(
            'dbFields' => array(
                'primary_address_street',
                'alt_address_street',
            ),
            'vname' => 'LBL_STREET',
            'type' => 'text',
        ),
        'address_city' => array(
            'dbFields' => array(
                'primary_address_city',
                'alt_address_city',
            ),
            'vname' => 'LBL_CITY',
            'type' => 'text',
        ),
        'address_state' => array(
            'dbFields' => array(
                'primary_address_state',
                'alt_address_state',
            ),
            'vname' => 'LBL_STATE',
            'type' => 'text',
        ),
        'address_postalcode' => array(
            'dbFields' => array(
                'primary_address_postalcode',
                'alt_address_postalcode',
            ),
            'vname' => 'LBL_POSTAL_CODE',
            'type' => 'text',
        ),
        'address_country' => array(
            'dbFields' => array(
                'primary_address_country',
                'alt_address_country',
            ),
            'vname' => 'LBL_COUNTRY',
            'type' => 'text',
        ),
        'campaign_name' => array(),
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
