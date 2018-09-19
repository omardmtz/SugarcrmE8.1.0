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
class ViewHome extends SugarView
{
	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   translate('LBL_MODULE_NAME','Administration'),
    	   ModuleBuilderController::getModuleTitle(),
    	   );
    }

	function display()
	{
		global $current_user;
		global $mod_strings;
		$smarty = new Sugar_Smarty();
		$smarty->assign('title' , $mod_strings['LBL_DEVELOPER_TOOLS']);
		$smarty->assign('question', $mod_strings['LBL_QUESTION_EDITOR']);
		$smarty->assign('defaultHelp', 'mainHelp');
		$this->generateHomeButtons();
		$smarty->assign('buttons', $this->buttons);
		$assistant=array('group'=>'main', 'key'=>'welcome');
		$smarty->assign('assistant',$assistant);
		//initialize Assistant's display property.
		$userPref = $current_user->getPreference('mb_assist', 'Assistant');
		if(!$userPref) $userPref="na";
		$smarty->assign('userPref',$userPref);
		$ajax = new AjaxCompose();
		$ajax->addSection('center', $mod_strings['LBL_HOME'],$smarty->fetch('modules/ModuleBuilder/tpls/wizard.tpl'));
		echo $ajax->getJavascript();
	}


	function generateHomeButtons() 
	{
	    global $current_user;
        if(displayStudioForCurrentUser() == true) {
		//$this->buttons['Application'] = array ('action' => '', 'imageTitle' => 'Application', 'size' => '128', 'help'=>'appBtn');
		$this->buttons[$GLOBALS['mod_strings']['LBL_STUDIO']] = array ('action' => 'javascript:ModuleBuilder.main("studio")', 'imageTitle' => 'Studio', 'size' => '128', 'help'=>'studioBtn');
        }
        if(is_admin($current_user)) {
		$this->buttons[$GLOBALS['mod_strings']['LBL_MODULEBUILDER']] = array ('action' => 'javascript:ModuleBuilder.main("mb")', 'imageTitle' => 'ModuleBuilder', 'size' => '128', 'help'=>'mbBtn');

		$this->buttons[$GLOBALS['mod_strings']['LBL_SUGARPORTAL']] = array ('action' => 'javascript:ModuleBuilder.main("sugarportal")', 'imageTitle' => $GLOBALS['mod_strings']['LBL_SUGAR_PORTAL'], 'imageName' => 'SugarPortal', 'size' => '128', 'help'=>'sugarPortalBtn');
        }
		$this->buttons[$GLOBALS['mod_strings']['LBL_DROPDOWNEDITOR']] = array ('action' => 'javascript:ModuleBuilder.main("dropdowns")', 'imageTitle' => $GLOBALS['mod_strings']['LBL_HOME_EDIT_DROPDOWNS'], 'imageName' => 'DropDownEditor', 'size' => '128', 'help'=>'dropDownEditorBtn');
	}
}