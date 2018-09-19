<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
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

require_once('service/core/REST/SugarRest.php');

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

/**
 * This class is a serialize implementation of REST protocol
 * @api
 */
class SugarRestSerialize extends SugarRest{

	/**
	 * It will serialize the input object and echo's it
	 *
	 * @param array $input - assoc array of input values: key = param name, value = param type
	 * @return String - echos serialize string of $input
	 */
	function generateResponse($input){
		ob_clean();
		if (isset($this->faultObject)) {
			$this->generateFaultResponse($this->faultObject);
		} else {
			echo serialize($input);
		}
	} // fn

	/**
	 * This method calls functions on the implementation class and returns the output or Fault object in case of error to client
	 *
	 * @return unknown
	 */
	function serve(){
		$GLOBALS['log']->info('Begin: SugarRestSerialize->serve');
		if(empty($_REQUEST['method']) || !method_exists($this->implementation, $_REQUEST['method'])){
			$er = new SoapError();
			$er->set_error('invalid_call');
			$this->fault($er);
		}else{
			$method = $_REQUEST['method'];
			$data = InputValidation::getService()->getValidInputRequest(
                'rest_data',
                array('Assert\PhpSerialized' => array('htmlEncoded' => true)),
                ''
            );
			if(!is_array($data))$data = array($data);
			$GLOBALS['log']->info('End: SugarRestSerialize->serve');
			return call_user_func_array(array( $this->implementation, $method),$data);
		} // else
	} // fn

	/**
	 * This function sends response to client containing error object
	 *
	 * @param SoapError $errorObject - This is an object of type SoapError
	 * @access public
	 */
	function fault($errorObject){
		$this->faultServer->faultObject = $errorObject;
	} // fn

	function generateFaultResponse($errorObject){
		$error = $errorObject->number . ': ' . $errorObject->name . '<br>' . $errorObject->description;
		$GLOBALS['log']->error($error);
		ob_clean();
		echo serialize($errorObject);
	} // fn

} // clazz
