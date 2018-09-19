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
$dictionary['SavedReport'] = array ( 'table' => 'saved_reports',
    'visibility' => array('ReportVisibility' => true),
    'favorites'=>true,
    'full_text_search' => false,
    'fields' => array (
  'module' =>
  array (
    'name' => 'module',
    'vname' => 'LBL_MODULE',
    'type' => 'enum',
    'function' => 'getModulesDropdown',
    'required' => true,
      'massupdate' => false,
  ),
  'report_type' =>
  array (
  	'name' => 'report_type',
    'vname' => 'LBL_REPORT_TYPE',
    'type' => 'enum',
    'options' => 'dom_report_types',
    'required' => true,
      'massupdate' => false,
  ),
  'content' =>
  array (
  	'name' => 'content',
    'vname' => 'LBL_CONTENT',
    'type' => 'longtext',
  ),
  'is_published' =>
  array (
  	'name' => 'is_published',
    'vname' => 'LBL_IS_PUBLISHED',
    'type' => 'bool',
    'default'=>0,
    'required'=>true,
      'massupdate' => false,
  ),
  'last_run_date' => array(
    'name' => 'last_run_date',
    'id_name' => 'report_cache_id',
    'vname' => 'LBL_REPORT_LAST_RUN_DATE',
    'type' => 'datetime',
    'table' => 'report_cache',
    'isnull' => 'true',
    'module' => 'Reports',
    'reportable' => false,
    'source' => 'non-db',
    'massupdate' => false,
    'duplicate_merge' => 'disabled',
    'hideacl' => true,
    'width' => '15',
      'link' => 'last_run_date_link',
      'rname_link' => 'date_modified',
  ),
        'last_run_date_link' => array(
            'name' => 'last_run_date_link',
            'type' => 'link',
            'relationship' => 'reports_last_run_date',
            'source' => 'non-db',
            'vname' => 'LBL_REPORT_LAST_RUN_DATE',
            'reportable' => false,
            'primary_only' => true,
            'link_type' => 'one',
        ),
  'report_cache_id' => array(
    'name' => 'report_cache_id',
    'rname' => 'id',
    'id_name' => 'report_cache_id',
    'vname' => 'LBL_REPORT_CACHE_ID',
    'type' => 'relate',
    'dbType' => 'id',
    'table' => 'report_cache',
    'isnull' => 'true',
    'module' => 'Reports',
    'reportable' => false,
    'source' => 'non-db',
    'massupdate' => false,
    'duplicate_merge' => 'disabled',
    'hideacl' => true,
    'studio' => false,
  ),
  'chart_type' =>
  array (
  	'name' => 'chart_type',
    'vname' => 'LBL_CHART_TYPE',
    'type' => 'varchar',
    'required'=>true,
    'default'=>'none',
    'len' => 36
  ),
    'schedule_type' =>
  array (
  	'name' => 'schedule_type',
    'vname' => 'LBL_SCHEDULE_TYPE',
    'type' => 'varchar',
    'len'=>'3',
    'default'=>'pro',
  ),
 'favorite' =>
  array (
    'name' => 'favorite',
    'vname' => 'LBL_FAVORITE',
    'type' => 'bool',
    'required' => false,
    'reportable' => false,
        'massupdate' => false,
  ),
        'next_run' => array(
            'name' => 'next_run',
            'id_name' => 'report_id',
            'link' => 'next_run_link',
            'table' => 'report_schedules',
            'vname' => 'LBL_SCHEDULE_REPORT',
            'type' => 'datetime',
            'source' => 'non-db',
            'rname_link' => 'next_run',
            'massupdate' => false,
            'reportable' => false,
        ),
        'next_run_link' => array(
            'name' => 'next_run_link',
            'type' => 'link',
            'relationship' => 'reports_next_run',
            'source' => 'non-db',
            'vname' => 'LBL_SCHEDULE_REPORT',
            'primary_only' => true,
        ),

),
'indices' => array (
    'idx_savedreport_module' => array(
        'name' => 'idx_savedreport_module',
        'type' => 'index',
        'fields' => array('module'),
    ),
),
'relationships'=>array(
    'reports_last_run_date' => array(
        'lhs_module' => 'Reports',
        'lhs_table' => 'saved_reports',
        'lhs_key' => 'id',
        'rhs_module' => 'Users',
        'rhs_table' => 'users',
        'rhs_key' => 'id',
        'join_table' => 'report_cache',
        'join_key_lhs' => 'id',
        'join_key_rhs' => 'assigned_user_id',
        'relationship_type' => 'user-based',
        'user_field' => 'assigned_user_id',
    ),
    'reports_next_run' => array(
        'lhs_module' => 'Reports',
        'lhs_table' => 'saved_reports',
        'lhs_key' => 'id',
        'rhs_module' => 'Users',
        'rhs_table' => 'users',
        'rhs_key' => 'id',
        'join_table' => 'report_schedules',
        'join_key_lhs' => 'report_id',
        'join_key_rhs' => 'user_id',
        'relationship_type' => 'user-based',
        'user_field' => 'user_id',
        'relationship_role_column' => 'active',
        'relationship_role_column_value' => '1',
    ),
   ),
    'uses' => array(
        'basic',
        'assignable',
        'team_security',
    ),
    'ignore_templates' => array(
        'following',
        'lockable_fields',
    ),
);

VardefManager::createVardef('Reports', 'SavedReport');

// to override field attributes
$dictionary['SavedReport']['fields']['id']['reportable'] = false;
$dictionary['SavedReport']['fields']['modified_user_id']['reportable'] = false;
$dictionary['SavedReport']['fields']['date_entered']['required'] = true;
$dictionary['SavedReport']['fields']['date_modified']['required'] = true;
$dictionary['SavedReport']['fields']['assigned_user_id']['reportable'] = false;
