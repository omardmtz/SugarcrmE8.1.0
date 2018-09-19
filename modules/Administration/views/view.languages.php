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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once 'include/SugarObjects/LanguageManager.php';
class ViewLanguages extends SugarView
{
    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;

    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".$mod_strings['LBL_MODULE_NAME']."</a>",
    	   $mod_strings['LBL_MANAGE_LANGUAGES']
    	   );
    }

    /**
	 * @see SugarView::preDisplay()
	 */
	public function preDisplay()
	{
	    global $current_user;

	    if (!is_admin($current_user)) {
	        sugar_die("Unauthorized access to administration.");
        }
	}

    /**
	 * @see SugarView::display()
	 */
	public function display()
	{
        global $mod_strings;
        global $app_list_strings;
        global $app_strings;
        
        $languages = LanguageManager::getEnabledAndDisabledLanguages();

        $this->ss->assign('APP', $GLOBALS['app_strings']);
        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('enabled_langs', json_encode($languages['enabled']));
        $this->ss->assign('disabled_langs', json_encode($languages['disabled']));
        $this->ss->assign('title',$this->getModuleTitle(false));

        echo $this->ss->fetch('modules/Administration/templates/Languages.tpl');
    }
}
