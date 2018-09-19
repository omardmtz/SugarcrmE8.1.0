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

$viewdefs['Contacts']['portal']['view']['record'] = array(
    'hashSync' => false,
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
                    'fields' => array('salutation','first_name', 'last_name'),
                ),
            ),
        ),
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' =>
            array(
                array(
                    'name' => 'title',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name' => 'email',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name' => 'portal_password',
                    'type' => 'change-my-password',
                    'label' => 'LBL_CONTACT_EDIT_PASSWORD',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name' => 'phone_work',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name' => 'primary_address_street',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name' => 'primary_address_city',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name' => 'primary_address_state',
                    'options' => 'state_dom',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name' => 'primary_address_postalcode',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array (
                    'name' => 'primary_address_country',
                    'options' => 'countries_dom',
                    'displayParams' =>
                    array(
                        'colspan' => 2,
                    ),
                ),
                array (
                    'name' => 'preferred_language',
                    'type' => 'language',
                    'options' => 'available_language_dom',
                ),
            ),
        ),
    ),
);
