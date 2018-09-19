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

require_once('include/workflow/workflow_utils.php');
/**
 * Vardef Handler Object
 * @api
 */
class VarDefHandler {

	var $meta_array_name;
	var $target_meta_array = false;
	var $start_none = false;
	var $extra_array = array();					//used to add custom items
	var $options_array = array();
	var $module_object;
	var $start_none_lbl = null;
    public $all_meta_array = array();

    /**
     * Method parts used in compare_value
     * @var array
     */
    protected $compareMethods = array(
        'inc_override' => 'IncOverride',
        'ex_override' => 'ExOverride',
        'inclusion' => 'Inclusion',
        'exclusion' => 'Exclusion',
    );

    /**
     * Array of arrays to check against when comparing
     * @var array
     */
    protected $checkArrays = array(
        'target_meta_array',
        'all_meta_array',
    );


    public function __construct($module, $meta_array_name = null)
    {
        $this->meta_array_name = $meta_array_name;
		$this->module_object = $module;
		if($meta_array_name!=null){
			global $vardef_meta_array;
			include("include/VarDefHandler/vardef_meta_arrays.php");
			//BEGIN WFLOW PLUGINS
			get_plugin("workflow", "vardef_handler_hook", $this);
			//END WFLOW PLUGINS
			$this->target_meta_array = $vardef_meta_array[$meta_array_name];
            if (isset($vardef_meta_array['all'])) {
                $this->all_meta_array = $vardef_meta_array['all'];
            }
		}

	//end function setup
	}

	function get_vardef_array($use_singular=false, $remove_dups = false, $use_field_name = false, $use_field_label = false, $visible_only = false, $mlink = true){
		global $dictionary;
		global $current_language;
		global $app_strings;
		global $app_list_strings;

		$temp_module_strings = return_module_language($current_language, $this->module_object->module_dir);

		$base_array = $this->module_object->field_defs;
		//$base_array = $dictionary[$this->module_object->object_name]['fields'];

		///Inclue empty none set or not
		if($this->start_none==true){
			if(!empty($this->start_none_lbl)){
				$this->options_array[''] = $this->start_none_lbl;
			} else {
				$this->options_array[''] = $app_strings['LBL_NONE'];
			}
		}

	///used for special one off items added to filter array	 ex. would be href link for alert templates
		if(!empty($this->extra_array)){

			foreach($this->extra_array as $key => $value){
				$this->options_array[$key] = $value;
			}
		}
	/////////end special one off//////////////////////////////////

		foreach($base_array as $key => $value_array){
			$compare_results = $this->compare_type($value_array);

			// Strict false check for visibility of a field based on vardef. This
			// is used for mapped fields like tag_lower
			if ($visible_only && isset($value_array['visible']) && $value_array['visible'] === false) {
				continue;
			}

			if($compare_results == true){
                $label_name = '';
                if ($value_array['type'] == 'link') {
                    $this->module_object->load_relationship($value_array['name']);
                    if (empty($this->module_object->{$value_array['name']})) {
                        $GLOBALS['log']->fatal("Failed to load relationship {$value_array['name']}");
                        continue;
                    }
                    // Exclude modules on the many side if $mlink == false
                    if (!$mlink && $this->module_object->{$value_array['name']}->getType() === 'many') {
                        continue;
                    }
                }
                if ($value_array['type'] == 'link' && !$use_field_label) {
                    $relModName = $this->module_object->{$value_array['name']}->getRelatedModuleName();
                    if(!empty($app_list_strings['moduleList'][$relModName])){
                    	$label_name = $app_list_strings['moduleList'][$relModName];
                    }else{
                    	$label_name = $relModName;
                    }
                }
				else if(!empty($value_array['vname'])){
					$label_name = $value_array['vname'];
				} else {
					$label_name = $value_array['name'];
				}


				$label_name = get_label($label_name, $temp_module_strings);

				if(!empty($value_array['table'])){
					//Custom Field
					$column_table = $value_array['table'];
				} else {
					//Non-Custom Field
					$column_table = $this->module_object->table_name;
				}

                if($value_array['type'] == 'link'){
                	if($use_field_name){
                		$index = $value_array['name'];

                	}else{
                		$index = $this->module_object->$key->getRelatedModuleName();
                	}
                }else{
					$index = $key;
                }

				$value = trim($label_name, ':');
				if($remove_dups){
					if(!in_array($value, $this->options_array)) {
						$this->options_array[$index] = $value;
					}
				}
				else {
					$this->options_array[$index] = $value;
				}

			//end if field is included
			}

		//end foreach
		}
		if($use_singular == true){
			return convert_module_to_singular($this->options_array);
		} else {
			return $this->options_array;
		}

	//end get_vardef_array
	}


