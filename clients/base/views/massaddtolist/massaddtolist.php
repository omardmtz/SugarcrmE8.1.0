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

$viewdefs['base']['view']['massaddtolist'] = array(
    'buttons' => array(
        array(
            'name' => 'create_button',
            'type' => 'button',
            'label' => 'LBL_CREATE_NEW_TARGET_LIST',
            'acl_action' => 'create',
            'acl_module' => 'ProspectLists',
            'css_class' => 'btn-link btn-invisible',
        ),
        array(
            'type' => 'button',
            'value' => 'cancel',
            'css_class' => 'btn-link btn-invisible cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'primary' => false,
        ),
        array(
            'name' => 'update_button',
            'type' => 'button',
            'label' => 'LBL_UPDATE',
            'acl_action' => 'edit',
            'acl_module' => 'ProspectLists',
            'css_class' => 'btn-primary',
            'primary' => true,
        ),
    ),
);
