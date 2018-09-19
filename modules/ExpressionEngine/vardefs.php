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

$dictionary['Formula'] = array(
    'table' => 'formulas',
    'comment' => 'Stored formulas that can be re-used ansd referenced in SugarLogic',
    'fields' => array(
        'target_module' => array(
            'name' => 'target_module',
            'vname' => 'LBL_TARGET_MODULE',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'The target module for this formula',
            'required' => true,
        ),
        'formula' => array(
            'name' => 'formula',
            'vname' => 'LBL_FORMULA',
            'type' => 'varchar',
            'len' => '255',
            'required' => true,
        ),
        'return_type' => array(
            'name' => 'return_type',
            'vname' => 'LBL_RETURN_TYPE',
            'type' => 'varchar',
            'len' => '255',
            'required' => true,
        ),
    ),
);

VardefManager::createVardef('ExpressionEngine','Formula', array('default', 'basic'));
