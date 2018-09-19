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

///////////////////////////////////////////////////////////////////////////////
////	LOCAL UTILITY
function compare($table_name, $db_indexes, $var_indexes) {
	global $add_index, $drop_index, $change_index;
	if(empty($change_index))$change_index = array();
	foreach ($var_indexes as $var_i_name=>$var_i_def) {
		//find corresponding db index with same name
		//else by columns in the index.
		$sel_db_index = null;
		$var_fields_string ='';
		if(count($var_i_def['fields'])>0)
			$var_fields_string = implode('',$var_i_def['fields']);
		$field_list_match = false;
		if(isset($db_indexes[$var_i_name])) {
			$sel_db_index = $db_indexes[$var_i_name];
			$db_fields_string = implode('', $db_indexes[$var_i_name]['fields']);
			if(strcasecmp($var_fields_string, $db_fields_string)==0) {
				$field_list_match=true;
			}
		} else {
			//search by column list.
			foreach ($db_indexes as $db_i_name=>$db_i_def) {
				$db_fields_string=implode('',$db_i_def['fields']);
				if(strcasecmp($var_fields_string , $db_fields_string)==0) {
					$sel_db_index=$db_indexes[$db_i_name];
					$field_list_match=true;
					break;
				}
			}
		}

		//no matching index in database.
		if(empty($sel_db_index)) {
			$add_index[]=$GLOBALS['db']->add_drop_constraint($table_name,$var_i_def);
			continue;
		}
		if(!$field_list_match) {
			//drop the db index and create new index based on vardef
			$drop_index[]=$GLOBALS['db']->add_drop_constraint($table_name,$sel_db_index,true);
			$add_index[]=$GLOBALS['db']->add_drop_constraint($table_name,$var_i_def);
			continue;
		}
		//check for name match.
		//it should not occur for indexes of type primary or unique.
		if( $var_i_def['type'] != 'primary' and $var_i_def['type'] != 'unique' and $var_i_def['name'] != $sel_db_index['name']) {
			//rename index.
			$rename=$GLOBALS['db']->renameIndexDefs($sel_db_index,$var_i_def,$table_name);
			if(is_array($rename)) {
				$change_index=array_merge($change_index,$rename);
			} else {
				$change_index[]=$rename;
			}
			continue;
		}
	}
}
////	END LOCAL UTILITY
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	PROCESS
if(!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
set_time_limit(3600);
/**
 * Note: $_REQUEST['silent'] is set from ModuleInstaller::repair_indices();
 */
if(!isset($_REQUEST['silent'])) $_REQUEST['silent'] = false;

$add_index=array();
$drop_index=array();
$change_index=array();

global $current_user, $beanFiles, $dictionary, $sugar_config, $mod_strings;;
include_once ('include/database/DBManager.php');

$db = DBManagerFactory::getInstance();
$processed_tables=array();

///////////////////////////////////////////////////////////////////////////////
////	PROCESS MODULE BEANS
(function_exists('logThis')) ? logThis("found ".count($beanFiles)." Beans to process") : "";
(function_exists('logThis')) ? logThis("found ".count($dictionary)." Dictionary entries to process") : "";

foreach ($beanFiles as $beanname=>$beanpath) {
	require_once($beanpath);
	$focus = BeanFactory::newBeanByName($beanname);

	//skips beans based on same tables. user, employee and group are an example.
	if(empty($focus->table_name) || isset($processed_tables[$focus->table_name])) {
		continue;
	} else {
		$processed_tables[$focus->table_name]=$focus->table_name;
	}

	if(!empty($dictionary[$focus->object_name]['indices'])) {
		$indices=$dictionary[$focus->object_name]['indices'];
	} else {
		$indices=array();
	}

	//clean vardef defintions.. removed indexes not value for this dbtype.
	//set index name as the key.
	$var_indices=array();
	foreach ($indices as $definition) {
		//database helpers do not know how to handle full text indices
		if ($definition['type']=='fulltext') {
			continue;
		}

		if(empty($definition['db']) or $definition['db'] == $focus->db->dbType) {
			$var_indices[$definition['name']] = $definition;
		}
	}

	$db_indices=$focus->db->get_indices($focus->table_name);
	compare($focus->table_name,$db_indices,$var_indices);
}
////	END PROCESS MODULE BEANS
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	PROCESS RELATIONSHIP METADATA - run thru many to many relationship files too...
include('modules/TableDictionary.php');
foreach ($dictionary as $rel=>$rel_def) {
	if(!empty($rel_def['indices'])) {
		$indices=$rel_def['indices'];
	} else {
		$indices=array();
	}

	//clean vardef defintions.. removed indexes not value for this dbtype.
	//set index name as the key.
	$var_indices=array();
	foreach ($indices as $definition) {
		if(empty($definition['db']) or $definition['db'] == $focus->db->dbType) {
			$var_indices[$definition['name']] = $definition;
		}
	}

	$db_indices=$focus->db->get_indices($rel_def['table']);

	compare($rel_def['table'],$db_indices,$var_indices);
}
////	END PROCESS RELATIONSHIP METADATA
///////////////////////////////////////////////////////////////////////////////


(function_exists('logThis')) ? logThis("RepairIndex: we have ".count($drop_index)." indices to DROP.") : "";
(function_exists('logThis')) ? logThis("RepairIndex: we have ".count($add_index)." indices to ADD.") : "";
(function_exists('logThis')) ? logThis("RepairIndex: we have ".count($change_index)." indices to CHANGE.") : "";

if((count($drop_index) > 0 or count($add_index) > 0 or count($change_index) > 0)) {
	if(!isset($_REQUEST['mode']) or $_REQUEST['mode'] != 'execute') {
		echo ($_REQUEST['silent']) ? "" : "<BR><BR><BR>";
		echo ($_REQUEST['silent']) ? "" : "<a href='index.php?module=Administration&action=RepairIndex&mode=execute'>Execute Script</a>";
	}

	$focus = BeanFactory::newBean('Accounts');
	if(count($drop_index) > 0) {
		if(isset($_REQUEST['mode']) and $_REQUEST['mode']=='execute') {
			echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_DROPPING'];
			foreach ($drop_index as $statement) {
				echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_EXECUTING'].$statement;
				(function_exists('logThis')) ? logThis("RepairIndex: {$statement}") : "";
				$focus->db->query($statement);
			}
		} else {
			echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_DROP'];
			foreach ($drop_index as $statement) {
				echo ($_REQUEST['silent']) ? "" : "<BR>".$statement.";";
			}
		}
	}

	if(count($add_index) > 0) {
		if(isset($_REQUEST['mode']) and $_REQUEST['mode']=='execute') {
			echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_ADDING'];
			foreach ($add_index as $statement) {
				echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_EXECUTING'].$statement;
				(function_exists('logThis')) ? logThis("RepairIndex: {$statement}") : "";
				$focus->db->query($statement);
			}
		} else {
			echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_ADD'];
			foreach ($add_index as $statement) {
				echo ($_REQUEST['silent']) ? "" : "<BR>".$statement.";";
			}
		}
	}
	if(count($change_index) > 0) {
		if(isset($_REQUEST['mode']) and $_REQUEST['mode']=='execute') {
			echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_ALTERING'];
			foreach ($change_index as $statement) {
				echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_EXECUTING'].$statement;
				(function_exists('logThis')) ? logThis("RepairIndex: {$statement}") : "";
				$focus->db->query($statement);
			}
		} else {
			echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_ALTER'];
			foreach ($change_index as $statement) {
				echo ($_REQUEST['silent']) ? "" : "<BR>".$statement.";";
			}
		}
	}

	if(!isset($_REQUEST['mode']) or $_REQUEST['mode'] != 'execute') {
		echo ($_REQUEST['silent']) ? "" : "<BR><BR><BR>";
		echo ($_REQUEST['silent']) ? "" : "<a href='index.php?module=Administration&action=RepairIndex&mode=execute'>Execute Script</a>";
	}
} else {
	(function_exists('logThis')) ? logThis("RepairIndex: Index definitions are in sync.") : "";
	echo ($_REQUEST['silent']) ? "" : $mod_strings['LBL_REPAIR_INDEX_SYNC'];
}