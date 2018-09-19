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

$viewdefs['base']['view']['password-expired'] = array(
    'action' => 'list',
    'buttons' => array(
        array(
            'name' => 'save_button',
            'type' => 'button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'value' => 'save',
            'css_class' => 'btn-primary save-profile',
        ),
    ),
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array(
                    'name' => 'expired_password_update',
                    'type' => 'change-my-password',
                    'label' => 'LBL_CONTACT_EDIT_PASSWORD',
                    'displayParams' => array(
                        'colspan' => 2,
                    ),
                ),
            ),
            array(
                'name' => 'name_field',
                'type' => 'text',
                'css_class' => 'hp',
                'placeholder' => "LBL_HONEYPOT",
            ),
        ),
    ),
);
