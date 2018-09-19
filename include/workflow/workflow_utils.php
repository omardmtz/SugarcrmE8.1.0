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

 // $Id: workflow_utils.php 53057 2009-12-08 01:36:37Z mitani $
function get_module_map($start_none = false){
	global $current_user;
	global $app_strings;
	global $app_list_strings;
		//$module_map = array();
		$module_map = get_workflow_admin_modules_for_user($current_user);

	if($start_none==true){
			$module_map[''] = $app_strings['LBL_PLEASE_SELECT'];
	}
	foreach($module_map as $key => $value){
		$module_map[$key] = $app_list_strings['moduleList'][$key];
	}
	asort($module_map);
	return $module_map;

//end function get_module_map
}

function get_column_select($base_module, $drop_down_module="", $exclusion_array="", $inclusion_array="", $include_none=false){
	global $beanList;
	$column_options = array();
	if(!empty($drop_down_module)){
		$column_module = $drop_down_module;
	} else {
		$column_module = $base_module;
	}

	$temp_focus = BeanFactory::newBean($column_module);
	add_to_column_select($column_options, $temp_focus, $column_module, $exclusion_array, $inclusion_array, $include_none);
	return $column_options;

	//end function get_column_select
}

function compare_type($value_array, $exclusion_array, $inclusion_array){

	if($value_array['type'] == 'id'){
		return false;
	}

	if(!empty($inclusion_array)){

		if(isset($inclusion_array[$value_array['type']])){
			$inclusion = true;
		} else {
			$inclusion = false;
		}

		if($inclusion == true){

			return true;
		} else {
			return false;
		}
	}


	if(isset($value_array['reportable'])){
		$reportable = $value_array['reportable'];
	} else {
		$reportable = "";
	}


	if(isset($exclusion_array[$value_array['type']]) && $exclusion_array[$value_array['type']]!=""){
		$exclusion = true;
	} else {
		$exclusion = false;
	}

	if(
	($reportable!==false || $value_array['type']=="team_list")
		&&
	($exclusion==false)
	){

		return true;
	} else {
		return false;
	}

//end function compare_type
}


	function add_to_column_select(& $column_options, $temp_focus, $module_name, $exclusion_array="", $inclusion_array="", $include_none=false){
		global $dictionary;
		global $current_language;
		global $app_strings;

		$temp_module_strings = return_module_language($current_language, $temp_focus->module_dir);

		$base_array = $dictionary[$temp_focus->object_name]['fields'];

		///Inclue empty none set or not
		if($include_none==true){
			$column_options[''] = 'None';
		}

		foreach($base_array as $key => $value){
			if(compare_type($value, $exclusion_array, $inclusion_array)==true){

				if(!empty($value['vname'])){
					$label_name = $value['vname'];
				} else {
					$label_name = $value['name'];
				}

				$label_name = get_label($label_name, $temp_module_strings);

				if(!empty($value['table'])){
					//Custom Field
					$column_table = $value['table'];
				} else {
					//Non-Custom Field
					$column_table = $temp_focus->table_name;
				}

					$index = $key;

				$value = $label_name;
				$column_options[$index] = $value;

			//end if field is reportable, hence trigger makable
			}

		//end foreach
		}


	//end function add_to_column_select
	}

function translate_label_from_module($target_module, $target_element){
		global $current_language;

		$temp_module = BeanFactory::newBean($target_module);
		$temp_module_strings = return_module_language($current_language, $temp_module->module_dir);
		$target_element_label = $temp_module->field_defs[$target_element]['vname'];
		return get_label($target_element_label, $temp_module_strings);

//end function translate_label_from_module
}

function translate_option_name_from_bean(& $target_bean, $target_element, $target_value){
	global $app_list_strings;
    # Bug #37487 We should use 'function' from var_def if it is specified.
    if (
        isset($target_bean->field_defs[$target_element]['function'])
        && $target_bean->field_defs[$target_element]['function'] != ''
    )
    {

        $function = $target_bean->field_defs[$target_element]['function'];

        // If we have function_bean defined, use it
        if (!empty($target_bean->field_defs[$target_element]['function_bean'])) {
            $functionBean = BeanFactory::newBean($target_bean->field_defs[$target_element]['function_bean']);
            $return = $functionBean->$function();
        } else {
            $return = $function();
        }
        
        if (isset($return[$target_value]))
        {
            return $return[$target_value];
        }
        else
        {
            return $target_value;
        }
    }
    else
    {
        $dom_name = $target_bean->field_defs[$target_element]['options'];
    }
	if($app_list_strings[$dom_name][$target_value]!=""){
		return $app_list_strings[$dom_name][$target_value];
	} else {
		return $target_value;
	}

//end function translate_option_name_from_bean
}

