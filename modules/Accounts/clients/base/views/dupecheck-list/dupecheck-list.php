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
$viewdefs['Accounts']['base']['view']['dupecheck-list'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'name',
                    'link' => true,
                    'label' => 'LBL_LIST_ACCOUNT_NAME',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'billing_address_city',
                    'label' => 'LBL_LIST_CITY',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'billing_address_country',
                    'label' => 'LBL_BILLING_ADDRESS_COUNTRY',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'phone_office',
                    'label' => 'LBL_LIST_PHONE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'assigned_user_name',
                    'label' => 'LBL_LIST_ASSIGNED_USER',
                    'id' => 'ASSIGNED_USER_ID',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'email',
                    'label' => 'LBL_EMAIL_ADDRESS',
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'date_entered',
                    'type' => 'datetime',
                    'label' => 'LBL_DATE_ENTERED',
                    'enabled' => true,
                    'default' => false,
                    'readonly' => true,
                ),
            ),
        ),
    ),
);
