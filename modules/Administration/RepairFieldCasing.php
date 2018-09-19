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
//Check if current user has admin access
if(is_admin($current_user)) {
    global $mod_strings;

    //echo out processing message
    echo '<br>'.$mod_strings['LBL_REPAIR_FIELD_CASING_PROCESSING'];

    //store the affected entries
    $database_entries = array();
    $module_entries = array();

    $query = "SELECT * FROM fields_meta_data";
    $result = $GLOBALS['db']->query($query);
    while($row = $GLOBALS['db']->fetchByAssoc($result)) {
    	  $name = $row['name'];
    	  $id = $row['id'];
    	  $module_entries[$row['custom_module']] = true;

    	  //Only run database SQL where the name or id casing does is not lowercased
    	  if($name != strtolower($row['name'])) {
    	  	 $database_entries[$row['custom_module']][$name] = $row;
    	  }
    }

    //If we have database entries to process
    if(!empty($database_entries)) {

       foreach($database_entries as $module=>$entries) {
       	   $table_name = strtolower($module) . '_cstm';

           foreach($entries as $original_col_name=>$entry) {
               echo '<br>'. string_format($mod_strings['LBL_REPAIR_FIELD_CASING_SQL_FIELD_META_DATA'], array($entry['name']));
           	   $update_sql = "UPDATE fields_meta_data SET id = '" . $entry['custom_module'] . strtolower($entry['name']) . "', name = '" . strtolower($entry['name']) . "' WHERE id = '" . $entry['id'] . "'";
           	   $GLOBALS['db']->query($update_sql);

           	   echo '<br>'. string_format($mod_strings['LBL_REPAIR_FIELD_CASING_SQL_CUSTOM_TABLE'], array($entry['name'], $table_name));

      		   $GLOBALS['db']->query($GLOBALS['db']->renameColumnSQL($table_name, $entry['name'], strtolower($entry['name'])));
           }
       }
    }

    //If we have metadata files to alter
    if(!empty($module_entries)) {
	    $modules = array_keys($module_entries);
	    $views = array('basic_search', 'advanced_search', 'detailview', 'editview', 'quickcreate');
	    $class_names = array();


	    foreach($modules as $module) {
	       if(isset($GLOBALS['beanList'][$module])) {
	       	  $class_names[] = $GLOBALS['beanList'][$module];
	       }

	       $repairClass->module_list[] = $module;
	       foreach($views as $view) {
                try{
                    $parser = ParserFactory::getParser($view, $module);
                }
                catch(Exception $e){
                    $GLOBALS['log']->fatal("Caught exception in RepairFieldCasing script: ".$e->getMessage());
                    continue;
                }
	       		if(isset($parser->_viewdefs['panels'])) {
	       		   foreach($parser->_viewdefs['panels'] as $panel_id=>$panel) {
	       		   	  foreach($panel as $row_id=>$row) {
	       		   	  	 foreach($row as $entry_id=>$entry) {
	       		   	  	 	if(is_array($entry) && isset($entry['name'])) {
	       		   	  	 	   $parser->_viewdefs['panels'][$panel_id][$row_id][$entry_id]['name'] = strtolower($entry['name']);
	       		   	  	 	}
	       		   	  	 }
	       		   	  }
	       		   }
	       		} else {
	       		  //For basic_search and advanced_search views, just process the fields
       		   	  foreach($parser->_viewdefs as $entry_id=>$entry) {
       		   	  	 if(is_array($entry) && isset($entry['name'])) {
       		   	  	 	$parser->_viewdefs[$entry_id]['name'] = strtolower($entry['name']);
       		   	  	 }
       		   	  }
	       		}

	       		//Save the changes
	       		$parser->handleSave(false);
	       } //foreach

	       //Now clear the cache of the .tpl files
	       TemplateHandler::clearCache($module);


	    } //foreach

	    echo '<br>'.$mod_strings['LBL_CLEAR_VARDEFS_DATA_CACHE_TITLE'];
        $repair = new RepairAndClear();
        $repair->show_output = false;
        $repair->module_list = array($class_names);
        $repair->clearVardefs();
    }

    echo '<br>'.$mod_strings['LBL_DIAGNOSTIC_DONE'];

}
?>
