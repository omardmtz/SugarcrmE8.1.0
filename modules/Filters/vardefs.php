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

$dictionary['Filters'] = array(
    'table' => 'filters',
    'duplicate_merge' => true,
    'fields' => array(
        'filter_definition' => array(
            'required' => true,
            'name' => 'filter_definition',
            'vname' => 'LBL_FILTER_DEFINITION',
            'dbType' => 'longtext',
            'type' => 'json',
            'massupdate' => 0,
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
            'studio' => 'visible',
        ),
        'filter_template' => array(
            'required' => true,
            'name' => 'filter_template',
            'vname' => 'LBL_FILTER_TEMPLATE',
            'dbType' => 'longtext',
            'type' => 'json',
            'massupdate' => 0,
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
            'studio' => 'visible',
        ),
        'module_name' => array(
            'required' => true,
            'name' => 'module_name',
            'vname' => 'LBL_MODULE_NAME',
            'dbType' => 'varchar',
            'len' => 100,
            'type' => 'text',
            'massupdate' => 0,
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
        ),
    ),
    'relationships' => array(),
    'acls' => array('SugarACLFilters' => true, 'SugarACLStatic' => false),
    'optimistic_locking' => true,
    // @TODO Fix the Default and Basic SugarObject templates so that Basic
    // implements Default. This would allow the application of various
    // implementations on Basic without forcing Default to have those so that
    // situations like this - implementing taggable - doesn't have to apply to
    // EVERYTHING. Since there is no distinction between basic and default for
    // sugar objects templates yet, we need to forecefully remove the taggable
    // implementation fields. Once there is a separation of default and basic
    // templates we can safely remove these as this module will implement
    // default instead of basic.
    'ignore_templates' => array(
        'taggable',
    ),
);

if (!class_exists('VardefManager')) {
}

VardefManager::createVardef(
    'Filters',
    'Filters',
    array('basic', 'team_security', 'assignable')
);
