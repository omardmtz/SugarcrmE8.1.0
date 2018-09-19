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
$module_name = '<module_name>';
$_module_name = '<_module_name>';
$viewdefs[$module_name]['base']['view']['list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_ACCOUNT_NAME',
                    'link' => true,
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'billing_address_city',
                    'label' => 'LBL_CITY',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_office',
                    'label' => 'LBL_PHONE',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => $_module_name . '_type',
                    'label' => 'LBL_TYPE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'industry',
                    'label' => 'LBL_INDUSTRY',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'annual_revenue',
                    'label' => 'LBL_ANNUAL_REVENUE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_fax',
                    'label' => 'LBL_PHONE_FAX',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'billing_address_street',
                    'label' => 'LBL_BILLING_ADDRESS_STREET',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'billing_address_state',
                    'label' => 'LBL_BILLING_ADDRESS_STATE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'billing_address_postalcode',
                    'label' => 'LBL_BILLING_ADDRESS_POSTALCODE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'billing_address_country',
                    'label' => 'LBL_BILLING_ADDRESS_COUNTRY',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'shipping_address_street',
                    'label' => 'LBL_SHIPPING_ADDRESS_STREET',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'shipping_address_city',
                    'label' => 'LBL_SHIPPING_ADDRESS_CITY',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'shipping_address_state',
                    'label' => 'LBL_SHIPPING_ADDRESS_STATE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'shipping_address_postalcode',
                    'label' => 'LBL_SHIPPING_ADDRESS_POSTALCODE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'shipping_address_country',
                    'label' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'phone_alternate',
                    'label' => 'LBL_PHONE_ALT',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'website',
                    'label' => 'LBL_WEBSITE',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'ownership',
                    'label' => 'LBL_OWNERSHIP',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'employees',
                    'label' => 'LBL_EMPLOYEES',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'ticker_symbol',
                    'label' => 'LBL_TICKER_SYMBOL',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'email',
                    'label' => 'LBL_ANY_EMAIL',
                    'link' => true,
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'team_name',
                    'label' => 'LBL_TEAM',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_ASSIGNED_TO_NAME',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'date_modified',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'date_entered',
                    'enabled' => true,
                    'default' => true,
                ),
            ),
        ),
    ),
);
