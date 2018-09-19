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
 * $Id: view.detail.php
 * Description: This file is used to override the default Meta-data EditView behavior
 * to provide customization specific to the Calls module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class ProjectViewDetail extends ViewDetail 
{
 	/**
 	 * @see SugarView::display()
 	 */
 	public function display() 
 	{
		global $beanFiles;
		require_once($beanFiles['Project']);

		$focus = BeanFactory::getBean('Project', $_REQUEST['record']);

		global $app_list_strings, $current_user, $mod_strings;
		$this->ss->assign('APP_LIST_STRINGS', $app_list_strings);

		if($current_user->id == $focus->assigned_user_id || $current_user->is_admin){
			$this->ss->assign('OWNER_ONLY', true);
		}
		else{
			$this->ss->assign('OWNER_ONLY', false);
		}

		if(ACLController::checkAccess('ProjectTask', 'edit', true)) {
			$this->ss->assign('EDIT_RIGHTS_ONLY', true);
		}
		else{
			$this->ss->assign('EDIT_RIGHTS_ONLY', false);
		}

		$this->ss->assign('SAVE_AS', $mod_strings['LBL_SAVE_AS_TEMPLATE']);
		$this->ss->assign("IS_TEMPLATE", 0);

 		parent::display();
 	}
}
