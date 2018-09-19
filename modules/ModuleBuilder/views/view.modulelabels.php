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

class ViewModulelabels extends SugarView
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

    public function display()
    {
        global $mod_strings, $locale;
        $bak_mod_strings=$mod_strings;
        $smarty = new Sugar_Smarty();
        $smarty->assign('mod_strings', $mod_strings);
        $package_name = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $module_name = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        require_once 'modules/ModuleBuilder/MB/ModuleBuilder.php';
        $mb = new ModuleBuilder();
        $mb->getPackage($package_name);
        $package = $mb->packages[$package_name];
        $package->getModule($module_name);
        $mbModule = $package->modules[$module_name];

        $selected_lang = $this->request->getValidInputRequest('selected_lang', 'Assert\Language');
        if (empty($selected_lang)) {
            $selected_lang = $locale->getAuthenticatedUserLanguage();
        }

        //need to change the following to interface with MBlanguage.

        $smarty->assign('MOD', $mbModule->getModStrings($selected_lang));
        $smarty->assign('APP', $GLOBALS['app_strings']);
        $smarty->assign('selected_lang', $selected_lang);
        $smarty->assign('view_package', $package_name);
        $smarty->assign('view_module', $module_name);
        $smarty->assign('mb','1');
        $smarty->assign('available_languages', get_languages());
        ///////////////////////////////////////////////////////////////////
         ////ASSISTANT
         $smarty->assign('assistant',array('group'=>'module', 'key'=>'labels'));
        /////////////////////////////////////////////////////////////////
         ////ASSISTANT

        $ajax = new AjaxCompose();
        $ajax->addCrumb($bak_mod_strings['LBL_MODULEBUILDER'], 'ModuleBuilder.main("mb")');
        $ajax->addCrumb($package_name, 'ModuleBuilder.getContent("module=ModuleBuilder&action=package&package='.$package->name. '")');
        $ajax->addCrumb($module_name, 'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_package='.$package->name.'&view_module='. $module_name . '")');
        $ajax->addCrumb($bak_mod_strings['LBL_LABELS'], '');
        $ajax->addSection('center', $bak_mod_strings['LBL_LABELS'],$smarty->fetch('modules/ModuleBuilder/tpls/labels.tpl'));
        echo $ajax->getJavascript();
    }
}
