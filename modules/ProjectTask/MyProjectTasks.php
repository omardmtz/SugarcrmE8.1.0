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

// $Id: MyProjectTasks.php 45763 2009-04-01 19:16:18Z majed $







global $timedate;
global $app_strings;
global $app_list_strings;
global $current_language, $current_user;
$current_module_strings = return_module_language($current_language, 'ProjectTask');

$today = $timedate->nowDbDate(); 
$today = $timedate->handle_offset($today, $timedate->dbDayFormat, false);

$ListView = new ListView();
$seedProjectTask = BeanFactory::newBean('ProjectTask');
$where = "project_task.assigned_user_id='{$current_user->id}'"
	. " AND (project_task.status IS NULL OR (project_task.status!='Completed' AND project_task.status!='Deferred'))"
	. " AND (project_task.date_start IS NULL OR project_task.date_start <= '$today')";
$ListView->initNewXTemplate('modules/ProjectTask/MyProjectTasks.html',
	$current_module_strings);
$header_text = '';

if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{	
	$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=MyTasks&from_module=Tasks'>"
		. SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])
		. '</a>';
}
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_MY_PROJECT_TASKS'].$header_text);
$ListView->setQuery($where, "", "date_due,priority desc", "PROJECT_TASK");
$ListView->processListView($seedProjectTask, "main", "PROJECT_TASK");
?>
