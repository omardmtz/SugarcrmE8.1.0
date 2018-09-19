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

require_once('modules/ProductTemplates/Formulas.php');
refresh_price_formulas();
require_once('include/formbase.php');

$focus = BeanFactory::getBean('ProductTemplates', $_REQUEST['record']);

foreach($focus->column_fields as $field) {
	if(isset($_REQUEST[$field])) {
		$focus->$field = InputValidation::getService()->getValidInputRequest($field);
	}
}

foreach($focus->additional_column_fields as $field) {
	if(isset($_REQUEST[$field])) {
		$focus->$field = InputValidation::getService()->getValidInputRequest($field);
	}
}
$focus = populateFromPost('', $focus);
$focus->unformat_all_fields();
$focus->save();
$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "ProductTemplates";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

handleRedirect($return_id, $return_module);
//header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
