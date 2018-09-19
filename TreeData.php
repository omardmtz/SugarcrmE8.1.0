<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
 //Request object must have these property values:
 //		Module: module name, this module should have a file called TreeData.php
 //		Function: name of the function to be called in TreeData.php, the function will be called statically.
 //		PARAM prefixed properties: array of these property/values will be passed to the function as parameter.

$ret=array();
$params1=array();
$nodes=array();

$GLOBALS['log']->debug("TreeData:session started");
$current_language = $GLOBALS['current_language'];

//process request parameters. consider following parameters.
//function, and all parameters prefixed with PARAM.
//PARAMT_ are tree level parameters.
//PARAMN_ are node level parameters.
//module  name and function name parameters are the only ones consumed
//by this file..
foreach ($_REQUEST as $key=>$value) {

	switch ($key) {
	
		case "function":
		case "call_back_function":
			$func_name=$value;
			$params1['TREE']['function']=$value;
			break;
			
		default:
			$pssplit=explode('_',$key);
			if ($pssplit[0] =='PARAMT') {
				unset($pssplit[0]);
				$params1['TREE'][implode('_',$pssplit)]=$value;				
			} else {
				if ($pssplit[0] =='PARAMN') {
					$depth=$pssplit[count($pssplit)-1];
					//parmeter is surrounded  by PARAMN_ and depth info.
					unset($pssplit[count($pssplit)-1]);unset($pssplit[0]);	
					$params1['NODES'][$depth][implode('_',$pssplit)]=$value;
				} else {
					if ($key=='module') {
						if (!isset($params1['TREE']['module'])) {
							$params1['TREE'][$key]=$value;	
						}
					} else { 	
						$params1['REQUEST'][$key]=$value;
					}					
				}
			}
	}	
}	
$modulename=$params1['TREE']['module']; ///module is a required parameter for the tree.
require('include/modules.php');
if (!empty($modulename) && !empty($func_name) && isset($beanList[$modulename]) ) {
    require_once('modules/'.$modulename.'/TreeData.php');
    $TreeDataFunctions = array(
        'ProductTemplates' => array('get_node_data'=>'','get_categories_and_products'=>''),
        'ProductCategories' => array('get_node_data'=>'','get_product_categories'=>''),
        'Forecasts' => array(
            'get_node_data'=>'',
            'get_worksheet'=>'',
            'commit_forecast'=>'',
            'save_worksheet'=>'',
            'list_nav'=>'',
            'reset_worksheet'=>'',
            'get_chart'=>'',
            ),
        'Documents' => array(
            'get_node_data'=>'',
            'get_category_nodes'=>'',
            'get_documents'=>'',
            ),
        );
        
	if (isset($TreeDataFunctions[$modulename][$func_name])) {
		$ret=call_user_func($func_name,$params1);
    }
}

if (!empty($ret)) {
	echo $ret;
}

?>
