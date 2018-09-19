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

class ViewLabels extends ViewModulefields
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

     //STUDIO LABELS ONLY//
     //TODO Bundle Studio and ModuleBuilder label handling to increase maintainability.
     function display()
     {
         global $locale;

         $editModule = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');
         $labels = $this->request->getValidInputRequest('labels');
         $allLabels = ($labels == 'all');

         if (!isset($_REQUEST['MB'])) {
             global $app_list_strings;
             $moduleNames = array_change_key_case($app_list_strings['moduleList']);
             $translatedEditModule = $moduleNames[strtolower($editModule)];
         }
         $selected_lang = $this->request->getValidInputRequest(
             'selected_lang',
             'Assert\Language',
             $locale->getAuthenticatedUserLanguage()
         );

        $smarty = new Sugar_Smarty();
        global $mod_strings;
        $smarty->assign('mod_strings', $mod_strings);
        $smarty->assign('available_languages',get_languages());

        $objectName = BeanFactory::getObjectName($editModule);
        VardefManager::loadVardef($editModule, $objectName);
        global $dictionary;
        $vnames = array();
        //jchi 24557 . We should list all the lables in viewdefs(list,detail,edit,quickcreate) that the user can edit them.
        $parser = ParserFactory::getParser(MB_LISTVIEW, $editModule);
        foreach ($parser->getLayout() as $key => $def) {
            if(isset($def['label'])) {
               $vnames[$def['label']] = $def['label'];
            }
        }

        $variableMap = $this->getVariableMap($editModule);
        foreach ($variableMap as $key => $value) {
            $gridLayoutMetaDataParserTemp = ParserFactory::getParser($key, $editModule);
            foreach ($gridLayoutMetaDataParserTemp->getLayout() as $panel) {
                foreach ($panel as $row) {
                    foreach ($row as $fieldArray) { // fieldArray is an array('name'=>name,'label'=>label)
                        if (isset($fieldArray['label'])) {
                            $vnames[$fieldArray['label']] = $fieldArray['label'];
                        }
                    }
                }
            }
        }
        //end

        //Get Subpanel Labels:
        $subList =  SubPanel::getModuleSubpanels ( $editModule );
        foreach ($subList as $subpanel => $titleLabel) {
            $vnames[$titleLabel] = $titleLabel;
        }

        foreach ($dictionary[$objectName]['fields'] as $name=>$def) {
            if (isset($def['vname'])) {
               $vnames[$def['vname']] = $def['vname'];
            }
        }
        $formatted_mod_strings = array();

         // we shouldn't set the $refresh=true here, or will lost template language
         // mod_strings.
         // return_module_language($selected_lang, $editModule,false) :
         // the mod_strings will be included from cache files here.
        foreach (return_module_language($selected_lang, $editModule,false) as $name=>$label) {
            //#25294
            if($allLabels || isset($vnames[$name]) || preg_match( '/lbl_city|lbl_country|lbl_billing_address|lbl_alt_address|lbl_shipping_address|lbl_postal_code|lbl_state$/si' , $name)) {
                // Bug 58174 - Escaped labels are sent to the client escaped
                // even in the label editor in studio
                $formatted_mod_strings[$name] = html_entity_decode($label, null, 'UTF-8');
            }
        }
        //Grab everything from the custom files
        $mod_bak = $mod_strings;
        $files = array(
            "custom/modules/$editModule/language/$selected_lang.lang.php",
            "custom/modules/$editModule/Ext/Language/$selected_lang.lang.ext.php"
        );
        foreach ($files as $langfile) {
            $mod_strings = array();
            if (is_file($langfile)) {
               include($langfile);
               foreach ($mod_strings as $key => $label) {
                   // Bug 58174 - Escaped labels are sent to the client escaped
                   // even in the label editor in studio
                   $formatted_mod_strings[$key] = html_entity_decode($label, null, 'UTF-8');
               }
            }
        }
        $mod_strings = $mod_bak;
        ksort($formatted_mod_strings);
        $smarty->assign('MOD', $formatted_mod_strings);
        $smarty->assign('view_module', $editModule);
        $smarty->assign('APP', $GLOBALS['app_strings']);
        $smarty->assign('selected_lang', $selected_lang);
        $smarty->assign('defaultHelp', 'labelsBtn');
        $smarty->assign('assistant', array('key'=>'labels', 'group'=>'module'));
        $smarty->assign('labels_choice', $mod_strings['labelTypes']);
        $smarty->assign('labels_current', $allLabels?"all":"");

        $ajax = new AjaxCompose();
        $ajax->addCrumb($mod_strings['LBL_STUDIO'], 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard")');
        $ajax->addCrumb($translatedEditModule, 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module='.$editModule.'")');
        $ajax->addCrumb($mod_strings['LBL_LABELS'], '');

        $html = $smarty->fetch('modules/ModuleBuilder/tpls/labels.tpl');
        $ajax->addSection('center', $GLOBALS['mod_strings']['LBL_SECTION_EDLABELS'], $html);
        echo $ajax->getJavascript();
     }

    // fixing bug #39749: Quick Create in Studio
    public function getVariableMap($module)
    {
        if (isModuleBWC($module)) {
            $variableMap = array(
                MB_EDITVIEW => 'EditView',
                MB_DETAILVIEW => 'DetailView',
                MB_QUICKCREATE => 'QuickCreate',
            );

            $hideQuickCreateForModules = array(
                'Campaigns',
                'Quotes',
                'ProductTemplates',
                'ProjectTask'
            );

            if (in_array($module, $hideQuickCreateForModules)) {
                if (isset($variableMap['quickcreate'])) {
                    unset($variableMap['quickcreate']);
                }
            }

        } else {
            $variableMap = array(
                MB_RECORDVIEW => 'record',
            );
        }

        return $variableMap;
    }
}
