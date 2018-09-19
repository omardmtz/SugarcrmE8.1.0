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
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class UsersController extends SugarController
{
	protected function action_login()
	{
		if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'] == 1) {
			$_SESSION['isMobile'] = true;
			$this->view = 'wirelesslogin';
		}
		else{
			$this->view = 'login';
		}
	}

	protected function action_authenticate()
	{
	    $this->view = 'authenticate';
	}

	protected function action_default()
	{
		if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'] == 1){
			$_SESSION['isMobile'] = true;
			$this->view = 'wirelesslogin';
		}
		else{
			$this->view = 'classic';
		}
	}

    /**
     * Triggers reset preferences for a given user.
     *
     * If the user is resetting his own preferences, make sure he logs out to
     * have full refresh settings coming up (he was warned already).
     * If an admin is resetting other user's preferences, redirect him back to
     * that user's detail view.
     */
    protected function action_resetPreferences()
    {
        if (!($_REQUEST['record'] == $GLOBALS['current_user']->id || ($GLOBALS['current_user']->isAdminForModule('Users')))) {
            return;
        }

        $user = BeanFactory::getBean('Users', $_REQUEST['record']);
        $user->resetPreferences();

        if ($user->id !== $GLOBALS['current_user']->id) {
            SugarApplication::redirect("index.php?module=Users&record=" . $_REQUEST['record'] . "&action=DetailView"); //bug 48170]
        }
        echo '<script>parent.SUGAR.App.router.navigate("logout/?clear=1", {trigger: true});</script>';
    }

	protected function action_delete()
	{
	    if($_REQUEST['record'] != $GLOBALS['current_user']->id && ($GLOBALS['current_user']->isAdminForModule('Users')
            ))
        {
            $u = BeanFactory::getBean('Users', $_REQUEST['record']);
            $u->status = 'Inactive';
            $u->deleted = 1;
            $u->employee_status = 'Terminated';
            $u->save();
            $GLOBALS['log']->info("User id: {$GLOBALS['current_user']->id} deleted user record: {$_REQUEST['record']}");

            $privateTeamID = $u->getPrivateTeamID();
            $t = BeanFactory::getBean('Teams', $privateTeamID);
            // This will only be deleted if no user is assigned to the team.
            $t->delete_team();

            $eapm = BeanFactory::newBean('EAPM');
            $eapm->delete_user_accounts($_REQUEST['record']);
            $GLOBALS['log']->info("Removing user's External Accounts");
            $u->mark_deleted($u->id);
            if($u->portal_only == '0'){
                SugarApplication::redirect("index.php?module=Users&action=reassignUserRecords&record={$u->id}");
            }
            else{
                SugarApplication::redirect("index.php?module=Users&action=index");
            }
        }
        else
            sugar_die("Unauthorized access to administration.");
	}
	/**
	 * Clear the reassign user records session variables.
	 *
	 */
	protected function action_clearreassignrecords()
	{
        if( $GLOBALS['current_user']->isAdminForModule('Users'))
            unset($_SESSION['reassignRecords']);
        else
	       sugar_die("You cannot access this page.");
	}

	protected function action_wirelessmain()
	{
		$this->view = 'wirelessmain';
	}
	protected function action_wizard()
	{
		$this->view = 'wizard';
	}

	protected function action_saveuserwizard()
	{
	    global $current_user, $sugar_config;

	    // set all of these default parameters since the Users save action will undo the defaults otherwise
	    $_POST['record'] = $current_user->id;
	    $_POST['is_admin'] = ( $current_user->is_admin ? 'on' : '' );
	    $_POST['use_real_names'] = true;
	    $_POST['reminder_checked'] = '1';
	    $_POST['reminder_time'] = 1800;
        $_POST['mailmerge_on'] = 'on';
        $_POST['receive_notifications'] = $current_user->receive_notifications;
        $_POST['user_theme'] = (string) SugarThemeRegistry::getDefault();

	    // save and redirect to new view
	    $_REQUEST['return_module'] = 'Home';
	    $_REQUEST['return_action'] = 'index';
		require('modules/Users/Save.php');
	}

    protected function action_saveftsmodules()
    {
        $this->view = 'fts';
        $GLOBALS['current_user']->setPreference('fts_disabled_modules', $_REQUEST['disabled_modules']);
    }

    /**
     * action "save" (with a lower case S that is for OSX users ;-)
     * @see SugarController::action_save()
     */
    public function action_save()
    {
        require 'modules/Users/Save.php';
    }

    public function action_validate()
    {
        global $current_user;
        $only_verify_data = true;
        require 'modules/Users/Save.php';
    }
}

