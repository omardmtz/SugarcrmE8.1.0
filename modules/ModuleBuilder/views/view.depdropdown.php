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

require_once('modules/ModuleBuilder/MB/ModuleBuilder.php');

class ViewDepDropdown extends SugarView
{
    function display ()
    {
        $this->ss = new Sugar_Smarty();

        $editModule = $this->request->getValidInputRequest('editModule', 'Assert\ComponentName');
        $this->ss->assign('editModule', $editModule);

        $field = $this->request->getValidInputRequest('field');
        $this->ss->assign('field', $field);

        $parentList = $this->request->getValidInputRequest('parentList');
        $childList = $this->request->getValidInputRequest('childList');

        $mapping = empty($_REQUEST['mapping']) ? array() : json_decode(html_entity_decode($_REQUEST['mapping']), true);

        $this->ss->assign("mapping", $mapping);

        if (empty($_REQUEST['package']) || $_REQUEST['package'] == 'studio') {
            $sm = StudioModuleFactory::getStudioModule($_REQUEST['targetModule']);
            $fields = $sm->getFields();
            if (!empty($fields[$parentList]['options'])) {
                $parentList = $fields[$parentList]['options'];
            }
            $parentOptions = translate($parentList);
            $childOptions = translate($childList);
        }
        else {
            $mb = new ModuleBuilder();
            $moduleName = $_REQUEST['targetModule'];
            $sm = $mb->getPackageModule($_REQUEST['package'], $moduleName);
            $sm->getVardefs();
            $fields = $sm->mbvardefs->vardefs['fields'];
            if (!empty($fields[$parentList]['options'])) {
                $parentList = $fields[$parentList]['options'];
            }
            $parentOptions = $this->getMBOptions($parentList, $sm);
            $childOptions = $this->getMBOptions($childList, $sm);
        }

        $this->ss->assign("parent_list_options", $parentOptions);

        $parentOptionsArray = array();
        foreach($parentOptions as $value => $label)
        {
            $parentOptionsArray[] = array("value" => $value, "label" => $label);
        }
        $this->ss->assign("parentOptions",  json_encode($parentOptions));
        $this->ss->assign("child_list_options",  $childOptions);
        $childOptionsArray = array();
        foreach($childOptions as $value => $label)
        {
            $childOptionsArray[] = array("value" => $value, "label" => $label);
        }
        $this->ss->assign("childOptions",  json_encode($childOptionsArray));
        $this->ss->display("modules/ModuleBuilder/tpls/depdropdown.tpl");
    }


    protected function getMBOptions($label_key, $sm){
        global $app_list_strings;
        $lang = $GLOBALS['current_language'];
        $sm->mblanguage->generateAppStrings(false);
        $package_strings = $sm->mblanguage->getAppListStrings($lang.'.lang.php');
        $my_list_strings = $app_list_strings;
        $my_list_strings = array_merge($my_list_strings, $package_strings);
        foreach($my_list_strings as $key=>$value){
            if(!is_array($value)){
                unset($my_list_strings[$key]);
            }
        }

        if (empty($my_list_strings[$label_key]))
            return array();

        return $my_list_strings[$label_key];
    }
}
