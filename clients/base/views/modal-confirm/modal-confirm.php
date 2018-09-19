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

$viewdefs['base']['view']['modal-confirm'] = array(
    'buttons' => array(
        array(
            'name' => 'ok_button',
            'type' => 'button',
            'css_class' => 'btn-primary pull-right',
            'label' => 'LBL_EMAIL_OK',
            'primary' => true,
        ),
        array(
            'name' => 'close_button',
            'type' => 'button',
            'css_class' => 'btn-invisible btn-link',
            'label' => 'LBL_EMAIL_CANCEL',
            'primary' => false,
        ),
    ),
);
