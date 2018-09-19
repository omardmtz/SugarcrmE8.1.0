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
class EmployeesController extends SugarController{
	function action_editview(){
		if(is_admin($GLOBALS['current_user']) || $_REQUEST['record'] == $GLOBALS['current_user']->id) 
			$this->view = 'edit';
		else
			sugar_die("Unauthorized access to employees.");
		return true;
	}
	
	protected function action_delete()
	{
	    if($_REQUEST['record'] != $GLOBALS['current_user']->id && $GLOBALS['current_user']->isAdminForModule('Users'))
        {
            $u = BeanFactory::getBean('Users', $_REQUEST['record']);
            $u->deleted = 1;
            $u->status = 'Inactive';
            $u->employee_status = 'Terminated';
            $u->save();
            $GLOBALS['log']->info("User id: {$GLOBALS['current_user']->id} deleted user record: {$_REQUEST['record']}");
            
            if( !empty($u->user_name) ) //If user redirect back to assignment screen.
                SugarApplication::redirect("index.php?module=Users&action=reassignUserRecords&record={$u->id}");
            else
                SugarApplication::redirect("index.php?module=Employees&action=index");
        }
        else 
            sugar_die("Unauthorized access to administration.");
	}
	
}
