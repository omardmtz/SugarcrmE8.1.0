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




$local_log =& LoggerManager::getLogger('index');

$focus = BeanFactory::newBean('WorkFlow');
$controller = new Controller();

	
	//if we are saving from the adddatasetform
	$focus->retrieve($_REQUEST['workflow_id']);

		
		$magnitude = 1;
		$direction = $_REQUEST['direction'];

		$controller->init($focus, "Save");
		$controller->change_component_order($magnitude, $direction, $focus->base_module);

$focus->save();

$focus->write_workflow();
	
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Workflow";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "ProcessListView";

//echo "index.php?action=$return_action&module=$return_module&record=$return_id";

header("Location: index.php?action=$return_action&module=$return_module&base_module=".$_REQUEST['base_module']."");
?>
