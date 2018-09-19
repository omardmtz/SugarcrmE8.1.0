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
/*********************************************************************************
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$viewdefs['Accounts']['mobile']['view']['list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array(
                    'name' => 'name',
                    'label' => 'LBL_NAME',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                ),
                array(
                    'name' => 'billing_address_city',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'website',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                ),
                array(
                    'name' => 'phone_office',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'email',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'billing_address_street',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'billing_address_state',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'billing_address_postalcode',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'billing_address_country',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'shipping_address_street',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'shipping_address_city',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'shipping_address_state',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'shipping_address_postalcode',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'shipping_address_country',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'team_name',
                    'enabled' => true,
                    'default' => true,
                ),
            ),
        ),
    ),
);
