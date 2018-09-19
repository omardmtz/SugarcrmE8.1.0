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


class ViewProperty extends SugarView
{
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

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


    /**
     * {@inheritDoc}
     * pseduo-constuctor - given a well-known name to allow subclasses to call this classes constructor
     *
     * @param SugarBean $bean            Ignored
     * @param array     $view_object_map Ignored
     */
    public function init($bean = null, $view_object_map = array())
    {
        $this->editModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
        $this->editPackage = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
        $this->id = $this->request->getValidInputRequest('id', 'Assert\ComponentName');
        $this->subpanel = $this->request->getValidInputRequest('subpanel', 'Assert\ComponentName', '');
        $this->properties = array();
        foreach ($_REQUEST as $key=>$value) {
            if (substr($key,0,4) == 'name') {
                $value = $this->request->getValidInputRequest($key, 'Assert\ComponentName');
                $this->properties[substr($key,5)]['name'] = $value;
            }
            if (substr($key,0,2) == 'id') {
                $value = $this->request->getValidInputRequest($key, 'Assert\ComponentName');
                $this->properties[substr($key,3)]['id'] = $value;
            }
            if (substr($key,0,5) == 'value') {
                $value = $this->request->getValidInputRequest($key);
                $this->properties[substr($key,6)]['value'] = $value;
                // tyoung - now a nasty hack to disable editing of labels which contain Smarty functions - this is envisaged to be a temporary fix to prevent admins modifying these functions then being unable to restore the original complicated value if they regret it
                if (substr($key,6) == 'label') {
                    //#29796  , we disable the edit function for sub panel label
                    if (preg_match('/\{.*\}/',$value) || !empty($this->subpanel)) {
                        $this->properties[substr($key,6)]['hidden'] = 1;
                    }
                }
            }
            if (substr($key,0,5) == 'title') {
                $this->properties[substr($key,6)]['title'] = $this->request->getValidInputRequest($key);
            }
        }
     }

    public function display()
    {
        global $mod_strings, $locale;
        $ajax = new AjaxCompose();
        $smarty = new Sugar_Smarty();
        if (!empty($_REQUEST['MB'])) {
            $smarty->assign("MB", '1');
            $smarty->assign("view_package", $this->editPackage);
        }

        $selected_lang = $this->request->getValidInputRequest('selected_lang', 'Assert\Language');
        if (empty($selected_lang)) {
            $selected_lang = $locale->getAuthenticatedUserLanguage();
        }
        if(empty($selected_lang)){
            $selected_lang = $GLOBALS['sugar_config']['default_language'];
        }
        $smarty->assign('available_languages', get_languages());
        $smarty->assign('selected_lang', $selected_lang);

        ksort($this->properties);
        if (isset($this->properties['width'])) {
            $smarty->assign('defaultWidths', SidecarListLayoutMetaDataParser::getDefaultWidths());
        }
        // BWC modules width are in %, sidecar modules widths are in pixels.
        $smarty->assign('widthUnit', isModuleBWC($this->editModule) ? '%' : 'px');

        $smarty->assign("properties",$this->properties);
        $smarty->assign("mod_strings",$mod_strings);
        $smarty->assign('APP', $GLOBALS['app_strings']);
        $smarty->assign("view_module", $this->editModule);
        $smarty->assign("subpanel", $this->subpanel);
        if (isset($this->editPackage)) {
            $smarty->assign("view_package", $this->editPackage);
        }

        $ajax->addSection('east', translate('LBL_SECTION_PROPERTIES', 'ModuleBuilder'), $smarty->fetch('modules/ModuleBuilder/tpls/editProperty.tpl'));
        echo $ajax->getJavascript();
    }
}
