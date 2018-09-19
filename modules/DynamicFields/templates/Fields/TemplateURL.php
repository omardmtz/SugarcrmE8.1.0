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

class TemplateURL extends TemplateText{

    var $supports_unified_search = true;

	public function __construct()
	{
		// Handles setting the 'gen' flag on OOTB URL fields
		$this->vardef_map['ext3'] = 'gen';
		$this->vardef_map['gen'] = 'ext3';
		$this->vardef_map['ext4'] = 'link_target';
		$this->vardef_map['link_target'] = 'ext4';
	}
	
	var $type='url';
    function get_html_edit(){
        $this->prepare();
        return "<input type='text' name='". $this->name. "' id='".$this->name."' size='".$this->size."' title='{" . strtoupper($this->name) ."_HELP}' value='{". strtoupper($this->name). "}'>";
    }
    
    function get_html_detail(){ 
        $xtpl_var = strtoupper($this->name);
        return "<a href='{" . $xtpl_var . "}' target='_blank'>{" . $xtpl_var . "}</a>";
    }
    
    function get_xtpl_detail(){
        $value = parent::get_xtpl_detail();
        if(!empty($value) && substr_count($value, '://') == 0 && substr($value ,0,8) != 'index.php'){
            $value = 'http://' . $value;
        }
        return $value;
    }

	function get_field_def(){
		$def = parent::get_field_def();
		//$def['options'] = !empty($this->options) ? $this->options : $this->ext1;
		$def['default'] = !empty($this->default) ? $this->default : $this->default_value;
		$def['len'] = $this->len;
		$def['dbType'] = 'varchar';
		$def['gen'] = !empty($this->gen) ? $this->gen : $this->ext3;
        $def['link_target'] = !empty($this->link_target) ? $this->link_target : $this->ext4;
		return $def;	
	} 

}
?>
