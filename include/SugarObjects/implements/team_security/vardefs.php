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

$vardefs = array(
  'visibility' => array('TeamSecurity' => true),
  'fields' => array(
	'team_id' =>
		array (
			'name' => 'team_id',
			'vname' => 'LBL_TEAM_ID',
			'group'=>'team_name',
			'reportable'=>false,
			'dbType' => 'id',
			'type' => 'team_list',
			'audited'=>true,
			/*
			'source' => 'non-db',
			*/
            'duplicate_on_record_copy' => 'always',
            'comment' => 'Team ID for the account'
		),
		'team_set_id' =>
		array (
			'name' => 'team_set_id',
			'rname' => 'id',
			'id_name' => 'team_set_id',
			'vname' => 'LBL_TEAM_SET_ID',
			'type' => 'id',
		    'audited' => true,
		    'studio' => 'false',
			'dbType' => 'id',
            'duplicate_on_record_copy' => 'always',
        ),
        'acl_team_set_id' => array(
            'name' => 'acl_team_set_id',
            'vname' => 'LBL_TEAM_SET_SELECTED_ID',
            'type' => 'id',
            'audited' => true,
            'studio' => false,
            'isnull' => true,
            'duplicate_on_record_copy' => 'always',
        ),
		'team_count' =>
		array (
			'name' => 'team_count',
			'rname' => 'team_count',
			'id_name' => 'team_id',
			'vname' => 'LBL_TEAMS',
			'join_name'=>'ts1',
			'table' => 'team_sets',
			'type' => 'relate',
            'required' => 'true',
			'table' => 'teams',
			'isnull' => 'true',
			'module' => 'Teams',
			'link' => 'team_count_link',
			'massupdate' => false,
			'dbType' => 'int',
			'source' => 'non-db',
			'importable' => 'false',
			'reportable'=>false,
		    'duplicate_merge' => 'disabled',
            'duplicate_on_record_copy' => 'always',
            'studio' => 'false',
		    'hideacl' => true,
		),
		'team_name' =>
		array (
			'name' => 'team_name',
			'db_concat_fields'=> array(0=>'name', 1=>'name_2'),
		    'sort_on' => 'tj.name',
		    'join_name' => 'tj',
			'rname' => 'name',
			'id_name' => 'team_id',
			'vname' => 'LBL_TEAMS',
			'type' => 'relate',
            'required' => 'true',
			'table' => 'teams',
			'isnull' => 'true',
			'module' => 'Teams',
			'link' => 'team_link',
			'massupdate' => true,
			'dbType' => 'varchar',
			'source' => 'non-db',
			'len' => 36,
			'custom_type' => 'teamset',
            'studio' => array(
                   // Bug 56832 - Exclude list/detail/edit view from portal
                   'portallistview' => false,
                   'portalrecordview' => false,
               ), // don't show in studio fields list
            'duplicate_on_record_copy' => 'always',
            'exportable'=>true,
			// Until the same control is used for selecting.
            'fields' => array('acl_team_set_id'),
        ),
	  // handled by SugarFieldTeamset
        'acl_team_names' => array(
          'name' => 'acl_team_names',
          // Prevent a notice during import.
          'table' => 'teams',
          // Field is not a 'relationship_info', so needs 'module' & 'id_name' like other relate fields.
          'module' => 'Teams',
          'vname' => 'LBL_TEAM_SET_SELECTED_TEAMS',
          // Or db_concat_fields.
          'rname' => 'name',
          // Readable representation for export.
          'id_name' => 'acl_team_set_id',
          'source' => 'non-db',
          // Relate is the only way to export non-db field, link value is replaced in getExportContentFromResult()
          // value doesn't matter but "many" type is filtered by create_export_query().
          'link' => 'team_link',
          // On client equals "teamset", see "hacks.js".
          'type' => 'relate',
          // To replace an exported value in getExportContentFromResult()
          'custom_type' => 'teamset',
          'exportable' => true,
          'studio' => false,
          'massupdate' => false,
          'hideacl' => true,
      ),
		'team_link' =>
	    array (
	      'name' => 'team_link',
	      'type' => 'link',
	      'relationship' => strtolower($module). '_team',
	      'vname' => 'LBL_TEAMS_LINK',
	      'link_type' => 'one',
	      'module' => 'Teams',
	      'bean_name' => 'Team',
	      'source' => 'non-db',
	      'duplicate_merge' => 'disabled',
	      'studio' => 'false',
            'side' => 'right',
        ),
	    'team_count_link' =>
  			array (
  			'name' => 'team_count_link',
    		'type' => 'link',
    		'relationship' => strtolower($module).'_team_count_relationship',
            'link_type' => 'one',
		    'module' => 'Teams',
		    'bean_name' => 'TeamSet',
		    'source' => 'non-db',
		    'duplicate_merge' => 'disabled',
  			'reportable'=>false,
  			'studio' => 'false',
            'side' => 'right',
            ),
  		'teams' =>
		array (
		'name' => 'teams',
        'type' => 'link',
		'relationship' => strtolower($module).'_teams',
		'bean_filter_field' => 'team_set_id',
		'rhs_key_override' => true,
        'source' => 'non-db',
		'vname' => 'LBL_TEAMS',
		'link_class' => 'TeamSetLink',
		'link_file' => 'modules/Teams/TeamSetLink.php',
		'studio' => 'false',
		'reportable'=>false,
            'side' => 'left',
        ),
),

'relationships'=>array(
	strtolower($module).'_team_count_relationship' =>
		 array(
		 	'lhs_module'=> 'Teams',
		 	'lhs_table'=> 'team_sets',
		 	'lhs_key' => 'id',
    		'rhs_module'=> $module,
    		'rhs_table'=> $table_name,
    		'rhs_key' => 'team_set_id',
   			'relationship_type'=>'one-to-many'
		 ),
	strtolower($module).'_teams' =>
		array (
			'lhs_module'        => $module,
            'lhs_table'         => $table_name,
            'lhs_key'           => 'team_set_id',
            'rhs_module'        => 'Teams',
            'rhs_table'         => 'teams',
            'rhs_key'           => 'id',
            'relationship_type' => 'many-to-many',
            'join_table'        => 'team_sets_teams',
            'join_key_lhs'      => 'team_set_id',
            'join_key_rhs'      => 'team_id',
		),
   strtolower($module). '_team' =>
   array('lhs_module'=> 'Teams', 'lhs_table'=> 'teams', 'lhs_key' => 'id',
    'rhs_module'=> $module, 'rhs_table'=> $table_name, 'rhs_key' => 'team_id',
   'relationship_type'=>'one-to-many'),
),
'indices' => array(
		'team_set_'.strtolower($table_name) => array(
			'name' => 'idx_'.strtolower($table_name).'_tmst_id',
			'type' => 'index',
			'fields' => array('team_set_id')
		),
	)
);
if (TeamBasedACLConfigurator::isEnabledForModule($module)) {
    $vardefs['acls']['SugarACLTeamBased'] = true;
}
