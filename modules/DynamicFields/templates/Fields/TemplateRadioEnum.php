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
class TemplateRadioEnum extends TemplateEnum{
	var $type = 'radioenum';
	
	function get_html_edit(){
		$this->prepare();
		$xtpl_var = strtoupper( $this->name);
		return "{RADIOOPTIONS_".$xtpl_var. "}";
	}
	
	function get_field_def(){
		$def = parent::get_field_def();
		$def['dbType'] = 'enum';
		$def['separator'] = '<br>';
		return $def;	
	}
	
	
	function get_xtpl_edit($add_blank = false){
		$name = $this->name;
		$value = '';
		if(isset($this->bean->$name)){
			$value = $this->bean->$name;
		}else{
			if(empty($this->bean->id)){
				$value= $this->default_value;	
			}	
		}
		if(!empty($this->help)){
		    $returnXTPL[$this->name . '_help'] = translate($this->help, $this->bean->module_dir);
		}
		
		global $app_list_strings;
		$returnXTPL = array();
		$returnXTPL[strtoupper($this->name)] = $value;

		
		$returnXTPL[strtoupper('RADIOOPTIONS_'.$this->name)] = $this->generateRadioButtons($value, false);
		return $returnXTPL;	
		
		
	}
	

	function generateRadioButtons($value = '', $add_blank =false){
		global $app_list_strings;
		$radiooptions = '';
		$keyvalues = $app_list_strings[$this->ext1];
		if($add_blank){
			$keyvalues = add_blank_option($keyvalues);
		}
		$help = (!empty($this->help))?"title='". translate($this->help, $this->bean->module_dir) . "'": '';
		foreach($keyvalues as $key=>$displayText){
			$selected = ($value == $key)?'checked': '';
			$radiooptions .= "<input type='radio' id='{$this->name}{$key}' name='$this->name'  $help value='$key' $selected><span onclick='document.getElementById(\"{$this->name}{$key}\").checked = true' style='cursor:default' onmousedown='return false;'>$displayText</span><br>\n";
		}
		return $radiooptions;
		
	}
	
	function get_xtpl_search(){
		$searchFor = '';
		if(!empty($_REQUEST[$this->name])){
			$searchFor = $_REQUEST[$this->name];
		}
		global $app_list_strings;
		$returnXTPL = array();
		$returnXTPL[strtoupper($this->name)] = $searchFor;
		$returnXTPL[strtoupper('RADIOOPTIONS_'.$this->name)] = $this->generateRadioButtons($searchFor, true);
		return $returnXTPL;	

	}
	
	function get_xtpl_detail(){
		$name = $this->name;
		if(isset($this->bean->$name)){
			global $app_list_strings;
			if(isset($app_list_strings[$this->ext1])){
				if(isset($app_list_strings[$this->ext1][$this->bean->$name])){
					return $app_list_strings[$this->ext1][$this->bean->$name];
				}
			}
		}else{
		    if(empty($this->bean->id)){
		        return $this->default_value;
		    }
		}
		return '';
	}
	
    public function get_db_default($modify = false)
    {
    return '';
}
	
}


?>
