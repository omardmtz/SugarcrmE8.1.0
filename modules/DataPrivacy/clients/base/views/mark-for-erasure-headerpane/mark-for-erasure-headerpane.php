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
$viewdefs['DataPrivacy']['base']['view']['mark-for-erasure-headerpane'] = array(
    'template' => 'headerpane',
    'fields' => array(
        array(
            'name' => 'title',
            'type' => 'label',
            'default_value' => 'LBL_DATAPRIVACY_PII',
        ),
    ),
    'buttons' => array(
        array(
            'name' => 'close_button',
            'type' => 'button',
            'label' => 'LBL_CLOSE_BUTTON_TITLE',
            'css_class' => 'btn btn-secondary',
        ),
        array(
            'name' => 'mark_for_erasure_button',
            'type' => 'button',
            'label' => 'LBL_DATAPRIVACY_MARK_FOR_ERASURE',
            'primary' => true,
            'css_class' => 'btn btn-primary disabled',
            'events' => array(
                'click' => 'button:mark_for_erasure_button:click',
            ),
        ),
    ),
);
