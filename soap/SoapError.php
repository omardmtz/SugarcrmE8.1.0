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
class SoapError{
	var $name;
	var $number;
	var $description;

    public function __construct()
    {
		$this->set_error('no_error');
	}

	function set_error($error_name){
		global $error_defs;
		if(!isset($error_defs[$error_name])){
			$this->name = 'An Undefined Error - ' . $error_name . ' occurred';
			$this->number = '-1';
			$this->description = 'There is no error definition for ' . 	$error_name;
		}else{
			$this->name = $error_defs[$error_name]['name'];
			$this->number = $error_defs[$error_name]['number'];
			$this->description = $error_defs[$error_name]['description'];
		}
	}

	function get_soap_array(){
		return Array('number'=>$this->number,
					 'name'=>$this->name,
					 'description'=>$this->description);

	}

	function getName() {
		return $this->name;
	} // fn

	function getFaultCode() {
		return $this->number;
	} // fn

	function getDescription() {
		return $this->description;
	} // fn


}
