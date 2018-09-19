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
/*********************************************************************************

 * Description:  
 ********************************************************************************/

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;




$focus = BeanFactory::getBean('Manufacturers', $_REQUEST['record']);

	foreach($focus->column_fields as $field)
	{
	if(isset($_REQUEST[$field]))
	{
		$focus->$field = InputValidation::getService()->getValidInputRequest($field);
		
	}
	}

	foreach($focus->additional_column_fields as $field)
	{
	if(isset($_REQUEST[$field]))
	{
		$focus->$field = InputValidation::getService()->getValidInputRequest($field);
		
	}
	}



$focus->save();
$return_id = $focus->id;

$edit='';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Manufacturers";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(!empty($_REQUEST['edit'])) {
	$return_id='';
	$edit='edit=true';
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&$edit");
?>