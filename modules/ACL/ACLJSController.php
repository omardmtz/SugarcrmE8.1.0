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


class ACLJSController{

    public function __construct($module, $form = '', $is_owner = false)
    {

		$this->module = $module;
		$this->is_owner = $is_owner;
		$this->form = $form;
	}

	function getJavascript(){
		global $action;
		if(!ACLController::moduleSupportsACL($this->module)){
			return '';
		}
		$script = "<SCRIPT>\n//BEGIN ACL JAVASCRIPT\n";

		if($action == 'DetailView'){
			if(!ACLController::checkAccess($this->module,'edit', $this->is_owner)){
			$script .= <<<EOQ
						if(typeof(document.DetailView) != 'undefined'){
							if(typeof(document.DetailView.elements['Edit']) != 'undefined'){
								document.DetailView.elements['Edit'].disabled = 'disabled';
							}
							if(typeof(document.DetailView.elements['Duplicate']) != 'undefined'){
								document.DetailView.elements['Duplicate'].disabled = 'disabled';
							}
						}
EOQ;
}
			if(!ACLController::checkAccess($this->module,'delete', $this->is_owner)){
			$script .= <<<EOQ
						if(typeof(document.DetailView) != 'undefined'){
							if(typeof(document.DetailView.elements['Delete']) != 'undefined'){
								document.DetailView.elements['Delete'].disabled = 'disabled';
							}
						}
EOQ;
}
		}
        if (file_exists('modules/'. $this->module . '/metadata/acldefs.php')) {
			include('modules/'. $this->module . '/metadata/acldefs.php');

			foreach($acldefs[$this->module]['forms'] as $form_name=>$form){

				foreach($form as $field_name=>$field){

					if($field['app_action'] == $action){
						switch($form_name){
							case 'by_id':
								$script .= $this->getFieldByIdScript($field_name, $field);
								break;
							case 'by_name':
								$script .= $this->getFieldByNameScript($field_name, $field);
								break;
							default:
								$script .= $this->getFieldByFormScript($form_name, $field_name, $field);
								break;
						}
					}

				}
			}
		}
		$script .=  '</SCRIPT>';

		return $script;


	}

	function getHTMLValues($def){
		$return_array = array();
		switch($def['display_option']){
			case 'clear_link':
				$return_array['href']= "#";
				$return_array['className']= "nolink";
				break;
			default;
				$return_array[$def['display_option']] = $def['display_option'];
				break;

		}
		return $return_array;

	}

	function getFieldByIdScript($name, $def){
		$script = '';
		if(!ACLController::checkAccess($def['module'], $def['action_option'], true)){
		foreach($this->getHTMLValues($def) as $key=>$value){
			$script .=  "\nif(document.getElementById('$name'))document.getElementById('$name')." . $key . '="' .$value. '";'. "\n";
		}
		}
		return $script;

	}

	function getFieldByNameScript($name, $def){
		$script = '';
		if(!ACLController::checkAccess($def['module'], $def['action_option'], true)){

		foreach($this->getHTMLValues($def) as $key=>$value){
			$script .=  <<<EOQ
			var aclfields = document.getElementsByName('$name');
			for(var i in aclfields){
				aclfields[i].$key = '$value';
			}
EOQ;
		}
		}
		return $script;

	}

	function getFieldByFormScript($form, $name, $def){
		$script = '';


		if(!ACLController::checkAccess($def['module'], $def['action_option'], true)){
			foreach($this->getHTMLValues($def) as $key=>$value){
				$script .= "\nif(typeof(document.$form.$name.$key) != 'undefined')\n document.$form.$name.".$key . '="' .$value. '";';
			}
		}
		return $script;

	}








}

