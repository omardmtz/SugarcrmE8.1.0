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

$viewdefs['base']['layout']['first-login-wizard'] = array(
    'type' => 'wizard',
    'buttons' => array(
        array(
            'name' => 'previous_button',
            'type' => 'button',
            'label' => 'LBL_BACK',
            'css_class' => 'btn-link btn-invisible',
        ),
        array(
            'name' => 'next_button',
            'type' => 'button',
            'label' => 'LNK_LIST_NEXT',
            'primary' => true,
        ),
        array(
            'name' => 'start_sugar_button',
            'type' => 'button',
            'label' => 'LBL_WIZ_START_SUGAR',
            'primary' => true,
        ),
    ),
    'components' => array(
        array(
            'view' => 'user-wizard-page',
            'primary' => true,
        ),
        array(
            'view' => "user-locale-wizard-page",
        ),
        array(
            'view' => 'setup-complete-wizard-page',
        )
    ),
);
