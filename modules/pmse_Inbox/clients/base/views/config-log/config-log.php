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
$viewdefs[$module_name]['base']['view']['config-log'] = array(
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'name' => 'comboLogConfig',
                    'type' => 'enum',
                    'options' => array(
                        'emergency'=>'EMERGENCY',
                        'alert'=>'ALERT',
                        'critical'=>'CRITICAL',
                        'error'=>'ERROR',
                        'warning'=>'WARNING',
                        'notice'=>'NOTICE',
                        'info'=>'INFO',
                        'debug'=>'DEBUG'
                    ),
//                    'event' => 'change:logSelect',
                    'view' => 'edit',
                )
            ),
        ),
    )
);