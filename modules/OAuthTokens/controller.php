<?PHP
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

class OAuthTokensController extends SugarController
{
	protected function action_delete()
	{
	    global $current_user;
		//do any pre delete processing
		//if there is some custom logic for deletion.
		if(!empty($_REQUEST['record'])){
			if(!is_admin($current_user) && $this->bean->assigned_user_id != $current_user->id) {
                ACLController::displayNoAccess(true);
                sugar_cleanup(true);
			}
			$this->bean->mark_deleted($_REQUEST['record']);
        }else{
			sugar_die("A record number must be specified to delete");
		}
	}

	protected function post_delete()
	{
        if(!empty($_REQUEST['return_url'])){
            $_REQUEST['return_url'] =urldecode($_REQUEST['return_url']);
            $this->redirect_url = $_REQUEST['return_url'];
        } else {
            parent::post_delete();
        }
	}
}