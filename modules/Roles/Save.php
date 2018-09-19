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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;





$focus = BeanFactory::newBean('Roles');

$tabs_def = urldecode($_REQUEST['display_tabs_def']);
$tabs_hide = urldecode($_REQUEST['hide_tabs_def']);

$allow_modules = explode(':::', $tabs_def);
$disallow_modules = explode(':::', $tabs_hide);

$focus->retrieve($_POST['record']);
unset($_POST['id']);


foreach($focus->column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$focus->$field = $value;

	}
}


$check_notify = FALSE;

$focus->save($check_notify);
$return_id = $focus->id;

$focus->clear_module_relationship($return_id);
$focus->set_module_relationship($return_id, $allow_modules, 1);
$focus->set_module_relationship($return_id, $disallow_modules, 0);


$return_module = InputValidation::getService()->getValidInputRequest('return_module', 'Assert\Mvc\ModuleName', 'Roles');
$return_action = InputValidation::getService()->getValidInputRequest('return_action', null, 'DetailView');
$return_id = InputValidation::getService()->getValidInputRequest('return_id', 'Assert\Guid');

	$GLOBALS['log']->debug("Saved record with id of ".$return_id);
	header("Location: index.php?action=".htmlspecialchars($return_action, ENT_QUOTES, 'UTF-8')."&module=".htmlspecialchars($return_module, ENT_QUOTES, 'UTF-8')."&record=".htmlspecialchars($return_id, ENT_QUOTES, 'UTF-8'));


?>
