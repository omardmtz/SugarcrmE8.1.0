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
require_once('modules/DynamicFields/FieldCases.php');

global $mod_strings;
$db = DBManagerFactory::getInstance();
$result = $db->query( 'SELECT * FROM fields_meta_data WHERE deleted = 0 ORDER BY custom_module');
$modules = array();
 /*
  * get the real field_meta_data
  */
 while($row = $db->fetchByAssoc($result)){
 	$the_modules = $row['custom_module'];
 	if(!isset($modules[$the_modules])){
 		$modules[$the_modules] = array();
 	}
 	$modules[$the_modules][$row['name']] = $row['name'];
 }

 $simulate = false;
 if(!isset($_REQUEST['run'])){
 	$simulate = true;
 	echo $mod_strings['LBL_SIMULATION_MODE'];
 }

 foreach($modules as $the_module=>$fields){
 	echo "<br><br>".$mod_strings['LBL_SCAN_MODULE']." $the_module <br>";
 	    $mod = BeanFactory::newBean($the_module);

		if(!$db->tableExists($mod->table_name . "_cstm")){
			$mod->custom_fields = new DynamicField();
			$mod->custom_fields->setup($mod);
		    $mod->custom_fields->createCustomTable();
		}

		$result = $db->query("DESCRIBE $mod->table_name" . "_cstm");

		while($row = $db->fetchByAssoc($result)) {
			$col = $row['Field'];
			$type = $row['Type'];
			$fieldDef = $mod->getFieldDefinition($col);
			$the_field = get_widget($fieldDef['type']);
			$the_field->set($fieldDef);

			if(!isset($fields[$col]) && $col != 'id_c'){
				if(!$simulate) {
				    $db->query("ALTER TABLE $mod->table_name" . "_cstm DROP COLUMN $col");
				}
				unset($fields[$col]);
				echo string_format($mod_strings['LBL_DROPPING_COLUMN'], array($col, $mod->table_name . "_cstm"))." $the_module<br>";
			} else {
				if($col != 'id_c') {
				    if(trim($the_field->get_db_type()) != trim($type)){
    					echo string_format($mod_strings['LBL_FIX_COLUMN_TYPE'], array($col, $type)). $the_field->get_db_type() . "<br>";
					    if(!$simulate) {
					        $db->query($the_field->get_db_modify_alter_table($mod->table_name . '_cstm'));
					    }
					}
				}
				unset($fields[$col]);
			}

		}

		echo sizeof($fields) .  $mod_strings['LBL_FIX_COLUMN_TYPE'] .  $mod->table_name . "_cstm<br>";
		foreach($fields as $field){
		    echo string_format($mod_strings['LBL_ADDING_COLUMN'], array($field)). $mod->table_name . "_cstm<br>";
			if(!$simulate){
				$the_field = $mod->getFieldDefinition($field);
				$field = get_widget($the_field['type']);
				$field->set($the_field);
				$query = $field->get_db_add_alter_table($mod->table_name . '_cstm');
				echo $query;
            	if(!empty($query)){
                	$mod->db->query($query);
            	}
			}
        }
	}


	DynamicField::deleteCache();
	echo '<br>'.$mod_strings['LBL_DONE'].'<br>';
	if($simulate){
		echo '<a href="index.php?module=Administration&action=UpgradeFields&run=true">'.$mod_strings['LBL_EXE_NON_SIM_MODE'].'</a>';
	}
