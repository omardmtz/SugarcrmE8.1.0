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

$module_name = 'pmse_Inbox';
$viewdefs[$module_name]['base']['view']['reassignCases-headerpane'] = array(
    'template' => 'headerpane',
    'title'    => 'LBL_PMSE_TITLE_ACTIVITY_TO_REASSIGN',
    'buttons'  => array(
        array(
            'name'      => 'cancel_button',
            'type'      => 'button',
            'label'     => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
        ),
        array(
            'name'      => 'done_button',
            'type'      => 'button',
            'label'     => 'LBL_DONE_BUTTON_LABEL',
            'css_class' => 'btn-primary',
        )
    ),
);