function translate_label_from_bean(& $target_bean, $target_element){
//use if the bean is already present
		global $current_language;
		$temp_module_strings = return_module_language($current_language, $target_bean->module_dir);
		$target_element_label = $target_bean->field_defs[$target_element]['vname'];
		return get_label($target_element_label, $temp_module_strings);

//end function translate_label_from_module
}

function translate_label($label_tag){
	global $mod_strings;
	return get_label($label_tag, $mod_strings);

}

function write_workflow(& $workflow_object){
	global $beanlist;
	$module = $workflow_object->base_module;
    if (!\BeanFactory::getBeanClass($module)) {
        throw new \RuntimeException(sprintf('Invalid module %s', $module));
    }

		$file = "modules/".$module . '/workflow/workflow.php';
		$file = create_custom_directory($file);
		$dump = $workflow_object->get_trigger_contents();
		$fp = sugar_fopen($file, 'wb');
		fwrite($fp,"<?php\n");
		fwrite($fp,'
use Sugarcrm\Sugarcrm\Util\Arrays\ArrayFunctions\ArrayFunctions;
include_once("include/workflow/alert_utils.php");
include_once("include/workflow/action_utils.php");
include_once("include/workflow/time_utils.php");
include_once("include/workflow/trigger_utils.php");
//BEGIN WFLOW PLUGINS
include_once("include/workflow/custom_utils.php");
//END WFLOW PLUGINS
	class '.$workflow_object->base_module.'_workflow {
	function process_wflow_triggers(& $focus){
		include("custom/modules/'.$module . '/workflow/triggers_array.php");
		include("custom/modules/'.$module . '/workflow/alerts_array.php");
		include("custom/modules/'.$module . '/workflow/actions_array.php");
		include("custom/modules/'.$module . '/workflow/plugins_array.php");
		'.$dump.'
	//end function process_wflow_triggers
	}

	//end class
	}
');

		fwrite($fp, "\n?>");
		fclose($fp);
//end function write_triggers
}

function process_workflow(& $focus, $logic_hook){
	//Now just include the modules workflow from this bean

		if($logic_hook=="BeforeSave") {
			foreach(SugarAutoLoader::existing("custom/modules/".$focus->module_dir."/workflow/workflow.php") as $workflow_path) {
				include_once($workflow_path);
				process_wflow_triggers($focus);
			}
		}
}

function get_field_type($field_array)
{
	//first see if this is a custom field
	if(!empty($field_array['custom_type'])) {
		return $field_array['custom_type'];
	} else {
		return $field_array['type'];
	}


//end function get_field_type
}


//Old function
function get_rel_module_name($base_module, $relationship_name, & $db){

	global $beanList;
	global $dictionary;

		$rel_bean = BeanFactory::newBean('Relationships');
		$rel_module = $rel_bean->get_other_module($relationship_name, $base_module, $db);
		return $rel_module;

//end function get_rel_module_name
}


function build_source_array($type, $field_value, $var_symbol=true){
	if($var_symbol==true){
		$var_symbol = "$";
	} else {
		$var_symbol = "";
	}

		if($type=="past"){
				return sprintf("%sfocus->fetched_row['%s']", $var_symbol, $field_value);
		}
		if($type=="future"){
				return sprintf("isset(%sfocus->%s) && %sfocus->%s", $var_symbol, $field_value, $var_symbol, $field_value);
		}

	//end function build_source_array
	}

    function write_record_type(& $eval_dump, $record_type, $row = array())
    {
		if($record_type=="All"){
			return false;
		}
		if($record_type=="Update"){
			$eval_dump .= "if(isset(\$focus->fetched_row['id']) && \$focus->fetched_row['id']!=\"\"){ \n ";
			return true;
		}
        if ($record_type=="New")
        {
            $condition = "empty(\$focus->fetched_row['id'])";
            if (isset($row['id']) && $row['id'] != false)
            {

                $condition .= ' || (!empty($_SESSION["workflow_cron"]) && $_SESSION["workflow_cron"]=="Yes" && !empty($_SESSION["workflow_id_cron"]) && '
                    . 'ArrayFunctions::in_array_access("' . $row['id'] . '", $_SESSION["workflow_id_cron"]))';
            }
            $eval_dump .= "if($condition){ \n ";
            return true;
        }

	//end function write_record_type
	}

//Plugin Function

	function get_plugin($plugin_type, $function_name, & $opt)
	{
		//eventually expand this and move the plugin_utils.php to include/plugins
		include_once('include/workflow/plugin_utils.php');

		if(!function_exists($function_name)){
			return;
		}

		return $function_name($opt);
	}
