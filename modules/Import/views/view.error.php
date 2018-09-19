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

 * Description: view handler for error page of the import process
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/


class ImportViewError extends SugarView 
{	
    /**
     * @see SugarView::getMenu()
     */
    public function getMenu($module = null, $mod_strings_override = false)
    {
        global $mod_strings, $current_language;
        
        if ( empty($module) )
            $module = $_REQUEST['import_module'];
        
        $old_mod_strings = $mod_strings;
        $mod_strings = return_module_language($current_language, $module);
        $returnMenu = parent::getMenu($module, $mod_strings_override);
        $mod_strings = $old_mod_strings;
        
        return $returnMenu;
    }
    
 	/**
     * @see SugarView::_getModuleTab()
     */
 	protected function _getModuleTab()
    {
        global $app_list_strings, $moduleTabMap;
        
 		// Need to figure out what tab this module belongs to, most modules have their own tabs, but there are exceptions.
        if ( !empty($_REQUEST['module_tab']) )
            return $_REQUEST['module_tab'];
        elseif ( isset($moduleTabMap[$_REQUEST['import_module']]) )
            return $moduleTabMap[$_REQUEST['import_module']];
        // Default anonymous pages to be under Home
        elseif ( !isset($app_list_strings['moduleList'][$_REQUEST['import_module']]) )
            return 'Home';
        else
            return $_REQUEST['import_module'];
 	}
 	
 	/** 
     * @see SugarView::display()
     */
 	public function display()
    {
        $this->ss->assign("IMPORT_MODULE", $this->request->getValidInputRequest('import_module', 'Assert\Mvc\ModuleName', ''));
        $this->ss->assign("ACTION", 'Step1');
        $this->ss->assign("MESSAGE", $this->request->getValidInputRequest('message', null, ''));
        $this->ss->assign("SOURCE","");
        if ( isset($_REQUEST['source']) )
        $this->ss->assign("SOURCE", $this->request->getValidInputRequest('source', array('Assert\Choice' => array('choices' => self::getImportSourceOptions())), ''));
        
        $this->ss->display('modules/Import/tpls/error.tpl');
    }
}
