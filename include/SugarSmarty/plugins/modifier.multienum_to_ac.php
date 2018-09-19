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


function smarty_modifier_multienum_to_ac($value='', $field_options=array()){
	$value = trim($value);
	if(empty($value) || empty($field_options)){
		return '';
	}
	
	$expl = explode("^,^", $value);
	if(count($expl) == 1){
		if(array_key_exists($value, $field_options)){
			return $field_options[$value] . ", ";
		}
		else{
			return '';
		}
	}
	else{
		$final_array = array();
		foreach($expl as $key_val){
			if(array_key_exists($key_val, $field_options)){
				$final_array[] = $field_options[$key_val];
			}
		}
		return implode(", ", $final_array) . ", ";
	}
	
	return '';
}
