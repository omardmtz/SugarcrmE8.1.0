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

$viewdefs['base']['view']['forgotpassword'] = array(
    'buttons' =>
    array(
        array(
            'name' => 'forgotPassword_button',
            'type' => 'button',
            'label' => 'LBL_REQUEST_PASSWORD',
            'primary' => true,
        ),
        array(
            'name' => 'cancel_button',
            'type' => 'button',
            'label' => 'LBL_LOGIN_BUTTON_LABEL',
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
                    'name' => 'username',
                    'type' => 'username',
                    'placeholder' => "LBL_LIST_USER_NAME",
                    'required' => true,
                ),
                array(
                    'name' => 'email',
                    'type' => 'email-text',
                    'placeholder' => "LBL_EMAIL_BUTTON",
                    'required' => true,
                ),
                array(
                    'name' => 'first_name',
                    'type' => 'text',
                    'css_class' => 'hp',
                    'placeholder' => "LBL_HONEYPOT",
                ),
            ),
        ),
    ),
);
