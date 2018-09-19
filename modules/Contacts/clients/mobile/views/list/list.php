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
$viewdefs['Contacts']['mobile']['view']['list'] = array(
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
                    'related_fields' => array('first_name', 'last_name', 'salutation'),
                ),
                array(
                    'name' => 'title',
                    'label' => 'LBL_TITLE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'email',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'phone_work',
                    'label' => 'LBL_OFFICE_PHONE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'phone_mobile',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'phone_home',
                    'enabled' => true,
                    'default' => true,
                ),
                array (
                    'name' => 'picture',
                    'label' => 'LBL_PICTURE_FILE',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'primary_address_street',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'primary_address_city',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'primary_address_state',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'primary_address_postalcode',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'primary_address_country',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'alt_address_street',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'alt_address_city',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'alt_address_state',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'alt_address_postalcode',
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'alt_address_country',
                    'enabled' => true,
                    'default' => true,
                ),
            ),
        ),
    ),
);
