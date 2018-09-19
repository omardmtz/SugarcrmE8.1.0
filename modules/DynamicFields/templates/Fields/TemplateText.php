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

class TemplateText extends TemplateField
{
	var $type='varchar';

    /**
     * {@inheritDoc}
     */
    public $len = 255;

    var $supports_unified_search = true;

	function get_xtpl_edit(){
		$name = $this->name;
		$returnXTPL = array();
	
		if(!empty($this->help)){
		    $returnXTPL[strtoupper($this->name . '_help')] = translate($this->help, $this->bean->module_dir);
		}
	
		if(isset($this->bean->$name)){
		    $returnXTPL[$this->name] = $this->bean->$name;
		}else{
			if(empty($this->bean->id)){
				 $returnXTPL[$this->name] =  $this->default_value;	
			}	
		}
		return $returnXTPL;
	}

	function get_xtpl_search(){
		if(!empty($_REQUEST[$this->name])){
			return $_REQUEST[$this->name];
		}	
	}

	function get_xtpl_detail(){
		$name = $this->name;
		if(isset($this->bean->$name)){
			return $this->bean->$name;	
		}
		return '';
		
	}
}