	function compare_type($value_array) {
		//Filter nothing?
		if(!is_array($this->target_meta_array)){
			return true;
		}

        // Loop over our collection of handle methods for each index in the 
        // vardef_meta_array as well as for the all_meta_array. Expectation is
        // a boolean result, so if the result of any method is a true null then
        // keep on going.
        foreach ($this->compareMethods as $key => $method) {
            $call = 'handle' . $method;
            $result = $this->$call($value_array, $key);
            if ($result !== null) {
                return $result;
            }
        }

        return true;
	}

    /**
     * Handles checking of the inc_override values for this meta array name
     *
     * @param array $array The value array
     * @param string $index The index of the meta array to check
     * @return boolean
     */
    public function handleIncOverride($array, $index)
    {
        foreach ($this->checkArrays as $arrays) {
            if (isset($this->{$arrays}[$index])) {
                foreach ($this->{$arrays}[$index] as $attribute => $value) {
                    foreach ($value as $actual_value) {
                        if (isset($array[$attribute]) && $array[$attribute] == $actual_value) {
                            return true;
                        }
                    }

                    if (isset($array[$attribute]) && $array[$attribute] == $value) {
                        return true;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Handle checking of the ex_override values for this meta array name
     *
     * @param array $array The value array
     * @param string $index The index of the meta array to check
     * @return boolean
     */
    public function handleExOverride($array, $index)
    {
        foreach ($this->checkArrays as $arrays) {
            if (isset($this->{$arrays}[$index])) {
                foreach ($this->{$arrays}[$index] as $attribute => $value) {
                    foreach ($value as $actual_value) {
                        if (isset($array[$attribute]) && $array[$attribute] == $actual_value) {
                            return false;
                        }

                        if (isset($array[$attribute]) && $array[$attribute] == $value) {
                            return false;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Handles the inclusion values for this meta array name
     *
     * @param array $array The value array
     * @param string $index The index of the meta array to check
     * @return boolean
     */
    public function handleInclusion($array, $index)
    {
        foreach ($this->checkArrays as $arrays) {
            if (isset($this->{$arrays}[$index])){
                foreach($this->{$arrays}[$index] as $attribute => $value) {
                    if ($attribute == "type") {
                        foreach ($value as $actual_value) {
                            if (isset($array[$attribute]) && $array[$attribute] != $actual_value) {
                                return false;
                            }
                        }
                    } else {
                        if (isset($array[$attribute]) && $array[$attribute] != $value) {
                            return false;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Handles the exclusion values for this meta array name
     *
     * @param array $array The value array
     * @param string $index The index of the meta array to check
     * @return boolean
     */
    public function handleExclusion($array, $index)
    {
        foreach ($this->checkArrays as $arrays) {
            if (isset($this->{$arrays}[$index])) {
                foreach ($this->{$arrays}[$index] as $attribute => $value) {
                    foreach ($value as $actual_value) {
                        if (isset($array[$attribute]) && $array[$attribute] == $actual_value) {
                            return false;
                        }
                    }
                }
            }
        }

        return null;
    }
}
