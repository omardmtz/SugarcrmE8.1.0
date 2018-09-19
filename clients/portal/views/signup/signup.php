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

$viewdefs['portal']['view']['signup'] = array(
    'action' => 'list',
    'buttons' =>
    array(
        array(
            'name' => 'signup_button',
            'type' => 'button',
            'label' => 'LBL_SIGNUP_BUTTON_LABEL',
            'primary' => true,
        ),
        array(
            'name' => 'cancel_button',
            'type' => 'button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'pull-left',
        ),
    ),
    'panels' =>
    array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' =>
            array(
                array(
                    'name' => 'first_name',
                    'type' => 'varchar',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_FIRST_NAME',
                    'required' => true,
                ),
                array(
                    'name' => 'last_name',
                    'type' => 'varchar',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_LAST_NAME',
                    'required' => true,
                ),
                array(
                    'name' => 'email',
                    'type' => 'email-text',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_EMAIL',
                    'required' => true,
                ),
                array(
                    'name' => 'phone_work',
                    'type' => 'phone',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_PHONE',
                ),
                array(
                    'name' => 'country',
                    'type' => 'enum',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_COUNTRY',
                    'options' => 'countries_dom',
                    'required' => true,
                ),
                array(
                    'name' => 'state',
                    'type' => 'enum',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_STATE',
                    'options' => 'state_dom',
                ),
                array(
                    'name' => 'company',
                    'type' => 'varchar',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_COMPANY',
                    'required' => true,
                ),
                array(
                    'name' => 'title',
                    'type' => 'varchar',
                    'placeholder' => 'LBL_PORTAL_SIGNUP_JOBTITLE',
                ),
            ),
        ),
    ),
);
