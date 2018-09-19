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

require_once('service/v4/registry.php');

class registry_v4_1 extends registry_v4 {


	/**
	 * registerFunction
     *
     * Registers all the functions on the service class
	 *
	 */
	protected function registerFunction()
	{
		$GLOBALS['log']->info('Begin: registry->registerFunction');
		parent::registerFunction();

        //Add get_relationships with "pagination" support
        $this->serviceClass->registerFunction(
            'get_relationships',
            array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string', 'link_field_name'=>'xsd:string', 'related_module_query'=>'xsd:string', 'related_fields'=>'tns:select_fields', 'related_module_link_name_to_fields_array'=>'tns:link_names_to_fields_array', 'deleted'=>'xsd:int', 'order_by'=>'xsd:string', 'offset'=>'xsd:int' , 'limit'=>'xsd:int'),
            array('return'=>'tns:get_entry_result_version2'));


        //Add get_modified_relationship function
        $this->serviceClass->registerFunction(
            'get_modified_relationships',
            array('session'=>'xsd:string', 'module_name'=>'xsd:string','related_module'=>'xsd:string', 'from_date'=>'xsd:string', 'to_date'=>'xsd:string','offset'=>'xsd:int', 'max_results'=>'xsd:int','deleted'=>'xsd:int', 'module_user_id'=>'xsd:string', 'select_fields'=>'tns:select_fields', 'relationship_name'=>'xsd:string', 'deletion_date'=>'xsd:string'),
            array('return'=>'tns:modified_relationship_result'));

	}


    /**
   	 * This method registers all the complex types
   	 *
   	 */
   	protected function registerTypes() {

           parent::registerTypes();

           $this->serviceClass->registerType
           (
               'error_value',
               'complexType',
               'struct',
               'all',
               '',
               array(
                   'number'=>array('name'=>'number', 'type'=>'xsd:string'),
                   'name'=>array('name'=>'name', 'type'=>'xsd:string'),
                   'description'=>array('name'=>'description', 'type'=>'xsd:string'),
               )
           );

            //modified_relationship_entry_list
            //This type holds the array of modified_relationship_entry types
            $this->serviceClass->registerType(
                'modified_relationship_entry_list',
                'complexType',
                'array',
                '',
                'SOAP-ENC:Array',
                array(),
                array(
                    array('ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:modified_relationship_entry[]')
                ),
                'tns:modified_relationship_entry'
            );

            //modified_relationship_entry
            //This type consists of id, module_name and name_value_list type
            $this->serviceClass->registerType
            (
                 'modified_relationship_entry',
                 'complexType',
                 'struct',
                 'all',
                 '',
                 array(
                     'id' => array('name'=>'id', 'type'=>'xsd:string'),
                     'module_name' => array('name'=>'module_name', 'type'=>'xsd:string'),
                     'name_value_list' => array('name'=>'name_value_lists', 'type'=>'tns:name_value_list')
                 )
            );

            //modified_relationship_result
            //the top level result array
            $this->serviceClass->registerType
            (
                'modified_relationship_result',
                'complexType',
                'struct',
                'all',
                '',
                array(
                   'result_count' => array('name'=>'result_count', 'type'=>'xsd:int'),
                   'next_offset' => array('name'=>'next_offset', 'type'=>'xsd:int'),
                   'entry_list' => array('name'=>'entry_list', 'type'=>'tns:modified_relationship_entry_list'),
                   'error' => array('name' =>'error', 'type'=>'tns:error_value'),
                )
           );

}

}