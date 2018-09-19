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
$viewdefs['Emails']['base']['view']['list-headerpane'] = array(
    'buttons' => array(
        array(
            'name' => 'create_button',
            'type' => 'emailaction',
            'label' => 'LBL_COMPOSE_MODULE_NAME_SINGULAR',
            'button' => true,
            'primary' => true,
            'acl_action' => 'create',
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
);
