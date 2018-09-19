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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

require_once('service/core/REST/SugarRestSerialize.php');

/**
 * This class is a JSON implementation of REST protocol
 * @api
 */
class SugarRestJSON extends SugarRestSerialize{

    /**
     * A callback function name provided by a jQuery JSONP request
     * @var string
     */
    protected $callback ='';

    /**
     * @inheritdoc
     */
    public function __construct($implementation)
    {
        parent::__construct($implementation);

        $this->callback = InputValidation::getService()->getValidInputGet('jsoncallback');

        if (!\SugarConfig::getInstance()->get('jsonp_web_service_enabled', false)) {
            $this->callback = '';
        }
    }

	/**
	 * It will json encode the input object and echo's it
	 *
	 * @param array $input - assoc array of input values: key = param name, value = param type
	 * @return String - echos json encoded string of $input
	 */
    function generateResponse($input) {
        if (isset($this->faultObject)) {
            $this->generateFaultResponse($this->faultObject);
        } else {
            $this->printResponse($input);
        }
	} // fn

	/**
	 * This method calls functions on the implementation class and returns the output or Fault object in case of error to client
	 *
	 * @return unknown
	 */
	function serve(){
		$GLOBALS['log']->info('Begin: SugarRestJSON->serve');
		$json_data = !empty($_REQUEST['rest_data'])? $GLOBALS['RAW_REQUEST']['rest_data']: '';
		if(empty($_REQUEST['method']) || !method_exists($this->implementation, $_REQUEST['method'])){
			$er = new SoapError();
			$er->set_error('invalid_call');
			$this->fault($er);
		}else{
			$method = $_REQUEST['method'];
			$json = getJSONObj();
			$data = $json->decode($json_data);
			if(!is_array($data))$data = array($data);
			$res = call_user_func_array(array( $this->implementation, $method),$data);
			$GLOBALS['log']->info('End: SugarRestJSON->serve');
			return $res;
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
        $this->printResponse($errorObject);
	} // fn

    /**
     * Encode and print response object. With or without JSONP callback function
     */
    protected function printResponse($response)
    {
        $json = getJSONObj();
        ob_clean();

        if (!empty($this->callback)) {
            echo $this->callback . '(';
        }

        echo $json->encode($response);

        if (!empty($this->callback)) {
            echo ')';
        }
    }
} // class
