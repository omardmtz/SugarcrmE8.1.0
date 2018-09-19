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
//require_once('include/Utils.php');

class ViewEditDepDropdown extends SugarView
{
    public function __construct()
    {
        $this->options['show_footer'] = false;
        if (isset ($_REQUEST['embed']) && $_REQUEST['embed'])
        {
            $this->options['show_header'] = false;
        }
        parent::__construct();
    }

    function display(){
        global $app_strings, $current_user, $mod_strings, $app_list_strings;
        $smarty = new Sugar_Smarty();
        //Load the field list from the target module
        if (!empty($_SESSION["authenticated_user_language"])) {
            $selected_lang = $_SESSION["authenticated_user_language"];
        } else {
            $selected_lang = $GLOBALS["sugar_config"]["default_language"];
        }
        $vardef = array();
        //Copy app strings
        $my_list_strings = array_merge($app_list_strings);
        $child = $_REQUEST['field'];

        //if we are using ModuleBuilder then process the following
        if(!empty($_REQUEST['package']) && $_REQUEST['package'] != 'studio'){
            require_once('modules/ModuleBuilder/MB/ModuleBuilder.php');
            $mb = new ModuleBuilder();
            $this->module = $mb->getPackageModule($_REQUEST['package'], $_REQUEST['view_module']);
            $vardef = $this->module->getVardefs();
            $this->module->mblanguage->generateAppStrings(false) ;
            $my_list_strings = array_merge( $my_list_strings, $this->module->mblanguage->appListStrings[$selected_lang.'.lang.php'] );
        } else {
            $vardef = BeanFactory::newBean($_REQUEST['view_module'])->field_defs;
        }

        foreach($my_list_strings as $key=>$value){
            if(!is_array($value)){
                unset($my_list_strings[$key]);
            }
        }

        $parents = $this->getParentDDs($vardef, $child, $my_list_strings);
        $visibility_grid = !empty($vardef[$child]['visibility_grid']) ? $vardef[$child]['visibility_grid'] : array();

        $smarty->assign('app_strings', $app_strings);
        $smarty->assign('mod', $mod_strings);
        $smarty->assign('parents', JSON::encode($parents));
        $smarty->assign('visibility_grid', JSON::encode($visibility_grid));
        $smarty->display('modules/ExpressionEngine/tpls/ddEditor.tpl');
    }

    /**
     * Takes an array of field defs and returns a formated list of fields that are valid for use in expressions.
     *
     * @param array $fieldDef
     * @return array
     */
    protected function getParentDDs($fields, $childField, $list_strings){
        $ret = array();
        foreach($fields as $name => $def)
        {
            //Return all the enum fields
            if(!empty($def['type']) && $def['type'] == "enum" && !empty($list_strings[$def['options']]) && $name != $childField)
            {
                $ret[$name] = array(
                    "label" => translate($def['vname']),
                    "options" => $list_strings[$def['options']],
                );
            }
        }
        return $ret;
    }
}

