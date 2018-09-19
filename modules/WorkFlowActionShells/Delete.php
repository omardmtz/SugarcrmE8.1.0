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



$focus = BeanFactory::newBean('WorkFlowActionShells');

if(!isset($_REQUEST['record']))
	sugar_die($mod_strings['ERR_DELETE_RECORD']);

	$focus->retrieve($_REQUEST['record']);
	
	//check for bridged child (invites for meetings/calls
	$focus->check_for_child_bridge(true);
	
	//mark delete alertshell components
	mark_delete_components($focus->get_linked_beans('actions','WorkFlowAction'));
	mark_delete_components($focus->get_linked_beans('rel1_action_fil','Expression'));
	$focus->mark_deleted($_REQUEST['record']);

	$workflow_object = $focus->get_workflow_object();
	$workflow_object->write_workflow();

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
