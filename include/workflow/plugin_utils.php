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

// $Id: plugin_utils.php 51719 2009-10-22 17:18:00Z mitani $

require_once('include/workflow/workflow_utils.php');
	//workflow plugin utility functions

class SugarWorkflowPluginList
{
    public static $list;
    static function getList() {
        if(is_null(self::$list)) {
            if(SugarAutoLoader::existing('custom/workflow/plugins/plugin_list.php')) {
                require 'custom/workflow/plugins/plugin_list.php';
                self::$list = $plugin_list;
            } else {
                self::$list = array();
            }
        }
        return self::$list;
    }
}

	function trigger_createstep1(& $opt_array)
	{
		global $process_dictionary;
		global $mod_strings;


		//check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
		    return;
		}

		//for several javascript items on createstep1.html
		$jscript_array = array();
		$jscript_array['jscript_part1'] = "";
		$jscript_array['jscript_part2'] = "";

		//foreach array
		if(!empty($plugin_list['trigger']['createstep1'])){
			foreach($plugin_list['trigger']['createstep1'] as $plugin_array){
				//check to see if plugin list exists
				foreach(SugarAutoLoader::existing('custom/workflow/plugins/'.$plugin_array["directory"].'/'.$plugin_array['meta_file'].'.php') as $file){
					require_once $file;
				}

				//get the javascript handling
				if(!empty($plugin_array["directory"])){

					$plugin_array['function'] = $plugin_array["jscript_function"];

					$return_jscript_array = grab_plugin_function($plugin_array, $opt);

					$jscript_array['jscript_part1'] .= $return_jscript_array['jscript_part1'];
					$jscript_array['jscript_part2'] .= $return_jscript_array['jscript_part2'];
					//end if the type of triggershell is present
				}

			//end foreach plugin
			}
		}

		$final_jscript_array = array();
		$final_jscript_array['jscript_part1'] = $jscript_array['jscript_part1'];
		$final_jscript_array['jscript_part2'] = $jscript_array['jscript_part2'];

		return $final_jscript_array;

	//end function trigger_createstep1
	}

	function trigger_listview(& $opt){

		global $process_dictionary;
		global $mod_strings;

		//check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		//foreach array
		if(!empty($plugin_list['trigger']['listview'][$opt->type])){

			$plugin_array = $plugin_list['trigger']['listview'][$opt->type];
			return grab_plugin_function($plugin_array, $opt);


		//end if the type of triggershell is present
		}



	//end function trigger_listview
	}


	function trigger_glue(& $opt){
		global $process_dictionary;
		global $mod_strings;

			//check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		if($opt['trigger_position'] == "Primary"){
			$type = "trigger_type";
		} else {
			$type = "type";
		}


		if(!empty($plugin_list['trigger']['glue'][$opt['row'][$type]])){
			$plugin_array = $plugin_list['trigger']['glue'][$opt['row'][$type]];
			return grab_plugin_function($plugin_array, $opt);
		//end if the type of triggershell is present
		}

	//end function trigger_glue
	}



	function trigger_eval_dump(& $opt)
	{
		global $process_dictionary;
		global $mod_strings;

	    //check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		//foreach array
		if(!empty($plugin_list['trigger']['eval_dump'][$opt['object']->type])){
			$plugin_array = $plugin_list['trigger']['eval_dump'][$opt['object']->type];
			return grab_plugin_function($plugin_array, $opt);
		//end if the type of triggershell is present
		}

	//end function trigger_eval_dump
	}




	function grab_plugin_function($plugin_array, & $opt)
	{
		//get the plugin class and function
		foreach(SugarAutoLoader::existing('custom/workflow/plugins/'.$plugin_array["directory"].'/'.$plugin_array["file"].'.php') as $file) {
			require_once $file;
			if(class_exists($plugin_array["class"])) {
				$plugin_class = new $plugin_array["class"]();
                return $plugin_class->{$plugin_array['function']}($opt);
			}
		}
		return null;
	}


    function vardef_handler_hook(& $opt_array)
    {
		global $vardef_meta_array;
		global $mod_strings;

	    //check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		//foreach array
		if(!empty($plugin_list['vardef_handler_hook'])){
			foreach($plugin_list['vardef_handler_hook'] as $plugin_array){
				//check to see if plugin list exists
				foreach(SugarAutoLoader::existing('custom/workflow/plugins/'.$plugin_array["directory"].'/'.$plugin_array['meta_file'].'.php') as $file){
					require_once $file;
				}
			}
		}
    }

