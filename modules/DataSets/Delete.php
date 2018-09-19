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


global $mod_strings;


if(!isset($_REQUEST['record']))
	sugar_die($mod_strings['ERR_DELETE_RECORD']);

$focus = BeanFactory::getBean('DataSets', $_REQUEST['record']);

if(empty($focus))
	sugar_die($mod_strings['ERR_DELETE_RECORD']);

//if report_id is present
	if(!empty($focus->report_id) && $focus->report_id!=""){
	//now we need to go in and reorder the reports
		$controller = new Controller();
		$controller->init($focus, "Delete");
		$controller->delete_adjust_order($focus->report_id);

	//end if report id exists;
	}

$focus->mark_deleted($_REQUEST['record']);
$focus->disable_custom_layout();

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
