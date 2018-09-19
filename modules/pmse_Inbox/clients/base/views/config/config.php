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
$viewdefs[$module_name]['base']['view']['config'] = array(
    'panels' => array(
        array(
//            'columns' => 2,
            'fields' => array(
//                array(
//                    'name' => 'logger_level',
//                    'label'=>'LBL_PMSE_SETTING_LOG_LEVEL',
////                    'description'=>'LBL_PMSE_SETTING_NUMBER_CYCLES',
//                    'type' => 'enum',
//                    'width' => 15,
//                    'options' => array(
//                        'emergency'=>'EMERGENCY',
//                        'alert'=>'ALERT',
//                        'critical'=>'CRITICAL',
//                        'error'=>'ERROR',
//                        'warning'=>'WARNING',
//                        'notice'=>'NOTICE',
//                        'info'=>'INFO',
//                        'debug'=>'DEBUG'
//                    ),
////                    'event' => 'change:logSelect',
//                    'view' => 'edit',
//                    'required'=>true,
//                ),
                array(
                    'name' => 'error_number_of_cycles',
                    'label'=>'LBL_PMSE_SETTING_NUMBER_CYCLES',
//                    'description'=>'LBL_PMSE_SETTING_NUMBER_CYCLES',
                    'type' => 'int',
//                    'rows'=>'10',
//                    'cols'=>'15',
                    'view' => 'edit',
                    'required'=>true,
                ),
//                array(
//                    'name' => 'error_timeout',
//                    'label'=>'LBL_PMSE_SETTING_TIMEOUT',
////                    'description'=>'LBL_PMSE_SETTING_TIMEOUT',
//                    'type' => 'int',
////                    'rows'=>'10',
////                    'cols'=>'15',
//                    'view' => 'edit',
//                    'required'=>true,
//                )
            ),
        ),
    )
);