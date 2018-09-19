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
global $app_strings;
global $app_list_strings;
global $current_language, $current_user, $timedate;
$current_module_strings = return_module_language($current_language, 'Tasks');

$tomorrow = $timedate->getNow()->get("+1 day")->asDb();

$ListView = new ListView();
$seedTasks = BeanFactory::newBean('Tasks');
$where = "tasks.assigned_user_id='". $current_user->id ."' and (tasks.status is NULL or (tasks.status!='Completed' and tasks.status!='Deferred')) ";
$where .= "and (tasks.date_start is NULL or ";
$where .= $seedTasks->db->convert($seedTasks->db->convert("tasks.date_start", "date_format", '%Y-%m-%d'),  "CONCAT",
    array("' '", $seedTasks->db->convert("tasks.time_start", "time_format"))). " <= ".$seedTasks->db->quoted($tomorrow);

$ListView->initNewXTemplate( 'modules/Tasks/MyTasks.html',$current_module_strings);
$header_text = '';

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=MyTasks&from_module=Tasks'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
}
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_MY_TASKS'].$header_text);
$ListView->setQuery($where, "", "date_due,priority desc", "TASK");
$ListView->processListView($seedTasks, "main", "TASK");
