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
if(!empty($_REQUEST['workflow_id']) && !empty($_REQUEST['record_id']))
{
    $action_shell = BeanFactory::getBean('WorkFlowActionShells', $_REQUEST['record_id']);
	$new_action_shell = $action_shell;
	$new_action_shell->id = "";
	$new_action_shell->parent_id = $_REQUEST['workflow_id'];
	$new_action_shell->save();
	$new_id = $new_action_shell->id;

	//process actions
	$action_shell->retrieve($_REQUEST['record_id']);
	$action_shell->copy($new_id);

    $workflow = BeanFactory::getBean('WorkFlow', $_REQUEST['workflow_id']);
	$workflow->write_workflow();

	$javascript = "<script>window.opener.document.DetailView.action.value = 'DetailView';";
	$javascript .= "window.opener.document.DetailView.submit();";
	$javascript .= "window.close();</script>";
	echo $javascript;
}
?>
