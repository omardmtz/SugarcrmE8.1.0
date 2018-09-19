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

$viewdefs['base']['view']['vcard-import-headerpane'] = array(
    'template' => 'headerpane',
    'fields' => array(
        array(
            'name' => 'title',
            'type' => 'label',
            'default_value' => 'LBL_IMPORT_VCARD',
        ),
    ),
    'buttons' => array(
        array(
            'name'    => 'vcard_cancel_button',
            'type'    => 'button',
            'label'   => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'acl_action' => 'create',
            'buttons' => array(
                array(
                    'name' => 'vcard_finish_button',
                    'type' => 'rowaction',
                    'label' => 'LBL_CREATE_BUTTON_LABEL',
                    'acl_action' => 'create',
                    'css_class' => 'btn-primary',
                    'events' => array(
                        'click' => 'vcard:import:finish',
                    ),
                ),
            ),
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
);
