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
function display_conflict_between_objects($object_1, $object_2, $field_defs,$module_dir, $display_name){
	$mod_strings = return_module_language($GLOBALS['current_language'],'OptimisticLock');
	$title = '<tr><td >&nbsp;</td>';
	$object1_row= '<tr class="oddListRowS1"><td><b>'. $mod_strings['LBL_YOURS'] . '</b></td>';
	$object2_row= '<tr class="evenListRowS1"><td><b>' . $mod_strings['LBL_IN_DATABASE'] . '</b></td>';
	$exists = false;

	foreach( $field_defs as  $name=>$ignore)
	{
        $value = $object_1[$name];
        // FIXME: Replace the comparison here with a function from SugarWidgets
        if ( !is_scalar($value) || $name == 'team_name' ) {
            continue;
        }
		if( $value != $object_2->$name && !($object_2->$name instanceOf Link)){
			$title .= '<td ><b>&nbsp;' . translate($field_defs[$name]['vname'], $module_dir). '</b></td>';
			$object1_row .= '<td>&nbsp;' . $value. '</td>';
			$object2_row .= '<td>&nbsp;' . $object_2->$name . '</td>';
			$exists = true;
		}
	}

	if($exists){
		echo "<b>{$mod_strings['LBL_CONFLICT_EXISTS']}<a href='index.php?action=DetailView&module=$module_dir&record={$object_1['id']}'  target='_blank'>$display_name</a> </b> <br><table  class='list view' border='0' cellspacing='0' cellpadding='2'>$title<td  >&nbsp;</td></tr>$object1_row<td><a href='index.php?&module=OptimisticLock&action=LockResolve&save=true'>{$mod_strings['LBL_ACCEPT_YOURS']}</a></td></tr>$object2_row<td><a href='index.php?&module=$object_2->module_dir&action=DetailView&record=$object_2->id'>{$mod_strings['LBL_ACCEPT_DATABASE']}</a></td></tr></table><br>";
	}else{
		echo "<b>{$mod_strings['LBL_RECORDS_MATCH']}</b><br>";
	}
}

if(isset($_SESSION['o_lock_object'])){
	global $beanFiles, $moduleList;
	$object = 	$_SESSION['o_lock_object'];
	$current_state = BeanFactory::getBean($_SESSION['o_lock_module'], $object['id']);

	if(isset($_REQUEST['save'])){
		$_SESSION['o_lock_fs'] = true;
		echo  $_SESSION['o_lock_save'];
		die();
	}else{
		display_conflict_between_objects($object, $current_state, $current_state->field_defs, $current_state->module_dir, $_SESSION['o_lock_class']);
}}else{
	echo $mod_strings['LBL_NO_LOCKED_OBJECTS'];
}

?>
