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

$dictionary['pmse_BpmnDiagram'] = array(
    'table' => 'pmse_bpmn_diagram',
    'audited' => false,
    'activity_enabled' => false,
    'duplicate_merge' => true,
    'reassignable' => false,
    'fields' => array(
        'dia_uid' => array(
            'required' => true,
            'name' => 'dia_uid',
            'vname' => '',
            'type' => 'varchar',
            'massupdate' => false,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '36',
            'size' => '36',
        ),
        'prj_id' => array(
            'required' => true,
            'name' => 'prj_id',
            'vname' => '',
            'type' => 'varchar',
            'massupdate' => false,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '36',
            'size' => '36',
        ),
        'dia_is_closable' => array(
            'required' => true,
            'name' => 'dia_is_closable',
            'vname' => '',
            'type' => 'int',
            'massupdate' => false,
            'default' => '0',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '4',
            'size' => '20',
            'enable_range_search' => false,
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
    ),
    'relationships' => array(),
    'optimistic_locking' => true,
    'unified_search' => true,
    'ignore_templates' => array(
        'taggable',
        'lockable_fields',
    ),
    'uses' => array(
        'basic',
        'assignable',
    ),
);

VardefManager::createVardef('pmse_BpmnDiagram', 'pmse_BpmnDiagram');
