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
require_once 'service/core/SugarSoapService.php';
require_once 'vendor/nusoap/nusoap.php';

/**
 * This is an abstract class for the soap implementation for using NUSOAP. This class is responsible for making
 * all NUSOAP call by passing the client's request to NUSOAP server and seding response back to client
 * @api
 */
abstract class NusoapSoap extends SugarSoapService{
	/**
	 * This is the constructor. It creates an instance of NUSOAP server.
	 *
	 * @param String $url - This is the soap URL
	 * @access public
	 */
	public function __construct($url){
		$GLOBALS['log']->info('Begin: NusoapSoap->__construct');
		$this->server = new soap_server();
		$this->soapURL = $url;
		$this->server->configureWSDL('sugarsoap', $this->getNameSpace(), $url);
		parent::__construct();
		$GLOBALS['log']->info('End: NusoapSoap->__construct');
	} // ctor

	/**
	 * Fallback function to catch unexpected failure in SOAP
	 */
	public function shutdown()
	{
		if($this->in_service) {
			$out = ob_get_contents();
			ob_end_clean();
			$GLOBALS['log']->info('NusoapSoap->shutdown: service died unexpectedly');
			$this->server->fault(-1, "Unknown error in SOAP call: service died unexpectedly", '', $out);
			$this->server->send_response();
		}
	}

	/**
	 * It passes request data to NUSOAP server and sends response back to client
	 * @access public
	 */
	public function serve(){
		$GLOBALS['log']->info('Begin: NusoapSoap->serve');
		ob_clean();
		$this->in_service = true;
		register_shutdown_function(array($this, "shutdown"));
		ob_start();
        $body = file_get_contents('php://input');
        $this->server->service($body);
		$this->in_service = false;
		ob_end_flush();
		flush();
		$GLOBALS['log']->info('End: NusoapSoap->serve');
	} // fn

	/**
	 * This method registers all the complex type with NUSOAP server so that proper WSDL can be generated
	 *
	 * @param String $name - name of complex type
	 * @param String $typeClass - (complexType|simpleType|attribute)
	 * @param String $phpType - array or struct
	 * @param String $compositor - (all|sequence|choice)
	 * @param String $restrictionBase - SOAP-ENC:Array or empty
	 * @param Array $elements - array ( name => array(name=>'',type=>'') )
	 * @param Array $attrs - array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:string[]'))
	 * @param String $arrayType - arrayType: namespace:name (xsd:string)
	 * @access public
	 */
	public function registerType($name, $typeClass, $phpType, $compositor, $restrictionBase, $elements, $attrs=array(), $arrayType=''){
		$this->server->wsdl->addComplexType($name, $typeClass, $phpType, $compositor, $restrictionBase, $elements, $attrs, $arrayType);
  	} // fn

  	/**
  	 * This method registers all the functions you want to expose as services with NUSOAP
  	 *
  	 * @param String $function - name of the function
  	 * @param Array $input - assoc array of input values: key = param name, value = param type
  	 * @param Array $output - assoc array of output values: key = param name, value = param type
	 * @access public
  	 */
	function registerFunction($function, $input, $output){
		if(in_array($function, $this->excludeFunctions))return;
		$use = false;
		$style = false;
		if (isset($_REQUEST['use']) && ($_REQUEST['use'] == 'literal')) {
			$use = "literal";
		} // if
		if (isset($_REQUEST['style']) && ($_REQUEST['style'] == 'document')) {
			$style = "document";
		} // if
		$this->server->register($function, $input, $output, $this->getNameSpace(), '',$style, $use);
	} // fn

	/**
	 * This function registers implementation class name with NUSOAP so when NUSOAP makes a call to a funciton,
	 * it will be made on this class object
	 *
	 * @param String $implementationClass
	 * @access public
	 */
	function registerImplClass($implementationClass){
		$GLOBALS['log']->info('Begin: NusoapSoap->registerImplClass');
		if (empty($implementationClass)) {
			$implementationClass = $this->implementationClass;
		} // if
		$this->server->register_class($implementationClass);
		$GLOBALS['log']->info('End: NusoapSoap->registerImplClass');
	} // fn

	/**
	 * Sets the name of the registry class
	 *
	 * @param String $registryClass
	 * @access public
	 */
	function registerClass($registryClass){
		$GLOBALS['log']->info('Begin: NusoapSoap->registerClass');
		$this->registryClass = $registryClass;
		$GLOBALS['log']->info('End: NusoapSoap->registerClass');
	} // fn

	/**
	 * This function sets the fault object on the NUSOAP
	 *
	 * @param SoapError $errorObject - This is an object of type SoapError
	 * @access public
	 */
	public function error($errorObject){
		$GLOBALS['log']->info('Begin: NusoapSoap->error');

        // report all failures as caused by client since we don't have the needed attribute
        // in existing error definitions
        $this->server->fault('SOAP-ENV:Client', $errorObject->getName(), '', array(
            'code' => $errorObject->getFaultCode(),
            'message' => $errorObject->getDescription(),
        ));
        $GLOBALS['log']->info('End: NusoapSoap->error');
	} // fn

} // clazz
