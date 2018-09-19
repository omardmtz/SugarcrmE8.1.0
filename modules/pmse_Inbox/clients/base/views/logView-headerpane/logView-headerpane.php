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
$viewdefs[$module_name]['base']['view']['logView-headerpane'] = array(
    'template' => 'headerpane',
    'title' => 'LBL_PMSE_TITLE_LOG_VIEWER',
    'buttons' => array(
        array(
            'name'    => 'log_pmse_button',
            'type'    => 'button',
            'label'   => 'LBL_PMSE_BUTTON_REFRESH',
            'acl_action' => 'create',
            'css_class' => 'btn-primary',
        ),
        array(
            'name'    => 'log_clear_button',
            'type'    => 'button',
            'label'   => 'LBL_PMSE_BUTTON_CLEAR',
            'acl_action' => 'create',
            'css_class' => 'btn-primary',
        ),
    ),
);
