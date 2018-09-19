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

 // $Id: trigger_utils.php 51719 2009-10-22 17:18:00Z mitani $
// $Id: trigger_utils.php 51719 2009-10-22 17:18:00Z mitani $

include_once('include/workflow/workflow_utils.php');
include_once('include/utils/expression_utils.php');

function get_trigger_count_bool($focus, $target_count_array){

	$count_value = get_trigger_count($focus, $target_count_array);

	$translated_operator = translate_operator($target_count_array['base']['operator']);

	//is this a new record?
	if($focus->fetched_row['id']==""){
	$count_value = $count_value + 1;	
	}	
	//echo $count_value ."".$translated_operator."".$target_count_array['base']['rhs_value'];

	return compare_values_number($count_value, $translated_operator, $target_count_array['base']['rhs_value']);
	
//end function get_trigger_count
}	

function get_trigger_count(&$focus, $target_count_array){
	global $beanList;
	
	//Check to see if we need to start with a related module
	if($target_count_array['base']['lhs_type'] == "rel_module"){
		
		///Build the relationship information using the Relationship handler
		$rel_handler = & $focus->call_relationship_handler("module_dir", true);
		$rel_handler->set_rel_vardef_fields($target_count_array['base']['lhs_module']);
		$rel_handler->build_info(true);
		$rel_handler->set_rel_relationship_names();

	
		//If this has related module and this is a new record, we have to do something special here
		//
		if(empty($focus->fetched_row['id'])){
			
			$temp_module = & $rel_handler->rel1_bean;
			$target_id = "";

			foreach($focus->relationship_fields as $key => $value){
				if(!empty($value) && $value == $rel_handler->base_vardef_field){
					$target_id = $key;	
				}	
			//end if rel name match		
			}

			if($target_id!=""){		
			$temp_module->retrieve($focus->$target_id);
				$rel_object = $temp_module;
			} else {
				
				$rel_object = "";	
			}	
			
		} else {
			//Coming from update, so proceed as normal	
			$rel_module_list = $rel_handler->build_related_list("base");
			if(!empty($rel_module_list[0])){
				$rel_object= $rel_module_list[0];
			} else {
				$rel_object = "";	
			}	
		}
		

		//if there is an empty list than just return 0
		if(isset($rel_object) && $rel_object!='') {
			//this is trying to obtain the $rel1_vardef_field_base
			$rel_handler->get_rel1_vardef_field_base($rel_object->field_defs);
	
		//compensate for cases	
			
			//use the first related object.  
			$target_list = $rel_object->get_linked_beans($rel_handler->rel1_vardef_field_base, $focus->object_name);
			
				if(isset($target_list[0]) && $target_list[0]!='') {
				//is filter 1 present?
				if(!empty($target_count_array['filter1'])){
					$target_list = setup_filter_records($target_list, $target_count_array['filter1']);
				//end filter1
				}		
				//is filter 2 present?
				if(!empty($target_count_array['filter2'])){
					$target_list = setup_filter_records($target_list, $target_count_array['filter2']);				
				//end filter2		
				}				
				return count($target_list);
			} else {
				//count is zero
				return "0";	
			}	
			
		//end if there is an empty rel_module_list
		}		

	//end if the lhs_type is rel_module
	} else {
			
		return build_count_query($focus, $target_count_array);
			
	//end if else base_module as lhs_type
	}		
	
//end function get_trigger_count
}	


function build_count_query(& $focus, $target_count_array){
	
	$select = "SELECT count(*) total_count";
	$from = "FROM ".$focus->table_name." WHERE";
	$where = "";
	
		if(!empty($target_count_array['filter1'])){
			
			$where .= build_filter_where($focus->table_name, $where, $target_count_array['filter1']);
		//end filter1
		}		
		
		//is filter 2 present?
		if(!empty($target_count_array['filter2'])){
			
			$where .= build_filter_where($focus->table_name, $where, $target_count_array['filter1']);	
		//end filter2		
		}	
		
		
	//add the delete part
	if($where!=""){
		$where .= " AND ".$focus->table_name.".deleted='0'";
	} else {
		$where .= " ".$focus->table_name.".deleted='0'";
	}		
	
	$query = $select." ".$from." ".$where;
	
	//execute the query and return the row total_count value
	$result = $focus->db->query($query,true," Error getting total count");
	$row  =  $focus->db->fetchByAssoc($result);
		if ($row != null) {
			return $row['total_count'];		
		}
		
		return "0";
		
//end function build_count_query
}

function check_rel_filter(& $focus, $secondary_array, $relationship_name, $rel_filter_array, $rel_module_type="any"){
	
	global $beanList;
	$relationship_name = strtolower($relationship_name);
	//list is already cached from a prior secondary trigger filter
	if(!empty($secondary_array['cached_lists'][$relationship_name])){
	
		$target_array = & $rel_filter_array['base'];
		$target_list = $secondary_array['cached_lists'][$relationship_name];

	} else {
	//create new list	
		$target_array = & $rel_filter_array['base'];
		
		$target_list = $focus->get_linked_beans($relationship_name, $beanList[$target_array['lhs_module']]);
		
		//Possibility exists that this is a new relationship, so capture via relationship fields
		if(empty($target_list)){
			$target_list = search_filter_rel_info($focus, $target_array['lhs_module'], $relationship_name);
		//end if the target list is empty	
		}	
	}		

	$all_count = count($target_list);

	$filtered_rel_list =  setup_filter_records($target_list, $target_array);
	
	if($rel_module_type=="all"){
		
		//compares before count and after count to makes sure 'all' records remained and none were filtered
		if(count($filtered_rel_list)==$all_count){
			$secondary_array['results'] = true;
			$secondary_array['cached_lists'][$relationship_name] = $filtered_rel_list;	
		} else {
			$secondary_array['results'] = false;
			$secondary_array['cached_lists'][$relationship_name] = $filtered_rel_list;	
		}			
	
	
	
	//if rel_module_type is all
	}	
	
	if($rel_module_type=="any"){
		if(!empty($filtered_rel_list)){
			$secondary_array['results'] = true;
			$secondary_array['cached_lists'][$relationship_name] = $filtered_rel_list;	
		} else {
			$secondary_array['results'] = false;
			$secondary_array['cached_lists'][$relationship_name] = $filtered_rel_list;	
		}		
	
	//if rel_module_type is any
	}	
		
	return $secondary_array;
	
//end function check_rel_filter	
}	




?>