//ACTION HOOOKS
	function action_createstep1(& $opt_array)
	{
		global $process_dictionary;
		global $mod_strings;


		//check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		//for several javascript items on createstep1.html
		$jscript_array = array();
		$jscript_array['jscript_part1'] = "";
		$jscript_array['jscript_part2'] = "";
		//$opt['jscript_array'] = $jscript_array;

		if(!empty($plugin_list['action']['createstep1'])){
		//foreach array
		foreach($plugin_list['action']['createstep1'] as $plugin_array){
			//check to see if plugin list exists
			foreach(SugarAutoLoader::existing('custom/workflow/plugins/'.$plugin_array["directory"].'/'.$plugin_array['meta_file'].'.php') as $file){
				require_once $file;
			}

			//get the javascript handling
			if(!empty($plugin_array["directory"])){

			$plugin_array['function'] = $plugin_array["jscript_function"];

			$return_jscript_array = grab_plugin_function($plugin_array, $opt);

				$jscript_array['jscript_part1'] .= $return_jscript_array['jscript_part1'];
				$jscript_array['jscript_part2'] .= $return_jscript_array['jscript_part2'];


			//end if the type of triggershell is present
			}

		//end foreach plugin
		}
		}

		$final_jscript_array = array();
		$final_jscript_array['jscript_part1'] = $jscript_array['jscript_part1'];
		$final_jscript_array['jscript_part2'] = $jscript_array['jscript_part2'];

		return $final_jscript_array;

	//end function action_createstep1
	}


	function action_createstep2(& $opt)
	{
		global $process_dictionary;
		global $mod_strings;

		//check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		//foreach array
		if(!empty($plugin_list['action']['createstep2'][$opt['action_shell']->action_type])){

			$plugin_array = $plugin_list['action']['createstep2'][$opt['action_shell']->action_type];
			return grab_plugin_function($plugin_array, $opt);


		//end if the type of triggershell is present
		}



	//end function action_createstep2
	}

	function action_listview(& $opt)
	{
		global $process_dictionary;
		global $mod_strings;

		//check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		if(!empty($plugin_list['action']['listview'][$opt->action_type])) {
			$plugin_array = $plugin_list['action']['listview'][$opt->action_type];
			return grab_plugin_function($plugin_array, $opt);
		}
	}

	function action_glue(& $opt)
	{
		global $process_dictionary;
		global $mod_strings;

		//check to see if plugin list exists
		$plugin_list = SugarWorkflowPluginList::getList();
		if(empty($plugin_list)) {
			return;
		}

		if(!empty($plugin_list['action']['glue'][$opt['row']['action_type']])){
			$plugin_array = $plugin_list['action']['glue'][$opt['row']['action_type']];
			return grab_plugin_function($plugin_array, $opt);
		//end if the type of triggershell is present
		}

	//end function action_glue
	}



	function build_plugin_list()
	{
		$plugin_list_dump = extract_plugin_list();

		$file = "workflow/plugins/plugin_list.php";
		$file = create_custom_directory($file);
		write_array_to_file('plugin_list', $plugin_list_dump, $file);
	}


	function extract_plugin_list()
	{
		$component_arrays = array();

		foreach(SugarAutoLoader::getDirFiles("custom/workflow/plugins", true) as $file) {
		    foreach(SugarAutoLoader::existing("$file/component_list.php") as $comp_file) {
		        include $comp_file;
                	//bug 62487  - Corrects the array key that is used in custom/workflow/plugins/plugin_list.php, which makes is compatible with SugarCRM 6.5.x
                	$file = basename($file);

                	//triggers
				if(!empty($component_list['trigger'])){
					foreach($component_list['trigger'] as $hook => $hook_array){
						$component_arrays['trigger'][$hook][$file] = $hook_array;
					}
				}

				//actions
				if(!empty($component_list['action'])){
					foreach($component_list['action'] as $hook => $hook_array){
						$component_arrays['action'][$hook][$file] = $hook_array;
					}
				}

				//vardef_handler hooks
				if(!empty($component_list['vardef_handler_hook'])){
					foreach($component_list['vardef_handler_hook'] as $def_field => $def_value){
						$component_arrays['vardef_handler_hook'][$file][$def_field] = $def_value;
					}
				}
		    }
		}

		return $component_arrays;
	}
