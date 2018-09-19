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

 * Description: view handler for undo step of the import process
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/


class ImportViewUndo extends ImportView 
{	

    protected $pageTitleKey = 'LBL_UNDO_LAST_IMPORT';
    
 	/** 
     * @see SugarView::display()
     */
 	public function display()
    {
        global $mod_strings, $current_user, $current_language;

        $importModule = $this->request->getValidInputRequest('import_module', 'Assert\Mvc\ModuleName', false);
        $this->ss->assign("IMPORT_MODULE", $importModule);
        // lookup this module's $mod_strings to get the correct module name
        $old_mod_strings = $mod_strings;
        $module_mod_strings = 
            return_module_language($current_language, $importModule);
        $this->ss->assign("MODULENAME",$module_mod_strings['LBL_MODULE_NAME']);
        $this->ss->assign("MODULE_TITLE", $this->getModuleTitle(false), ENT_NOQUOTES);
        // reset old ones afterwards
        $mod_strings = $old_mod_strings;
        
        $last_import = BeanFactory::newBean('Import_2');
        $this->ss->assign('UNDO_SUCCESS',$last_import->undo($importModule));
        $this->ss->assign("JAVASCRIPT", $this->_getJS());
        $content = $this->ss->fetch('modules/Import/tpls/undo.tpl');
        $this->ss->assign("CONTENT",$content);
        $this->ss->display('modules/Import/tpls/wizardWrapper.tpl');
    }
    
    /**
     * Returns JS used in this view
     */
    private function _getJS()
    {
        return <<<EOJAVASCRIPT

document.getElementById('finished').onclick = function() {
    var form = $(this).closest('form'),
        module = form.find('input[name=import_module]').val(),
        action = 'index';
    form.find('input[name=module]').val(module);
    form.find('input[name=action]').val(action);

    parent.SUGAR.App.metadata.getModule(module).isBwcEnabled ?
        form.submit() :
        parent.SUGAR.App.router.navigate(module, {trigger: true});
};
EOJAVASCRIPT;
    }
}
