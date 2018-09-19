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

class ViewDropdowns extends SugarView
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
		$ajax = new AjaxCompose();
		$smarty = new Sugar_Smarty();
		
 		if (isset($_REQUEST['refreshTree']))
		{
			$mbt = new DropDownTree();
			$ajax->addSection('west', $mbt->getName(), $mbt->fetchNodes());
			$smarty->assign('refreshTree',true);
		}
                
        global $mod_strings;
        $ajax->addCrumb($mod_strings['LBL_DROPDOWNEDITOR'], 'ModuleBuilder.main("dropdowns")');
        
        $dd = new DropDownBrowser();
        
        $smarty->assign('LBL_BTN_ADDDROPDOWN',translate('LBL_BTN_ADDDROPDOWN'));
        $smarty->assign('dropdowns', $dd->getNodes());
		$smarty->assign('deleteImage', SugarThemeRegistry::current()->getImage( 'delete_inline', '',null,null,'.gif',$mod_strings['LBL_MB_DELETE']));
		$smarty->assign('editImage', SugarThemeRegistry::current()->getImage( 'edit_inline', '',null,null,'.gif',$mod_strings['LBL_EDIT']));
		$smarty->assign('action', 'savedropdown');
		

		$ajax->addSection('center', $GLOBALS['mod_strings']['LBL_DROPDOWNEDITOR'], $smarty->fetch('modules/ModuleBuilder/tpls/MBModule/dropdowns.tpl') );
 		echo $ajax->getJavascript();
 	}
}